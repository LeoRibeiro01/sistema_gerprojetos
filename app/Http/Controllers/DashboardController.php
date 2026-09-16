<?php

namespace App\Http\Controllers;

use App\Models\DailyCheckin;
use App\Models\Sprint;
use App\Models\Tarefa;
use App\Models\Timesheet;
use App\Models\UserStory;
use App\Models\User;
use App\Models\DashboardChart;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->user()->hasRole(\App\Enums\UserRole::Client)) {
            return redirect()->route('client-portal.index');
        }

        $today = today();
        $activeSprint = Sprint::where('status', 'ativa')->latest('data_inicio')->first();
        $teamSize = User::whereIn('role', ['admin', 'pm', 'dev'])->count();
        $activeAssignments = Tarefa::whereNotIn('status', [Tarefa::STATUS_CONCLUIDO])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $burndown = $activeSprint
            ? $this->burndown($activeSprint)
            : [];
        $velocity = Sprint::query()
            ->where('status', 'encerrada')
            ->orderByDesc('data_fim')
            ->take(6)
            ->get()
            ->map(fn (Sprint $sprint) => [
                'nome' => $sprint->nome,
                'pontos' => (int) UserStory::where('sprint_id', $sprint->id)->where('status', 'concluida')->sum('pontos'),
            ])
            ->reverse()
            ->values();

        return view('dashboard', [
            'dailyCheckin' => DailyCheckin::where('user_id', auth()->id())->whereDate('data', today())->first(),
            'kpis' => [
                'atrasadas' => Tarefa::whereDate('data_termino', '<', $today)->where('status', '!=', Tarefa::STATUS_CONCLUIDO)->count(),
                'horas_faturaveis' => round(Timesheet::where('billable', true)->whereBetween('inicio', [now()->startOfMonth(), now()->endOfMonth()])->sum('duracao_minutos') / 60, 2),
                'sprints_ativas' => Sprint::where('status', 'ativa')->count(),
                'alocacao' => $teamSize ? round(($activeAssignments / $teamSize) * 100) : 0,
            ],
            'activeSprint' => $activeSprint,
            'burndown' => $burndown,
            'velocity' => $velocity,
            'charts' => auth()->user()->dashboardCharts()->latest()->get()->map(fn (DashboardChart $chart) => [
                'model' => $chart,
                'data' => $this->chartData($chart->metrica),
            ]),
        ]);
    }

    public function chartData(string $metric): array
    {
        return match ($metric) {
            'overdue' => [
                'labels' => ['Atrasadas', 'No prazo'],
                'values' => [
                    Tarefa::whereDate('data_termino', '<', today())->where('status', '!=', Tarefa::STATUS_CONCLUIDO)->count(),
                    Tarefa::where(function ($query) {
                        $query->whereNull('data_termino')->orWhereDate('data_termino', '>=', today());
                    })->where('status', '!=', Tarefa::STATUS_CONCLUIDO)->count(),
                ],
            ],
            'billable_hours' => [
                'labels' => ['Faturáveis', 'Não faturáveis'],
                'values' => [
                    round(Timesheet::where('billable', true)->sum('duracao_minutos') / 60, 2),
                    round(Timesheet::where('billable', false)->sum('duracao_minutos') / 60, 2),
                ],
            ],
            'productivity' => [
                'labels' => ['Concluídas', 'Em andamento'],
                'values' => [
                    Tarefa::where('status', Tarefa::STATUS_CONCLUIDO)->count(),
                    Tarefa::where('status', '!=', Tarefa::STATUS_CONCLUIDO)->count(),
                ],
            ],
            default => [
                'labels' => ['Backlog', 'A fazer', 'Desenvolvimento', 'QA', 'Concluído'],
                'values' => [
                    Tarefa::where('status', Tarefa::STATUS_BACKLOG)->count(),
                    Tarefa::where('status', Tarefa::STATUS_A_FAZER)->count(),
                    Tarefa::where('status', Tarefa::STATUS_DESENVOLVIMENTO)->count(),
                    Tarefa::where('status', Tarefa::STATUS_QA)->count(),
                    Tarefa::where('status', Tarefa::STATUS_CONCLUIDO)->count(),
                ],
            ],
        };
    }

    private function burndown(Sprint $sprint): array
    {
        $total = Tarefa::where('sprint_id', $sprint->id)->count();
        $start = Carbon::parse($sprint->data_inicio);
        $end = Carbon::parse($sprint->data_fim);
        $days = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $completed = Tarefa::where('sprint_id', $sprint->id)
                ->where('status', Tarefa::STATUS_CONCLUIDO)
                ->whereDate('updated_at', '<=', $date)
                ->count();
            $days[] = ['label' => $date->format('d/m'), 'remaining' => max(0, $total - $completed)];
        }

        return $days;
    }
}
