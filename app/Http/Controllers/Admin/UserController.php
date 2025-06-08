<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function changeRole(Request $request, User $user)
    {
        Gate::authorize('changeRoles', $user);

        $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|exists:roles,id'
        ]);

        $user->roles()->sync($request->role_ids);
        return back()->with('success', 'Roles Changed Successfully');
    }
}
