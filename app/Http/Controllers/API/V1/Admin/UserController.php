<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\User\ChangeUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends BaseApiController
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }

    public function show(User $user): JsonResponse
    {
        $user = $this->userService->getUser($user);
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    public function changeRole(ChangeUserRoleRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->changeUserRoles($user, $request->role_ids);
        return $this->sendResponse($user, 'User roles updated successfully');
    }
}
