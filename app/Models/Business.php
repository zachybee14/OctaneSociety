<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Article;
use OctaneSociety\Models\Comment;

class Business extends Eloquent {
	protected $table = 'businesses';
	public $timestamps = false;

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withPivot('type');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }
}