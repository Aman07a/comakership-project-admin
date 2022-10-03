<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('acceptance')->nullable();
            $table->string('acceptance_description')->nullable();
            $table->datetime('acceptance_date')->nullable();
            $table->string('self_Intrest')->nullable();
            $table->string('is_for_rent')->nullable();
            $table->string('is_for_sale')->nullable();
            $table->string('open_house')->nullable();
            $table->string('is_incentive')->nullable();
            $table->datetime('available_from_date')->nullable();
            $table->datetime('available_until_date')->nullable();
            $table->datetime('auction_date')->nullable();
            $table->string('linked_object')->nullable();
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
        Schema::dropIfExists('offers');
    }
}
