<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SprintController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Sprint::class);

        return view('sprints.index', ['sprints' => Sprint::orderByDesc('data_inicio')->get()]);
    }

    public function create(): View
    {
        $this->authorize('create', Sprint::class);

        return view('sprints.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Sprint::class);
        Sprint::create($this->validated($request));

        return redirect()->route('sprints.index')->with('success', 'Sprint criada com sucesso.');
    }

    public function show(Sprint $sprint): View
    {
        $this->authorize('view', $sprint);
        $sprint->load('tarefas');

        return view('sprints.show', compact('sprint'));
    }

    public function edit(Sprint $sprint): View
    {
        $this->authorize('update', $sprint);

        return view('sprints.edit', compact('sprint'));
    }

    public function update(Request $request, Sprint $sprint): RedirectResponse
    {
        $this->authorize('update', $sprint);
        $sprint->update($this->validated($request));

        return redirect()->route('sprints.index')->with('success', 'Sprint atualizada com sucesso.');
    }

    public function destroy(Sprint $sprint): RedirectResponse
    {
        $this->authorize('delete', $sprint);
        $sprint->delete();

        return redirect()->route('sprints.index')->with('success', 'Sprint removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'meta' => ['nullable', 'string'],
            'status' => ['required', 'in:planejada,ativa,encerrada'],
        ]);
    }
}
