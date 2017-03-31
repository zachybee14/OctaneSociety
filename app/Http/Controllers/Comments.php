<?php namespace OctaneSociety\Http\Controllers;

use Auth;
use Input;
use Response;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Models\Comment;

class Comments extends Controller 
{
    use AuthorizesRequests, DispatchesJobs;

    public function index() 
    {
    	$comments = Comment::get();

    	return Response::json([
			'success' => true,
			'comments' => $comments
		]);
    }

    public function getAllCommentsForTarget($targetId) 
	{
		$comments = Comment::where('target_id', $targetId)->get(['id', 'user_id', 'text', 'created_at']);

		return Response::json([
			'success' => true,
			'comments' => $comments
		]);
	}
}