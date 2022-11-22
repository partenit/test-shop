<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductControllerTest extends TestCase
{
    public function testShowAction()
    {
        $item = Product::factory()
            ->for(Category::factory()->create())
            ->create();

        $response = $this
            ->get('api/v1/product/' . $item->slug);

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
            ->get('api/v1/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $items->map(function ($item) {
                    return $this->getDataForAssertJson($item);
                })->toArray()
            ]);
    }

    public function getDataForAssertJson($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => number_format($item->price, 2),
            'category_id' => $item->category_id,
        ];
    }
}
