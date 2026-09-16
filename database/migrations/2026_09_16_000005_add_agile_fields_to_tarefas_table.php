<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->string('status', 30)->default('backlog')->change();
            $table->string('tipo', 30)->default('feature')->after('status');
            $table->string('prioridade', 30)->default('media')->after('tipo');
            $table->string('tag')->nullable()->after('prioridade');
            $table->foreignId('sprint_id')->nullable()->after('projeto_id')->constrained('sprints')->nullOnDelete();
        });

        DB::table('tarefas')->where('status', 'pendente')->update(['status' => 'a_fazer']);
        DB::table('tarefas')->where('status', 'atrasada')->update(['status' => 'a_fazer']);
        DB::table('tarefas')->where('status', 'concluida')->update(['status' => 'concluido']);
    }

    public function down(): void
    {
        DB::table('tarefas')->where('status', 'a_fazer')->update(['status' => 'pendente']);
        DB::table('tarefas')->where('status', 'concluido')->update(['status' => 'concluida']);

        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropForeign(['sprint_id']);
            $table->dropColumn(['sprint_id', 'tipo', 'prioridade', 'tag']);
            $table->enum('status', ['pendente', 'atrasada', 'concluida'])->default('pendente')->change();
        });
    }
};
