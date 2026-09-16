<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_internal_user_can_create_a_project(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);

        $this->actingAs($user)
            ->post(route('projeto.store'), [
                'titulo' => 'Projeto colaborativo',
                'descricao' => 'Descrição',
                'data_inicio' => now()->toDateString(),
                'user_id' => $user->id,
            ])
            ->assertRedirect(route('projeto.index'));

        $this->assertDatabaseHas('projetos', ['titulo' => 'PROJETO COLABORATIVO', 'updated_by' => $user->id]);
    }

    public function test_internal_user_can_edit_and_audit_another_users_task(): void
    {
        $owner = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $editor = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $projeto = Projeto::create([
            'titulo' => 'Projeto',
            'data_inicio' => now()->toDateString(),
            'status' => 'pendente',
            'user_id' => $owner->id,
        ]);
        $tarefa = Tarefa::create([
            'titulo' => 'Tarefa original',
            'data_inicio' => now()->toDateString(),
            'status' => Tarefa::STATUS_BACKLOG,
            'tipo' => 'feature',
            'prioridade' => 'media',
            'projeto_id' => $projeto->id,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($editor)
            ->put(route('tarefas.update', $tarefa), [
                'titulo' => 'Tarefa revisada',
                'data_inicio' => now()->toDateString(),
                'projeto_id' => $projeto->id,
                'tipo' => 'melhoria',
                'prioridade' => 'alta',
                'status' => Tarefa::STATUS_DESENVOLVIMENTO,
            ])
            ->assertRedirect(route('tarefas.index'));

        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'updated_by' => $editor->id]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => Tarefa::class,
            'auditable_id' => $tarefa->id,
            'user_id' => $editor->id,
            'event' => 'updated',
        ]);
    }
}
