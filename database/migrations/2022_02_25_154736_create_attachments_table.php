<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('main_image')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->datetime('creation_date_time')->nullable();
            $table->datetime('modification_date_time')->nullable();
            $table->string('hash')->nullable();
            $table->string('URL_normalized_file')->nullable();
            $table->string('URL_medium_file')->nullable();
            $table->string('index')->nullable();
            $table->string('URL_thumb_file')->nullable();
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
        Schema::dropIfExists('attachments');
    }
}
