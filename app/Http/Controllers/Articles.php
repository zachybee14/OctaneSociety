<?php namespace OctaneSociety\Http\Controllers;

use OctaneSociety\Models\Vehicle;
use Request;
use Response;
use DateTime;

use OctaneSociety\Exceptions\ErrorMessageException;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Helpers\InputValidator;

use OctaneSociety\Models\Article;
use OctaneSociety\Models\ArticleType;

use OctaneSociety\Services\ArticleReviews;
use OctaneSociety\Services\ArticleSales;
use OctaneSociety\Services\ArticleSteps;

class Articles extends Controller
{
	public function index() {
	    //['id', 'type', 'user_id', 'title', 'body', 'is_certified']
		$articles = Article::get();

		return Response::json([
			'success' => true,
			'articles' => $articles
		]);
	}

	public function show($id) {
		$article = Article::findOrFail($id);

        return Response::json([
            'success' => true,
            'article' => $article
        ]);
	}

	public function store() {
		$input = Request::only('article_type_id', 'applicable_vehicles', 'content');
        $content = $input['content'];
        InputValidator::required($input, ['article_type_id']);

        $articleType = ArticleType::find($input['article_type_id']);
        if (!$articleType)
            throw new ErrorMessageException('No article type was found with ID ' . $input['article_type_id']);

        DB::beginTransaction();

        $article = new Article;
        $article->type_id = $articleType->id;
        $article->user_id = Auth::id();
        $article->title = $content['article_title'];
        $article->body = $content['article_body'];
        $article->created_at = new DateTime;
        $article->save();

        foreach ($input['applicable_vehicles'] as $vehicle) {
            $vehicleObject = Vehicle::find($vehicle['id']);
            if (!$vehicleObject)
                throw new ErrorMessageException('No vehicle was found with ID ' . $vehicle['id']);

            $article->vehicles()->save($vehicleObject);
        }

        if ($articleType->name == 'How to')
            ArticleSteps::createStep($article->id, $content);
        else if ($articleType->name == 'For Sale')
            ArticleSales::createSale($article->id, $content);
        else if ($articleType->name == 'Review')
            ArticleReviews::createReview($article->id, $content);

        DB::commit();

		return Response::json([
			'success' => true,
			'article_id' => $article->id
		]);
	}

	public function update($id) {
		$input = Request::only('article', 'applicable_vehicles', 'content');
		$article = Article::findOrFail($id);
        $content = $input['content'];

        if (is_null($content))
            throw new ErrorMessageException('No new content was provided.');

        if (!is_null($content['title']))
            $article->body = $content['title'];

        if (!is_null($content['body']))
            $article->body = $content['body'];

        DB::beginTransaction();

        // these update functions have NOT been written in their services
        if ($content['steps'])
            ArticleSteps::updateStep($article->id, $content['step']);
        else if ($content['sale'])
            ArticleSales::updateSale($article->id, $content['sale']);
        else if ($content['review'])
            ArticleReviews::updateReview($article->id, $content['review']);

		$article->save();

        DB::commit();

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
		$input = Request::only('type');

		if (strlen($input['type']) > 0)
			$articlesTitles = Article::where('type', $input['type'])->get(['id', 'title']);
		else
			$articlesTitles = Article::get(['id', 'title']);

		return Response::json([
			'success' => true,
			'type' => $input['type'],
			'articles_titles' => $articlesTitles
		]);
	}

	public function addComment($articleId)
	{
        $article = Article::findOrFail($articleId);
		$input = Request::only('text');

        if (strlen($input['text']) < 1)
            throw new ErrorMessageException('Comments must contain text');

		$comment = new Comment;
		$comment->user_id = Auth::id();
		$comment->target_type = 'article';
		$comment->target_id = $article->id;
		$comment->text = $input['text'];
		$comment->created_at = new DateTime;
		$comment->save();

		return Response::json([
			'success' => true
		]);
	}
}