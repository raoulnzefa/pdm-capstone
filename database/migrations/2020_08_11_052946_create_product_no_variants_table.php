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
            $table->string('product_number')->unique();
            $table->decimal('price',9,2);
            $table->timestamps();
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
        Schema::dropIfExists('product_no_variants');
    }
}
