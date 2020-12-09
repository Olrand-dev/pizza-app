<?php

namespace App\Policies;

use App\Http\Controllers\Controller;
use App\Models\User;

class Policy
{
    protected function checkPermission(User $user, array $permissionsMap) : bool
    {
        return Controller::checkPermission($user, $permissionsMap);
    }
}
