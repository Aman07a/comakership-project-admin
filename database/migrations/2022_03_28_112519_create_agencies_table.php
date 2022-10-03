<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('foreign_ID')->nullable();
            $table->string('name')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('logo')->nullable();
            $table->string('bank_account_appellation')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('COC_number')->nullable();
            $table->string('real_estate_association')->nullable();
            $table->string('real_estate_association_number')->nullable();
            $table->string('visit_address')->nullable();
            $table->string('visit_street')->nullable();
            $table->string('visit_house_number')->nullable();
            $table->string('visit_zip_code')->nullable();
            $table->string('visit_district')->nullable();
            $table->string('visit_city')->nullable();
            $table->string('visit_sub_region')->nullable();
            $table->string('visit_region')->nullable();
            $table->string('visit_country_code')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('postal_street')->nullable();
            $table->string('postal_house_number')->nullable();
            $table->string('postal_zip_code')->nullable();
            $table->string('postal_district')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('postal_sub_region')->nullable();
            $table->string('postal_region')->nullable();
            $table->string('postal_country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
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
        Schema::dropIfExists('agencies');
    }
}
