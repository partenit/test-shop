<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Orders
 *
 * API для работы с заказами
 */
class OrderController extends Controller
{
    /**
     * Create Categories
     *
     * @authenticated
     * @param CreateOrderRequest $request
     *
     * @return JsonResponse
     * @bodyParam status_id integer Статус заказа (0-ожидает оплаты, 1-оплачен, 2-отправлен, 3-доставлен, 4-отменен)
     * @bodyParam products array required Массив с продуктами
     * @bodyParam description string Комментарий к заявке
     */
    public function create(CreateOrderRequest $request)
    {
        $summa = 0;
        $order = new Order();
        $order->fill($request->only($order->getFillable()));

        if ($request->status_id) {
            $order->status_id = $request->status_id;
        }

        $order->save();

        array_map(function ($product) use ($order, &$summa) {
            $product['id'] = $product['id'] ?? null;
            $product['quantity'] = $product['quantity'] ?? 0;
            $product['price'] = $product['price'] ?? 0;

            if ($product['id'] && $product['quantity'] && $product['price']) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);
                $summa += $product['price'] * $product['quantity'];
            }
        }, $request->products);

        $order->summa = $summa;
        $order->save();
        $order->load('orderProducts');

        return response()->json($order, Response::HTTP_CREATED);
    }
}
