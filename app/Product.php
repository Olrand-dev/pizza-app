<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cost',
        'weight',
    ];

    protected $attributes = [
        'type_id' => 1,
    ];

    protected $appends = [
        'image_url',
    ];


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


    public function getImageUrlAttribute()
    {
        return (!empty($this->image)) ? 
            asset('storage/' . $this->image) : 
            asset('storage/system/no_photo.png');
    }
}
