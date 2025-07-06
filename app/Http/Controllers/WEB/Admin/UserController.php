<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeUserRoleRequest;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Support\Facades\Gate;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService
    ) {
    }

    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->userService->getAllUsers();
        $roles = $this->roleService->getExcepteOwnerRoles();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function changeRole(ChangeUserRoleRequest $request, User $user)
    {
        Gate::authorize('changeRoles', $user);

        $this->userService->changeUserRoles($user, $request->role_ids);

        return back()->with('success', 'Roles Changed Successfully');
    }
}
