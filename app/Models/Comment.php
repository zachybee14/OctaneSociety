<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\User;
use OctaneSociety\Models\Event;
use OctaneSociety\Models\Article;
use OctaneSociety\Models\Blog;
use OctaneSociety\Models\Cruise;

class Comment extends Eloquent
{
	protected $table = 'comments';
	public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->morphTo();
    }
}