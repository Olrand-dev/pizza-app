<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PizzaSet extends Model
{
    protected $fillable = [
        'name',
    ];


    public function products()
    {
        return $this->belongsToMany(
            'App\Models\Product',
            'pizzaset_product',
            'pizzaset_id',
            'product_id'
        )->as('connection')->withTimestamps();
    }


    public function pizzas()
    {
        return $this->hasMany('App\Models\Pizza');
    }
}
