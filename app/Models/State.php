<?php namespace OctaneSociety\Models;

use Eloquent;

class State extends Eloquent {
	protected $table = 'state_abbreviations';
	public $timestamp = false;
}