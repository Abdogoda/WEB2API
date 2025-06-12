<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        Gate::authorize('create', Role::class);

        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name
        ]);
        return back()->with('success', 'Role created successfully');
    }

    public function update(Request $request, Role $role)
    {
        Gate::authorize('update', $role);

        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id'
        ]);

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
