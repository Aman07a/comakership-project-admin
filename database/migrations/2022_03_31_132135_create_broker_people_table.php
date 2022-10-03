<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_people', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('foreign_ID')->nullable();
            $table->string('title')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('photo')->nullable();
            $table->string('social_medias')->nullable();
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
        Schema::dropIfExists('broker_people');
    }
}
