<?php

namespace App\Policies;

use App\Models\SaleOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaleOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return !empty($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SaleOrder $saleOrder)
    {
        return !empty($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return !empty($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SaleOrder $saleOrder)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SaleOrder $saleOrder)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SaleOrder $saleOrder)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SaleOrder $saleOrder)
    {
        //
    }
}
