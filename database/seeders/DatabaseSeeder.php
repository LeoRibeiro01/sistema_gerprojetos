<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Bug;
use App\Models\ClientApproval;
use App\Models\Cliente;
use App\Models\Comment;
use App\Models\DashboardChart;
use App\Models\DailyCheckin;
use App\Models\Epic;
use App\Models\Projeto;
use App\Models\Sprint;
use App\Models\Tarefa;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $admin = User::updateOrCreate(
            ['email' => 'admin@demo.test'],
            ['name' => 'Ana Administradora', 'password' => $password, 'role' => UserRole::Admin->value, 'is_admin' => true],
        );
        $pm = User::updateOrCreate(
            ['email' => 'pm@demo.test'],
            ['name' => 'Paulo Product Manager', 'password' => $password, 'role' => UserRole::Pm->value, 'is_admin' => false],
        );
        $scrumMaster = User::updateOrCreate(
            ['email' => 'scrum@demo.test'],
            ['name' => 'Sofia Scrum Master', 'password' => $password, 'role' => UserRole::ScrumMaster->value, 'is_admin' => false],
        );
        $manager = User::updateOrCreate(
            ['email' => 'manager@demo.test'],
            ['name' => 'Marcos Engineering Manager', 'password' => $password, 'role' => UserRole::Manager->value, 'is_admin' => false],
        );
        $devs = collect([
            ['email' => 'dev1@demo.test', 'name' => 'Diego Backend'],
            ['email' => 'dev2@demo.test', 'name' => 'Beatriz Frontend'],
            ['email' => 'qa@demo.test', 'name' => 'Quintino QA'],
        ])->map(fn (array $data) => User::updateOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'], 'password' => $password, 'role' => UserRole::Dev->value, 'is_admin' => false],
        ));

        $cliente = Cliente::updateOrCreate(
            ['email' => 'contato@acme.demo'],
            ['nome' => 'Acme Tecnologia', 'telefone' => '(41) 99999-0000', 'endereco' => 'Rua das Startups, 100'],
        );
        $clienteUser = User::updateOrCreate(
            ['email' => 'cliente@acme.demo'],
            ['name' => 'Carla Cliente', 'password' => $password, 'role' => UserRole::Client->value, 'is_admin' => false, 'cliente_id' => $cliente->id],
        );

        $activeSprint = Sprint::updateOrCreate(
            ['nome' => 'Sprint Demo Atual'],
            ['data_inicio' => now()->startOfWeek(), 'data_fim' => now()->endOfWeek(), 'meta' => 'Entregar o novo fluxo de onboarding e estabilizar o checkout.', 'status' => 'ativa'],
        );
        $previousSprint = Sprint::updateOrCreate(
            ['nome' => 'Sprint Demo Anterior'],
            ['data_inicio' => now()->subWeeks(2)->startOfWeek(), 'data_fim' => now()->subWeek()->endOfWeek(), 'meta' => 'Concluir a primeira versão do painel.', 'status' => 'encerrada'],
        );

        $projects = collect([
            ['titulo' => 'Plataforma de Onboarding', 'cliente_id' => $cliente->id, 'descricao' => 'Jornada digital para ativação de novos clientes.', 'status' => 'pendente'],
            ['titulo' => 'Portal Financeiro', 'cliente_id' => $cliente->id, 'descricao' => 'Portal para faturas, indicadores e conciliação.', 'status' => 'pendente'],
            ['titulo' => 'Backoffice Interno', 'cliente_id' => null, 'descricao' => 'Automação dos processos operacionais da equipe.', 'status' => 'concluido'],
        ])->mapWithKeys(function (array $data) use ($pm) {
            $project = Projeto::updateOrCreate(
                ['titulo' => $data['titulo']],
                array_merge($data, ['data_inicio' => now()->subDays(20), 'user_id' => $pm->id]),
            );

            return [Str::slug($data['titulo']) => $project];
        });

        $onboarding = $projects['plataforma-de-onboarding'];
        $finance = $projects['portal-financeiro'];
        $backoffice = $projects['backoffice-interno'];

        $epicOnboarding = Epic::updateOrCreate(
            ['projeto_id' => $onboarding->id, 'titulo' => 'Ativação de conta'],
            ['descricao' => 'Simplificar e medir a primeira experiência do cliente.', 'status' => 'em_andamento'],
        );
        $epicFinance = Epic::updateOrCreate(
            ['projeto_id' => $finance->id, 'titulo' => 'Conciliação financeira'],
            ['descricao' => 'Reduzir trabalho manual no fechamento mensal.', 'status' => 'aberta'],
        );

        foreach ([
            [$epicOnboarding, $activeSprint, 'Cadastro em duas etapas', 'Permitir cadastro progressivo com validação de e-mail.', 5, 'Usuário consegue concluir cada etapa e retomar depois.', 'em_andamento'],
            [$epicOnboarding, $activeSprint, 'Checklist de boas-vindas', 'Exibir tarefas iniciais para o cliente.', 3, 'Checklist aparece após o primeiro login.', 'concluida'],
            [$epicFinance, $activeSprint, 'Importação de extrato', 'Importar arquivos OFX e CSV.', 8, 'Arquivo válido é processado e apresenta erros de linha.', 'backlog'],
            [$epicFinance, $previousSprint, 'Resumo mensal', 'Apresentar receitas e despesas por período.', 5, 'Usuário consegue filtrar e exportar o resumo.', 'concluida'],
        ] as [$epic, $sprint, $title, $description, $points, $criteria, $status]) {
            UserStory::updateOrCreate(
                ['epic_id' => $epic->id, 'titulo' => $title],
                ['sprint_id' => $sprint->id, 'descricao' => $description, 'pontos' => $points, 'criterio_aceite' => $criteria, 'status' => $status],
            );
        }

        $tasks = collect([
            [$onboarding, $activeSprint, $devs[0], 'Mapear eventos do onboarding', Tarefa::STATUS_BACKLOG, 'feature', 'media', 'onboarding', 180],
            [$onboarding, $activeSprint, $devs[1], 'Construir tela de boas-vindas', Tarefa::STATUS_A_FAZER, 'feature', 'alta', 'frontend', 300],
            [$onboarding, $activeSprint, $devs[0], 'Criar endpoint de ativação', Tarefa::STATUS_DESENVOLVIMENTO, 'feature', 'alta', 'api', 240],
            [$onboarding, $activeSprint, $devs[1], 'Revisar componentes de formulário', Tarefa::STATUS_CODE_REVIEW, 'melhoria', 'media', 'frontend', 120],
            [$finance, $activeSprint, $devs[2], 'Validar importação de CSV', Tarefa::STATUS_QA, 'bug', 'critica', 'qa', 90],
            [$finance, $previousSprint, $devs[0], 'Corrigir arredondamento do total', Tarefa::STATUS_CONCLUIDO, 'bug', 'alta', 'financeiro', 120],
            [$backoffice, null, $manager, 'Documentar processo de deploy', Tarefa::STATUS_CONCLUIDO, 'debito_tecnico', 'baixa', 'docs', 60],
        ])->map(function (array $data) use ($admin) {
            [$project, $sprint, $user, $title, $status, $type, $priority, $tag, $estimate] = $data;
            return Tarefa::updateOrCreate(
                ['titulo' => $title, 'projeto_id' => $project->id],
                ['descricao' => 'Dado demonstrativo para exercitar o fluxo completo da plataforma.', 'data_inicio' => now()->subDays(4), 'data_termino' => now()->addDays(5), 'status' => $status, 'tipo' => $type, 'prioridade' => $priority, 'tag' => $tag, 'estimativa_minutos' => $estimate, 'projeto_id' => $project->id, 'sprint_id' => $sprint?->id, 'user_id' => $user->id, 'updated_by' => $admin->id],
            );
        });

        $tasks[2]->dependencias()->syncWithoutDetaching([$tasks[0]->id]);
        $tasks[4]->dependencias()->syncWithoutDetaching([$tasks[2]->id]);

        Bug::updateOrCreate(
            ['projeto_id' => $finance->id, 'titulo' => 'Total diverge após importar CSV'],
            ['user_id' => $devs[2]->id, 'descricao' => 'Cenário de demonstração para o fluxo de bugs.', 'severidade' => 'alta', 'status' => 'em_andamento'],
        );

        foreach ([[$tasks[2], $devs[0], 135, true], [$tasks[4], $devs[2], 75, true], [$tasks[5], $devs[0], 120, false]] as [$task, $user, $minutes, $billable]) {
            Timesheet::updateOrCreate(
                ['tarefa_id' => $task->id, 'user_id' => $user->id, 'descricao' => 'Sessão demonstrativa'],
                ['inicio' => now()->subDays(2), 'fim' => now()->subDays(2)->addMinutes($minutes), 'duracao_minutos' => $minutes, 'billable' => $billable],
            );
        }

        Comment::updateOrCreate(
            ['tarefa_id' => $tasks[2]->id, 'user_id' => $devs[0]->id],
            ['conteudo' => "**Implementação iniciada**. @qa pode preparar os cenários de validação.\n\n```php\nreturn response()->json(['ok' => true]);\n```", 'mencoes' => ['qa']],
        );
        DailyCheckin::updateOrCreate(
            ['user_id' => $devs[0]->id, 'data' => today()],
            ['ontem' => 'Implementei o endpoint de ativação.', 'hoje' => 'Vou acompanhar o code review.', 'impedimentos' => 'Nenhum.'],
        );
        ClientApproval::updateOrCreate(
            ['projeto_id' => $onboarding->id, 'user_id' => $clienteUser->id],
            ['status' => 'aprovado', 'comentario' => 'Escopo inicial aprovado para demonstração.', 'approved_at' => now()],
        );

        foreach ([
            [$admin, 'Distribuição de tarefas', 'task_status', 'bar', '#4f46e5'],
            [$admin, 'Produtividade da equipe', 'productivity', 'pie', '#0ea5e9'],
            [$pm, 'Horas faturáveis', 'billable_hours', 'line', '#059669'],
        ] as [$user, $title, $metric, $type, $color]) {
            DashboardChart::updateOrCreate(
                ['user_id' => $user->id, 'titulo' => $title],
                ['metrica' => $metric, 'tipo' => $type, 'cor' => $color],
            );
        }

        $this->command?->info('Dados demonstrativos criados. Senha de todos os usuários demo: password');
    }
}
