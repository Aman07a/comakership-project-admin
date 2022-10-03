<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('street', 120)->nullable();
            $table->string('house_number', 20)->nullable();
            $table->string('addition', 20)->nullable();
            $table->string('zipcode', 50)->nullable();
            $table->string('province', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('main_image')->nullable();
            $table->string('effective_area')->nullable();
            $table->string('property_info_ID')->nullable();
            $table->unsignedBigInteger('broker_id');

            // Foreign connection:
            $table->foreign('broker_id')->references('id')->on('brokers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
