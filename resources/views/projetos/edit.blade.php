<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Projeto</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Editar Projeto</h1>
        @if (auth()->check() && auth()->user()->isAdmin())
        <form action="{{ route('projeto.update', $projeto->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $projeto->titulo }}" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao">{{ $projeto->descricao }}</textarea>
            </div>
            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data Início</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ $projeto->data_inicio }}" required>
            </div>
            <div class="mb-3">
                <label for="data_termino" class="form-label">Data Término</label>
                <input type="date" class="form-control" id="data_termino" name="data_termino" value="{{ $projeto->data_termino }}">
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">Cliente</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $projeto->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status do projeto -->
            <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="pendente" {{ $projeto->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="atrasado" {{ $projeto->status == 'atrasado' ? 'selected' : '' }}>Atrasado</option>
                        <option value="concluido" {{ $projeto->status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    </select>
                </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
        @else
        <div class="alert alert-danger" role="alert">
            Você não tem permissão para editar um projeto.
        </div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Voltar à Página Principal</a>
    @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
