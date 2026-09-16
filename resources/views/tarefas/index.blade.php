<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tarefas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('tarefas.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 bg-white p-4 rounded-lg shadow-sm">
                <input type="text" name="titulo" placeholder="Título" value="{{ request('titulo') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
                <select name="projeto_id" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Projeto</option>
                    @foreach ($projetos as $projeto)
                        <option value="{{ $projeto->id }}" @selected(request('projeto_id') == $projeto->id)>{{ $projeto->titulo }}</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Status</option>
                    @foreach (App\Models\Tarefa::statuses() as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
                <input type="date" name="data_termino" value="{{ request('data_termino') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Filtrar</button>
            </form>

            @can('create', App\Models\Tarefa::class)
                <a href="{{ route('tarefas.create') }}" class="inline-flex rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">Nova tarefa</a>
            @endcan

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2">OK</th>
                            <th class="px-3 py-2 text-left">Título</th>
                            <th class="px-3 py-2 text-left">Projeto</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($tarefas as $tarefa)
                            <tr>
                                <td class="px-3 py-2">
                                    @can('update', $tarefa)
                                        <form action="{{ route('tarefas.concluir', $tarefa) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="checkbox" onchange="this.form.submit()" @checked($tarefa->status === App\Models\Tarefa::STATUS_CONCLUIDO)>
                                        </form>
                                    @endcan
                                </td>
                                <td class="px-3 py-2">{{ $tarefa->titulo }}</td>
                                <td class="px-3 py-2">{{ $tarefa->projeto->titulo ?? 'N/A' }}</td>
                                <td class="px-3 py-2 capitalize">{{ $tarefa->status }}</td>
                                <td class="px-3 py-2 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('tarefas.show', $tarefa) }}" class="text-indigo-600 hover:underline">Ver</a>
                                    @can('update', $tarefa)
                                        <a href="{{ route('tarefas.edit', $tarefa) }}" class="text-amber-600 hover:underline">Editar</a>
                                    @endcan
                                    @can('delete', $tarefa)
                                        <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Excluir tarefa?')">Excluir</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
