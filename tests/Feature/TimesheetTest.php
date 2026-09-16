<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimesheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_start_and_stop_a_timer_for_a_task(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $projeto = Projeto::create([
            'titulo' => 'Projeto de horas',
            'data_inicio' => now()->toDateString(),
            'status' => 'pendente',
            'user_id' => $user->id,
        ]);
        $tarefa = Tarefa::create([
            'titulo' => 'Apontar horas',
            'data_inicio' => now()->toDateString(),
            'status' => Tarefa::STATUS_DESENVOLVIMENTO,
            'tipo' => 'feature',
            'prioridade' => 'media',
            'estimativa_minutos' => 120,
            'projeto_id' => $projeto->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->postJson(route('tarefas.timer.start', $tarefa), ['descricao' => 'Implementação', 'billable' => true])
            ->assertOk();

        $timesheet = Timesheet::firstOrFail();
        $timesheet->update(['inicio' => now()->subMinutes(12)]);

        $this->actingAs($user)
            ->postJson(route('tarefas.timer.start', $tarefa))
            ->assertStatus(422);

        $this->actingAs($user)
            ->postJson(route('tarefas.timer.stop', $tarefa), ['descricao' => 'Implementação concluída'])
            ->assertOk()
            ->assertJsonPath('duracao_minutos', 12);

        $this->assertDatabaseHas('timesheets', [
            'id' => $timesheet->id,
            'duracao_minutos' => 12,
            'billable' => true,
        ]);
    }
}
