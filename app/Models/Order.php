<?php

namespace App\Models;

use App\Traits\OrderBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes, OrderBy;

    protected $fillable = [
        'status_id',
        'summa',
        'description'
    ];

    public function getAll($request)
    {
        $order_by = $this->getOrderBy($request);

        return Order::select('id', 'status_id', 'summa', 'description')
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
        return $this->belongsToMany(Product::class, 'orders_products_rel', 'order_id', 'product_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }
}
