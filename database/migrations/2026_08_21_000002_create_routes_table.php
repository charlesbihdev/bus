<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_town_id')->constrained('towns')->cascadeOnDelete();
            $table->foreignId('destination_town_id')->constrained('towns')->cascadeOnDelete();
            $table->unsignedInteger('base_price'); // pesewas (GHS * 100)
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['origin_town_id', 'destination_town_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
