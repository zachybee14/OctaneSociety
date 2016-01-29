<?php namespace OctaneSociety\Http\Controllers;

use Auth;
use Input;
use Mail;
use Log;
use Response;
use View;
use Redirect;

use OctaneSociety\Exceptions\ErrorMessageException;
use OctaneSociety\Helpers\InputValidator;
use OctaneSociety\Services\GoogleGeolocationService;

use OctaneSociety\Models\User;
use OctaneSociety\Models\Event;

class EventsController extends Controller {
	public function showEventPage($id) {
		$event = Event::findOrFail($id);

		return View::make('events/events', ['event' => $event]);
	}

	public function getEvents() {
		$events = Event::get();

		$user = Auth::user();

		if (is_null($user->address)) {
			$address = $user->zip;
		}
		else {
			$address = [
				'address' => $user->address,
				'city' => $user->city,
				'state' => $user->state,
				'zip' => $user->zip
			];
		}

		return Response::json([
			'success' => true,
			'events' => $events,
			'user_address' => $address
		]);
	}

	public function createEvent() {
		$input = Input::only('fb_id', 'name', 'type', 'description', 'start_time', 'location');
		$address = $input['location'];
		$dateTime = explode('T',  $input['start_time']);
		$startDate = $dateTime[0];
		$startTime = $dateTime[1];
		$user = Auth::user();

		if (count($address) == 4) {

			$geocode = $this->getGeocodedAddress($address);
			$latitude = $geocode->geometry->location->lat;
			$longitude = $geocode->geometry->location->lng;
		}
		else {
			$latitude = $address['latitude'];
			$longitude = $address['longitude'];
		}

		$event = Event::where('date_start', $startDate)
				->where('x_latitude', $latitude)
				->where('x_longitude', $longitude)
				->first();
			
		if ($event)
			throw new ErrorMessageException('There is already an event going on at that location on those days.');

		// check if the type is actually one of the types
		$event = new Event;
		$event->x_fb_id = $input['fb_id'];
		$event->creator_id = $user->id;
		$event->name = $input['name'];
		$event->type = $input['type'];
		$event->description = $input['description'];
		//$event->phone = $input['phone'];
		//$event->e_mail = $input['email'];
		$event->address = $address['street'];
		$event->city = $address['city'];
		$event->state = $address['state'];
		$event->zip = $address['zip'];
		$event->x_latitude = $latitude;
		$event->x_longitude = $longitude;
		$event->date_start = $startDate;
		$event->time_start = $startTime;
		$event->save();

		return Response::json([
			'success' => true,
			'event_id' => $event->id,
		]);
	}

	private function getGeocodedAddress($address) {
		$geocode = GoogleGeolocationService::geocodeAddress($address);

		return $geocode->results[0];
	}
}