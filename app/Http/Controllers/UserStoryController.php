<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\UserStory;
use App\Models\Sprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserStoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', UserStory::class);

        return view('user_stories.index', ['userStories' => UserStory::with('epic')->latest()->get()]);
    }

    public function create(): View
    {
        $this->authorize('create', UserStory::class);

        return view('user_stories.create', ['epics' => Epic::orderBy('titulo')->get(), 'sprints' => Sprint::orderByDesc('data_inicio')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', UserStory::class);
        UserStory::create($this->validated($request));

        return redirect()->route('user-stories.index')->with('success', 'User story criada com sucesso.');
    }

    public function show(UserStory $userStory): View
    {
        $this->authorize('view', $userStory);
        $userStory->load('epic');

        return view('user_stories.show', compact('userStory'));
    }

    public function edit(UserStory $userStory): View
    {
        $this->authorize('update', $userStory);

        return view('user_stories.edit', ['userStory' => $userStory, 'epics' => Epic::orderBy('titulo')->get(), 'sprints' => Sprint::orderByDesc('data_inicio')->get()]);
    }

    public function update(Request $request, UserStory $userStory): RedirectResponse
    {
        $this->authorize('update', $userStory);
        $userStory->update($this->validated($request));

        return redirect()->route('user-stories.index')->with('success', 'User story atualizada com sucesso.');
    }

    public function destroy(UserStory $userStory): RedirectResponse
    {
        $this->authorize('delete', $userStory);
        $userStory->delete();

        return redirect()->route('user-stories.index')->with('success', 'User story removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'epic_id' => ['nullable', 'exists:epics,id'],
            'sprint_id' => ['nullable', 'exists:sprints,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'pontos' => ['nullable', 'integer', 'min:0', 'max:100'],
            'criterio_aceite' => ['nullable', 'string'],
            'status' => ['required', 'in:backlog,em_andamento,concluida'],
        ]);
    }
}
