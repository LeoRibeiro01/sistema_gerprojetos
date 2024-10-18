<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Projetos</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Projetos</h1>
        <a href="{{ route('projeto.create') }}" class="btn btn-primary mb-3">Novo Projeto</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Data Início</th>
                    <th>Data Término</th>
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
                    <td>{{ $projeto->data_inicio }}</td>
                    <td>{{ $projeto->data_termino }}</td>
                    <td>{{ $projeto->cliente->nome ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('projeto.show', $projeto->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('projeto.edit', $projeto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('projetos.destroy', $projeto->id) }}" method="POST" style="display:inline;">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
