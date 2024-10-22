<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;
use App\Models\User; // Importando o modelo User
use Dompdf\Dompdf; // Importa a classe Dompdf
use Dompdf\Options; // Importa a classe Options

class ProjetoController extends Controller
{
    // Exibe uma lista de todos os projetos
    public function index(Request $request)
    {
        $projeto = Projeto::query();

        // Filtro por título
        if ($request->has('titulo') && $request->titulo != '') {
            $projeto->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        // Filtro por status
        if ($request->has('status') && $request->status != '') {
            $projeto->where('status', $request->status);
        }

        // Filtro por data (data de início)
        if ($request->has('data_inicio') && $request->data_inicio != '') {
            $projeto->whereDate('data_inicio', '=', $request->data_inicio);
        }

        // Filtro por data (data de término)
        if ($request->has('data_termino') && $request->data_termino != '') {
            $projeto->whereDate('data_termino', '=', $request->data_termino);
        }

        // Ordenar por ID ou outro critério (opcional)
        $projeto = $projeto->get();

        return view('projetos.index', compact('projeto'));
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
            'user_id' => 'required|exists:users,id', // Verificando se o user_id existe na tabela users
        ]);

        $projeto = new Projeto();
        $projeto->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $projeto->descricao = $request->descricao;
        $projeto->data_inicio = $request->data_inicio;
        $projeto->data_termino = $request->data_termino;
        $projeto->user_id = $request->user_id; // Atribuindo o user_id ao projeto
        // O status será atribuído automaticamente pelo valor default na migração
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
            'user_id' => 'required|exists:users,id', // Verificando se o user_id existe na tabela users
            'status' => 'required|string|in:pendente,atrasado,concluido', // O campo status é obrigatório
        ]);

        $projeto->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $projeto->descricao = $request->descricao;
        $projeto->data_inicio = $request->data_inicio;
        $projeto->data_termino = $request->data_termino;
        $projeto->user_id = $request->user_id;
        $projeto->status = $request->status; // Atribui o status diretamente
        $projeto->save();

        return redirect()->route('projeto.index');
    }

    // Conclui um projeto (muda o status para "concluído")
    public function concluir($id)
    {
        $projeto = Projeto::findOrFail($id);

        // Alterna o status entre "concluída" e "pendente"
        $projeto->status = $projeto->status == 'concluido' ? 'pendente' : 'concluido';
        $projeto->save();

        return redirect()->route('projeto.index')->with('success', 'Projeto atualizado com sucesso.');
    }

    // Remove um projeto do banco de dados
    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return redirect()->route('projeto.index');
    }

    public function report()
    {
        // Obtém todos os projetos e suas tarefas e usuários
        $projetos = Projeto::with(['tarefas', 'user'])->get();
        $tarefas = Tarefa::with(['projeto', 'responsavel'])->get(); // Supondo que 'responsavel' seja a relação correta
    
        // Configura o Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
    
        // Gera a view para o PDF
        $pdfView = view('projetos.report', compact('projetos', 'tarefas')); // Passando 'projetos' e 'tarefas'
    
        // Carrega a view no Dompdf
        $dompdf->loadHtml($pdfView);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Exibe o PDF no navegador
        return $dompdf->stream('relatorio_projetos.pdf', ["Attachment" => false]);
    }
    
    

    public function singleReport($id)
    {
        // Obtém o projeto específico com as relações necessárias
        $projeto = Projeto::with(['tarefas', 'user'])->findOrFail($id); // Ajuste as relações conforme seu modelo

        // Configura o Dompdf
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        //$options->set('isRemoteEnabled', true); // Habilita imagens externas
        $dompdf = new \Dompdf\Dompdf($options);

        // Gera a view para o PDF
        $pdfView = view('projetos.singleReport', compact('projeto')); // A view deve ser criada para o relatório específico

        // Carrega a view no Dompdf
        $dompdf->loadHtml($pdfView);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Exibe o PDF no navegador
        return $dompdf->stream('relatorio_projeto_'.$projeto->id.'.pdf', ["Attachment" => false]);
    }
}
