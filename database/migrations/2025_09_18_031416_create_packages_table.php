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
         Schema::create('packages', function (Blueprint $t) {
            $t->id();
            $t->unsignedSmallInteger('exam_time_minutes');   // 20–120 (validated)
            $t->unsignedSmallInteger('question_number');     // 25–200 (validated)
            $t->decimal('pass_mark_percent', 5, 2);          // >= 40 (validated)
            $t->text('exam_instructions')->nullable();
            $t->dateTime('starts_at');                       // dateTime avoids MySQL default issues
            $t->dateTime('ends_at');
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
