<?php

namespace App\Models;

class PizzaSet extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'image_url',
    ];


    public function products()
    {
        return $this->belongsToMany(
            'App\Models\Product',
            'pizzaset_product',
            'pizzaset_id',
            'product_id'
        )
            ->as('connection')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function pizzas()
    {
        return $this->hasMany('App\Models\Pizza');
    }


    public function getImageUrlAttribute()
    {
        return (!empty($this->image)) ?
            asset('storage/' . $this->image) :
            asset('storage/system/no_photo.png');
    }
}
