<?php namespace OctaneSociety\Http\Controllers;

use Auth;
use Request;
use DB;
use Response;
use DateTime;
use DateInterval;

use OctaneSociety\Exceptions\ErrorMessageException;

use OctaneSociety\Services\GoogleGeolocation;
use OctaneSociety\Services\CreatePlace;
use OctaneSociety\Helpers\LocationHelper;
use OctaneSociety\Helpers\InputValidator;

use OctaneSociety\Models\Place;
use OctaneSociety\Models\Event;
use OctaneSociety\Models\Comment;
use OctaneSociety\Models\Event\Day;
use OctaneSociety\Models\Vehicle\Make;

/*
	Events can have cruises to them
	Cruises do not need events 
*/

class Events extends Controller
{
	public function index()
	{
		$events = Event::get();

		return Response::json([
			'success' => true,
			'events' => $events
		]);
	}
	
	public function show($id)
	{
		$event = Event::findOrFail($id);

		return Response::json([
			'success' => true,
			'events' => $event
		]);
	}

	public function getFilteredEvents()
	{
		$input = Request::only('radius', 'limit', 'make_id', 'trending', 'mutual_friends');
		$radius = is_numeric($input['radius']) ?: 30;

		// to narrow the amount of events we create max degrees for N, S, E, W from the user set latitude and longitude
		//$maxDeg = LocationHelper::calculateMaxLonLat($radius, $user->latitude, $user->longitude);
		$maxDeg = LocationHelper::calculateMaxLonLat($radius, 42.756368, -71.465578);

		// make sure the limit is no less than 10
        $limit = $input['limit'];
		if (!is_numeric($limit) || $limit < 10)
			$limit = 10;

        $query = Event::query();
		if ($input['trending'] == 'true' || $input['mutual_friends'] == 'true') {
            $query = $query->join('users_events', 'events.id', '=', 'users_events.event_id')
                ->whereIn('events.place_id', function($query) use ($maxDeg) {
                    $query->select('id')->from('places')
                        ->whereBetween('latitude', [ $maxDeg->lat_south, $maxDeg->lat_north ])
                        ->whereBetween('longitude', [ $maxDeg->lon_west, $maxDeg->lon_east ]);
                })
                ->whereIn('users_events.status', ['going', 'interested'])
                ->groupBy('events.id')
                ->limit($limit);

		    if ($input['mutual_friends'] == 'true') {
                $events = $query->join('users_friends', 'users_friends.friend_id', '=', 'users_events.user_id')
                    ->where('users_friends.user_id', Auth::id()) //$user->id
                    ->where('users_friends.status', 'accepted')
                    ->get([ 'events.*', DB::raw('count(users_events.event_id) as friends_interested_count') ]);
            }
            else {
                $events = $query->where('users_events.created_at', '>=', (new DateTime)->sub(new DateInterval('PT1H')))
                    ->orderBy('action_count', 'desc')
                    ->get([ 'events.*', 'users_events.created_at', DB::raw('count(users_events.event_id) as action_count') ]);
            }
		}
		else {
			$query->whereIn('place_id', function($query) use ($maxDeg) {
				$query->select('id')->from('places')
                    ->whereBetween('latitude', [ $maxDeg->lat_south, $maxDeg->lat_north ])
                    ->whereBetween('longitude', [ $maxDeg->lon_west, $maxDeg->lon_east]);
			});

			// if there is a Make then add the Make to the query
			if (is_numeric($input['make_id'])) {
			    if (!$make = Make::find($input['make_id']))
			        throw new ErrorMessageException('No vehicle make was found with ID ' . $input['make_id']);

                $query->join('events_vehicle_makes', 'events.id', '=', 'events_vehicle_makes.event_id')->where('events_vehicle_makes.make_id', '=', $input['make_id']);
            }

			// get the events 
			$events = $query->limit($limit)->get(['id', 'name', 'type', 'description', 'place_id']);
		}

        $eventMap = [];
        foreach ($events as $event)
            $eventMap[$event->id] = $event;

        // calculate each events distance from the center location
        $eventDistances = [];
        foreach ($events as $event) {
            $place = Place::find($event->place_id);
            $distance = LocationHelper::calculateDistanceMiles(42.752117, -71.451216, $place->latitude, $place->longitude);
            $eventDistances[$event->id] = round($distance, 2);
        }

        // sort the array from smallest distance to largest
        asort($eventDistances);

        $events = [];
        foreach ($eventDistances as $id => $distance) {
            // if the event distance is outside of the radius then break out of the loop
            if ($distance > $radius)
                break;

            $event = $eventMap[$id];
            $events[] = $event;
        }

        if (empty($events))
            $events = NULL;

		return Response::json([
			'success' => true,
			'events' => $events
		]);
	}

