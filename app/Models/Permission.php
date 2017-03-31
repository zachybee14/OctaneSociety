<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\User;

class Permission extends Eloquent {
	protected $table = 'permissions';
	public $timestamps = false;

    public function users() {
        return $this->hasMany(User::class);
    }
}