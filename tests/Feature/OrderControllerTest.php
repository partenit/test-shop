<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testCreateAction()
    {
        $data = app('Database\Factories\OrderFactory')->definition();
        $data['products'] = [
            [
                'id' => Product::factory()->for(Category::factory()->create())->create()->id,
                'quantity' => $this->faker->numberBetween(1, 10),
                'price' => $this->faker->numberBetween(1, 100),
            ],
            [
                'id' => Product::factory()->for(Category::factory()->create())->create()->id,
                'quantity' => $this->faker->numberBetween(1, 10),
                'price' => $this->faker->numberBetween(1, 100),
            ],
        ];

        $response = $this
            ->postJson('api/v1/order', $data)
            ->assertStatus(Response::HTTP_CREATED);

        $item = Order::first();

        $response->assertJson($this->getDataForAssertJson($item));
    }

    public function getDataForAssertJson($item)
    {
        return [
            'id' => $item->id,
            'description' => $item->description,
            'status_id' => $item->status_id,
            'summa' => $item->summa,
            'order_products' => $item->orderProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                ];
            })->toArray(),
        ];
    }
}
