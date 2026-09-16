<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Projeto::class);

        return view('reports.index');
    }

    public function show(int $id): View
    {
        $projeto = Projeto::with(['tarefas', 'user'])->findOrFail($id);

        $this->authorize('view', $projeto);

        return view('reports.show', compact('projeto'));
    }
}
