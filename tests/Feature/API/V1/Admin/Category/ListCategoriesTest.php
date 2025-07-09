<?php

namespace Tests\Feature\API\V1\Admin\Category;

use App\Models\Category;
use Tests\TestCase;

class ListCategoriesTest extends TestCase
{
  private $url;
  public function setUp(): void
  {
    parent::setUp();
    $this->url = $this->apiBaseUrl . '/admin/categories';
  }

  public function test_it_cannot_list_categories_without_authentication()
  {
    $response = $this->getJson($this->url);
    $response->assertStatus(401);
  }

  public function test_it_cannot_list_categories_without_authorization()
  {
    $this->actingAsUser();
    $response = $this->getJson($this->url);
    $response->assertStatus(403);
  }

  public function test_it_can_return_empty_list_of_categories()
  {
    $this->actingAsOwner();

    $response = $this->getJson($this->url);
    $response->assertStatus(200)
      ->assertJsonStructure(['success', 'message', 'data'])
      ->assertJsonCount(0, 'data');
  }
  public function test_it_can_return_list_of_categories()
  {
    $this->actingAsOwner();

    Category::factory(10)->create();

    $response = $this->getJson($this->apiBaseUrl . '/admin/categories');
    $response->assertStatus(200)
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'description',
            'image'
          ]
        ]
      ])
      ->assertJsonCount(10, 'data');
  }
}
