<?php namespace OctaneSociety\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

use OctaneSociety\Models\vehicle;

/*	
	this service is to get data from Edmunds API. Primarily used to get Car Makes and Models
	Edmonds API key: 4eu432shmff83ppynaykt54f
*/

class EdmundsData {
	const EDMUNDS_API_KEY = '4eu432shmff83ppynaykt54f';

    public static function getMakes($year) {
        $response = self::sendRequest('makes', [ 'year' => $year ]);
        $makes = $response->makes;

        return $makes;
    }

	public static function getStyles($year, $make, $model) {
		$response = self::sendRequest($make . '/' . $model . '/' . $year);
		$styles = $response->styles;

		return $styles;
	}

    public static function getStyle($styleId) {
        $response = self::sendRequest('styles/' . $styleId, [ 'view' => 'full' ]);

        return $response;
    }

	private static function sendRequest($url, $params = []) {
        // fill in extra parameters
        $params['fmt'] = 'json';
        $params['api_key'] = self::EDMUNDS_API_KEY;

		// create guzzle client
		$client = new GuzzleClient;

		// get the models from Edmunds
        $url = 'https://api.edmunds.com/api/vehicle/v2/' . $url . '?' . http_build_query($params);
        $response = $client->get($url);
		$body = $response->getBody()->getContents();

		return json_decode($body);
	}
}