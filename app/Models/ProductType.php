<?php

namespace App\Models;

class ProductType extends Model
{
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
