<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo_url');
            $table->string('logo_path');
            $table->string('image_url');
            $table->string('image_path');
            $table->string('name');
            $table->string('address');
            $table->string('contact_number');
            $table->string('tin_number');
            $table->text('about_us');
            $table->text('terms_and_conditions');
            $table->text('return_policy');
            $table->integer('delivery_days');
            $table->integer('due_payment_days');
            $table->integer('follow_up_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_details');
    }
}
