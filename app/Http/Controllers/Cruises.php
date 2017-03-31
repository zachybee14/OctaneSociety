<?php namespace OctaneSociety\Http\Controllers;

use DB;
use Auth;
use Request;
use Response;
use DateTime;
use DateInterval;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Helpers\InputValidator;
use OctaneSociety\Helpers\LocationHelper;
use OctaneSociety\Exceptions\ErrorMessageException;
use OctaneSociety\Services\GoogleGeolocationService;
use OctaneSociety\Services\CreatePlaceService;

use OctaneSociety\Models\User;
use OctaneSociety\Models\Event;
use OctaneSociety\Models\Cruise;
use OctaneSociety\Models\Place;
use OctaneSociety\Models\Event\Day;
use OctaneSociety\Models\Event\User as UserStatus;

/*
	This controller will be in charge or adding new cruises and linking them to the event, as well as
	adding users to the cruise via the events_cruises_users pivot table
*/
class Cruises extends Controller
{
	public function getCruise($id) {
		$cruise = Cruise::findOrFail($id);

		return Response::json([
			'success' => true,
			'cruise' => $cruise
		]);
	}

	public function createCruise()
	{
		$input = Request::only('event_id', 'name', 'start_time', 'estimated_duration', 'start_location', 'end_location', 'stops');
        InputValidator::required($input, ['name', 'start_time', 'start_location']);

		if (is_null($input['estimated_duration']))
			$input['estimated_duration'] = '5';

		$event = null;
		$endPlace = null;
		if (!is_null($input['event_id']) && strlen($input['name']) > 0) 
		{
			$event = Event::find($input['event_id']);
			if (is_null($event))
				throw new ErrorMessageException('No event was found with the ID: ' . $input['event_id']);

			// set the events location to the cruises end place
			$endPlace = Place::find($event->place_id);
			if (is_null($endPlace))
				throw new ErrorMessageException('No place was found with the ID: ' . $event->place_id);
		} 
		else if (is_null($input['end_location']))
		{
			throw new ErrorMessageException('A cruise with no event is required to have end_location');
		}

		// geoencode the location and check if it worked
		$startLatLon = $this->getGeocodedAddress($input['start_location']);
		if (is_null($startLatLon))
			throw new ErrorMessageException('There was an error geocoding the location');

		DB::beginTransaction();

		$startPlace = Place::where('latitude', $startLatLon->lat)->where('longitude', $startLatLon->lng)->first();
		if (is_null($startPlace)) 
		{
			$startPlace = $this->createPlace($input['start_location'], $startLatLon->lat, $startLatLon->lng);
		}

		if (is_null($endPlace)) 
		{
			$endtLatLon = $this->getGeocodedAddress($input['end_location']);
			$endPlace = $this->createPlace($input['end_location'], $endtLatLon->lat, $endtLatLon->lng);
		}

		$cruise = new Cruise;
		$cruise->creator_id = Auth::id();
		$cruise->creator_id = 1;
		$cruise->name = $input['name'];
		$cruise->start_time = $input['start_time'];
		//$cruise->estimated_duration = $input['estimated_duration'];
		$cruise->start_place_id = $startPlace->id;
		$cruise->end_place_id = $endPlace->id;
		$cruise->created_at = new DateTime;
		$cruise->save();

		if (!is_null($event)) 
		{
			$event->cruises()->save($cruise, [
				'created_at' => new DateTime
			]);
		}

		// if there are stops then add them to the cruise
		if (!is_null($input['stops']))
		{
			foreach ($input['stops'] as $stop)
			{
				$stopLatLon = $this->getGeocodedAddress($stop);

				if (is_null($stopLatLon))
					throw new ErrorMessageException('There was an error geocoding the Stop');

				// if there is no place with this latitude and longitude then create one
				$stopPlace = Place::where('latitude', $stopLatLon->lat)->where('longitude', $stopLatLon->lng)->first();
				if (is_null($stopPlace))
					$stopPlace = $this->createPlace($stop, $stopLatLon->lat, $stopLatLon->lng);

				// create a new relation with the $cruise-> and the $place->id plus duration
				$cruiseStops = $cruise->stops()->save($stopPlace, [
					'duration' => $stop['duration'],
					'created_at' => new DateTime
				]);

				$cruiseStops->save();
			}
		}

		DB::commit();

		return Response::json([
			'success' => true
		]);
	}

	public function deleteCruise($cruiseId)
	{
		$cruise = Cruise::findOrFail($cruiseId);

		$cruise->deleted_at = new DateTime;
		$cruise->save();

		return Response::json([
			'success' => true
		]);
	}

	// TEST THIS
	// expects a cruise ID and an array of user IDs
	public function addCruiseUsers()
	{
		$input = Request::only('cruise_id', 'user_ids');
		$cruise = Cruise::findOrFail($input['cruise_id']);

		DB::beginTransaction();

		foreach ($input['user_ids'] as $userId) {
			$user = User::findOrFail($userId);

			$cruiseUser = $cruise->users()->save($user, [
				'created_at' => new DateTime
			]);

			$cruiseUser->save();
		}

		DB::commit();

		return Response::json([
			'success' => true
		]);
	}

	// TEST THIS
	public function getCruiseUsers($cruiseId)
	{
        $cruise = Cruise::findOrFail($cruiseId);
		$input = Request::only('going', 'interested', 'ignored');
		$query = $cruise->users();

		if ($input['going'] == 'true')
            $query->where('status', 'going');

        if ($input['interested'] == 'true')
            $query->where('status', 'interested');

        if ($input['ignored'] == 'true')
            $query->where('status', 'ignored');

		$users = $query->get();

		return Response::json([
			'success' => true,
			'cruise_users' => $users
		]);
	}

	public function addComment($cruiseId)
	{
        $cruise = Cruise::findOrFail($cruiseId);
		$input = Request::only('text');

        if (strlen($input['text']) < 1)
            throw new ErrorMessageException('Comments must contain text');

		$comment = new Comment;
		$comment->user_id = Auth::id();
		$comment->target_type = 'cruise';
		$comment->target_id = $cruise->id;
		$comment->text = $input['text'];
		$comment->created_at = new DateTime;
		$comment->save();

		return Response::json([
			'success' => true
		]);
	}

	private function getGeocodedAddress($address)
	{
		$geocode = GoogleGeolocationService::geocodeAddress($address);
		$location =  $geocode->results[0]->geometry->location;

		return $location;
	}

	private function createPlace($location ,$latitude, $longitude)
    {
		$place = CreatePlaceService::createPlace($location['street'], $location['city'], $location['state'], $location['zip'], $latitude, $longitude);

		return $place;
	}
}
