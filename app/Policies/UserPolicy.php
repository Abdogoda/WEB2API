<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_USERS->value);
    }

    public function changeRoles(User $user, User $model): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::CHANGE_USER_ROLES->value);
    }
}
