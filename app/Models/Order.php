<?php

namespace App\Models;

class Order extends Model
{
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
        return $this->morphMany('App\Models\Comment', 'commentable');
    }


    public function status()
    {
        return $this->belongsTo('App\Models\OrderStatus');
    }


    public function products()
    {
        return $this->belongsToMany(
            'App\Models\Product',
            'order_product',
            'order_id',
            'product_id'
        )->as('connection')->withTimestamps();
    }


    public function pizzasets()
    {
        return $this->belongsToMany(
            'App\Models\PizzaSet',
            'order_pizzaset',
            'order_id',
            'pizzaset_id'
        )->as('connection')->withTimestamps();
    }
}
