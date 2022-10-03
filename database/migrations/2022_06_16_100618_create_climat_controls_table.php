<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClimatControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('climat_controls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('energy_class')->nullable();
            $table->string('energy_index')->nullable();
            $table->string('due_date')->nullable();
            $table->string('has_energy_certificate')->nullable();
            $table->string('number')->nullable();
            $table->string('heating_is_combi_boiler')->nullable();
            $table->string('heating_year_of_manufacture')->nullable();
            $table->string('heating_energy_source')->nullable();
            $table->string('heating_ownership')->nullable();
            $table->string('heating_methods_water')->nullable();
            $table->string('heating_methods')->nullable();
            $table->string('heating_type_of_boiler')->nullable();
            $table->string('ventilation')->nullable();
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
        Schema::dropIfExists('climat_controls');
    }
}
