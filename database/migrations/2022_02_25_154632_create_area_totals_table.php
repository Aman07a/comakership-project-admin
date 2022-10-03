<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_totals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('floor_area_gross')->nullable();
            $table->string('storage_area_external')->nullable();
            $table->string('building_related_outdoor_space_area')->nullable();
            $table->string('effective_area')->nullable();
            $table->string('glass_coverings')->nullable();
            $table->string('other_indoor_space_area')->nullable();
            $table->string('floor_area')->nullable();
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
        Schema::dropIfExists('area_totals');
    }
}
