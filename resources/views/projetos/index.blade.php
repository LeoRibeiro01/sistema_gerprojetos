<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Projetos</title>
    <style>
        body {
            background-color: #f8f9fa; /* Cor de fundo suave */
        }
        h1 {
            color: #343a40; /* Cor do título */
        }
        .table thead th {
            background-color: #e9ecef; /* Cabeçalho da tabela em cinza claro */
        }
        .table th, .table td {
            vertical-align: middle; /* Centraliza o texto nas células */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Lista de Projetos</h1>

        <!-- Formulário de filtro -->
        <form method="GET" action="{{ route('projeto.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="titulo" class="form-control" placeholder="Filtrar por título" value="{{ request('titulo') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">Filtrar por status</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="atrasado" {{ request('status') == 'atrasado' ? 'selected' : '' }}>Atrasado</option>
                        <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="data_inicio" class="form-control" placeholder="Data de início" value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="data_termino" class="form-control" placeholder="Data de término" value="{{ request('data_termino') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <a href="{{ route('projeto.create') }}" class="btn btn-primary mb-3">Novo Projeto</a>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data Início</th>
                        <th>Data Término</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projeto as $projeto)
                    <tr>
                        <td>{{ $projeto->id }}</td>
                        <td>{{ $projeto->titulo }}</td>
                        <td>{{ $projeto->descricao }}</td>
                        <td>{{ $projeto->data_inicio ? \Carbon\Carbon::parse($projeto->data_inicio)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $projeto->data_termino ? \Carbon\Carbon::parse($projeto->data_termino)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $projeto->user->name ?? 'N/A' }}</td>
                        <td>
                            @if ($projeto->status == 'pendente')
                                <span class="badge bg-warning">Pendente</span>
                            @elseif ($projeto->status == 'atrasada')
                                <span class="badge bg-danger">Atrasado</span>
                            @else
                                <span class="badge bg-success">Concluído</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('projeto.show', $projeto->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('projeto.edit', $projeto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('projeto.destroy', $projeto->id) }}" method="POST" style="display:inline;">
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
