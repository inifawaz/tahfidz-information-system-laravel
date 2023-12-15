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
        Schema::create('revision_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revision_part_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['setoran sempurna', 'pertanyaan']);
            $table->date('due_date')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_tasks');
    }
};
