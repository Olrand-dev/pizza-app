<?php

namespace App\Models;

use Carbon\Carbon;

class Customer extends Model
{
    protected $appends = [
        'registered_at',
    ];

    public $permissionsList = [
        'viewAny',
        'create',
        'update',
        'forceDelete',
        'uiButtonAddNew',
        'uiButtonEdit',
        'uiButtonDelete',
    ];


    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }


    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }


    public function getRegisteredAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y, H:i');
    }
}
