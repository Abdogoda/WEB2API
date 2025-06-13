<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{

    public function index()
    {
        Gate::authorize('viewAny', Role::class);

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        Role::create([
            'name' => $request->name
        ]);
        return back()->with('success', 'Role created successfully');
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);

        if ($role->name == 'Owner') {
            return back()->with('error', 'You cannot update this role');
        }

        $role->update(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);
        return back()->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        if ($role->name == 'Owner') {
            return back()->with('error', 'You cannot delete this role');
        }
        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }
}
