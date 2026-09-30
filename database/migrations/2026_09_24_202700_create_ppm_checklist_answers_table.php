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
        Schema::create('ppm_checklist_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppm_checklist_id')->constrained('ppm_checklists')->cascadeOnDelete();
            $table->foreignId('checklist_question_id')->constrained('checklist_questions')->cascadeOnDelete();

            $table->enum('response', ['ok', 'not_ok'])->nullable();
            $table->text('comment')->nullable();
            $table->string('dpn')->nullable();
            $table->text('observation')->nullable();

            $table->timestamps();

            $table->unique(['ppm_checklist_id', 'checklist_question_id'], 'ppm_checklist_answers_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppm_checklist_answers');
    }
};
