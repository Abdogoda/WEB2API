<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_ORDERS->value);
    }

    public function view(User $user, Order $order): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_ORDER->value);
    }

    public function changeStatus(User $user, Order $order): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::CHANGE_ORDER_STATUS->value);
    }
}
