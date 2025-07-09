<?php

namespace Tests\Feature\API\Admin;

use Tests\TestCase;

class ShowUserTest extends TestCase
{
  public function test_show_requires_authentication()
  {
    $response = $this->getJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}");
    $response->assertStatus(401);
  }

  public function test_show_requires_authorization()
  {
    $this->actingAsUser();
    $response = $this->getJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}");
    $response->assertStatus(403);
  }

  public function test_show_returns_user_and_handles_errors()
  {
    $this->actingAsOwner();

    $response = $this->getJson($this->apiBaseUrl . "/admin/users/{$this->createUser()->id}");
    $response->assertStatus(200)->assertJsonStructure(['success', 'message', 'data']);
  }

  public function test_show_handles_errors()
  {
    $this->actingAsOwner();

    $response = $this->getJson($this->apiBaseUrl . "/admin/users/999999");
    $response->assertStatus(404);
  }
}
