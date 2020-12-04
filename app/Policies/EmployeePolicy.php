<?php

namespace App\Policies;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function view(User $user, Employee $employee)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function update(User $user, Employee $employee)
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function delete(User $user, Employee $employee)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function restore(User $user, Employee $employee)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function forceDelete(User $user, Employee $employee)
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    public function uiButtonAddNew(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    public function uiButtonEdit(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    public function uiButtonDelete(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }
}
