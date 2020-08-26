<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductNoVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_no_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_number')->unique();
            $table->string('product_number');
            $table->decimal('price',9,2);
            $table->timestamps();
            $table->foreign('product_number')->references('number')->on('products');
            $table->foreign('inventory_number')->references('number')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_no_variants');
    }
}
