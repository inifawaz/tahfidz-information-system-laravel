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
        Schema::create('promotion_mistakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mistake_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('verse_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('from_index')->nullable();
            $table->unsignedInteger('to_index')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_mistakes');
    }
};
