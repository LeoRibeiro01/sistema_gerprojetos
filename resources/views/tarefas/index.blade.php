<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Tarefas</title>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: #2c3e50;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn {
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #007bff;
            color: white;
        }

        .badge {
            font-size: 1.1em;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }

        .table thead {
            background-color: #e9ecef;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 8px;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .checkbox-label {
            margin-left: 10px;
        }

        /* Estilo da Navbar */
        .navbar {
            background-color: #001f3f; /* Azul marinho */
            padding: 0.3% 2rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            width: 107px;
            height: 60px;
            margin-right: 20px;
        }

        .navbar .nav-link {
            color: white;
        }

        .navbar .nav-link:hover {
            color: #f8f9fa;
        }

        /* Botão de voltar */
        .btn-back {
            background-color: #001f3f; /* Mesma cor da navbar */
            padding: 30px;
            border: none;
        }

        .btn-back img {
            width: 30px;
            height: 30px;
        }

        .btn-back:hover {
            background-color: #004085; /* Azul mais escuro */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Imagem de voltar dentro da marca da navbar -->
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="https://infotech-solucoes.com/novo/public/img/logo_infotech.png" alt="Voltar para a home">
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Lista de Tarefas</h1>

        <!-- Formulário de filtro -->
        <form method="GET" action="{{ route('tarefas.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="titulo" class="form-control" placeholder="Filtrar por título" value="{{ request('titulo') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">Filtrar por status</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="atrasada" {{ request('status') == 'atrasada' ? 'selected' : '' }}>Atrasada</option>
                        <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="data_inicio" class="form-control" placeholder="Data de início" value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="data_termino" class="form-control" placeholder="Data de término" value="{{ request('data_termino') }}">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Botão para criar nova tarefa -->
        <a href="{{ route('tarefas.create') }}" class="btn btn-success mb-3">Nova Tarefa</a>

        <!-- Tabela de Tarefas -->
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
                        <td>
                            <form action="{{ route('tarefas.concluir', $tarefa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $tarefa->status == 'concluida' ? 'checked' : '' }}>
                                <label class="checkbox-label"></label>
                            </form>
                        </td>
                        <td>{{ $tarefa->id }}</td>
                        <td>{{ $tarefa->titulo }}</td>
                        <td>{{ $tarefa->descricao }}</td>
                        <td>{{ $tarefa->data_inicio ? \Carbon\Carbon::parse($tarefa->data_inicio)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $tarefa->data_termino ? \Carbon\Carbon::parse($tarefa->data_termino)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $tarefa->projeto->titulo ?? 'N/A' }}</td>
                        <td>
                            @if ($tarefa->status == 'pendente')
                                <span class="badge bg-warning">Pendente</span>
                            @elseif ($tarefa->status == 'atrasada')
                                <span class="badge bg-danger">Atrasada</span>
                            @else
                                <span class="badge bg-success">Concluída</span>
                            @endif
                        </td>
                        <td class="actions-btns">
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
