<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detalhes do Projeto</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        h1, h3 {
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Detalhes do Projeto</h1>
        <dl class="row">
            <dt class="col-sm-3">Título:</dt>
            <dd class="col-sm-9">{{ $projeto->titulo }}</dd>

            <dt class="col-sm-3">Descrição:</dt>
            <dd class="col-sm-9">{{ $projeto->descricao }}</dd>

            <dt class="col-sm-3">Cliente:</dt>
            <dd class="col-sm-9">{{ $projeto->user->name }}</dd>
        </dl>

        <h3 class="mt-4">Tarefas Vinculadas</h3>
        @if($projeto->tarefas->isEmpty())
            <p class="text-muted">Não há tarefas vinculadas a este projeto.</p>
        @else
            <ul class="list-group">
                @foreach ($projeto->tarefas as $tarefa)
                    <li class="list-group-item">
                        <strong>{{ $tarefa->titulo }}</strong> - {{ $tarefa->descricao ?? 'Sem descrição' }}
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('projeto.index') }}" class="btn btn-secondary mt-3">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
