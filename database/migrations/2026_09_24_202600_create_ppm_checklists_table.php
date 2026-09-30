<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppm_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppm_records_id')->constrained('ppm_records')->cascadeOnDelete();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            // Kept as a stored column for fast reporting; recompute with refreshTotalTime()
            $table->unsignedInteger('total_time_minutes')->nullable();

            $table->string('completed_by_matricule')->nullable();
            $table->foreign('completed_by_matricule')->references('matricule')->on('users')->nullOnDelete();
            $table->date('completed_at')->nullable();

            $table->string('verified_by_matricule')->nullable();
            $table->foreign('verified_by_matricule')->references('matricule')->on('users')->nullOnDelete();
            $table->date('verified_at')->nullable();

            $table->string('verified_by_quality_matricule')->nullable();
            $table->foreign('verified_by_quality_matricule')->references('matricule')->on('users')->nullOnDelete();
            $table->date('verified_quality_at')->nullable();
            $table->string('status_admin')->default('not_verified');
            $table->string('status_quality')->default('not_verified');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppm_checklists');
    }
};
