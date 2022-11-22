<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Traits\ValidateSlug;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * @group Admin/Products
 *
 * API для работы с продуктами в админке.
 */
class ProductController extends Controller
{
    use ValidateSlug;

    /**
     * получить список продуктов
     *
     * @authenticated
     * @param IndexProductRequest $request
     * @return JsonResponse
     *
     * @queryParam page integer Номер страницы с результатами выдачи
     * @queryParam sort string Поле для сортировки. По-умолчанию  'id|asc'
     * @queryParam search string Строка, которая должна содержаться в результатах выдачи
     * @queryParam category_id integer ID категории
     * @queryParam per_page integer Количество возвращаемых записей на страницу.
     */
    public function index(IndexProductRequest $request)
    {
        $products = (new Product())->getAll($request);

        return response()->json($products);
    }

    /**
     * получить продукт по его id
     *
     * @authenticated
     * @param  string  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $page = Product::where('id', $id)
            ->first();

        if (! $page) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($page);
    }

    /**
     * создать продукт
     *
     * @authenticated
     * @param CreateProductRequest $request
     * @return JsonResponse
     *
     * @bodyParam name string required Название категории
     * @bodyParam description string optional Описание категории
     * @bodyParam photo string optional Картинка категории
     * @bodyParam category_id integer required ID категории
     * @bodyParam price integer required Цена
     * @bodyParam code integer Код продукта
     */
    public function create(CreateProductRequest $request): JsonResponse
    {
        $product = new Product();
        $product->fill($request->only($product->getFillable()));
        $product->slug = Str::slug($request->name, '-');
        $product->code = $request->get('code', Str::random(10));

        if ($request->hasFile('photo')) {
            // логика добавления фото продукта
        }

        $product->save();

        return response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * обновить продукт
     *
     * @authenticated
     * @param UpdateProductRequest $request
     * @param string $id
     * @return JsonResponse
     * @bodyParam name string optional Название категории
     * @bodyParam description string optional Описание категории
     * @bodyParam photo string optional Картинка категории
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        $product->fill($request->only($product->getFillable()));

        if ($request->name) {
            $product->slug = Str::slug($request->name, '-');
        }

        $product->save();

        return response()->json($product);
    }

    /**
     * удалить продукт
     *
     * @authenticated
     * @param  string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        $product->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => __('http.removed')
        ], Response::HTTP_OK);
    }
}
