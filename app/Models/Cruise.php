<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Place;
use OctaneSociety\Models\Event;
use OctaneSociety\Models\User;
use OctaneSociety\Models\Comment;

class Cruise extends Eloquent
{
	protected $table = 'cruises';
	public $timestamps = false;

	// cruises can have many stops
	public function stops()
    {
		return $this->belongsToMany(Place::class, 'cruises_stops', 'cruise_id')->withPivot('duration', 'created_at');
	}

	public function events()
    {
		return $this->belongsToMany(Event::class, 'events_cruises', 'cruise_id')->withPivot('created_at');
	}

	public function users()
    {
		return $this->belongsToMany(User::class, 'users_cruises', 'cruise_id')->withPivot('status', 'created_at');
	}

    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }
}