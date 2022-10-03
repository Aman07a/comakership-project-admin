<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('administrative_area_level_1')->nullable();
            $table->string('administrative_area_level_1_ID')->nullable();
            $table->string('administrative_area_level_1_short_name')->nullable();
            $table->string('administrative_area_level_2')->nullable();
            $table->string('administrative_area_level_2_ID')->nullable();
            $table->string('administrative_area_level_2_short_name')->nullable();
            $table->string('administrative_area_level_3_ID')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('country_ID')->nullable();
            $table->string('country_name')->nullable();
            $table->string('formatted_address')->nullable();
            $table->string('house_number')->nullable();
            $table->string('house_number_addendum')->nullable();
            $table->string('ISO2_country_code')->nullable();
            $table->string('ISO2_language_code')->nullable();
            $table->string('locality')->nullable();
            $table->string('locality_ID')->nullable();
            $table->string('locality_short_name')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('street_ID')->nullable();
            $table->string('street_name')->nullable();
            $table->string('street_name_short_name')->nullable();
            $table->string('sub_locality')->nullable();
            $table->string('sub_locality_ID')->nullable();
            $table->string('sub_locality_short_name')->nullable();
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
        Schema::dropIfExists('location_details');
    }
}
