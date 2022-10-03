<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_infos', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->boolean('hide_address')->nullable();
            $table->boolean('hide_house_number')->nullable();
            $table->boolean('hide_price')->nullable();
            $table->string('confidential')->nullable();
            $table->string('mandate_date')->nullable();
            $table->string('creation_date_time')->nullable();
            $table->string('modification_date_time')->nullable();
            $table->string('foreign_agency_ID')->nullable();
            $table->string('foreign_ID')->nullable();
            $table->string('property_info_ID')->nullable();
            $table->string('property_company_name')->nullable();
            $table->string('origin')->nullable();
            $table->string('public_reference_number')->nullable();
            $table->string('exclusive_status')->nullable();
            $table->string('tags')->nullable();
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
        Schema::dropIfExists('property_infos');
    }
}
