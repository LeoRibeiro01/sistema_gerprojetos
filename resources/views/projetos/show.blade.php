<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detalhes do Projeto</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Detalhes do Projeto</h1>
        <dl class="row">
            <dt class="col-sm-3">Título</dt>
            <dd class="col-sm-9">{{ $projeto->titulo }}</dd>
            <dt class="col-sm-3">Descrição</dt>
            <dd class="col-sm-9">{{ $projeto->descricao }}</dd>
            <dt class="col-sm-3">Data Início</dt>
            <dd class="col-sm-9">{{ $projeto->data_inicio }}</dd>
            <dt class="col-sm-3">Data Término</dt>
            <dd class="col-sm-9">{{ $projeto->data_termino }}</dd>
            <dt class="col-sm-3">Cliente</dt>
            <dd class="col-sm-9">{{ $projeto->user->name}}</dd>
        </dl>
        <a href="{{ route('projeto.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
