<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countdown_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Next Exam');
            $table->timestamp('target_at'); // target date-time to count down to
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countdown_settings');
    }
};