<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('inicio');
            $table->timestamp('fim')->nullable();
            $table->unsignedInteger('duracao_minutos')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('billable')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'fim']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
