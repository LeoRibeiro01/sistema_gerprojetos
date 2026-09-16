<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar tarefa</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('tarefas.update', $tarefa) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="titulo" value="Título" />
                <x-text-input id="titulo" name="titulo" class="block mt-1 w-full" :value="old('titulo', $tarefa->titulo)" required />
            </div>
            <div>
                <x-input-label for="descricao" value="Descrição" />
                <textarea id="descricao" name="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3">{{ old('descricao', $tarefa->descricao) }}</textarea>
            </div>
            <div>
                <x-input-label for="data_inicio" value="Data início" />
                <x-text-input id="data_inicio" type="date" name="data_inicio" class="block mt-1 w-full" :value="old('data_inicio', $tarefa->data_inicio?->format('Y-m-d'))" required />
            </div>
            <div>
                <x-input-label for="data_termino" value="Data término" />
                <x-text-input id="data_termino" type="date" name="data_termino" class="block mt-1 w-full" :value="old('data_termino', $tarefa->data_termino?->format('Y-m-d'))" />
            </div>
            <div>
                <x-input-label for="projeto_id" value="Projeto" />
                <select id="projeto_id" name="projeto_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach ($projetos as $projeto)
                        <option value="{{ $projeto->id }}" @selected(old('projeto_id', $tarefa->projeto_id) == $projeto->id)>{{ $projeto->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="tipo" value="Tipo" />
                    <select id="tipo" name="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @foreach (App\Models\Tarefa::TIPOS as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo', $tarefa->tipo) === $tipo)>{{ ucfirst(str_replace('_', ' ', $tipo)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="prioridade" value="Prioridade" />
                    <select id="prioridade" name="prioridade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @foreach (App\Models\Tarefa::PRIORIDADES as $prioridade)
                            <option value="{{ $prioridade }}" @selected(old('prioridade', $tarefa->prioridade) === $prioridade)>{{ ucfirst($prioridade) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <x-input-label for="estimativa_minutos" value="Estimativa (minutos)" />
                <x-text-input id="estimativa_minutos" type="number" min="1" name="estimativa_minutos" class="block mt-1 w-full" :value="old('estimativa_minutos', $tarefa->estimativa_minutos)" />
            </div>
            <div>
                <x-input-label for="sprint_id" value="Sprint" />
                <select id="sprint_id" name="sprint_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Sem sprint</option>
                    @foreach ($sprints as $sprint)
                        <option value="{{ $sprint->id }}" @selected(old('sprint_id', $tarefa->sprint_id) == $sprint->id)>{{ $sprint->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="tag" value="Tag" />
                <x-text-input id="tag" name="tag" class="block mt-1 w-full" :value="old('tag', $tarefa->tag)" placeholder="Ex.: mobile" />
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach (App\Models\Tarefa::statuses() as $status)
                        <option value="{{ $status }}" @selected(old('status', $tarefa->status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <x-primary-button>Salvar</x-primary-button>
        </form>
    </div>
</x-app-layout>
