<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_CATEGORIES->value);
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_CATEGORY->value);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::CREATE_CATEGORY->value);
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::UPDATE_CATEGORY->value);
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::DELETE_CATEGORY->value);
    }
}
