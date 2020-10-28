<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    public function set()
    {
        return $this->belongsTo('App\Models\PizzaSet');
    }


    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function comments()
    {
        return $this->morphToMany('App\Models\Comment', 'commentable');
    }
}
