<?php

namespace Tests\Feature\API\Admin;

use Tests\TestCase;

class ChangeUserRolesTest extends TestCase
{
  public function test_change_roles_requires_authentication()
  {
    $response = $this->postJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}/change-role", []);
    $response->assertStatus(401);
  }

  public function test_change_roles_requires_authorization()
  {
    $this->actingAsUser();
    $response = $this->postJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}/change-role", []);
    $response->assertStatus(403);
  }

  public function test_change_role_has_validation()
  {
    $this->actingAsOwner();

    $response = $this->postJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}/change-role", []);
    $response->assertStatus(422);
  }

  public function test_change_role_updates_roles_successfully()
  {
    $this->actingAsOwner();

    $response = $this->postJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}/change-role", [
      'role_ids' => [1]
    ]);
    $response->assertStatus(200)->assertJsonStructure(['success', 'message', 'data']);
  }
}
