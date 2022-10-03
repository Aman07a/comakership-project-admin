<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText('description_nl')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('balcony_description')->nullable();
            $table->longText('ground_floor_description')->nullable();
            $table->longText('first_floor_description')->nullable();
            $table->longText('second_floor_description')->nullable();
            $table->longText('other_floor_description')->nullable();
            $table->longText('details_description')->nullable();
            $table->longText('garden_description')->nullable();
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
        Schema::dropIfExists('descriptions');
    }
}
