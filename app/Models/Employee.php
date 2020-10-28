<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
