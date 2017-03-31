<?php namespace OctaneSociety\Services;

use DateTime;

use OctaneSociety\Models\Place;

class CreatePlace
{
    public static function createPlace($street, $city, $state, $zip, $latitude, $longitude)
    {

        $place = new Place;
        $place->street = $street;
        $place->city = $city;
        $place->state = $state;
        $place->zip = $zip;
        $place->latitude = $latitude;
        $place->longitude = $longitude;
        $place->created_at = new DateTime;
        $place->save();

        return $place;
    }
}