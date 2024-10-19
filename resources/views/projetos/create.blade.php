<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Criar Projeto</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Criar Projeto</h1>

        @if (auth()->check() && auth()->user()->isAdmin()) {{-- Verifica se o usuário está autenticado e é admin --}}
            <form action="{{ route('projeto.store') }}" method="POST"> {{-- Corrigido para a rota de armazenamento --}}
                @csrf
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao"></textarea>
                </div>
                <div class="mb-3">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                </div>
                <div class="mb-3">
                    <label for="data_termino" class="form-label">Data Término</label>
                    <input type="date" class="form-control" id="data_termino" name="data_termino">
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label">Selecione um Cliente</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="" disabled selected>Selecione um Cliente</option>
                        @foreach ($users as $user) {{-- Alterado para usar a variável 'users' --}}
                            <option value="{{ $user->id }}">{{ $user->name }}</option> {{-- Altere 'name' se necessário --}}
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        @else
            <div class="alert alert-danger" role="alert">
                Você não tem permissão para criar um projeto.
            </div>
            <a href="{{ route('home') }}" class="btn btn-secondary">Voltar à Página Principal</a>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
