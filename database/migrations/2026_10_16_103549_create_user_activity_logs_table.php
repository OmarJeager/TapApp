<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('user_activity_logs')) {
            return;
        }

        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();

            // Actor / target
            $table->foreignId('actor_user_id')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->string('actor_name')->nullable();

            $table->foreignId('target_user_id')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->string('target_name')->nullable();

            // Event info
            $table->string('event', 100);
            $table->string('severity', 20)->default('info');
            $table->string('description', 255)->nullable();

            // Request context
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('route_name')->nullable();
            $table->json('metadata')->nullable();

            // Timestamp (append-only, no updated_at because model has $timestamps = false)
            $table->timestamp('created_at')->useCurrent();

            // Indexes — AFTER all columns are declared
            $table->index('event');
            $table->index('created_at');
            $table->index(['severity', 'created_at'], 'ual_severity_created_idx');
            $table->index(['event', 'created_at'],    'ual_event_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
