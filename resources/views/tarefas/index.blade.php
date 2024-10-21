<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Tarefas</title>
    <style>
        body {
            background-color: #f8f9fa; /* Fundo suave */
        }
        h1 {
            color: #343a40; /* Cor do título */
        }
        .table th, .table td {
            vertical-align: middle; /* Centraliza o texto nas células */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Lista de Tarefas</h1>
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary mb-3">Nova Tarefa</a>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Concluir</th>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data Início</th>
                        <th>Data Término</th>
                        <th>Projeto</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tarefas as $tarefa)
                    <tr>
                        <!-- Checkbox para concluir tarefa -->
                        <td>
                            <form action="{{ route('tarefas.concluir', $tarefa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $tarefa->status == 'concluida' ? 'checked' : '' }}>
                            </form>
                        </td>
                        <td>{{ $tarefa->id }}</td>
                        <td>{{ $tarefa->titulo }}</td>
                        <td>{{ $tarefa->descricao }}</td>
                        <td>{{ $tarefa->data_inicio ? \Carbon\Carbon::parse($tarefa->data_inicio)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $tarefa->data_termino ? \Carbon\Carbon::parse($tarefa->data_termino)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $tarefa->projeto->titulo ?? 'N/A' }}</td>
                        <!-- Exibe o status da tarefa -->
                        <td>
                            @if ($tarefa->status == 'pendente')
                                <span class="badge bg-warning">Pendente</span>
                            @elseif ($tarefa->status == 'atrasada')
                                <span class="badge bg-danger">Atrasada</span>
                            @else
                                <span class="badge bg-success">Concluída</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tarefas.show', $tarefa->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
