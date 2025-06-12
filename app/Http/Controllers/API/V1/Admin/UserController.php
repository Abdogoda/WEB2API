<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'user' => $user->load('roles')
        ]);
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|exists:roles,id'
        ]);

        $user->roles()->sync($request->role_ids);
        return response()->json([
            'message' => 'Roles Changed Successfully',
            'user' => $user->load('roles')
        ]);
    }
}
