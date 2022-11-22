<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testShowAction()
    {
        $item = Order::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get('api/v1/admin/order/' . $item->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($this->getDataForAssertJson($item));
    }

    public function testIndexAction5()
    {
        $items = Order::factory()
            ->count(5)
            ->create()
            ->sortBy('id');

        $response = $this
            ->actingAs($this->user)
            ->get('api/v1/admin/orders');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $items->map(function ($item) {
                    return $this->getDataForAssertJson($item);
                })->toArray()
            ]);
    }

    public function testUpdateAction()
    {
        $item = Order::factory()->create();
        $data = [
            'status_id' => $this->faker->numberBetween(0, 4),
            'description' => $this->faker->text(100),
        ];

        $response = $this->actingAs($this->user)
            ->json('put', "/api/v1/admin/order/{$item->id}", $data)
            ->assertStatus(Response::HTTP_OK);

        $item->refresh();

        $response->assertJson($this->getDataForAssertJson($item));
    }

    public function getDataForAssertJson($item)
    {
        return [
            'id' => $item->id,
            'status_id' => $item->status_id,
            'description' => $item->description,
        ];
    }
}
