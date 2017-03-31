<?php namespace OctaneSociety\Http\Controllers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SPA extends Controller {
	public function render() {
		return view('main');
	}

	public function deliverTemplates() {
		// use glob or readdir or scandir to get a list of files in base_path() . '/vue-templates'
		$templatesPath = base_path() .'/resources/vue-templates';
		$dirIterator = new RecursiveDirectoryIterator($templatesPath, RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);

		$files = [];
		foreach ($iterator as $file)
			if (preg_match('/\.html$/', $filePath = $file->getPathname()))
				$files[] = $filePath;
		
		$templates = [];
		
		foreach ($files as $file) {
			$contents = file_get_contents($file);
			$templateName = substr($file, strlen($templatesPath) + 1);
			$templateName = str_replace('/', '.', $templateName);
			$templateName = substr($templateName, 0, -5);
			$templates[$templateName] = $contents;
		}

		$js = 'window.os_templates=' . json_encode($templates) . ';';

		return response($js)->header('Content-Type', 'text/javascript');
	}
}