<?php namespace OctaneSociety\Models;

use Eloquent;

class Tool extends Eloquent {
    protected $table = 'tools';
    public $timestamps = false;

    public function articleSteps()
    {
        return $this->belongsToMany(ArticleStep::class);
    }
}