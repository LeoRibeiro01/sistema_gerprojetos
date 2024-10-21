<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarefa;
use App\Models\Projeto;

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
        $projetos = Projeto::all(); 
        return view('tarefas.create', compact('projetos'));
    }

    // Armazena uma nova tarefa no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'projeto_id' => 'required|exists:projetos,id',
        ]);

        $tarefa = new Tarefa();
        $tarefa->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $tarefa->descricao = $request->descricao;
        $tarefa->data_inicio = $request->data_inicio;
        $tarefa->data_termino = $request->data_termino;
        $tarefa->projeto_id = $request->projeto_id; 
        $tarefa->user_id = auth()->id();
        $tarefa->status = 'pendente'; // Define o status inicial como "pendente"
        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    // Exibe os detalhes de uma tarefa específica
    public function show(Tarefa $tarefa)
    {
        return view('tarefas.show', compact('tarefa'));
    }

    // Mostra o formulário para editar uma tarefa existente
    public function edit(Tarefa $tarefa)
    {
        $projetos = Projeto::all(); 
        return view('tarefas.edit', compact('tarefa', 'projetos'));
    }

    // Atualiza uma tarefa existente no banco de dados
    public function update(Request $request, Tarefa $tarefa)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'projeto_id' => 'required|exists:projetos,id',
            'status' => 'required|string|in:pendente,atrasada,concluida', // Validação do campo status
        ]);

        $tarefa->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $tarefa->descricao = $request->descricao;
        $tarefa->data_inicio = $request->data_inicio;
        $tarefa->data_termino = $request->data_termino;
        $tarefa->projeto_id = $request->projeto_id;
        $tarefa->status = $request->status; // Atualiza o status com o valor enviado no formulário

        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function concluir($id)
    {
        $tarefa = Tarefa::findOrFail($id);

        // Atualiza o status para 'concluída'
        $tarefa->status = $tarefa->status == 'concluida' ? 'pendente' : 'concluida';
        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso.');
    }

    // Remove uma tarefa do banco de dados
    public function destroy($id)
    {
        $tarefa = Tarefa::findOrFail($id);

        if (!auth()->user()->is_admin) {
            return redirect()->route('tarefas.index')->with('error', 'Você não tem permissão para excluir esta tarefa.');
        }

        $tarefa->delete();
        return redirect()->route('tarefas.index')->with('success', 'Tarefa excluída com sucesso.');
    }
}
