<?php namespace OctaneSociety\Models\Vehicle;

use Eloquent;

use OctaneSociety\Models\Event;

class Make extends Eloquent {
	protected $table = 'vehicle_makes';
	public $timestamps = false;

    public function events()
    {
        return $this->belongsToMany(Event::class, 'events_vehicle_makes', 'make_id', 'event_id');
    }
}