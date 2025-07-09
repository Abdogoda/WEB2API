<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends BaseApiController
{

    public function __construct(protected RoleService $roleService)
    {
    }

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Role::class);
        $roles = $this->roleService->getAllRoles();
        return $this->sendResponse(RoleResource::collection($roles), 'Roles retrieved successfully.');
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        Gate::authorize('create', Role::class);
        $role = $this->roleService->createRole($request->validated());
        return $this->sendResponse(new RoleResource($role), 'Role created successfully', 201);
    }

    public function show(Role $role): JsonResponse
    {
        Gate::authorize('view', $role);
        $role->load('permissions');
        return $this->sendResponse(new RoleResource($role), 'Role retrieved successfully.');
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        Gate::authorize('update', $role);
        if ($role->name == 'Owner') {
            return response()->json([
                'error' => 'You cannot update this role'
            ], 403);
        }

        $role = $this->roleService->updateRole($request->validated(), $role);
        return $this->sendResponse(new RoleResource($role->load('permissions')), 'Role updated successfully');
    }

    public function destroy(Role $role): JsonResponse
    {
        Gate::authorize('delete', $role);
        if ($role->name == 'Owner') {
            return response()->json([
                'error' => 'You cannot delete this role'
            ], 403);
        }
        $role->delete();
        return $this->sendResponse(message: 'Role deleted successfully');
    }

    public function permissions(): JsonResponse
    {
        $permissions = Permission::all();
        return $this->sendResponse(PermissionResource::collection($permissions), 'Permissions retrieved successfully');
    }
}
