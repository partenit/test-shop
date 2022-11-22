<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexProductRequest;
use App\Models\Product;
use App\Traits\ValidateSlug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Products
 *
 * API для работы с продуктами.
 */
class ProductController extends Controller
{
    use ValidateSlug;

    /**
     * получить список продуктов
     *
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
     * получить продукт по его slug
     *
     * @param  string  $slug
     * @return JsonResponse
     */
    public function show($slug)
    {
        if (! $this->validateSlug($slug)) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.incorrect slug format')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $page = Product::where('slug', $slug)
            ->first();

        if (! $page) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($page);
    }
}
