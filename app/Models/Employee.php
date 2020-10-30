<?php

namespace App\Models;

class Employee extends Model
{
    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }


    public function user()
    {
        return $this->hasOne('App\Models\User');
    }


    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
}
