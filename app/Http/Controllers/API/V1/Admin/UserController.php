<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return $this->sendResponse($users, 'Users retrieved successfully');
    }

    public function show(User $user): JsonResponse
    {
        return $this->sendResponse($user->load('roles'), 'User retrieved successfully');
    }

    public function changeRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|exists:roles,id'
        ]);

        $user->roles()->sync($request->role_ids);
        return $this->sendResponse($user->load('roles'), 'User roles updated successfully');
    }
}
