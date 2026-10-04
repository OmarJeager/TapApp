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
