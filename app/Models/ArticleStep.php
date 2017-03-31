<?php namespace OctaneSociety\Models;

use Eloquent;

use OctaneSociety\Models\Product;
use OctaneSociety\Models\Tool;

class ArticleStep extends Eloquent
{
    protected $table = 'articles_steps';
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function tools()
    {
        return $this->belongsToMany(Tool::class);
    }
}