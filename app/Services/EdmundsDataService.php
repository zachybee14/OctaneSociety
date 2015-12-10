<?php namespace OctaneSociety\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

/*	
	this service is to get data from Edmunds API. Primarily used to get Car Makes and Models
	Edmonds API key: 4eu432shmff83ppynaykt54f
*/

class EdmundsDataService {
	const EDMUNDS_API_KEY = '4eu432shmff83ppynaykt54f';

	public static function getModels($make) {
		$response = self::sendRequest(rawurlencode($make));
		$models = $response->models;
		
		return $models;
	}

	public static function getStyles($styleInfo) {
		if ($styleInfo['style_id'])
			$url = 	'styles/' . $styleInfo['style_id'];
		else 
			$url = rawurlencode($styleInfo['make']) . '/' . rawurlencode($styleInfo['model']) . '/' . rawurlencode($styleInfo['year']) . '/styles';

		$response = self::sendRequest($url);

		if ($styleInfo['style_id'])
			$stylesData = $response;
		else	
			$stylesData = $response->styles;

		return $stylesData;
	}

	private static function sendRequest($urlData) {
		// create guzzle client
		$client = new GuzzleClient;

		// get the models from Edmunds
		$response = $client->get('http://api.edmunds.com/api/vehicle/v2/'  . $urlData . '?fmt=json&api_key=' . self::EDMUNDS_API_KEY);
		$body = $response->getBody();
		$bodyObj = json_decode($body->getContents());
		
		return $bodyObj;
	}
}