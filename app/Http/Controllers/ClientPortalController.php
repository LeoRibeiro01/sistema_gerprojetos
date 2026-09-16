<?php

namespace App\Http\Controllers;

use App\Models\ClientApproval;
use App\Models\Projeto;
use App\Models\Sprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasRole(\App\Enums\UserRole::Client), 403);

        $projetos = Projeto::where('cliente_id', auth()->user()->cliente_id)
            ->with(['tarefas' => fn ($query) => $query->select('id', 'projeto_id', 'titulo', 'status')])
            ->orderBy('titulo')
            ->get();
        $sprint = Sprint::where('status', 'ativa')->latest('data_inicio')->first();

        return view('client_portal.index', compact('projetos', 'sprint'));
    }

    public function approve(Request $request, Projeto $projeto): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole(\App\Enums\UserRole::Client), 403);
        abort_unless((int) $projeto->cliente_id === (int) auth()->user()->cliente_id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:aprovado,ajustes_solicitados'],
            'comentario' => ['nullable', 'string', 'max:5000'],
        ]);

        ClientApproval::updateOrCreate(
            ['projeto_id' => $projeto->id, 'user_id' => auth()->id()],
            [
                'status' => $validated['status'],
                'comentario' => $validated['comentario'] ?? null,
                'approved_at' => $validated['status'] === 'aprovado' ? now() : null,
            ],
        );

        return back()->with('success', 'Retorno registrado com sucesso.');
    }
}
