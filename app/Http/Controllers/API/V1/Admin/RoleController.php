<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);
    }

    public function show(Role $role)
    {
        return response()->json([
            'role' => $role->load('permissions')
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'sometimes|string|max:50|unique:roles,name,' . $role->id,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'required|exists:permissions,id'
        ]);

        if ($role->name == 'Owner') {
            return response()->json([
                'error' => 'You cannot update this role'
            ], 403);
        }

        if ($request->has('name')) {
            $role->name = $request->name;
        }
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }
        $role->save();

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'Owner') {
            return response()->json([
                'error' => 'You cannot delete this role'
            ], 403);
        }
        $role->delete();
        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }

    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json([
            'permissions' => $permissions
        ]);
    }
}
