<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detalhes da Tarefa</title>
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
        h1 {
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Detalhes da Tarefa</h1>
        <dl class="row">
            <dt class="col-sm-3">Título:</dt>
            <dd class="col-sm-9">{{ $tarefa->titulo }}</dd>

            <dt class="col-sm-3">Descrição:</dt>
            <dd class="col-sm-9">{{ $tarefa->descricao ?? 'Sem descrição' }}</dd>

            <dt class="col-sm-3">Data de Início:</dt>
            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($tarefa->data_inicio)->format('d/m/Y') }}</dd>

            <dt class="col-sm-3">Data de Término:</dt>
            <dd class="col-sm-9">{{ $tarefa->data_termino ? \Carbon\Carbon::parse($tarefa->data_termino)->format('d/m/Y') : 'N/A' }}</dd>

            <dt class="col-sm-3">Projeto:</dt>
            <dd class="col-sm-9">{{ $tarefa->projeto->titulo ?? 'N/A' }}</dd>
        </dl>
        <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
