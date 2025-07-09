<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        $this->assertEquals('testing', config('app.env'));
        $this->assertEquals('sqlite', config('database.default'));

        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected string $apiBaseUrl = '/api/v1';

    protected function createUser(array $attributes = [])
    {
        return User::factory()->create($attributes);
    }

    protected function createOwner(array $attributes = [])
    {
        $user = $this->createUser($attributes);

        $user->roles()->attach([1]); // Assuming role ID 1 is for owner
        return $user;
    }

    protected function actingAsUser($user = null)
    {
        $user = $user ?: $this->createUser();
        Sanctum::actingAs($user);
        return $user;
    }

    protected function actingAsOwner($owner = null)
    {
        $owner = $owner ?: $this->createOwner();
        Sanctum::actingAs($owner);
        return $owner;
    }
}
