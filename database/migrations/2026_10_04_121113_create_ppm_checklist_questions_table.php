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
        Schema::create('ppm_checklist_questions', function (Blueprint $table) {
            $table->id();
             $table->foreignId('ppm_checklist_id')
        ->constrained('ppm_checklists')
        ->cascadeOnDelete();

     $table->foreignId('checklist_question_id')
        ->nullable()
        ->constrained('checklist_questions')
        ->nullOnDelete();

    // Snapshot
    $table->string('type');
    $table->unsignedInteger('frequency')->nullable();
    $table->text('question_text');
    $table->integer('order')->default(0);
    $table->string('variant')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppm_checklist_questions');
    }
};
