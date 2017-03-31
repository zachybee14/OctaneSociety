<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Comment;

class Blog extends Eloquent
{
	protected $table = 'blogs';
	public $timestamps = false;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }
}