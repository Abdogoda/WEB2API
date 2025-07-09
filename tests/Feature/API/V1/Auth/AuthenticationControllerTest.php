<?php

namespace Tests\Feature\API\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
  public function test_it_can_register_a_user()
  {
    $userData = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'password'
    ];

    $response = $this->postJson($this->apiBaseUrl . '/register', $userData);

    $response->assertCreated()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          'user' => [
            'id',
            'name',
            'email'
          ],
          'token'
        ]
      ]);

    $this->assertDatabaseHas('users', [
      'name' => 'Test User',
      'email' => 'test@example.com'
    ]);
  }

  public function test_it_fails_registration_with_invalid_data()
  {
    // Missing name
    $response = $this->postJson($this->apiBaseUrl . '/register', [
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'password'
    ]);
    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['name']);

    // Invalid email
    $response = $this->postJson($this->apiBaseUrl . '/register', [
      'name' => 'Test User',
      'email' => 'not-an-email',
      'password' => 'password',
      'password_confirmation' => 'password'
    ]);
    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['email']);

    // Password mismatch
    $response = $this->postJson($this->apiBaseUrl . '/register', [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'different-password'
    ]);
    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['password']);
  }

  public function test_it_can_login_with_valid_credentials()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => Hash::make('password')
    ]);

    $response = $this->postJson($this->apiBaseUrl . '/login', [
      'email' => 'test@example.com',
      'password' => 'password'
    ]);

    $response->assertOk()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          'user' => [
            'id',
            'name',
            'email'
          ],
          'token'
        ]
      ]);
  }

  public function test_it_fails_login_with_invalid_credentials()
  {
    User::factory()->create([
      'email' => 'test@example.com',
      'password' => Hash::make('password')
    ]);

    // Wrong password
    $response = $this->postJson($this->apiBaseUrl . '/login', [
      'email' => 'test@example.com',
      'password' => 'wrong-password'
    ]);
    $response->assertUnauthorized()
      ->assertJson(['message' => 'Invalida Credentials']);

    // Wrong email
    $response = $this->postJson($this->apiBaseUrl . '/login', [
      'email' => 'nonexistent@example.com',
      'password' => 'password'
    ]);
    $response->assertUnauthorized()
      ->assertJson(['message' => 'Invalida Credentials']);
  }

  public function test_it_can_logout()
  {
    $user = User::factory()->create();
    $token = $user->createToken('api_token')->plainTextToken;

    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $token
    ])->postJson($this->apiBaseUrl . '/logout');

    $response->assertOk()
      ->assertJson(['message' => 'Logged out successfully']);

    $this->assertCount(0, $user->tokens);
  }

  public function test_it_requires_authentication_to_logout()
  {
    $response = $this->postJson($this->apiBaseUrl . '/logout');
    $response->assertUnauthorized();
  }

  public function test_it_can_logout_from_other_devices()
  {
    $user = User::factory()->create([
      'password' => Hash::make('password')
    ]);

    // Create multiple tokens
    $currentToken = $user->createToken('current')->plainTextToken;
    $otherToken1 = $user->createToken('other1')->plainTextToken;
    $otherToken2 = $user->createToken('other2')->plainTextToken;

    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $currentToken
    ])->postJson($this->apiBaseUrl . '/logout-other-devices', [
          'password' => 'password'
        ]);

    $response->assertOk()
      ->assertJson(['message' => 'Logged out from other devices']);

    // Refresh user instance to get updated tokens
    $user->refresh();

    // Current token should still exist
    $this->assertCount(1, $user->tokens);
  }

  public function test_it_fails_logout_other_devices_with_wrong_password()
  {
    $user = User::factory()->create([
      'password' => Hash::make('password')
    ]);
    $token = $user->createToken('api_token')->plainTextToken;

    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $token
    ])->postJson($this->apiBaseUrl . '/logout-other-devices', [
          'password' => 'wrong-password'
        ]);

    $response->assertUnauthorized()
      ->assertJson(['message' => 'Invalid password']);
  }

  public function test_it_requires_password_to_logout_other_devices()
  {
    $user = User::factory()->create();
    $token = $user->createToken('api_token')->plainTextToken;

    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $token
    ])->postJson($this->apiBaseUrl . '/logout-other-devices');

    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['password']);
  }

  public function test_remember_me_functionality()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => Hash::make('password')
    ]);

    $response = $this->postJson($this->apiBaseUrl . '/login', [
      'email' => 'test@example.com',
      'password' => 'password',
      'remember' => true
    ]);

    $response->assertOk();

    // The remember me functionality is typically handled by Laravel's session cookie
    // which isn't directly testable in API tests, but we can verify the request was accepted
  }
}