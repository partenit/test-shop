<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function testShowAction()
    {
        $item = Product::factory()
            ->for(Category::factory()->create())
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get('api/v1/admin/product/' . $item->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($this->getDataForAssertJson($item));
    }

    public function testIndexAction()
    {
        $items = Product::factory()
            ->count(5)
            ->for(Category::factory()->create())
            ->create()
            ->sortBy('id');

        $response = $this
            ->actingAs($this->user)
            ->get('api/v1/admin/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $items->map(function ($item) {
                    return $this->getDataForAssertJson($item);
                })->toArray()
            ]);
    }

    public function testCreateAction()
    {
        $data = app('Database\Factories\ProductFactory')->definition();
        $data['category_id'] = Category::factory()->create()->id;

        $response = $this
            ->actingAs($this->user)
            ->postJson('api/v1/admin/product', $data)
            ->assertStatus(Response::HTTP_CREATED);

        $item = Product::first();

        $response->assertJson($this->getDataForAssertJson($item));
    }

    public function testUpdateAction()
    {
        $item = Product::factory()->for(Category::factory()->create())->create();
        $data = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(100),
        ];

        $response = $this->actingAs($this->user)
            ->json('put', "/api/v1/admin/product/{$item->id}", $data)
            ->assertStatus(Response::HTTP_OK);

        $item->refresh();

        $response->assertJson($this->getDataForAssertJson($item));
    }

    public function testDeleteAction()
    {
        $item = Product::factory()->for(Category::factory()->create())->create();

        $this->actingAs($this->user)
            ->json('delete', "api/v1/admin/product/{$item->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertSoftDeleted($item);
    }

    public function getDataForAssertJson($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'slug' => $item->slug,
            'category_id' => $item->category_id,
            'price' => number_format($item->price, 2),
            'code' => $item->code,
        ];
    }
}
