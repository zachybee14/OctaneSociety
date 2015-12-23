<?php namespace OctaneSociety\Http\Controllers;

use Response;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Models\State;

class StatesController extends Controller {
    use AuthorizesRequests, DispatchesJobs;

    public function index() {
    	$states = State::get();

    	return Response::json([
			'success' => true,
			'states' => $states
		]);
    }
}