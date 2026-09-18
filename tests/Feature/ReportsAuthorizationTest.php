<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_require_authentication(): void
    {
        $this->get(route('reports.index'))->assertRedirect(route('login'));
        $this->get(route('projetos.report'))->assertRedirect(route('login'));
    }

    public function test_authenticated_internal_user_can_open_reports_index(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk();
    }

    public function test_project_report_view_includes_task_title_in_the_table(): void
    {
        $user = User::factory()->create(['role' => 'pm', 'is_admin' => false]);
        $projeto = Projeto::create([
            'titulo' => 'Portal de Clientes',
            'descricao' => 'Relatório de entregas',
            'data_inicio' => now()->toDateString(),
            'data_termino' => now()->addDays(10)->toDateString(),
            'status' => 'pendente',
            'user_id' => $user->id,
        ]);
        $tarefa = Tarefa::create([
            'titulo' => 'Implementar relatório do cliente',
            'descricao' => 'Relatório para portal',
            'data_inicio' => now()->toDateString(),
            'data_termino' => now()->addDays(5)->toDateString(),
            'status' => Tarefa::STATUS_A_FAZER,
            'tipo' => 'feature',
            'prioridade' => 'alta',
            'projeto_id' => $projeto->id,
            'user_id' => $user->id,
        ]);

        $view = view('projetos.report', ['projetos' => collect([$projeto]), 'tarefas' => collect([$tarefa])]);

        $this->assertStringContainsString('Implementar relatório do cliente', (string) $view);
    }
}
