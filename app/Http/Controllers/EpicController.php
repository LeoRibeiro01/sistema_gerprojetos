<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Projeto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EpicController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Epic::class);

        return view('epics.index', ['epics' => Epic::with('projeto')->latest()->get()]);
    }

    public function create(): View
    {
        $this->authorize('create', Epic::class);

        return view('epics.create', ['projetos' => Projeto::orderBy('titulo')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Epic::class);
        Epic::create($this->validated($request));

        return redirect()->route('epics.index')->with('success', 'Epic criada com sucesso.');
    }

    public function show(Epic $epic): View
    {
        $this->authorize('view', $epic);
        $epic->load('projeto', 'userStories');

        return view('epics.show', compact('epic'));
    }

    public function edit(Epic $epic): View
    {
        $this->authorize('update', $epic);

        return view('epics.edit', ['epic' => $epic, 'projetos' => Projeto::orderBy('titulo')->get()]);
    }

    public function update(Request $request, Epic $epic): RedirectResponse
    {
        $this->authorize('update', $epic);
        $epic->update($this->validated($request));

        return redirect()->route('epics.index')->with('success', 'Epic atualizada com sucesso.');
    }

    public function destroy(Epic $epic): RedirectResponse
    {
        $this->authorize('delete', $epic);
        $epic->delete();

        return redirect()->route('epics.index')->with('success', 'Epic removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'status' => ['required', 'in:aberta,em_andamento,concluida'],
        ]);
    }
}
