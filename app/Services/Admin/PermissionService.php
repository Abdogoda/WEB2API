<?php

namespace App\Services\Admin;

use App\Models\Permission;

class PermissionService
{

  public function getAllPermissions()
  {
    return Permission::all();
  }
}