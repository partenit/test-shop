<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

trait OrderBy
{
    public function getOrderBy(Request $request, $default = 'id|asc')
    {
        $order_by = $request->input('sort', $default);

        if ($order_by) {
            $default_order_by = explode('|', $default);
            $order_by = explode('|', $order_by);
            $columns  = Schema::getColumnListing($this->table);

            $order_by[0] = ! empty($order_by[0]) && in_array($order_by[0], $columns)
                ? $order_by[0]
                : $default_order_by[0];

            $order_by[1] = ! empty($order_by[1]) && in_array($order_by[1], ['asc', 'desc'])
                ? $order_by[1]
                : $default_order_by[1];
        }

        return $order_by;
    }
}
