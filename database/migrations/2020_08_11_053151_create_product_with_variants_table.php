<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductWithVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_with_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_number')->unique();
            $table->string('variant_value');
            $table->decimal('variant_price',9,2);
            $table->tinyInteger('variant_status')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('product_with_variants');
    }
}
