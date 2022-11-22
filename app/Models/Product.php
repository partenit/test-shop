<?php

namespace App\Models;

use App\Traits\OrderBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, OrderBy;

    protected $perPage = 15;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'code',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getAll($request)
    {
        $order_by = $this->getOrderBy($request);

        return Product::select('id', 'name', 'slug', 'photo', 'price', 'category_id', 'code', 'description')
            ->with('category')
            ->when($order_by, function($query) use ($order_by) {
                $query->orderBy($order_by[0], $order_by[1]);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->paginate($request->has('per_page') ? $request->per_page : $this->perPage);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
