<?php

namespace Tests\Feature\API\V1\Admin\Category;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
  private $url;
  private $category;

  public function setUp(): void
  {
    parent::setUp();
    $this->category = Category::factory()->create();
    $this->url = $this->apiBaseUrl . '/admin/categories/' . $this->category->slug;
  }

  public function test_it_cannot_update_a_category_without_authentication()
  {
    $response = $this->putJson($this->url);
    $response->assertStatus(401);
  }

  public function test_it_cannot_update_a_category_without_authorization()
  {
    $this->actingAsUser();
    $response = $this->putJson($this->url);
    $response->assertStatus(403);
  }

  public function test_it_cannot_update_a_category_if_it_dose_not_exists()
  {
    $this->actingAsOwner();

    $response = $this->putJson($this->url . '00000');
    $response->assertNotFound();
  }

  public function test_it_cannot_update_a_category_with_validation_errors()
  {
    $this->actingAsOwner();
    $otherCategory = Category::factory()->create();

    // Test empty name
    $response = $this->putJson($this->url, ['name' => '']);
    $response->assertStatus(422)->assertJsonValidationErrors(['name']);

    // Test name already exists
    $response = $this->putJson($this->url, ['name' => $otherCategory->name]);
    $response->assertStatus(422)->assertJsonValidationErrors(['name']);
  }

  public function test_it_can_update_a_category_with_valid_data()
  {
    $this->actingAsOwner();
    Storage::fake('public');
    $image = UploadedFile::fake()->image('updated-category.jpg');

    $response = $this->putJson($this->url, [
      'name' => 'Updated Category',
      'description' => 'Updated description',
      'image' => $image
    ]);

    $response->assertOk()
      ->assertJsonStructure(['success', 'message', 'data']);

    $this->assertDatabaseHas('categories', [
      'id' => $this->category->id,
      'name' => 'Updated Category',
      'slug' => 'updated-category'
    ]);

    Storage::disk('public')->assertExists('categories/' . $image->hashName());
  }

  public function test_it_can_update_a_category_without_image()
  {
    $this->actingAsOwner();

    $response = $this->putJson($this->url, [
      'name' => 'Updated Without Image',
      'description' => 'Updated without image description'
    ]);

    $response->assertOk()
      ->assertJsonStructure(['success', 'message', 'data']);

    $this->assertDatabaseHas('categories', [
      'id' => $this->category->id,
      'name' => 'Updated Without Image',
      'slug' => 'updated-without-image'
    ]);
  }
}
