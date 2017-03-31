<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Comment;
use OctaneSociety\Models\Business;
use OctaneSociety\Models\Vehicle;

class Article extends Eloquent
{
	protected $table = 'articles';
	public $timestamps = false;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }

    public function businesses()
    {
        return $this->belongsToMany(Business::class)->withPivot('type');
    }

    public function vehicles() {
        return $this->belongsToMany(Vehicle::class);
    }
}