<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuários</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <a href="{{ route('users.create') }}" class="inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                Novo usuário
            </a>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Foto</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Nome</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">E-mail</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Papel</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3">{{ $user->id }}</td>
                                <td class="px-4 py-3"><img src="{{ $user->avatarUrl() }}" alt="Foto de {{ $user->name }}" class="h-9 w-9 rounded-full object-cover"></td>
                                <td class="px-4 py-3">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 uppercase text-xs tracking-wide">{{ $user->role }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:underline">Ver</a>
                                    <a href="{{ route('users.edit', $user) }}" class="text-amber-600 hover:underline">Editar</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Excluir usuário?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
