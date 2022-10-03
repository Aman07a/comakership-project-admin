<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('commission_contact_gross')->nullable();
            $table->string('commission_customer_bmm')->nullable();
            $table->string('commission_customer_gross')->nullable();
            $table->string('commission_customer_percent')->nullable();
            $table->string('service_costs')->nullable();
            $table->string('gas_costs')->nullable();
            $table->string('water_costs')->nullable();
            $table->string('electricity_costs')->nullable();
            $table->string('heating_costs')->nullable();
            $table->string('price_history')->nullable();
            $table->string('rent_price')->nullable();
            $table->string('rent_specification')->nullable();
            $table->string('rent_price_type')->nullable();
            $table->string('furniture_costs')->nullable();
            $table->string('advanced_payment_amount')->nullable();
            $table->string('deposit')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('realised_price')->nullable();
            $table->string('price_code')->nullable();
            $table->string('purchase_condition')->nullable();
            $table->string('purchase_specification')->nullable();
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
        Schema::dropIfExists('financials');
    }
}
