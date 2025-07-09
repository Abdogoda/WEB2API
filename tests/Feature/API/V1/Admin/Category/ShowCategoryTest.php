<?php

namespace Tests\Feature\API\V1\Admin\Category;

use App\Models\Category;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{
  private $url, $category;

  public function setUp(): void
  {
    parent::setUp();
    $this->category = Category::factory()->create();
    $this->url = $this->apiBaseUrl . '/admin/categories/' . $this->category->slug;
  }

  public function test_it_cannot_show_a_category_without_authentication()
  {
    $response = $this->getJson($this->url);
    $response->assertStatus(401);
  }

  public function test_it_cannot_show_a_category_without_authorization()
  {
    $this->actingAsUser();
    $response = $this->getJson($this->url);
    $response->assertStatus(403);
  }

  public function test_it_cannot_show_a_category_if_it_dose_not_exists()
  {
    $this->actingAsOwner();

    $response = $this->getJson($this->url . '00000');
    $response->assertNotFound();
  }

  public function test_it_can_show_a_category_with_valid_slug()
  {
    $this->actingAsOwner();

    $response = $this->getJson($this->url);
    $response->assertStatus(200)
      ->assertJsonStructure(['success', 'message', 'data']);
  }
}
