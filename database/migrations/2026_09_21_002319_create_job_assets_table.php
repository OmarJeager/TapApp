<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_assets', function (Blueprint $table) {
            $table->id();
            $table->integer('weak_due')->nullable();
            $table->dateTime('date_time_created')->nullable();
            $table->integer('job_id')->nullable();
            $table->string('asset_description')->nullable();
            $table->string('asset_id')->nullable();
            $table->string('position')->nullable();
            $table->integer('manufacture_serial_number')->nullable();
            $table->integer('frequency_and_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_assets');
    }
};
