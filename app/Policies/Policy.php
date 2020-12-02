<?php

namespace App\Policies;

use App\Models\User;

class Policy
{
    protected function checkPermission(User $user, array $permissionsMap) : bool
    {
        $permission = $permissionsMap[$user->role->slug] ?? null;
        return !empty($permission) and $permission === 1;
    }
}
