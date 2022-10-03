<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('for_special_target_audience')->nullable();
            $table->string('communal_areas')->nullable();
            $table->string('security_measures')->nullable();
            $table->string('is_qualified_for_people_with_disabilities')->nullable();
            $table->string('is_qualified_for_seniors')->nullable();
            $table->string('comfort_quality')->nullable();
            $table->string('certifications')->nullable();
            $table->string('polution_types')->nullable();
            $table->string('maintenance_inside')->nullable();
            $table->string('maintenance_outside')->nullable();
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
        Schema::dropIfExists('evaluations');
    }
}
