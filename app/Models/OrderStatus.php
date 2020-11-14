<?php

namespace App\Models;

class OrderStatus extends Model
{
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}
