<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('is_residential_lot')->nullable();
            $table->string('for_permanent_residence')->nullable();
            $table->string('for_recreation')->nullable();
            $table->string('property_type')->nullable();
            $table->string('is_residential')->nullable();
            $table->string('is_commercial')->nullable();
            $table->string('is_agricultural')->nullable();
            $table->string('foreign_property_tags')->nullable();
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
        Schema::dropIfExists('types');
    }
}
