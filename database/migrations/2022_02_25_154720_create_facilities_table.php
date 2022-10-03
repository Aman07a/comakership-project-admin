<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('air_treatments')->nullable();
            $table->string('air_treatments_office')->nullable();
            $table->string('balcony')->nullable();
            $table->string('company_listings')->nullable();
            $table->string('electricity')->nullable();
            $table->string('fencing')->nullable();
            $table->string('fire_place')->nullable();
            $table->string('garage')->nullable();
            $table->string('garden')->nullable();
            $table->string('horse_trough_indoor')->nullable();
            $table->string('horse_trough_outdoor')->nullable();
            $table->string('horse_trough_drainage')->nullable();
            $table->string('horse_walker')->nullable();
            $table->string('industrial_facilities')->nullable();
            $table->string('installations')->nullable();
            $table->string('internet_connection')->nullable();
            $table->string('leisure_facilities')->nullable();
            $table->string('local_Sewer')->nullable();
            $table->string('milking_system_types')->nullable();
            $table->string('office')->nullable();
            $table->string('office_facilities')->nullable();
            $table->string('office_facilities_office')->nullable();
            $table->string('parking_types')->nullable();
            $table->string('phone_line')->nullable();
            $table->string('poultry_housing')->nullable();
            $table->string('sewer_connection')->nullable();
            $table->string('social_property_facilities')->nullable();
            $table->string('structures')->nullable();
            $table->string('terrain')->nullable();
            $table->string('drainage')->nullable();
            $table->string('sanitation_lock')->nullable();
            $table->string('open_porch')->nullable()->nullable();
            $table->string('tank')->nullable();
            $table->string('house')->nullable();
            $table->string('storage_room')->nullable()->nullable();
            $table->string('upholstered')->nullable()->nullable();
            $table->string('upholstered_type')->nullable();
            $table->string('ventilation')->nullable();
            $table->string('alarm')->nullable();
            $table->string('roller_blinds')->nullable();
            $table->string('cable_TV')->nullable();
            $table->string('outdoor_awnings')->nullable();
            $table->string('swimming_pool')->nullable();
            $table->string('elevator')->nullable();
            $table->string('airco')->nullable();
            $table->string('windmill')->nullable();
            $table->string('sun_collectors')->nullable();
            $table->string('satellite_dish')->nullable();
            $table->string('jacuzzi')->nullable();
            $table->string('steam_cabin')->nullable();
            $table->string('flue_tube')->nullable();
            $table->string('sliding_doors')->nullable();
            $table->string('french_balcony')->nullable();
            $table->string('sky_light')->nullable();
            $table->string('sauna')->nullable();
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
        Schema::dropIfExists('facilities');
    }
}
