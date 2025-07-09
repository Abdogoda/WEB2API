<?php

namespace Tests\Feature\API\V1\User;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
  protected $url, $products, $category;

  public function setUp(): void
  {
    parent::setUp();
    $this->category = Category::factory()->create();
    $this->products = Product::factory()->count(5)->create([
      'category_id' => $this->category->id,
      'active' => true,
      'stock' => 10,
    ]);
    $this->url = $this->apiBaseUrl . '/products';
  }

  public function test_it_can_list_all_products()
  {
    $response = $this->getJson($this->url);
    $response->assertOk()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'price',
            'category',
            'images'
          ]
        ]
      ]);
  }

  public function test_it_can_filter_products_by_category()
  {
    $secondCategory = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $secondCategory->id]);

    $response = $this->getJson($this->url . '?category_ids[]=' . $this->category->id);
    $response->assertOk();
    $this->assertCount(5, $response->json('data'));
  }

  public function test_it_can_filter_products_by_price_range()
  {
    Product::factory()->create(['price' => 50]);
    Product::factory()->create(['price' => 150]);

    $response = $this->getJson($this->url . '?min_price=100&max_price=200');
    $response->assertOk();
    $products = $response->json('data');
    foreach ($products as $product) {
      $this->assertGreaterThanOrEqual(100, $product['price']);
      $this->assertLessThanOrEqual(200, $product['price']);
    }
  }

  public function test_it_can_show_a_single_product()
  {
    $product = $this->products->first();
    ProductImage::factory()->create(['product_id' => $product->id]);

    $response = $this->getJson($this->url . '/' . $product->slug);
    $response->assertOk()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          'id',
          'name',
          'description',
          'price',
          'category',
          'images'
        ]
      ]);
  }

  public function test_it_returns_404_for_nonexistent_product()
  {
    $response = $this->getJson($this->url . '/9999');
    $response->assertNotFound();
  }

  public function test_it_can_get_similar_products()
  {
    $product = $this->products->first();
    $response = $this->getJson($this->url . '/' . $product->slug . '/similar');
    $response->assertOk()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'price',
            'category'
          ]
        ]
      ]);
  }

  public function test_it_can_get_featured_products()
  {
    Product::factory()->count(3)->create(['featured' => true]);
    $response = $this->getJson($this->url . '/featured');
    $response->assertOk()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'price',
            'featured'
          ]
        ]
      ]);
  }

  public function test_it_can_get_latest_products()
  {
    $response = $this->getJson($this->url . '/latest');
    $response->assertOk()
      ->assertJsonStructure([
        'success',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'price'
          ]
        ]
      ]);
  }
}