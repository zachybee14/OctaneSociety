<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Cruise;
use OctaneSociety\Models\Event;

class Place extends Eloquent {
	protected $table = 'places';
	public $timestamps = false;

	public function cruises() {
		return $this->belongsToMany( Cruise::class, 'cruises_stops', 'place_id')->withPivot('status', 'created_at');
	}
}