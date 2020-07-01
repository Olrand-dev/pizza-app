<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }


    public function comments()
    {
        return $this->morphToMany('App\Comment', 'commentable');
    }
}
