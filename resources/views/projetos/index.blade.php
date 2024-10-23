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
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: #343a40; /* Cor do título */
            font-weight: 600;
            margin-bottom: 30px;
        }

        .table thead th {
            background-color: #e9ecef; /* Cabeçalho da tabela em cinza claro */
            text-align: center;
            vertical-align: middle;
        }

        .table th, .table td {
            vertical-align: middle; /* Centraliza o texto nas células */
            text-align: center;
        }

        .badge-warning {
            background-color: #f0ad4e;
        }

        .badge-danger {
            background-color: #d9534f;
        }

        .badge-success {
            background-color: #5bc0de;
        }

        .btn-custom {
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .form-control, .btn {
            border-radius: 20px;
        }

        .filter-form .row {
            margin-bottom: 15px;
        }

        .filter-form .btn {
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
        }

        .actions-btns a {
            margin-right: 5px;
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
    
    <h1 class="mb-4">Lista de Projetos</h1>
            
        <!-- Formulário de filtro -->
        <form method="GET" action="{{ route('projeto.index') }}" class="filter-form mb-4">
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
                <div class="col-md-3 text-center">
                    <button type="submit" class="btn btn-primary btn-custom">Filtrar</button>
                </div>
            </div>
        </form>

         <!-- Formulário de PDF com base no filtro -->
         <form action="{{ route('projetos.report') }}" method="GET" target="_blank">
            <input type="hidden" name="titulo" value="{{ request('titulo') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="data_inicio" value="{{ request('data_inicio') }}">
            <input type="hidden" name="data_termino" value="{{ request('data_termino') }}">
        </form>

        <!-- Botões para criar novo projeto e visualizar relatório PDF -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('projeto.create') }}" class="btn btn-success btn-custom me-2">Novo Projeto</a>
                <button type="submit" class="btn btn-danger btn-custom">Ver PDF Geral</button>
            </div>
        </div>

        <!-- Tabela de Projetos -->
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
                    @foreach ($projetos as $projeto)
                    <tr>
                        <td>{{ $projeto->id }}</td>
                        <td>{{ $projeto->titulo }}</td>
                        <td>{{ Str::limit($projeto->descricao, 50) }}</td>
                        <td>{{ $projeto->data_inicio ? \Carbon\Carbon::parse($projeto->data_inicio)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $projeto->data_termino ? \Carbon\Carbon::parse($projeto->data_termino)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $projeto->user->name ?? 'N/A' }}</td>
                        <td>
                            @if ($projeto->status == 'pendente')
                                <span class="badge bg-warning">Pendente</span>
                            @elseif ($projeto->status == 'atrasado')
                                <span class="badge bg-danger">Atrasado</span>
                            @else
                                <span class="badge bg-success">Concluído</span>
                            @endif
                        </td>
                        <td class="actions-btns">
                            <a href="{{ route('projeto.singleReport', $projeto->id) }}" class="btn btn-info btn-sm btn-custom">Pdf</a>
                            <a href="{{ route('projeto.edit', $projeto->id) }}" class="btn btn-warning btn-sm btn-custom">Editar</a>
                            <form action="{{ route('projeto.destroy', $projeto->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-custom">Excluir</button>
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
