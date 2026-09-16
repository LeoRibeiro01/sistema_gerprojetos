<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BugController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Bug::class);

        return view('bugs.index', ['bugs' => Bug::with(['projeto', 'user'])->latest()->get()]);
    }

    public function create(): View
    {
        $this->authorize('create', Bug::class);

        return view('bugs.create', [
            'projetos' => Projeto::orderBy('titulo')->get(),
            'users' => User::whereIn('role', ['admin', 'pm', 'manager', 'scrum_master', 'dev'])->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Bug::class);
        Bug::create($this->validated($request));

        return redirect()->route('bugs.index')->with('success', 'Bug criada com sucesso.');
    }

    public function show(Bug $bug): View
    {
        $this->authorize('view', $bug);
        $bug->load(['projeto', 'user']);

        return view('bugs.show', compact('bug'));
    }

    public function edit(Bug $bug): View
    {
        $this->authorize('update', $bug);

        return view('bugs.edit', [
            'bug' => $bug,
            'projetos' => Projeto::orderBy('titulo')->get(),
            'users' => User::whereIn('role', ['admin', 'pm', 'manager', 'scrum_master', 'dev'])->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Bug $bug): RedirectResponse
    {
        $this->authorize('update', $bug);
        $bug->update($this->validated($request));

        return redirect()->route('bugs.index')->with('success', 'Bug atualizada com sucesso.');
    }

    public function destroy(Bug $bug): RedirectResponse
    {
        $this->authorize('delete', $bug);
        $bug->delete();

        return redirect()->route('bugs.index')->with('success', 'Bug removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'severidade' => ['required', 'in:baixa,media,alta,critica'],
            'status' => ['required', 'in:aberto,em_andamento,resolvido,fechado'],
        ]);
    }
}
