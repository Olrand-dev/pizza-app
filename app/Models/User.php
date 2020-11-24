<?php

namespace App\Models;

use App\Consts\SystemConst;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function userable()
    {
        return $this->morphTo();
    }


    private function checkUserRole(int $roleId) : bool
    {
        if ($this->userable_type !== 'App\Models\Employee') return false;
        return Employee::find($this->userable_id)->role_id === $roleId;
    }


    public function isAdmin() : bool
    {
        return $this->checkUserRole(SystemConst::USER_ROLE_ADMIN);
    }

    public function isManager() : bool
    {
        return $this->checkUserRole(SystemConst::USER_ROLE_MANAGER);
    }

    public function isCourier() : bool
    {
        return $this->checkUserRole(SystemConst::USER_ROLE_COURIER);
    }

    public function isCook() : bool
    {
        return $this->checkUserRole(SystemConst::USER_ROLE_COOK);
    }

    public function isChef() : bool
    {
        return $this->checkUserRole(SystemConst::USER_ROLE_CHEF);
    }
}