	public function createEvent()
	{
		$input = Request::only('fb_id', 'name', 'type', 'description', 'days', 'location', 'phone', 'email');
        InputValidator::required($input, ['name', 'type', 'description', 'location']);

		//$user = Auth::user();
        $address = $input['location'];
		if (is_null($address['street']) || strlen($address['street']) == 0)
			throw new ErrorMessageException('Street address is required.');

		if (is_null($address['city']) || strlen($address['city']) == 0)
			throw new ErrorMessageException('City name is required.');

		if (is_null($address['state']) || strlen($address['state']) == 0)
			throw new ErrorMessageException('State is required.');

		if (is_null($address['zip']) || strlen($address['zip']) == 0)
			throw new ErrorMessageException('ZIP code is required.');

		$event = Event::where('name', $input['name'])->first();
		if ($event)
			throw new ErrorMessageException('An event with the name "' . $input['name'] . '" already exists.');

		// get the latitude and longitude of the event location
		$geoLatLon = $this->getGeocodedAddress($address);

		// Check if there is a Place with this lat/long
		$place = Place::where('latitude', $geoLatLon->lat)->where('longitude', $geoLatLon->lng)->first();
		if (!is_null($place)) {

			// if there is a place then check if any Events have this place_id
			$event = Event::where('place_id', $place->id)->first();
			if (!is_null($event)) {

				// if there is then loop through the dates and check the events_days table for any matches
				foreach ($input['days'] as $day) {

					// if there are any event days where the dates match throw an error
					$dateConflict = Day::where('date', $day['date'])->first();
					if ($dateConflict)
						throw new ErrorMessageException('There is already an event going on at this location on ' . $day['date'] . '.');
				}
			}
		}

		DB::beginTransaction();

		// if there is no existing place then create one
		if (is_null($place))
			$place = $this->createPlace($address, $geoLatLon->lat, $geoLatLon->lng);

		// check if the type is actually one of the types
		$event = new Event;
		$event->x_fb_id = $input['fb_id'];
		$event->creator_id = Auth::id();
		$event->creator_id = 1;
		$event->place_id = $place->id;
		$event->name = $input['name'];
		$event->type = $input['type'];
		$event->description = $input['description'];
		$event->created_at = new DateTime;

		if (!is_null($input['phone']) && strlen($input['phone']) != 0)
			$event->phone = $input['phone'];

		if (!is_null($input['email']) && strlen($input['email']) != 0)
			$event->e_mail = $input['email'];

		$event->save();

		// create each event day
		foreach ($input['days'] as $day) {
            InputValidator::required($day, ['date', 'start_time', 'end_time']);

			$eventDay = new Day;
			$eventDay->event_id = $event->id;
			$eventDay->date = $day['date'];
			$eventDay->time_start = $day['start_time'];
			$eventDay->time_end = $day['end_time'];
			$eventDay->created_at = new DateTime;
			$eventDay->save();
		}

		DB::commit();

		return Response::json([
			'success' => true,
			'event_id' => $event->id,
		]);
	}

	public function getEventCruises($eventId)
	{
		$event = Event::findOrfail($eventId);
		$cruises = $event->cruises();

		return Response::json([
			'success' => true,
			'event_id' => $eventId,
			'cruises' => $cruises
		]);
	}

	public function addUserStatus($eventId)
	{
		$event = Event::findOrFail($eventId);
        $input = Request::only('status');

        if (!in_array($input['status'], ['going','interested','ignored']))
            throw new ErrorMessageException('Invalid status ' . $input['status']);

		$eventUsers = $event->users();
		$eventUsers->attach(Auth::id(), ['status' => $input['status'], 'created_at' => new DateTime]);

		return Response::json([
			'success' => true
		]);
	}

	public function addComment($eventId)
	{
        $event = Event::findOrFail($eventId);
		$input = Request::only('text');

        if (strlen($input['text']) < 1)
            throw new ErrorMessageException('Comments must contain text');

		$comment = new Comment;
		$comment->user_id = Auth::id();
		$comment->target_type = 'event';
		$comment->target_id = $event->id;
		$comment->text = $input['text'];
		$comment->created_at = new DateTime;
		$comment->save();

		return Response::json([
			'success' => true
		]);
	}

	private function getGeocodedAddress($address) 
	{
		$geocode = GoogleGeolocation::geocodeAddress($address);
		$location =  $geocode->results[0]->geometry->location;

		return $location;
	}

	private function createPlace($location ,$latitude, $longitude) {
		return CreatePlace::createPlace($location['street'], $location['city'], $location['state'], $location['zip'], $latitude, $longitude);
	}
}