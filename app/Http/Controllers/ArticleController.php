<?php namespace OctaneSociety\Http\Controllers;

use Input;
use Response;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Models\Article;

class ArticleController extends Controller {
	public function index() {
		$articles = Article::get(['id', 'user_id', 'type', 'title', 'body', 'summary']);

		return Response::json([
			'success' => true,
			'articles' => $articles
		]);
	}

	public function show($id) {
		$article = Article::findOrFail($id);

		return View::make('pillars/article', $article);
	}

	public function store() {
		$input = Input::all();

		$article = new Article;
		$article->user_id = Auth::user()->id;
		$article->type = $input['type'];
		$article->title = $input['title'];
		$article->body = $input['body'];
		$article->summary = $input['summary'];
		$article->save();

		return Response::json([
			'success' => true,
			'article_id' => $article->id
		]);
	}

	public function update($id) {
		$input = Input::all();
		$article = Article::findOrFail($id);

		foreach ($input as $key => $value) {
			if (!is_null($value))
				$article->$key = $value;
		}

		$article->save();

		return Response::json([
			'success' => true
		]);
	}

	public function destroy($id) {
		$article = Article::findOrFail($id);

		$article->delete();

		return Response::json([
			'success' => true
		]);
	}

	public function getTitles() {
		$input = Input::only('type');

		if ($input['type'] != '') {
			$articlesTitles = Article::where('type', $input['type'])->get(['id', 'title']);
		}
		else {
			$articlesTitles = Article::get(['id', 'title']);
		}

		return Response::json([
			'success' => true,
			'type' => $input['type'],
			'articles_titles' => $articlesTitles
		]);
	}
}