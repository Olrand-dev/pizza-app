<?php

namespace App\Models;

use Carbon\Carbon;

class Order extends Model
{
    protected $appends = [
        'ordered_at',
        'last_updated_at',
    ];


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }


    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }


    public function status()
    {
        return $this->belongsTo('App\Models\OrderStatus');
    }


    public function employees()
    {
        return $this->belongsToMany(
            'App\Models\Employee',
            'employee_order',
            'order_id',
            'employee_id'
        )
            ->withTimestamps();
    }


    public function products()
    {
        return $this->belongsToMany(
            'App\Models\Product',
            'order_product',
            'order_id',
            'product_id'
        )
            ->as('connection')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function pizzaSets()
    {
        return $this->belongsToMany(
            'App\Models\PizzaSet',
            'order_pizzaset',
            'order_id',
            'pizzaset_id'
        )
            ->as('connection')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function getOrderedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y, H:i');
    }


    public function getLastUpdatedAtAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d-m-Y, H:i');
    }
}
