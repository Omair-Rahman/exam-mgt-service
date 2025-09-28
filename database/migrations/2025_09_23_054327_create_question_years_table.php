<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('question_years', function (Blueprint $table) {
            $table->id();
            $table->string('year')->unique();                       // e.g., "2025"
            $table->unsignedInteger('question_limit')->default(200); // limit field itself ≤ 200
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_years');
    }
};