<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;
use App\Models\Cliente;

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
        $clientes = Cliente::orderBy('nome')->get(); // Busca clientes ordenados
        return view('projetos.create', compact('clientes'));
    }

    // Armazena um novo projeto no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'cliente_id' => 'required|exists:clientes,id',
        ]);

        $projeto = new Projeto();
        $projeto->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $projeto->descricao = $request->descricao;
        $projeto->data_inicio = $request->data_inicio;
        $projeto->data_termino = $request->data_termino;
        $projeto->cliente_id = $request->cliente_id;
        $projeto->save();

        return redirect()->route('projetos.index');
    }

    // Exibe os detalhes de um projeto específico
    public function show(Projeto $projeto)
    {
        return view('projetos.show', compact('projeto'));
    }

    // Mostra o formulário para editar um projeto existente
    public function edit(Projeto $projeto)
    {
        $clientes = Cliente::all();
        return view('projetos.edit', compact('projeto', 'clientes'));
    }

    // Atualiza um projeto existente no banco de dados
    public function update(Request $request, Projeto $projeto)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'cliente_id' => 'required|exists:clientes,id',
        ]);

        $projeto->update([
            'titulo' => mb_strtoupper($request->titulo, 'UTF-8'),
            'descricao' => $request->descricao,
            'data_inicio' => $request->data_inicio,
            'data_termino' => $request->data_termino,
            'cliente_id' => $request->cliente_id,
        ]);

        return redirect()->route('projetos.index');
    }

    // Remove um projeto do banco de dados
    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return redirect()->route('projetos.index');
    }
}
