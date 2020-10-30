<?php

namespace App\Models;

class Customer extends Model
{
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }


    public function user()
    {
        return $this->hasOne('App\Models\User');
    }
}
