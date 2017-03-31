<?php namespace OctaneSociety\Models\Shop;

use Eloquent;

class Product extends Eloquent {
	protected $table = 'shop_products';
	public $timestamps = false;
}