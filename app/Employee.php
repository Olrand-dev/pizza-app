<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function orders()
    {
        return $this->belongsToMany('App\Order');
    }


    public function user()
    {
        return $this->hasOne('App\User');
    }


    public function role()
    {
        return $this->belongsTo('App\Role');
    }
}
