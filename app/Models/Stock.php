<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }


    public function comments()
    {
        return $this->morphToMany('App\Models\Comment', 'commentable');
    }
}
