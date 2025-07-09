<?php

namespace Tests\Feature\API\V1\Admin\Category;

use App\Models\Category;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
  private $url;
  private $category;

  public function setUp(): void
  {
    parent::setUp();
    $this->category = Category::factory()->create();
    $this->url = $this->apiBaseUrl . '/admin/categories/' . $this->category->slug;
  }

  public function test_it_cannot_delete_a_category_without_authentication()
  {
    $response = $this->deleteJson($this->url);
    $response->assertStatus(401);
  }

  public function test_it_cannot_delete_a_category_without_authorization()
  {
    $this->actingAsUser();
    $response = $this->deleteJson($this->url);
    $response->assertStatus(403);
  }


  public function test_it_cannot_delete_a_category_if_it_dose_not_exists()
  {
    $this->actingAsOwner();

    $response = $this->deleteJson($this->url . '00000');
    $response->assertNotFound();
  }


  public function test_it_can_delete_a_category()
  {
    $this->actingAsOwner();

    $categoriesCount = Category::count();

    $response = $this->deleteJson($this->url);
    $response->assertOk()
      ->assertJsonStructure(['success', 'message']);

    $this->assertDatabaseMissing('categories', [
      'id' => $this->category->id,
      'deleted_at' => null
    ])->assertDatabaseCount('categories', $categoriesCount - 1);
  }
}