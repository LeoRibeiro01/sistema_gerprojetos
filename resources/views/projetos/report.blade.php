<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral de Projetos e Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Relatório de Projetos e Tarefas</h1>

    <h2>Projetos</h2>
    <table>
        <thead>
            <tr>
                <th>Nome do Projeto</th>
                <th>Cliente</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projetos as $projeto)
                <tr>
                    <td>{{ $projeto->titulo }}</td>
                    <td>{{ $projeto->user->name ?? 'N/A' }}</td>
                    <td>{{ $projeto->descricao }}</td>
                    <td>{{ $projeto->status }}</td>
                    <td>{{ $projeto->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Tarefas</h2>
    <table>
        <thead>
            <tr>
                <th>Nome da Tarefa</th>
                <th>Projeto</th>
                <th>Responsável</th>
                <th>Status</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarefa as $tarefa)
                <tr>
                    <td>{{ $tarefa->nome }}</td>
                    <td>{{ $tarefa->projeto->nome }}</td>
                    <td>{{ $tarefa->responsavel->nome ?? 'N/A' }}</td>
                    <td>{{ $tarefa->status }}</td>
                    <td>{{ $tarefa->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        © {{ date('Y') }} - Relatório de Projetos e Tarefas
    </footer>
</body>
</html>
