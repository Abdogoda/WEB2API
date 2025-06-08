<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_ROLES->value);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_ROLE->value);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::CREATE_ROLE->value);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::UPDATE_ROLE->value);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::DELETE_ROLE->value);
    }
}
