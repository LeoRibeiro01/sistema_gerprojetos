<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo projeto</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        @can('create', App\Models\Projeto::class)
            <form method="POST" action="{{ route('projeto.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                <div>
                    <x-input-label for="titulo" value="Título" />
                    <x-text-input id="titulo" name="titulo" class="block mt-1 w-full" required />
                </div>
                <div>
                    <x-input-label for="descricao" value="Descrição" />
                    <textarea id="descricao" name="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3"></textarea>
                </div>
                <div>
                    <x-input-label for="data_inicio" value="Data início" />
                    <x-text-input id="data_inicio" type="date" name="data_inicio" class="block mt-1 w-full" required />
                </div>
                <div>
                    <x-input-label for="data_termino" value="Data término" />
                    <x-text-input id="data_termino" type="date" name="data_termino" class="block mt-1 w-full" />
                </div>
                <div>
                    <x-input-label for="user_id" value="Responsável" />
                    <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="" disabled selected>Selecione</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button>Salvar</x-primary-button>
            </form>
        @else
            <p class="text-red-600">Você não tem permissão para criar projetos.</p>
        @endcan
    </div>
</x-app-layout>
