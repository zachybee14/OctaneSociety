<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\User;
use OctaneSociety\Models\Cruise;
use OctaneSociety\Models\Place;
use OctaneSociety\Models\Comment;
use OctaneSociety\Models\Vehicle\Make;

class Event extends Eloquent
{
	protected $table = 'events';
	public $timestamps = false;

	public function users()
    {
		return $this->belongsToMany(User::class, 'users_events', 'event_id')->withPivot('status', 'created_at');
	}

    public function makes()
    {
        return $this->belongsToMany(Make::class, 'events_vehicle_makes', 'event_id', 'make_id');
    }

	public function cruises()
    {
		return $this->belongsToMany(Cruise::class, 'events_cruises', 'event_id')->withPivot('created_at');
	}

    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }
}