<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService
{

  public function getAllPermissions()
  {
    return Permission::all();
  }
}