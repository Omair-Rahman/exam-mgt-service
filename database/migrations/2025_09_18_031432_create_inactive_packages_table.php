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
        Schema::create('inactive_packages', function (Blueprint $t) {
            $t->id();
            $t->unsignedSmallInteger('exam_time_minutes');
            $t->unsignedSmallInteger('question_number');
            $t->decimal('pass_mark_percent', 5, 2);
            $t->text('exam_instructions')->nullable();
            $t->dateTime('starts_at');
            $t->dateTime('ends_at');
            $t->dateTime('archived_at')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inactive_packages');
    }
};
