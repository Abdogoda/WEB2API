<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = User::all();
        $roles = Role::where('name', '!=', 'Owner')->get();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function changeRole(ChangeUserRoleRequest $request, User $user)
    {
        Gate::authorize('changeRoles', $user);

        $user->roles()->sync($request->role_ids);
        return back()->with('success', 'Roles Changed Successfully');
    }
}
