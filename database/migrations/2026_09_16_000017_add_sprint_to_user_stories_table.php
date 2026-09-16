<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_stories', function (Blueprint $table) {
            $table->foreignId('sprint_id')->nullable()->after('epic_id')->constrained('sprints')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_stories', function (Blueprint $table) {
            $table->dropForeign(['sprint_id']);
            $table->dropColumn('sprint_id');
        });
    }
};
