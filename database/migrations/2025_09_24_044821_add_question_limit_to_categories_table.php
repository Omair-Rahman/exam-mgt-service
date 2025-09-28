<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'question_limit')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedInteger('question_limit')->default(200)->after('name');
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'question_limit')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('question_limit');
            });
        }
    }
};
