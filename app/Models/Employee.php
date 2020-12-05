<?php

namespace App\Models;

use Carbon\Carbon;

class Employee extends Model
{
    protected $appends = [
        'registered_at',
    ];

    public static $permissionsList = [
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
        return $this->belongsToMany('App\Models\Order');
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
