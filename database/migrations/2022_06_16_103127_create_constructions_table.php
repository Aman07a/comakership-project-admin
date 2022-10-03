<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constructions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('is_ready_for_construction')->nullable();
            $table->string('construction_period')->nullable();
            $table->string('construction_year_from')->nullable();
            $table->string('construction_year_to')->nullable();
            $table->string('is_under_construction')->nullable();
            $table->string('construction_comment')->nullable();
            $table->string('roof_type')->nullable();
            $table->string('roof_materials')->nullable();
            $table->string('roof_comments')->nullable();
            $table->string('isolation_types')->nullable();
            $table->string('is_new_estate')->nullable();
            $table->string('construction_options')->nullable();
            $table->string('windows')->nullable();
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
        Schema::dropIfExists('constructions');
    }
}
