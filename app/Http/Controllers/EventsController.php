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
	public function createEventView() {
		return View::make('events/events');
	}

	public function createNewEventView() {
		return View::make('events/create-event');
	}

	public function getEvents() {
		$events = Event::get();

		return Response::json([
			'success' => true,
			'events' => $events
		]);
	}

	public function createEvent() {
		$input = Input::all();
		$user = User::findOrFail($input['creator_id']);
		//$user = Auth::user();

		//$address = $input['full_address'];

		$address = [
			'address' => $input['address'],
			'city' => $input['city'],
			'state' => $input['state'],
			'zip' => $input['zip']
		];

		$geocode = $this->getGeocodedAddress($address);
		$latitude = $geocode->geometry->location->lat;
		$longitude = $geocode->geometry->location->lng;

		
		$event = Event::where('date_start', $input['start_date'])
				->where('x_latitude', $latitude)
				->where('x_longitude', $longitude)
				->first();

		if ($event)
			throw new ErrorMessageException('There is already an event going on at that location on those days.');

		$event = new Event;
		$event->creator_id = $user->id;
		$event->name = $input['name'];
		$event->phone = $input['phone'];
		$event->e_mail = $input['email'];
		$event->address = $address['address'];
		$event->city = $address['city'];
		$event->state = $address['state'];
		$event->zip = $address['zip'];
		$event->x_latitude = $latitude;
		$event->x_longitude = $longitude;
		$event->date_start = $input['start_date'];
		$event->date_end = $input['end_date'];
		$event->save();

		return Response::json([
			'success' => true,
			'event_id' => $event->id
		]);
	}

	private function getGeocodedAddress($address) {
		$geocode = GoogleGeolocationService::geocodeAddress($address);

		return $geocode->results[0];
	}
}