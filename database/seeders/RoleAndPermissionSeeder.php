<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder{

    public function run(): void{
        $permisions = collect(PermissionsEnum::values())->map(function($permission){
            return ['name' => $permission];
        })->toArray();

        Permission::upsert($permisions, ['name']);

        $ownerRole = Role::firstOrCreate(['name' => 'Owner']);
        $ownerRole->permissions()->syncWithoutDetaching(Permission::pluck('id')->toArray());
    }
}
