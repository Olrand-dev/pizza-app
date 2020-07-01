<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function pizzas()
    {
        return $this->hasMany('App\Pizza');
    }


    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }


    public function employees()
    {
        return $this->belongsToMany('App\Employee');
    }


    public function comments()
    {
        return $this->morphToMany('App\Comment', 'commentable');
    }
}
