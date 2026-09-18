<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Sprint;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\Comment;
use App\Events\TaskMovedToQa;
use Dompdf\Dompdf; // Importa a classe Dompdf
use Dompdf\Options; // Importa a classe Options

class TarefaController extends Controller
{
    // Exibe uma lista de todas as tarefas com filtros aplicados
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tarefa::class);

        $tarefas = Tarefa::with('projeto')->when($request->filled('projeto_id'), fn ($query) => $query->where('projeto_id', $request->integer('projeto_id')));

        if ($request->has('titulo') && $request->titulo != '') {
            $tarefas->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $tarefas->where('status', $request->status);
        }

        if ($request->has('data_inicio') && $request->data_inicio != '') {
            $tarefas->whereDate('data_inicio', '=', $request->data_inicio);
        }

        if ($request->has('data_termino') && $request->data_termino != '') {
            $tarefas->whereDate('data_termino', '=', $request->data_termino);
        }

        $tarefas = $tarefas->orderByDesc('updated_at')->get();
        $projetos = Projeto::orderBy('titulo')->get(['id', 'titulo']);

        return view('tarefas.index', compact('tarefas', 'projetos'));
    }

    public function kanban(Request $request)
    {
        $this->authorize('viewAny', Tarefa::class);

        $selectedProjetoId = $request->integer('projeto_id');

        $tarefas = Tarefa::with(['projeto', 'user', 'sprint'])
            ->when($selectedProjetoId, fn ($query) => $query->where('projeto_id', $selectedProjetoId))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('sprint_id'), fn ($query) => $query->where('sprint_id', $request->integer('sprint_id')))
            ->when($request->filled('prioridade'), fn ($query) => $query->where('prioridade', $request->string('prioridade')))
            ->when($request->filled('tag'), fn ($query) => $query->where('tag', $request->string('tag')))
            ->latest()
            ->get()
            ->map(fn (Tarefa $tarefa) => [
                'id' => $tarefa->id,
                'titulo' => $tarefa->titulo,
                'descricao' => $tarefa->descricao,
                'status' => $tarefa->status,
                'tipo' => $tarefa->tipo,
                'prioridade' => $tarefa->prioridade,
                'tag' => $tarefa->tag,
                'user_id' => $tarefa->user_id,
                'sprint_id' => $tarefa->sprint_id,
                'projeto_id' => $tarefa->projeto_id,
                'projeto' => $tarefa->projeto?->titulo,
                'responsavel' => $tarefa->user?->name,
                'sprint' => $tarefa->sprint?->nome,
            ])
            ->values();

        return view('kanban.index', [
            'tarefas' => $tarefas,
            'users' => User::whereIn('role', ['admin', 'pm', 'manager', 'scrum_master', 'dev'])->orderBy('name')->get(['id', 'name']),
            'sprints' => Sprint::orderByDesc('data_inicio')->get(['id', 'nome']),
            'projetos' => Projeto::orderBy('titulo')->get(['id', 'titulo']),
            'selectedProjetoId' => $selectedProjetoId,
            'columns' => self::kanbanColumns(),
            'prioridades' => Tarefa::PRIORIDADES,
        ]);
    }

    public function updateStatus(Request $request, Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Tarefa::statuses())],
        ]);

        $previousStatus = $tarefa->status;
        $tarefa->update(['status' => $validated['status']]);

        if ($previousStatus !== Tarefa::STATUS_QA && $tarefa->status === Tarefa::STATUS_QA) {
            TaskMovedToQa::dispatch($tarefa);
        }

        return response()->json([
            'message' => 'Status atualizado com sucesso.',
            'status' => $tarefa->status,
        ]);
    }

    private static function kanbanColumns(): array
    {
        return [
            ['status' => Tarefa::STATUS_BACKLOG, 'label' => 'Backlog'],
            ['status' => Tarefa::STATUS_A_FAZER, 'label' => 'A Fazer'],
            ['status' => Tarefa::STATUS_DESENVOLVIMENTO, 'label' => 'Em Desenvolvimento'],
            ['status' => Tarefa::STATUS_CODE_REVIEW, 'label' => 'Em Code Review'],
            ['status' => Tarefa::STATUS_QA, 'label' => 'Em Teste (QA)'],
            ['status' => Tarefa::STATUS_CONCLUIDO, 'label' => 'Concluído'],
        ];
    }

    // Mostra o formulário para criar uma nova tarefa
    public function create()
    {
        $this->authorize('create', Tarefa::class);

        $projetos = Projeto::all();
        $sprints = Sprint::orderByDesc('data_inicio')->get();

        return view('tarefas.create', compact('projetos', 'sprints'));
    }

    // Armazena uma nova tarefa no banco de dados
    public function store(Request $request)
    {
        $this->authorize('create', Tarefa::class);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'projeto_id' => 'required|exists:projetos,id',
            'tipo' => ['required', Rule::in(Tarefa::TIPOS)],
            'prioridade' => ['required', Rule::in(Tarefa::PRIORIDADES)],
            'estimativa_minutos' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'tag' => 'nullable|string|max:100',
            'sprint_id' => 'nullable|exists:sprints,id',
        ]);

        $tarefa = new Tarefa();
        $tarefa->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $tarefa->descricao = $request->descricao;
        $tarefa->data_inicio = $request->data_inicio;
        $tarefa->data_termino = $request->data_termino;
        $tarefa->projeto_id = $request->projeto_id; 
        $tarefa->user_id = auth()->id();
        $tarefa->tipo = $request->tipo;
        $tarefa->prioridade = $request->prioridade;
        $tarefa->estimativa_minutos = $request->estimativa_minutos;
        $tarefa->tag = $request->tag;
        $tarefa->sprint_id = $request->sprint_id;
        $tarefa->status = Tarefa::STATUS_BACKLOG;
        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    // Exibe os detalhes de uma tarefa específica
    public function show(Tarefa $tarefa)
    {
        $this->authorize('view', $tarefa);
        $tarefa->load(['projeto', 'user', 'updatedBy', 'timesheets.user', 'comments.user', 'dependencias.projeto']);
        $activeTimesheet = $tarefa->timesheets->first(fn (Timesheet $timesheet) => $timesheet->user_id === auth()->id() && $timesheet->fim === null);
        $executedMinutes = (int) $tarefa->timesheets->sum('duracao_minutos');
        $availableDependencies = Tarefa::where('projeto_id', $tarefa->projeto_id)
            ->where('id', '!=', $tarefa->id)
            ->whereNotIn('id', $tarefa->dependencias->modelKeys())
            ->orderBy('titulo')
            ->get(['id', 'titulo']);

        return view('tarefas.show', compact('tarefa', 'activeTimesheet', 'executedMinutes', 'availableDependencies'));
    }

    public function storeComment(Request $request, Tarefa $tarefa)
    {
        $this->authorize('view', $tarefa);
        $validated = $request->validate(['conteudo' => ['required', 'string', 'max:20000']]);

        Comment::create([
            'tarefa_id' => $tarefa->id,
            'user_id' => auth()->id(),
            'conteudo' => $validated['conteudo'],
            'mencoes' => Comment::extractMentions($validated['conteudo']),
        ]);

        return back()->with('success', 'Comentário adicionado.');
    }

    public function destroyComment(Tarefa $tarefa, Comment $comment)
    {
        $this->authorize('view', $tarefa);
        abort_unless((int) $comment->tarefa_id === (int) $tarefa->id, 404);
        abort_unless($comment->user_id === auth()->id() || auth()->user()->isAdmin(), 403);

        $comment->delete();

        return back()->with('success', 'Comentário removido.');
    }

    public function addDependency(Request $request, Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);
        $validated = $request->validate(['depende_de_id' => ['required', 'exists:tarefas,id', 'different:'.$tarefa->id]]);

        $dependency = Tarefa::findOrFail($validated['depende_de_id']);
        abort_unless((int) $dependency->projeto_id === (int) $tarefa->projeto_id, 422, 'A dependência deve pertencer ao mesmo projeto.');
        $tarefa->dependencias()->syncWithoutDetaching([$dependency->id]);

        return back()->with('success', 'Dependência adicionada.');
    }

    public function removeDependency(Tarefa $tarefa, Tarefa $dependency)
    {
        $this->authorize('update', $tarefa);
        $tarefa->dependencias()->detach($dependency->id);

        return back()->with('success', 'Dependência removida.');
    }

    public function startTimer(Request $request, Tarefa $tarefa)
    {
        $this->authorize('create', Timesheet::class);
        $request->validate(['descricao' => ['nullable', 'string', 'max:2000'], 'billable' => ['nullable', 'boolean']]);

        $active = Timesheet::where('user_id', auth()->id())->whereNull('fim')->first();
        if ($active) {
            return response()->json(['message' => 'Finalize o timer atual antes de iniciar outro.'], 422);
        }

        $timesheet = Timesheet::create([
            'tarefa_id' => $tarefa->id,
            'user_id' => auth()->id(),
            'inicio' => now(),
            'descricao' => $request->input('descricao'),
            'billable' => $request->boolean('billable', true),
        ]);

        return response()->json(['id' => $timesheet->id, 'inicio' => $timesheet->inicio->toIso8601String()]);
    }

    public function stopTimer(Request $request, Tarefa $tarefa)
    {
        $this->authorize('create', Timesheet::class);
        $timesheet = Timesheet::where('tarefa_id', $tarefa->id)
            ->where('user_id', auth()->id())
            ->whereNull('fim')
            ->latest('inicio')
            ->firstOrFail();

        $fim = now();
        $timesheet->update([
            'fim' => $fim,
            'duracao_minutos' => max(1, $timesheet->inicio->diffInMinutes($fim)),
            'descricao' => $request->input('descricao', $timesheet->descricao),
        ]);

        return response()->json(['duracao_minutos' => $timesheet->duracao_minutos]);
    }

    // Mostra o formulário para editar uma tarefa existente
    public function edit(Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $projetos = Projeto::all();
        $sprints = Sprint::orderByDesc('data_inicio')->get();

        return view('tarefas.edit', compact('tarefa', 'projetos', 'sprints'));
    }

    // Atualiza uma tarefa existente no banco de dados
    public function update(Request $request, Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_termino' => 'nullable|date',
            'projeto_id' => 'required|exists:projetos,id',
            'tipo' => ['required', Rule::in(Tarefa::TIPOS)],
            'prioridade' => ['required', Rule::in(Tarefa::PRIORIDADES)],
            'estimativa_minutos' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'tag' => 'nullable|string|max:100',
            'sprint_id' => 'nullable|exists:sprints,id',
            'status' => ['required', Rule::in(Tarefa::statuses())],
        ]);

        $tarefa->titulo = mb_strtoupper($request->titulo, 'UTF-8');
        $tarefa->descricao = $request->descricao;
        $tarefa->data_inicio = $request->data_inicio;
        $tarefa->data_termino = $request->data_termino;
        $tarefa->projeto_id = $request->projeto_id;
        $tarefa->tipo = $request->tipo;
        $tarefa->prioridade = $request->prioridade;
        $tarefa->estimativa_minutos = $request->estimativa_minutos;
        $tarefa->tag = $request->tag;
        $tarefa->sprint_id = $request->sprint_id;
        $tarefa->status = $request->status; // Atualiza o status com o valor enviado no formulário

        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    // Conclui uma tarefa (muda o status para "concluída")
    public function concluir($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $this->authorize('update', $tarefa);

        // Alterna o status entre "concluída" e "pendente"
        $tarefa->status = $tarefa->status === Tarefa::STATUS_CONCLUIDO
            ? Tarefa::STATUS_A_FAZER
            : Tarefa::STATUS_CONCLUIDO;
        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso.');
    }

    // Remove uma tarefa do banco de dados
    public function destroy($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $this->authorize('delete', $tarefa);

        $tarefa->delete();
        return redirect()->route('tarefas.index')->with('success', 'Tarefa excluída com sucesso.');
    }

    public function report()
{
    // Obtém todas as tarefas com as relações necessárias
    $tarefas = Tarefa::with(['projeto', 'user'])->get();

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
    $tarefa = Tarefa::with(['projeto', 'user'])->findOrFail($id);

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
