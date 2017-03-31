<?php namespace OctaneSociety\Http\Controllers;

use Input;
use Response;

use OctaneSociety\Models\Blog;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Blogs extends Controller 
{
	public function index() {
		$posts = Blog::get();

		return Response::json([
			'success' => true,
			'posts' => $posts
		]); 
	}

	public function show($id) {
		$post = Blog::findOrfail($id);

		return Response::json([
			'success' => true,
			'post' => $post
		]); 
	}

	public function store() {
		$input = Input::only('text');

		$post = new Blog;
		$post->poster_id = Auth::user()->id;
		$post->text = $input['text'];
		$post->save();

		return Response::json([
			'success' => true,
			'post_id' => $post->id
		]);
	}

	public function update($id) {
		$input = Input::only('text');

		if (is_null($input['text']))
			throw new ErrorMessageException ("There is no text to update.");

		$post = Blog::findOrfail($id);
		$post->text = $input['text'];
		$post->updated_at = date('Y-m-d h:i:sa');
		$post->save();

		return Response::json([
			'success' => true,
			'cars' => $post
		]);
	}

	public function destroy($id) {
		$post = Blog::findOrfail($id);

		$post->delete();

		return Response::json([
			'success' => true,
		]);
	}
}