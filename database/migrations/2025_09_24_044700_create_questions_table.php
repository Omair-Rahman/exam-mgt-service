<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('question_year_id')->constrained('question_years')->cascadeOnUpdate()->restrictOnDelete();

            // Content (rich-text HTML)
            $table->longText('question');   // DO NOT make unique directly on LONGTEXT
            $table->longText('answer_1');
            $table->longText('answer_2');
            $table->longText('answer_3');
            $table->longText('answer_4');
            $table->unsignedTinyInteger('correct_option');  // 1..4
            $table->longText('explanation')->nullable();

            // Uniqueness for LONGTEXT via hash
            $table->char('question_hash', 64)->unique();

            // Meta
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Fast counting for limits
            $table->index(['question_year_id']);
            $table->index(['question_year_id','category_id']);
            $table->index(['category_id','subcategory_id']);
            // MySQL 8+: you may enable a check
            // $table->check('correct_option between 1 and 4');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
