<?php

namespace App\Services;

use App\Models\Role;

class RoleService
{

  public function getAllRoles()
  {
    return Role::with('permissions')->get();
  }

  public function getExcepteOwnerRoles()
  {
    return Role::where('name', '!=', 'owner')->with('permissions')->get();
  }

  public function getRole(Role $role)
  {
    return $role->load('permissions');
  }

  public function createRole(array $data)
  {
    return Role::create([
      'name' => $data['name']
    ]);
  }

  public function updateRole(array $data, Role $role)
  {
    if (isset($data['name'])) {
      $role->name = $data['name'];
    }
    if ($data['permissions']) {
      $role->permissions()->sync($data['permissions']);
    }
    $role->save();
    return $role;
  }
}