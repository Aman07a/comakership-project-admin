<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGardensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gardens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('quality')->nullable();
            $table->string('type')->nullable();
            $table->string('width')->nullable();
            $table->string('length')->nullable();
            $table->string('area')->nullable();
            $table->string('is_main_garden')->nullable();
            $table->string('orientation')->nullable();
            $table->string('has_backyard_entrance')->nullable();
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
        Schema::dropIfExists('gardens');
    }
}
