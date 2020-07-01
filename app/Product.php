<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function type()
    {
        return $this->belongsTo('App\ProductType');
    }


    public function stocks()
    {
        return $this->belongsToMany('App\Stock');
    }


    public function sets()
    {
        return $this->belongsToMany('App\PizzaSet', 'pizzaset_product', 'product_id', 'pizzaset_id');
    }
}
