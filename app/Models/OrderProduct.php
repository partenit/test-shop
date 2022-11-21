<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'orders_products_rel';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public $timestamps = false;
}
