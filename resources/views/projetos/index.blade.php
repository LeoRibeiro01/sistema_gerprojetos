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
        <a href="{{ route('projeto.create') }}" class="btn btn-primary mb-3">Novo Projeto</a>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Cliente</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projetos as $projeto)
                    <tr>
                        <td>{{ $projeto->id }}</td>
                        <td>{{ $projeto->titulo }}</td>
                        <td>{{ $projeto->descricao }}</td>


                        <td>{{ $projeto->user->name ?? 'N/A' }}</td>
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
