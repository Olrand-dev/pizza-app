<?php

namespace App\Models;

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


    public static $permissionsList = [
        'viewAny',
        'create',
        'update',
        'forceDelete',
        'uiButtonAddNew',
        'uiButtonEdit',
        'uiButtonDetails',
        'uiButtonDelete',
    ];


    public function type()
    {
        return $this->belongsTo('App\Models\ProductType');
    }


    public function stocks()
    {
        return $this->belongsToMany('App\Models\Stock');
    }


    public function sets()
    {
        return $this->belongsToMany(
            'App\Models\PizzaSet',
            'pizzaset_product',
            'product_id',
            'pizzaset_id'
        );
    }
}
