<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->unsignedInteger('category_id');
            $table->string('product_name');
            $table->text('product_description');
            $table->string('product_variant')->nullable();
            $table->integer('product_stock');
            $table->integer('product_critical_level');
            $table->decimal('product_price',9,2);
            $table->string('product_weight')->nullable();
            $table->string('product_width')->nullable();
            $table->string('product_length')->nullable();
            $table->string('product_height')->nullable();
            $table->integer('product_status')->default(1);
            $table->string('product_image');
            $table->string('product_url');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
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
}
