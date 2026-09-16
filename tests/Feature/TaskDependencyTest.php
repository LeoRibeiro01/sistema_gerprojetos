<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDependencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_add_and_remove_a_dependency_from_same_project(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $projeto = Projeto::create(['titulo' => 'Projeto dependências', 'data_inicio' => now()->toDateString(), 'status' => 'pendente', 'user_id' => $user->id]);
        $blocked = Tarefa::create(['titulo' => 'Tarefa bloqueada', 'data_inicio' => now()->toDateString(), 'status' => Tarefa::STATUS_BACKLOG, 'tipo' => 'feature', 'prioridade' => 'media', 'projeto_id' => $projeto->id, 'user_id' => $user->id]);
        $dependency = Tarefa::create(['titulo' => 'Pré-requisito', 'data_inicio' => now()->toDateString(), 'status' => Tarefa::STATUS_BACKLOG, 'tipo' => 'feature', 'prioridade' => 'media', 'projeto_id' => $projeto->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('tarefas.dependencies.store', $blocked), ['depende_de_id' => $dependency->id])->assertRedirect();
        $this->assertDatabaseHas('tarefa_dependencias', ['tarefa_id' => $blocked->id, 'depende_de_id' => $dependency->id]);

        $this->actingAs($user)->delete(route('tarefas.dependencies.destroy', [$blocked, $dependency]))->assertRedirect();
        $this->assertDatabaseMissing('tarefa_dependencias', ['tarefa_id' => $blocked->id, 'depende_de_id' => $dependency->id]);
    }
}
