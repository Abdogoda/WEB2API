@extends('layouts.admin.app')

@section('title', 'Roles')
@section('style')
    <style>
        input[type="checkbox"]:checked + label {
            background-color: #4CAF50;
            color: white;             
            border-color: #45a049;
        }
    </style>
@endsection

@section('content')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Role Management</h2>
            @hasPermissionTo(\App\Enums\PermissionsEnum::CREATE_ROLE->value)
                <button onclick="openModal('addRoleModal')" class="bg-blue-500 text-white px-4 py-2 rounded">Add Role <i class="fa fa-plus"></i></button>
            @endhasPermissionTo
        </div>
        
        <div class="bg-gray-800 rounded-lg shadow-md p-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-3 border-b border-gray-600">ID</th>
                        <th class="p-3 border-b border-gray-600">Name</th>
                        <th class="p-3 border-b border-gray-600">Permissions</th>
                        <th class="p-3 border-b border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr class="hover:bg-gray-700">
                            <td class="p-3 border-b border-gray-700">{{$role->id}}</td>
                            <td class="p-3 border-b border-gray-700">{{$role->name}}</td>
                            <td class="p-3 border-b border-gray-700">{{count($role->permissions)}}</td>
                            <td class="p-3 border-b border-gray-700">
                                @hasPermissionTo(\App\Enums\PermissionsEnum::UPDATE_ROLE->value)
                                    <button onclick="openEditModal({{ $role->id }}, '{{ $role->name }}', {{ json_encode($role->permissions->pluck('id')) }})" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                                @endhasPermissionTo
                                @hasPermissionTo(\App\Enums\PermissionsEnum::DELETE_ROLE->value)
                                    <button onclick="openDeleteModal({{$role->id}})" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                                @endhasPermissionTo
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div id="addRoleModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-gray-800 p-6 rounded shadow-md w-96">
            <h3 class="text-xl font-bold mb-4">Add Role</h3>
            <form action="" method="POST">
                @csrf
                <input type="text" name="name" value="{{old('name')}}" placeholder="Role Name" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addRoleModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-gray-800 p-6 rounded shadow-md" style="width: 700px; max-width:100%">
            <h3 class="text-xl font-bold mb-4">Edit Role</h3>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editRoleId">
                <input type="text" id="editRoleName" name="name" placeholder="Role Name" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                
                <div id="permissionsList" class="flex flex-wrap items-center gap-2 mb-4">
                    @foreach ($permissions as $permission)
                        <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" class="hidden">
                        <label for="permission_{{ $permission->id }}"
                            data-permission-id="{{ $permission->id }}"
                            class="permission-label cursor-pointer p-2 border border-gray-600 rounded text-gray-100 hover:bg-gray-700 transition">
                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editRoleModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteRoleModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-gray-800 p-6 rounded shadow-md w-96">
            <h3 class="text-xl font-bold mb-4">Delete Role?</h3>
            <p>Are you sure you want to delete this role?</p>
            <form id="deleteRoleForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal('deleteRoleModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function openEditModal(id, name, rolePermissions) {
            document.getElementById('editRoleId').value = id;
            document.getElementById('editRoleName').value = name;
            document.getElementById('editRoleForm').action = `/admin/roles/${id}`;

            const permissionInputs = document.querySelectorAll('#permissionsList input[type="checkbox"]');
            permissionInputs.forEach(input => {
                input.checked = false;
            });
            rolePermissions.forEach(permissionId => {
                const input = document.querySelector(`#permissionsList input[value="${permissionId}"]`);
                if (input) {
                    input.checked = true;
                }
            });

            openModal('editRoleModal');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteRoleForm').action = `/admin/roles/${id}`;
            openModal('deleteRoleModal');
        }
    </script>
@endsection