<?php

namespace Tests\Feature\API\V1\Admin\User;

use Tests\TestCase;

class ListUsersTest extends TestCase
{
  public function test_index_requires_authentication()
  {
    $response = $this->getJson($this->apiBaseUrl . '/admin/users');
    $response->assertStatus(401);
  }

  public function test_index_requires_authorization()
  {
    $this->actingAsUser();
    $response = $this->getJson($this->apiBaseUrl . '/admin/users');
    $response->assertStatus(403);
  }

  public function test_index_return_list_of_users()
  {
    $this->actingAsOwner();
    $response = $this->getJson($this->apiBaseUrl . '/admin/users');
    $response->assertStatus(200)->assertJsonStructure(['success', 'message', 'data']);
  }
}
