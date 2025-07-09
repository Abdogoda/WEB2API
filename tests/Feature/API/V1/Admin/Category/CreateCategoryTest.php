<?php

namespace Tests\Feature\API\V1\Admin\Category;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
  private $url, $category;

  public function setUp(): void
  {
    parent::setUp();
    $this->category = Category::factory()->create();
    $this->url = $this->apiBaseUrl . '/admin/categories/';
  }

  public function test_it_cannot_create_a_category_without_authentication()
  {
    $response = $this->getJson($this->url);
    $response->assertStatus(401);
  }

  public function test_it_cannot_create_a_category_without_authorization()
  {
    $this->actingAsUser();
    $response = $this->getJson($this->url);
    $response->assertStatus(403);
  }

  public function test_it_cannot_create_a_category_with_validation_errors()
  {
    $this->actingAsOwner();

    // Test without name
    $response = $this->postJson($this->url, []);
    $response->assertStatus(422)->assertJsonValidationErrors(['name']);

    // Test name is already exists
    $response = $this->postJson($this->url, [
      'name' => $this->category->name
    ]);
    $response->assertStatus(422)->assertJsonValidationErrors(['name']);
  }

  public function test_it_can_create_a_category_with_valid_data()
  {
    $this->actingAsOwner();

    $categoriesCount = Category::count();

    Storage::fake('public');
    $image = UploadedFile::fake()->image('test-category.jpg');

    $response = $this->postJson($this->url, [
      'name' => 'Test Category',
      'description' => 'Description for test category',
      'image' => $image
    ]);
    $response->assertCreated()
      ->assertJsonStructure(['success', 'message', 'data']);

    $this->assertDatabaseHas('categories', [
      'name' => 'Test Category',
      'slug' => 'test-category'
    ])->assertDatabaseCount('categories', $categoriesCount + 1);

    Storage::disk('public')->assertExists('categories/' . $image->hashName());
  }
}
