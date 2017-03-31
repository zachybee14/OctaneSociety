<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\ArticleStep;

class Product extends Eloquent {
	protected $table = 'products';
	public $timestamps = false;

    public function articleSteps()
    {
        return $this->belongsToMany(ArticleStep::class);
    }
}