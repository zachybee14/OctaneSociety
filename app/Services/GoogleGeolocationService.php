<?php namespace OctaneSociety\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

/*	
	Google Maps Development API Server key: AIzaSyCVQIe19EhB0-_5FCJLL4dFZ3JkuryvUC0
*/

class GoogleGeolocationService {
	const GOOGLE_MAPS_API_KEY = 'AIzaSyCVQIe19EhB0-_5FCJLL4dFZ3JkuryvUC0';

	public static function geocodeAddress($address) {
		
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . rawurlencode($address['street']) . ',' . rawurlencode($address['city']) . ',' . $address['state'] . '&key=';

		$response = self::sendRequest($url);

		return $response;
	}

	private static function sendRequest($urlData) {
		// create guzzle client
		$client = new GuzzleClient;

		// get the models from Edmunds
		$response = $client->get($urlData . self::GOOGLE_MAPS_API_KEY);
		$body = $response->getBody();
		$bodyObj = json_decode($body->getContents());
		
		return $bodyObj;
	}
}