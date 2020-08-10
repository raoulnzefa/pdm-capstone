<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->string('product_number');
            $table->string('product_name');
            $table->decimal('price',10,2);
            $table->integer('quantity');
            $table->decimal('total',10,2);
            $table->string('status');
            $table->timestamps();
            $table->foreign('order_number')->references('number')->on('orders')->onDelete('cascade');
            $table->foreign('product_number')->references('number')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
