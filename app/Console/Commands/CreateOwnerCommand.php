<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateOwnerCommand extends Command{

    protected $signature = 'create:owner';

    protected $description = 'This command creates an owner user';

    public function handle(){
        if(!Role::where('name', 'Owner')->first()){
            Artisan::call('db:seed', ['--class' => 'RoleAndPermissionSeeder']);
        }

        $name = $this->ask('What is the owner name?');
        $email = $this->ask('What is the owner email?');
        $password = $this->ask('What is the owner password?');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            foreach($validator->errors()->all() as $error){
                $this->error($error);
            }
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
        ]);

        $ownerRole = Role::where('name', 'Owner')->first();
        $user->roles()->attach($ownerRole->id);

        $this->info('Owner '. $name .' created successfully');
    }
}
