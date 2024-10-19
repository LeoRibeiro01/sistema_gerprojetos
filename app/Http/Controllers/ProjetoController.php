<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;
use App\Models\User; // Atualizando para importar o modelo User

class ProjetoController extends Controller
{
    // Exibe uma lista de todos os projetos
    public function index()
    {
        $projetos = Projeto::all(); // Lista todos os projetos
        return view('projetos.index', compact('projetos'));
    }

    // Mostra o formulário para criar um novo projeto
    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get(); // Busca usuários não administradores ordenados
        return view('projetos.create', compact('users')); // Passa 'users' para a view
    }

    // Armazena um novo projeto no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'user_id' => 'required|exists:users,id', // Atualizando para verificar no modelo User
        ]);

        $projeto = new Projeto();
        $projeto->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $projeto->descricao = $request->descricao;
        $projeto->data_inicio = $request->data_inicio;
        $projeto->data_termino = $request->data_termino;
        $projeto->user_id = $request->user_id; // Isso deve ser um 'user_id' agora, se for o caso
        $projeto->save();

        return redirect()->route('projeto.index');
    }

    // Exibe os detalhes de um projeto específico
    public function show(Projeto $projeto)
    {
        return view('projetos.show', compact('projeto'));
    }

    // Mostra o formulário para editar um projeto existente
    public function edit(Projeto $projeto)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get(); // Busca usuários não administradores
        return view('projetos.edit', compact('projeto', 'users')); // Passa 'users' para a view
    }

    // Atualiza um projeto existente no banco de dados
    public function update(Request $request, Projeto $projeto)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'user_id' => 'required|exists:users,id', // Atualizando para verificar no modelo User
        ]);

        $projeto->update([
            'titulo' => mb_strtoupper($request->titulo, 'UTF-8'),
            'descricao' => $request->descricao,
            'data_inicio' => $request->data_inicio,
            'data_termino' => $request->data_termino,
            'user_id' => $request->user_id, // Isso deve ser um 'user_id' agora, se for o caso
        ]);

        return redirect()->route('projeto.index');
    }

    // Remove um projeto do banco de dados
    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return redirect()->route('projeto.index');
    }
}
