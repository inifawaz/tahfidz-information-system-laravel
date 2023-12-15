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
        Schema::create('promotion_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_part_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['setoran sempurna', 'pertanyaan']);
            $table->date('due_date')->nullable()->default(now()->addDays(2)->toDateString());
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_tasks');
    }
};
