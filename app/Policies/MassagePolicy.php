<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MassagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::VIEW_MESSAGES->value);
    }

    public function markAllAsRead(User $user): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::MARK_ALL_MESSAGES_AS_READ->value);
    }

    public function markAsRead(User $user, Message $message): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::MARK_MESSAGE_AS_READ->value);
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->hasPermissionTo(PermissionsEnum::DELETE_MESSAGE->value);
    }
}
