<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function pizzas()
    {
        return $this->hasMany('App\Models\Pizza');
    }


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }


    public function employees()
    {
        return $this->belongsToMany('App\Models\Employee');
    }


    public function comments()
    {
        return $this->morphToMany('App\Models\Comment', 'commentable');
    }
}
