<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarefa;
use App\Models\Projeto;
use Dompdf\Dompdf; // Importa a classe Dompdf
use Dompdf\Options; // Importa a classe Options

class TarefaController extends Controller
{
    // Exibe uma lista de todas as tarefas com filtros aplicados
    public function index(Request $request)
    {
        $tarefas = Tarefa::query();

        // Filtro por título
        if ($request->has('titulo') && $request->titulo != '') {
            $tarefas->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        // Filtro por status
        if ($request->has('status') && $request->status != '') {
            $tarefas->where('status', $request->status);
        }

        // Filtro por data (data de início)
        if ($request->has('data_inicio') && $request->data_inicio != '') {
            $tarefas->whereDate('data_inicio', '=', $request->data_inicio);
        }

        // Filtro por data (data de término)
        if ($request->has('data_termino') && $request->data_termino != '') {
            $tarefas->whereDate('data_termino', '=', $request->data_termino);
        }

        // Ordenar por ID ou outro critério (opcional)
        $tarefas = $tarefas->get();

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

    // Conclui uma tarefa (muda o status para "concluída")
    public function concluir($id)
    {
        $tarefa = Tarefa::findOrFail($id);

        // Alterna o status entre "concluída" e "pendente"
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

    public function report()
{
    // Obtém todas as tarefas com as relações necessárias
    $tarefas = Tarefa::with(['projeto', 'responsavel'])->get(); // Ajuste as relações conforme seu modelo

    // Configura o Dompdf
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    // Gera a view para o PDF
    $pdfView = view('tarefa.report', compact('tarefas')); // A view deve ser criada para o relatório

    // Carrega a view no Dompdf
    $dompdf->loadHtml($pdfView);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Exibe o PDF no navegador
    return $dompdf->stream('relatorio_tarefas.pdf', ["Attachment" => false]);
}

public function singleReport($id)
{
    // Obtém a tarefa específica com as relações necessárias
    $tarefa = Tarefa::with(['projeto', 'responsavel'])->findOrFail($id); // Ajuste as relações conforme seu modelo

    // Configura o Dompdf
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    // Gera a view para o PDF
    $pdfView = view('tarefa.singleReport', compact('tarefa')); // A view deve ser criada para o relatório específico

    // Carrega a view no Dompdf
    $dompdf->loadHtml($pdfView);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Exibe o PDF no navegador
    return $dompdf->stream('relatorio_tarefa_'.$tarefa->id.'.pdf', ["Attachment" => false]);
}
}
