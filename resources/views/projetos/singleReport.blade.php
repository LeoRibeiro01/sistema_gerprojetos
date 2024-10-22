<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Projeto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            filter: brightness(0) invert(1); /* Transforma a imagem em preto */
        }
        .report-title {
            color: #333;
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .section-title {
            color: #555;
            font-size: 20px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            text-align: left;
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }
        .info-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .task-table {
            width: 100%;
            border-collapse: collapse;
        }
        .task-table th, .task-table td {
            text-align: left;
            padding: 8px 12px;
            border: 1px solid #ddd;
        }
        .task-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .no-tasks {
            color: #777;
            font-style: italic;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho com logotipo da empresa em preto -->
    <!--<div class="header">
        <img src="https://www.infotech-solucoes.com/novo/public/img/logo_infotech.png" alt="">
    </div> -->

    <!-- Título do Relatório -->
    <h1 class="report-title">Relatório do Projeto: {{ $projeto->titulo }}</h1>

    <!-- Informações do Projeto -->
    <table class="info-table">
        <tr>
            <th>Título</th>
            <td>{{ $projeto->titulo }}</td>
        </tr>
        <tr>
            <th>Descrição</th>
            <td>{{ $projeto->descricao }}</td>
        </tr>
        <tr>
            <th>Data de Início</th>
            <td>{{ \Carbon\Carbon::parse($projeto->data_inicio)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Data de Término</th>
            <td>{{ \Carbon\Carbon::parse($projeto->data_termino)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Cliente</th>
            <td>{{ $projeto->user->name ?? 'Não definido' }}</td>
        </tr>
        <tr>
            <th>Responsável</th>
            <td>{{ $projeto->responsavel->nome ?? 'Não definido' }}</td>
        </tr>
    </table>

    <!-- Seção de Tarefas -->
    <h2 class="section-title">Tarefas</h2>
    <table class="task-table">
        <thead>
            <tr>
                <th>Nome da Tarefa</th>
                <th>Status</th>
                <th>Data de Término</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projeto->tarefas as $tarefa)
                <tr>
                    <td>{{ $tarefa->nome }}</td>
                    <td>{{ $tarefa->status }}</td>
                    <td>{{ \Carbon\Carbon::parse($tarefa->data_termino)->format('d/m/Y') ?? 'Não definido' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="no-tasks">Nenhuma tarefa registrada para este projeto.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Rodapé do Relatório -->
    <footer>
        Relatório gerado por InfoTech Soluções - {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </footer>
</body>
</html>
