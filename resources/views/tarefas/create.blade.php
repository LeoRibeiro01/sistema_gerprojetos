<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Criar Tarefa</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Criar Nova Tarefa</h1>

        <!-- Verifica se o usuário tem permissão para criar tarefas -->
        @if (!auth()->check() || !auth()->user()->isAdmin())
            <div class="alert alert-danger">
                <strong>Acesso negado!</strong> Você não tem permissão para criar tarefas.
                <a href="{{ route('home') }}" class="btn btn-secondary mt-2">Voltar à Página Principal</a>
            </div>
        @else
            <!-- Exibir mensagens de erro de validação -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tarefas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                    @error('titulo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="data_inicio" class="form-label">Data de Início</label>
                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" required>
                    @error('data_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="data_termino" class="form-label">Data de Término</label>
                    <input type="date" class="form-control @error('data_termino') is-invalid @enderror" id="data_termino" name="data_termino" value="{{ old('data_termino') }}">
                    @error('data_termino')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="projeto_id" class="form-label">Projeto</label>
                    <select class="form-select @error('projeto_id') is-invalid @enderror" id="projeto_id" name="projeto_id" required>
                        <option value="">Selecione o Projeto</option>
                        @foreach ($projetos as $projeto)
                            <option value="{{ $projeto->id }}" {{ old('projeto_id') == $projeto->id ? 'selected' : '' }}>{{ $projeto->titulo }}</option>
                        @endforeach
                    </select>
                    @error('projeto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
