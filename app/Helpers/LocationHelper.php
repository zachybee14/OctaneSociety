<?php namespace OctaneSociety\Helpers;

class LocationHelper {
	public static function calculateMaxLonLat($radiusMiles, $currentLat, $currentLon) {
		// one mile = 1.60934 km
		$radiusKm = $radiusMiles * 1.60934;

		// in km
		$r_earth = 6378;
		$pi = 3.14159;
		
		$latNorth = round(($currentLat + ($radiusKm / $r_earth) * (180 / $pi)), 6);
		$latSouth = round(($currentLat - ($radiusKm / $r_earth) * (180 / $pi)), 6);
		$lonWest = round(($currentLon - ($radiusKm / $r_earth) * (180 / $pi) / cos($currentLat * $pi / 180)), 6);
		$lonEast = round(($currentLon + ($radiusKm / $r_earth) * (180 / $pi) / cos($currentLat * $pi / 180)), 6);
		

		return (object)[
			'lat_north' => $latNorth,
			'lat_south' => $latSouth,
			'lon_west' => $lonWest,
			'lon_east' => $lonEast
		];
	}

	// source http://www.geodatasource.com/developers/php
	public static function calculateDistanceMiles($currentLat, $currentLon, $newLat, $newLon) {
		$theta = $currentLon - $newLon;
		$distance = sin(deg2rad($currentLat)) * sin(deg2rad($newLat)) +  cos(deg2rad($currentLat)) * cos(deg2rad($newLat)) * cos(deg2rad($theta));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$miles = $distance * 60 * 1.1515;

		return $miles;
	}
}