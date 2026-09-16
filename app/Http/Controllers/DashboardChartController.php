<?php

namespace App\Http\Controllers;

use App\Models\DashboardChart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardChartController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isInternalTeam(), 403);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:100'],
            'metrica' => ['required', 'in:task_status,overdue,billable_hours,productivity'],
            'tipo' => ['required', 'in:bar,pie,line'],
            'cor' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $request->user()->dashboardCharts()->create($validated);

        return back()->with('success', 'Gráfico adicionado ao dashboard.');
    }

    public function destroy(Request $request, DashboardChart $chart): RedirectResponse
    {
        abort_unless($request->user()->isInternalTeam(), 403);
        abort_unless($chart->user_id === $request->user()->id, 403);

        $chart->delete();

        return back()->with('success', 'Gráfico removido.');
    }
}
