<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarefa;
use App\Models\Project;

class TarefaController extends Controller
{
    // Exibe uma lista de todas as tarefas
    public function index()
    {
        $tarefas = Tarefa::all();
        return view('tarefas.index', compact('tarefas'));
    }

    // Mostra o formulário para criar uma nova tarefa
    public function create()
    {
        $project = Projects::all();
        return view('tarefas.create', compact('projects'));
    }

    // Armazena uma nova tarefa no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
        ]);

        $tarefa = new Tarefa();
        $tarefa->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $tarefa->descricao = $request->descricao;
        $tarefa->data_inicio = $request->data_inicio;
        $tarefa->data_termino = $request->data_termino;
        $tarefa->project_id = $request->project_id;
        $tarefa->save();

        return redirect()->route('tarefas.index');
    }

    // Exibe os detalhes de uma tarefa específica
    public function show(Tarefa $tarefa)
    {
        return view('tarefas.show', compact('tarefa'));
    }

    // Mostra o formulário para editar uma tarefa existente
    public function edit(Tarefa $tarefa)
    {
        $projects = Project::all();
        return view('tarefas.edit', compact('tarefa', 'projects'));
    }

    // Atualiza uma tarefa existente no banco de dados
    public function update(Request $request, Tarefa $tarefa)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
        ]);

        $tarefa->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $tarefa->descricao = $request->descricao;
        $tarefa->data_inicio = $request->data_inicio;
        $tarefa->data_termino = $request->data_termino;
        $tarefa->project_id = $request->project_id;
        $tarefa->save();

        return redirect()->route('tarefas.index');
    }

    // Remove uma tarefa do banco de dados
    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();
        return redirect()->route('tarefas.index');
    }
}
