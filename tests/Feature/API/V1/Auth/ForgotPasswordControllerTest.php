<?php

namespace Tests\Feature\API\V1\Auth;

use App\Mail\SendResetLinkMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{

  public function test_it_sends_password_reset_link()
  {
    Mail::fake();

    $user = User::factory()->create(['email' => 'test@example.com']);

    $response = $this->postJson($this->apiBaseUrl . '/forgot-password', [
      'email' => 'test@example.com'
    ]);

    $response->assertOk()
      ->assertJson([
        'success' => true,
        'message' => 'We have sent you an email with the reset link.'
      ]);

    // Assert token was stored in database
    $this->assertDatabaseHas('password_reset_tokens', [
      'email' => 'test@example.com'
    ]);

    // Assert email was sent
    Mail::assertQueued(SendResetLinkMail::class, function ($mail) use ($user) {
      return $mail->hasTo($user->email);
    });
  }

  public function test_it_fails_for_nonexistent_email()
  {
    Mail::fake();

    $response = $this->postJson($this->apiBaseUrl . '/forgot-password', [
      'email' => 'nonexistent@example.com'
    ]);

    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['email']);

    Mail::assertNotSent(SendResetLinkMail::class);
  }

  public function test_it_fails_with_invalid_email_format()
  {
    $response = $this->postJson($this->apiBaseUrl . '/forgot-password', [
      'email' => 'not-an-email'
    ]);

    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['email']);
  }

  public function test_it_resets_password_with_valid_token()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => Hash::make('old-password')
    ]);

    $token = Str::random(60);
    $hashedToken = Hash::make($token);

    DB::table('password_reset_tokens')->insert([
      'email' => 'test@example.com',
      'token' => $hashedToken,
      'created_at' => now()
    ]);

    $response = $this->postJson($this->apiBaseUrl . '/reset-password', [
      'email' => 'test@example.com',
      'token' => $token,
      'password' => 'new-password',
      'password_confirmation' => 'new-password'
    ]);

    $response->assertOk()
      ->assertJson([
        'success' => true,
        'message' => 'Password reset successfully, you can login now.',
      ]);

    // Assert password was updated
    $user->refresh();
    $this->assertTrue(Hash::check('new-password', $user->password));

    // Assert token was deleted
    $this->assertDatabaseMissing('password_reset_tokens', [
      'email' => 'test@example.com'
    ]);
  }

  public function test_it_fails_to_reset_with_invalid_token()
  {
    $user = User::factory()->create(['email' => 'test@example.com']);

    // Create valid token
    $validToken = Str::random(60);
    DB::table('password_reset_tokens')->insert([
      'email' => 'test@example.com',
      'token' => Hash::make($validToken),
      'created_at' => now()
    ]);

    // Attempt with wrong token
    $response = $this->postJson($this->apiBaseUrl . '/reset-password', [
      'email' => 'test@example.com',
      'token' => 'invalid-token',
      'password' => 'new-password',
      'password_confirmation' => 'new-password'
    ]);

    $response->assertStatus(422)
      ->assertJson([
        'success' => false,
        'message' => 'Invalid token.'
      ]);

    // Assert password wasn't changed
    $user->refresh();
    $this->assertFalse(Hash::check('new-password', $user->password));
  }

  public function test_it_fails_with_password_validation_errors()
  {
    $user = User::factory()->create(['email' => 'test@example.com']);
    $token = Str::random(60);
    DB::table('password_reset_tokens')->insert([
      'email' => 'test@example.com',
      'token' => Hash::make($token),
      'created_at' => now()
    ]);

    // Missing password confirmation
    $response = $this->postJson($this->apiBaseUrl . '/reset-password', [
      'email' => 'test@example.com',
      'token' => $token,
      'password' => 'new-password'
    ]);

    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['password']);

    // Password too short
    $response = $this->postJson($this->apiBaseUrl . '/reset-password', [
      'email' => 'test@example.com',
      'token' => $token,
      'password' => 'short',
      'password_confirmation' => 'short'
    ]);

    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['password']);

    // Password mismatch
    $response = $this->postJson($this->apiBaseUrl . '/reset-password', [
      'email' => 'test@example.com',
      'token' => $token,
      'password' => 'new-password',
      'password_confirmation' => 'different-password'
    ]);

    $response->assertUnprocessable()
      ->assertJsonValidationErrors(['password']);
  }
}