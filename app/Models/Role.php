<?php

namespace App\Models;

class Role extends Model
{
    public function employees()
    {
        return $this->hasMany('App\Models\Employee');
    }
}
