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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->nullable()->unsigned()->comment('ID категории из таблицы categories');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('code', 10);
            $table->string('name', 100);
            $table->text('description');
            $table->string('photo', 50);
            $table->string('slug', 100);
            $table->decimal('price', 10)->unsigned()->comment('ID категории из таблицы categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
