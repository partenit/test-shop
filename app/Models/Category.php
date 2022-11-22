<?php

namespace App\Models;

use App\Traits\OrderBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, string $slug)
 */
class Category extends Model
{
    use HasFactory, SoftDeletes, OrderBy;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'deleted_at',
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

        return Category::select('id', 'name', 'slug', 'photo', 'description')
            ->when($order_by, function($query) use ($order_by) {
                $query->orderBy($order_by[0], $order_by[1]);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($request->has('per_page') ? $request->per_page : $this->perPage);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
