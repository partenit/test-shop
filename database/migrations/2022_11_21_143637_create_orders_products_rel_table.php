<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_products_rel', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->comment('ID товара из таблицы products');
            $table->foreign('product_id')->references('id')->on('products');
            $table->bigInteger('order_id')->unsigned()->comment('ID заказа из таблицы orders');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->decimal('price', 10)->unsigned();
            $table->smallInteger('quantity')->unsigned();
            $table->index('product_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_products_rel');
    }
};
