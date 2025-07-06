<?php

namespace App\Services;

use App\Models\User;

class UserService
{
  public function getAllUsers()
  {
    return User::with(['roles'])->get();
  }

  public function getUser(User $user)
  {
    return $user->load(['roles']);
  }

  public function changeUserRoles(User $user, array $roleIds)
  {
    $user->roles()->sync($roleIds);
    return $user->load('roles');
  }
}
