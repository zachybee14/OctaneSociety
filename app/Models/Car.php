<?php namespace OctaneSociety\Models;

use Eloquent;

class Car extends Eloquent {
	protected $table = 'cars';
	public $timestamps = false;

	public function users() {
		return $this->belongsToMany('OctaneSociety\Models\User', 'users_cars');
	}
}