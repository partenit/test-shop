<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 10 продуктов одной категориии
        Product::factory()
            ->count(10)
            ->for(Category::factory()->create())
            ->create();

        // 10 продуктов другой категориии
        Product::factory()
            ->count(10)
            ->for(Category::factory()->create())
            ->create();

        // 10 продуктов третьей категориии
        Product::factory()
            ->count(10)
            ->for(Category::factory()->create())
            ->create();

        // 1 заказ
        $order = Order::factory()
            ->create();

        $products = Product::inRandomOrder()->take(3)->get();

        // в заказе 3 продукта
        foreach ($products as $product) {
            OrderProduct::factory()
                ->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ]);
        }

    }
}
