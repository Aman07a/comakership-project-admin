<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('count_of_floors')->nullable();
            $table->string('count_of_garages')->nullable();
            $table->string('count_of_rooms')->nullable();
            $table->string('count_of_bedrooms')->nullable();
            $table->string('count_of_bathrooms')->nullable();
            $table->string('count_of_toilettes')->nullable();
            $table->string('count_of_gardens')->nullable();
            $table->string('count_of_moorings_cattles')->nullable();
            $table->string('count_of_moorings_dairy_cattles')->nullable();
            $table->string('count_of_garage_places')->nullable();
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
        Schema::dropIfExists('counts');
    }
}
