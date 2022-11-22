<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\IndexCategoryRequest;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Category;
use App\Models\Order;
use App\Traits\ValidateSlug;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * @group Admin/Orders
 *
 * API для работы с заказами в админке.
 */
class OrderController extends Controller
{
    /**
     * получить список заказов
     *
     * @authenticated
     * @param IndexOrderRequest $request
     * @return JsonResponse
     *
     * @queryParam page integer Номер страницы с результатами выдачи
     * @queryParam sort string Поле для сортировки. По-умолчанию  'id|asc'
     * @queryParam search string Строка, которая должна содержаться в результатах выдачи
     * @queryParam per_page integer Количество возвращаемых записей на страницу.
     */
    public function index(IndexOrderRequest $request): JsonResponse
    {
        $orders = (new Order())->getAll($request);

        return response()->json($orders);
    }

    /**
     * получить категорию по ее id
     *
     * @authenticated
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $category = Order::where('id', $id)
            ->first();

        if (! $category) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($category);
    }

    /**
     * обновить заказ
     *
     * @authenticated
     * @param UpdateOrderRequest $request
     * @param string $id
     * @return JsonResponse
     * @bodyParam status_id string optional ID статуса заказа
     * @bodyParam description string optional Описание заказа
     */
    public function update(UpdateOrderRequest $request, string $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
        } catch (ModelNotFoundException) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        $order->fill($request->only($order->getFillable()));

        if ($request->name) {
            $order->slug = Str::slug($request->name, '-');
        }

        $order->save();

        return response()->json($order);
    }
}
