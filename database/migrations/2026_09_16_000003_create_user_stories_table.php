<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epic_id')->nullable()->constrained('epics')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->unsignedSmallInteger('pontos')->nullable();
            $table->text('criterio_aceite')->nullable();
            $table->string('status', 30)->default('backlog');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_stories');
    }
};
