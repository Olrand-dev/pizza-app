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
        return $this->morphOne('App\Models\User', 'userable');
    }


    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
}
