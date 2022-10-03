<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('address')->nullable();
            $table->string('street_name')->nullable();
            $table->string('house_number')->nullable();
            $table->string('house_number_postfix')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('district')->nullable();
            $table->string('city_name')->nullable();
            $table->string('subregion')->nullable();
            $table->string('region')->nullable();
            $table->string('country_code')->nullable();
            $table->string('floor')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('property_ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
