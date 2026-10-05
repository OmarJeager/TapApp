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
        Schema::create('ppm_checklist_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppm_checklist_id')->constrained('ppm_checklists')->cascadeOnDelete();
            $table->string('requested_by_matricule');
            $table->text('request_reason')->nullable();      // why user wants to edit
            $table->enum('status', ['pending', 'approved', 'rejected', 'used'])->default('pending');
            $table->string('decided_by_matricule')->nullable();
            $table->text('admin_note')->nullable();          // rejection reason
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppm_checklist_edit_requests');
    }
};
