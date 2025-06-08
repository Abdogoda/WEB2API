<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_PRODUCTS->value);
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_PRODUCT->value);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::CREATE_PRODUCT->value);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::UPDATE_PRODUCT->value);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::DELETE_PRODUCT->value);
    }
}
