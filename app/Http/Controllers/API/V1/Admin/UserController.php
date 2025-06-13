<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\User\ChangeUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $users = User::with(['roles', 'messages'])->get();
        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }

    public function show(User $user): JsonResponse
    {
        $user->load(['roles', 'messages']);
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    public function changeRole(ChangeUserRoleRequest $request, User $user): JsonResponse
    {
        $user->roles()->sync($request->role_ids);
        return $this->sendResponse($user->load('roles'), 'User roles updated successfully');
    }
}
