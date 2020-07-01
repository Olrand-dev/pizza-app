<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PizzaSet extends Model
{
    public function products()
    {
        return $this->belongsToMany('App\Product', 'pizzaset_product', 'pizzaset_id', 'product_id');
    }
    

    public function pizzas()
    {
        return $this->hasMany('App\Pizza');
    }
}
