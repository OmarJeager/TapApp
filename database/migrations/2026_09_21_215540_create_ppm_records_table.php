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
        Schema::create('ppm_records', function (Blueprint $table) {
            $table->id();
            $table->string('ppm_id')->nullable();
            $table->string('week_due')->nullable();
            $table->dateTime('date_time_created')->nullable();
            $table->string('job_id')->nullable();
            $table->string('asset_description')->nullable();
            $table->string('asset_id')->nullable();
            $table->string('position_3')->nullable();
            $table->string('manufacturer_serial_number')->nullable();
            $table->string('frequency')->nullable();
            $table->string('est_resource_minutes')->nullable();
            $table->string('trade')->nullable();
            $table->string('position_2')->nullable();
            $table->string('brief_description')->nullable();
            $table->string('frequency_text')->nullable();
            $table->string('asset_position')->nullable();
            $table->string('est_duration')->nullable();
            $table->string('est_resource_time')->nullable();
            $table->string('system')->nullable();
            $table->integer('risk_id')->nullable();
            $table->string('plant_group')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppm_records');
    }
};
