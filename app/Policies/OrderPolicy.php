<?php

namespace App\Policies;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy extends Policy
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
        return $this->checkPermission($user, Controller::getUserPermissionsMap(
            1,
            1,
            1,
            1,
            1
        ));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user)
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
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function restore(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }


    /*
     * Buttons
     */

    public function uiButtonAddNew(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiButtonEdit(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiButtonDetails(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1));
    }

    public function uiButtonGetOrder(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(0, 0, 1, 1, 1));
    }

    public function uiButtonOrderDelivered(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(0, 0, 0, 0, 1));
    }

    public function uiButtonRefuse(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(0, 0, 1, 1, 1));
    }

    public function uiButtonOrderStatusAccept(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiButtonOrderStatusCooking(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 0, 1, 1));
    }

    public function uiButtonOrderStatusReady(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 0, 1, 1));
    }

    public function uiButtonOrderStatusDelivery(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 0, 0, 0, 1));
    }

    public function uiButtonOrderStatusDelivered(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 0, 0, 0, 1));
    }

    public function uiButtonOrderStatusCompleted(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiButtonOrderStatusArchive(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }

    public function uiButtonOrderStatusDecline(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiButtonDelete(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1));
    }


    /*
     * UI Elements
     */

    public function uiElemFilterByOrderStatus(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }


    public function uiElemOrderDataId(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1, 1));
    }

    public function uiElemOrderDataName(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1, 1));
    }

    public function uiElemOrderDataPhone(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 0, 0, 1));
    }

    public function uiElemOrderDataCreateDate(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1, 1));
    }

    public function uiElemOrderDataUpdateDate(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiElemOrderDataWeight(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 0, 0, 1));
    }

    public function uiElemOrderDataCost(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiElemOrderDataAddress(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 0, 0, 1));
    }


    public function uiElemOrderDataEmplConnectManager(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiElemOrderDataEmplConnectChef(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1));
    }

    public function uiElemOrderDataEmplConnectCook(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1));
    }

    public function uiElemOrderDataEmplConnectCourier(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 0, 0, 1));
    }


    public function uiElemOrderDetailsCustomerData(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiElemOrderDetailsCustomerComment(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }

    public function uiElemOrderDetailsIngredients(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1));
    }

    public function uiElemOrderDetailsTotalWeight(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1, 1, 1));
    }

    public function uiElemOrderDetailsTotalCost(User $user) : bool
    {
        return $this->checkPermission($user, Controller::getUserPermissionsMap(1, 1));
    }
}
