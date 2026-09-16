<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefa_dependencias', function (Blueprint $table) {
            $table->foreignId('tarefa_id')->constrained('tarefas')->cascadeOnDelete();
            $table->foreignId('depende_de_id')->constrained('tarefas')->cascadeOnDelete();
            $table->primary(['tarefa_id', 'depende_de_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefa_dependencias');
    }
};
