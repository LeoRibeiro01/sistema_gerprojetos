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
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_inicio');
            $table->date('data_termino')->nullable();
            $table->foreignId('user_id') // Alterado de cliente_id para user_id
                  ->constrained('users') // Referência à tabela users
                  ->onDelete('cascade'); // Mantém o comportamento de exclusão em cascata
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projetos'); // Certifique-se de que o nome da tabela está correto
    }
};
