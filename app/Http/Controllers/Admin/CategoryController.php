<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\IndexCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * @group Admin/Authors
 *
 * API для работы с авторами в админке.
 */
class CategoryController extends Controller
{
    /**
     * получить список категорий
     *
     * @authenticated
     * @param IndexCategoryRequest $request
     * @return JsonResponse
     *
     * @queryParam page integer Номер страницы с результатами выдачи
     * @queryParam sort string Поле для сортировки. По-умолчанию  'id|asc'
     * @queryParam search string Строка, которая должна содержаться в результатах выдачи
     * @queryParam per_page integer Количество возвращаемых записей на страницу.
     */
    public function index(IndexCategoryRequest $request): JsonResponse
    {
        $categories = (new Category())->getAll($request);

        return response()->json($categories);
    }

    /**
     * получить категорию по ее id
     * @authenticated
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::where('id', $id)
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
     * создать категорию
     *
     * @authenticated
     * @param CreateCategoryRequest $request
     * @return JsonResponse
     *
     * @bodyParam name string required Название категории
     * @bodyParam description string optional Описание категории
     * @bodyParam photo string optional Картинка категории
     */
    public function create(CreateCategoryRequest $request): JsonResponse
    {
        $category = new Category();
        $category->fill($request->only($category->getFillable()));
        $category->slug = Str::slug($request->name, '-');
        $category->save();

        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * обновить категорию
     *
     * @authenticated
     * @param UpdateCategoryRequest $request
     * @param string $id
     * @return JsonResponse
     * @bodyParam name string optional Название категории
     * @bodyParam description string optional Описание категории
     * @bodyParam photo string optional Картинка категории
     */
    public function update(UpdateCategoryRequest $request, string $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
        } catch (ModelNotFoundException) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        $category->fill($request->only($category->getFillable()));

        if ($request->name) {
            $category->slug = Str::slug($request->name, '-');
        }

        $category->save();

        return response()->json($category);
    }

    /**
     * удалить категорию
     *
     * @authenticated
     * @param  string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
        } catch (ModelNotFoundException) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.not_found')
            ], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => __('http.removed')
        ], Response::HTTP_OK);
    }
}
