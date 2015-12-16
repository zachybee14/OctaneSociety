<?php namespace OctaneSociety\Http\Middleware;

use Closure;
use Exception;

class ProvideCompiledAssets {
	public function handle($request, Closure $next) {
		// get the path
		$path = $request->path();

		// if it's a request for a CSS file
		if (preg_match('/\.css$/', $path))
			// if a LESS file exists
			if (file_exists($scssFile = public_path() . '/' . preg_replace('/\.css$/', '.scss', $path)))
				// compile & return the LESS file
				return $this->getCompiledScssFile($scssFile);

		// otherwise, we can move on with our request
		return $next($request);
	}

	private function getCompiledScssFile($scssFile) {
		$scssMtime = filemtime($scssFile);
		$fileHash = md5($scssFile);
		
		$cacheFile = storage_path() . '/app/cache/' . $fileHash;
		
		if (file_exists($cacheFile) && filemtime($cacheFile) == $scssMtime) {
			$output = file_get_contents($cacheFile);
		}
		
		else {
			exec('/usr/local/bin/sassc "' . $scssFile . '" 2>&1', $output, $ret);
			$output = implode("\n", $output);
			if ($ret != 0) throw new Exception($output);
			
			/*$tmpFile = tempnam(sys_get_temp_dir(), 'scss');
			file_put_contents($tmpFile, $output);
			
			unset($output);
			exec('/usr/bin/postcss --use autoprefixer --use cssnano ' . $tmpFile, $output, $ret);
			$output = implode("\n", $output);
			if ($ret != 0) throw new Exception($output);
			
			unlink($tmpFile);*/
			file_put_contents($cacheFile, $output);
			
			touch($cacheFile, $scssMtime);
		}
		
		return response($output, 200)->header('Content-Type', 'text/css');
	}
}