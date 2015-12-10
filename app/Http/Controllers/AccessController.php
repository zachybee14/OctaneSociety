<?php namespace OctaneSociety\Http\Controllers;

use Auth;
use Input;
use Mail;
use Log;
use Response;
use View;
use Redirect;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use OctaneSociety\Exceptions\ErrorMessageException;
use OctaneSociety\Helpers\InputValidator;
use OctaneSociety\Services\EdmundsDataService;

use OctaneSociety\Models\car;
use OctaneSociety\Models\User;

class AccessController extends Controller {
	public function showAccessView() {
		if (Auth::check())
			return Redirect::to('dashboard');
		else
	   		return View::make('access/access');
	}

	// local login
	public function processLogin() {
		$input = Input::get('email', 'password');
		$approved = true;

		// attempt to log the user in with the email and password provided 
		if (!Auth::attempt([ 'email' => $input['email'], 'password' => $input['password']]))
			throw new ErrorMessageException('The e-mail address or password is incorrect.');

		// get the user and then check if they have been approved yet. If not then throw error
		$user = Auth::user();
		if ($user->accepted != 1)
			$approved = false;

		return Response::json([
			'success' => true,
			'approved' => $approved
		]);
	}

	// Facebook Login/signup 
	public function processFacebookLogin() {
		$fb = new Facebook([
			'app_id' => '720657848049692',
			'app_secret' => '84f302cf974a7038d5b50105f5e49a9c',
			'default_graph_version' => 'v2.2'
		]);

		$helper = $fb->getJavaScriptHelper();

		try {
		    $accessToken = $helper->getAccessToken();
		}
		catch (FacebookResponseException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook Graph exception occured.');
		}
		catch (FacebookSDKException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook SDK exception occurred.');
		}

		if (!isset($accessToken)) {
			throw new ErrorMessageException('No cookie set or no OAuth data could be obtained from cookie.');
		}
		
	 	try {
	 		$response = $fb->get('/me', $accessToken);
	 	} 
	 	catch (FacebookResponseException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook Graph exception occured.');
		}
		catch (FacebookSDKException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook SDK exception occurred.');
		}

		$fbUser = $response->getGraphUser();

		// Check database for an account with the Facebook ID provided	
		$fbUserId = $fbUser->getId();

		$user = User::where('fb_id', $fbUserId)->first();

		$newUser = false;
		if (!$user) {
			// use facebooks graphUser to get the persons info and then try the sendEmail function and see if you get it
			$user = new User;
			$user->fb_id = $fbUser->getId();
			$user->first_name = $fbUser->getFirstName();
			$user->last_name = $fbUser->getLastName();
			$user->email = $fbUser->getField('email');
			$user->save();

			//1588210491

			// send an email saying thank you for the request 
			//$this->sendNotificationEmail($user);

			// approved is false because they are not approved 
			$approved = false;
			$newUser = true;
		}
		else if ($user->accepted == 0) {

			// approved is false because they are not approved 
			$approved = false;
		}
		else {

			// log the user in 
			Auth::login($user);

			// approved is true because they have been approved 
			$approved = true;
		}

		return Response::json([
			'success' => true,
			'approved' => $approved,
			'new_user' => $newUser,
			'fb_id' => $user->fb_id
		]);
	}

	// local sign up 
	function processSignup() {
		$input = Input::all();
		$user = User::where('fb_id', $input['fb_id'])->first();
		$newUser = false;

		if (!$user) {
			InputValidator::required($input, ['first_name', 'last_name', 'email', 'password', 'style_id']);
			InputValidator::fixInputCase($input['first_name'], $input['last_name']);
			InputValidator::validateEmail($input['email']);

			if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL))
				throw new ErrorMessageException('The email address is not in a proper format.');
			if (User::where('email', $input['email'])->count())
				throw new ErrorMessageException('The e-mail address provided is already registered in this system. Please sign into your account.');
			if (strlen($input['password']) < 8)
				throw new ErrorMessageException('The password provided is too short.');

			// create the user
			$user = new User;
			$user->email = $userInfo['email'];
			$user->password = Hash::make($userInfo['password']);
			$user->first_name = $userInfo['first_name'];
			$user->last_name = $userInfo['last_name'];
			$user->save();

			$newUser = true;
		}
		
		$styleId = $input['style_id'];

		// check if the car is already in our database
		$carLocal = Car::where('x_edmunds_style_id', $styleId)->select('id')->first();

		// if we do not have the car in our database then add it
		if (!$carLocal) {
			// get the car data from Edmunds using the Edmunds style ID
			$style = EdmundsDataService::getStyles(['style_id' => $styleId]);

			// create a new car in our database and then return the ID
			$carLocal = new Car;
			$carLocal->make = $style->make->name;
			$carLocal->model = $style->model->name;
			$carLocal->year = $style->year->year;
			$carLocal->style_name = $style->name;
			$carLocal->x_edmunds_style_id = $style->id;
			$carLocal->save();
		}

		// attach the car to the user
		$user->cars()->attach($carLocal->id);

		// send an email to the admins alerting them of the request
		//$this->sendNotificationEmail($user);

		return Response::json([
			'success' => true,
			'new_user' => $newUser
		]);
	}

	public function processLogout() {
		Auth::logout();

		return Response::json([
			'success' => true
		]);
	}

	public function processPasswordReset() {
		$email = Input::only('e_mail');
		$user = User::where('email', $email)->first();

		if (!$user)
			throw new ErrorMessageException('There were no users found with this e-mail address.');

		// send email to the email address with a link to reset their password
		Mail::send('emails.join-request', ['user' => $user], function($message) use ($user) {
		    $message->to($user->email, $user->first_name + ' ' + $user->last_name)->subject('Octane Society Password reset');
		});

		return Response::json([
			'success' => true,
		]);
	}

	private function sendNotificationEmail($user) {
		// send an email to me with persons credentials
		Mail::send('emails.join-request', ['user' => $user], function($message) {
		    $message->to('octanesocietylife@gmail.com', 'Zachary Beland')->subject('Join Request');
		});

		// send an email saying thank you for the request 
		/*Mail::send('emails.join-request', ['user' => $user], function($message) use ($user) {
		    $message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Octane Society access');
		});*/

		// then send an email to the user when they have been approved
	}
}