<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KanbanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kanban_requires_authentication(): void
    {
        $this->get(route('kanban.index'))->assertRedirect(route('login'));
    }

    public function test_task_status_can_be_moved_by_assigned_developer(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $projeto = Projeto::create([
            'titulo' => 'Projeto Kanban',
            'descricao' => null,
            'data_inicio' => now()->toDateString(),
            'data_termino' => null,
            'status' => 'pendente',
            'user_id' => $user->id,
        ]);
        $tarefa = Tarefa::create([
            'titulo' => 'Implementar quadro',
            'descricao' => 'Mover tarefa entre colunas',
            'data_inicio' => now()->toDateString(),
            'data_termino' => null,
            'status' => Tarefa::STATUS_BACKLOG,
            'tipo' => 'feature',
            'prioridade' => 'alta',
            'projeto_id' => $projeto->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->patchJson(route('tarefas.status', $tarefa), ['status' => Tarefa::STATUS_DESENVOLVIMENTO])
            ->assertOk()
            ->assertJson(['status' => Tarefa::STATUS_DESENVOLVIMENTO]);

        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id,
            'status' => Tarefa::STATUS_DESENVOLVIMENTO,
        ]);
    }
}
