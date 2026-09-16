<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projetos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('projeto.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white p-4 rounded-lg shadow-sm">
                <input type="text" name="titulo" placeholder="Título" value="{{ request('titulo') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
                <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Status</option>
                    @foreach (['pendente', 'atrasado', 'concluido'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
                <input type="date" name="data_termino" value="{{ request('data_termino') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Filtrar</button>
            </form>

            <div class="flex flex-wrap gap-2">
                @can('create', App\Models\Projeto::class)
                    <a href="{{ route('projeto.create') }}" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">Novo projeto</a>
                @endcan
                <form action="{{ route('projetos.report') }}" method="GET" target="_blank" class="inline-flex flex-wrap gap-2">
                    <input type="hidden" name="titulo" value="{{ request('titulo') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="data_inicio" value="{{ request('data_inicio') }}">
                    <input type="hidden" name="data_termino" value="{{ request('data_termino') }}">
                    <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">PDF geral</button>
                </form>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">ID</th>
                            <th class="px-3 py-2 text-left">Título</th>
                            <th class="px-3 py-2 text-left">Responsável</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($projetos as $projeto)
                            <tr>
                                <td class="px-3 py-2">{{ $projeto->id }}</td>
                                <td class="px-3 py-2">{{ $projeto->titulo }}</td>
                                <td class="px-3 py-2">{{ $projeto->user->name ?? 'N/A' }}</td>
                                <td class="px-3 py-2 capitalize">{{ $projeto->status }}</td>
                                <td class="px-3 py-2 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('projeto.show', $projeto) }}" class="text-indigo-600 hover:underline">Ver</a>
                                    @can('update', $projeto)
                                        <a href="{{ route('projeto.edit', $projeto) }}" class="text-amber-600 hover:underline">Editar</a>
                                    @endcan
                                    <a href="{{ route('projeto.singleReport', $projeto) }}" target="_blank" class="text-red-600 hover:underline">PDF</a>
                                    @can('delete', $projeto)
                                        <form action="{{ route('projeto.destroy', $projeto) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:underline" onclick="return confirm('Excluir projeto?')">Excluir</button>
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
