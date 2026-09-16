<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar projeto</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('projeto.update', $projeto) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="titulo" value="Título" />
                <x-text-input id="titulo" name="titulo" class="block mt-1 w-full" :value="old('titulo', $projeto->titulo)" required />
            </div>
            <div>
                <x-input-label for="descricao" value="Descrição" />
                <textarea id="descricao" name="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3">{{ old('descricao', $projeto->descricao) }}</textarea>
            </div>
            <div>
                <x-input-label for="data_inicio" value="Data início" />
                <x-text-input id="data_inicio" type="date" name="data_inicio" class="block mt-1 w-full" :value="old('data_inicio', $projeto->data_inicio?->format('Y-m-d'))" required />
            </div>
            <div>
                <x-input-label for="data_termino" value="Data término" />
                <x-text-input id="data_termino" type="date" name="data_termino" class="block mt-1 w-full" :value="old('data_termino', $projeto->data_termino?->format('Y-m-d'))" />
            </div>
            <div>
                <x-input-label for="user_id" value="Responsável" />
                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $projeto->user_id) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach (['pendente', 'atrasado', 'concluido'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $projeto->status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <x-primary-button>Salvar</x-primary-button>
        </form>
    </div>
</x-app-layout>
