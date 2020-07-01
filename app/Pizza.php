<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    public function set()
    {
        return $this->belongsTo('App\PizzaSet');
    }


    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function comments()
    {
        return $this->morphToMany('App\Comment', 'commentable');
    }
}
