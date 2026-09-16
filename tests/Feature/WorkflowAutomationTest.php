<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class WorkflowAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_moving_task_to_qa_notifies_its_assignee(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $projeto = Projeto::create(['titulo' => 'Projeto QA', 'data_inicio' => now()->toDateString(), 'status' => 'pendente', 'user_id' => $user->id]);
        $tarefa = Tarefa::create(['titulo' => 'Validar entrega', 'data_inicio' => now()->toDateString(), 'status' => Tarefa::STATUS_DESENVOLVIMENTO, 'tipo' => 'feature', 'prioridade' => 'media', 'projeto_id' => $projeto->id, 'user_id' => $user->id]);

        $this->actingAs($user)->patchJson(route('tarefas.status', $tarefa), ['status' => Tarefa::STATUS_QA])->assertOk();

        Notification::assertSentTo($user, \App\Notifications\TaskReadyForQaNotification::class);
    }
}
