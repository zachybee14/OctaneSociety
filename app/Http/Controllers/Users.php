<?php namespace OctaneSociety\Http\Controllers;

use Response;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Models\User;

class Users extends Controller 
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index() {
		$users = User::get();

		return Response::json([
			'success' => true,
			'users' => $users
		]);
	}

	public function getNonAcceptedUsers() {
		$requests = User::where('accepted', 0)->select('id', 'fb_id', 'first_name', 'last_name', 'created_at')->orderBy('created_at', 'desc')->get();

		return Response::json([
			'success' => true,
			'requests' => $requests
		]);
	}
}
