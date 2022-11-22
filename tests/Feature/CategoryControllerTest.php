<?php

use App\Models\Category;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function testShowAction()
    {
        $item = Category::factory()
            ->create();

        $response = $this
            ->get('api/v1/category/' . $item->slug);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($this->getDataForAssertJson($item));
    }

    public function testIndexAction()
    {
        $items = Category::factory()
            ->count(5)
            ->create()
            ->sortBy('id');

        $response = $this
            ->get('api/v1/categories');

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
            'slug' => $item->slug,
        ];
    }
}
