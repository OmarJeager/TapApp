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
        Schema::create('ppm_week_controls', function (Blueprint $table) {
            $table->id();
                        // Example: 40, 41, 42...
            $table->string('week_due');

            // false = hidden from users
            // true  = visible to users
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
             $table->string('status')->default('draft');
             $table->timestamp('last_pushed_at')->nullable();
             $table->timestamp('completed_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->unsignedSmallInteger('year')->default(now()->year);
             $table->index(['year', 'week_due']);
             // One control record per week
            $table->unique('week_due');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppm_week_controls');
    }
};
