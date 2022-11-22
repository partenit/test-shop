<?php

namespace Tests\Feature\Admin;

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
            ->actingAs($this->user)
            ->get('api/v1/admin/category/' . $item->id);

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
            ->actingAs($this->user)
            ->get('api/v1/admin/categories');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $items->map(function ($item) {
                    return $this->getDataForAssertJson($item);
                })->toArray()
            ]);
    }

    public function testCreateAction()
    {
        $data = app('Database\Factories\CategoryFactory')->definition();

        $response = $this
            ->actingAs($this->user)
            ->postJson('api/v1/admin/category', $data)
            ->assertStatus(Response::HTTP_CREATED);

        $item = Category::first();

        $response->assertJson($this->getDataForAssertJson($item));
    }

    public function testUpdateAction()
    {
        $item = Category::factory()->create();
        $data = [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(100),
        ];

        $response = $this->actingAs($this->user)
            ->json('put', "/api/v1/admin/category/{$item->id}", $data)
            ->assertStatus(Response::HTTP_OK);

        $item->refresh();

        $response->assertJson($this->getDataForAssertJson($item));
    }

    public function testDeleteAction()
    {
        $item = Category::factory()->create();

        $this->actingAs($this->user)
            ->json('delete', "api/v1/admin/category/{$item->id}")
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
        ];
    }
}
