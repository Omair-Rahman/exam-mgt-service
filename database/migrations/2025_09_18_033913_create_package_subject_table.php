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
         Schema::create('package_subject', function (Blueprint $t) {
            $t->id();
            $t->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            // We treat categories as “subjects”
            $t->foreignId('subject_id')->constrained('categories')->cascadeOnDelete();
            $t->unique(['package_id','subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_subject');
    }
};
