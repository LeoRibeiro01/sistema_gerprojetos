<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Tarefa</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Editar Tarefa</h1>
        <form action="{{ route('tarefas.update', $tarefa->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $tarefa->titulo }}" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao">{{ $tarefa->descricao }}</textarea>
            </div>
            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data de Início</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ $tarefa->data_inicio }}" required>
            </div>
            <div class="mb-3">
                <label for="data_termino" class="form-label">Data de Término</label>
                <input type="date" class="form-control" id="data_termino" name="data_termino" value="{{ $tarefa->data_termino }}">
            </div>
            <div class="mb-3">
                <label for="projeto_id" class="form-label">Projeto</label>
                <select class="form-select" id="projeto_id" name="projeto_id" required>
                    @foreach ($projetos as $projeto)
                        <option value="{{ $projeto->id }}" {{ $tarefa->projeto_id == $projeto->id ? 'selected' : '' }}>
                            {{ $projeto->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
