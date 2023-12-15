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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('number');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('group_capacity');
            $table->enum('revision_task_type', ['setoran sempurna', 'pertanyaan', 'acak']);
            // Portion
            $table->enum('revision_quarter_portion', [1, 2, 4, 8]);
            $table->unsignedInteger('connection_block_portion');
            $table->unsignedInteger('memorization_block_portion');
            // Max Mistake
            $table->unsignedInteger('max_promotion_recitation_mistake');
            $table->unsignedInteger('max_promotion_question_mistake');
            $table->unsignedInteger('max_revision_recitation_mistake');
            $table->unsignedInteger('max_revision_question_mistake');
            $table->unsignedInteger('max_memorization_mistake');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
