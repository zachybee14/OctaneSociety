<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Article;

class Vehicle extends Eloquent {
	protected $table = 'vehicles';
	public $timestamps = false;

	public function users() {
		return $this->belongsToMany('OctaneSociety\Models\User', 'users_vehicles');
	}

	public function articles() {
        return $this->belongsToMany(Article::class);
    }
}