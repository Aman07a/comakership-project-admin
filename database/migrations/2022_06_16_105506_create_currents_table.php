<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('is_vacated')->nullable();
            $table->string('association_of_owners_has_long_term_maintenance_plan')->nullable();
            $table->string('check_list_association_of_owners_available')->nullable();
            $table->string('current_destination_description')->nullable();
            $table->string('current_usage_description')->nullable();
            $table->string('percentage_rented')->nullable();
            $table->string('is_partially_rented')->nullable();
            $table->string('revenue_per_year')->nullable();
            $table->string('for_take_over_items')->nullable();
            $table->string('pavement')->nullable();
            $table->string('sector_types')->nullable();
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
        Schema::dropIfExists('currents');
    }
}
