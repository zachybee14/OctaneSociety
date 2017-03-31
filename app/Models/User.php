<?php namespace OctaneSociety\Models;

use Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use OctaneSociety\Models\Vehicle;
use OctaneSociety\Models\Event;
use OctaneSociety\Models\Cruise;
use OctaneSociety\Models\Comment;
use OctaneSociety\Models\Permission;

class User extends Eloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

	protected $table = 'users';
    public $timestamps = false;

	public function permission()
    {
		return $this->belongsTo(Permission::class);
	}

	public function vehicle()
    {
		return $this->belongsToMany(Vehicle::class, 'users_vehicles');
	}

	public function events()
    {
		return $this->belongsToMany(Event::class, 'users_events', 'user_id')->withPivot('status', 'created_at');
	}

	public function cruises()
    {
		return $this->belongsToMany(Cruise::class, 'users_cruises', 'user_id')->withPivot('status', 'created_at');
	}

	public function friends()
    {
        return $this->belongsToMany(self::class, 'users_friends', 'user_id')->withPivot('status', 'updated_at', 'created_at');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'target');
    }
}