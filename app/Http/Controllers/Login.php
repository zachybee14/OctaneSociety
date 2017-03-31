<?php namespace OctaneSociety\Http\Controllers;

use Auth;
use DateTime;
use Request;
use Mail;
use Log;
use Response;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use OctaneSociety\Exceptions\ErrorMessageException;
use OctaneSociety\Helpers\InputValidator;
use OctaneSociety\Services\EdmundsData;
use OctaneSociety\Services\GoogleGeolocationService;

use OctaneSociety\Models\vehicle;
use OctaneSociety\Models\User;

/* This Controller needs to....
    Log user in
    Log user in w/ facebook
    Log user out
    Create new user from user input
    Create new user from Facebook
    Reset users password
    Send email to user (this should probably be a service/job)
*/
class Login extends Controller
{
	public function login()
    {
        $input = Request::only('email', 'password', 'vehicle_style_id');
        InputValidator::required($input, ['email', 'password']);
        InputValidator::validateEmail($input['email']);

        // if we fail to log the user in and there is no vehicle_style_id throw error else create a new user
        if (!Auth::attempt(['email' => $input['email'], 'password' => $input['password']]) && is_null($input['vehicle_style_id'])) {
            throw new ErrorMessageException('No user was found with this email and password.');
        }
        else if (!is_null($input['vehicle_style_id'])) {
            $user = new User;
            $user->email = Hash::make($input['email']);
            $user->password = Hash::make($input['password']);
            $user->created_at = new DateTime();
            $user->status = 'pending';
            /*if ($details['location'])
                $user->location = $details['location'];*/
            $user->save();

            $vehicle = EdmundsData::getStyles(['style_id' => $input['vehicle_style_id']]);
            $user->vehicle()->attach($vehicle->id);

            // send an email to the admins alerting them of the request
            //$this->sendNotificationEmail($user);
        }

        return Response::json([
            'success' => true
        ]);
	}

	public function fbLogin()
    {
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
		    throw new ErrorMessageException('An unexpected Facebook Graph exception occurred.');
		}
		catch (FacebookSDKException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook SDK exception occurred.');
		}

		if (!isset($accessToken))
			throw new ErrorMessageException('No cookie set or no OAuth data could be obtained from cookie.');
		
	 	try {
	 		$response = $fb->get('/me', $accessToken);
	 	} 
	 	catch (FacebookResponseException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook Graph exception occurred.');
		}
		catch (FacebookSDKException $ex) {
		    Log::error($ex);
		    throw new ErrorMessageException('An unexpected Facebook SDK exception occurred.');
		}

		$fbUser = $response->getGraphUser();
		$fbUserId = $fbUser->getId();
		$user = User::where('x_fb_id', $fbUserId)->first();

		// check if there is a user and if the user has been approved
        $input = Request::only('vehicle_style_id');
		if (!$user) {
            $user = new User;
            $user->x_fb_id = $fbUserId;
            $user->first_name = $fbUser->getFirstName();
            $user->last_name = $fbUser->getLastName();
            $user->email = $fbUser->getField('email');
            $user->status = 'pending';
            /*if ($details['location'])
                $user->location = $details['location'];*/
            $user->created_at = new DateTime();
            $user->save();

            if (!is_null($input['vehicle_style_id'])) {
                $vehicle = EdmundsData::getStyles(['style_id' => $input['vehicle_style_id']]);
                $user->vehicle()->attach($vehicle->id);
            }

            // send an email to the admins alerting them of the request
            //$this->sendNotificationEmail($user);
		}
		else if ($user->accepted == 0)
			throw new ErrorMessageException($user->first_name . ', your account is waiting approval.');
		else
			Auth::login($user);

		return Response::json([
			'success' => true,
			'facebook_id' => $user->x_fb_id
		]);
	}

	public function processLogout() {
		Auth::logout();

		return Response::json([
			'success' => true
		]);
	}

	public function processPasswordReset() {
		$email = Request::only('e_mail');
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