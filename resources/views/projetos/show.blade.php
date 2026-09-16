<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $projeto->titulo }}</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <dl class="bg-white rounded-lg shadow-sm divide-y divide-gray-100">
            <div class="px-6 py-4 flex justify-between gap-4"><dt class="text-gray-500">Responsável</dt><dd>{{ $projeto->user->name ?? 'N/A' }}</dd></div>
            <div class="px-6 py-4 flex justify-between gap-4"><dt class="text-gray-500">Status</dt><dd class="capitalize">{{ $projeto->status }}</dd></div>
            <div class="px-6 py-4"><dt class="text-gray-500 mb-1">Descrição</dt><dd>{{ $projeto->descricao ?: '—' }}</dd></div>
            <div class="px-6 py-4 flex justify-between gap-4"><dt class="text-gray-500">Última edição</dt><dd>{{ $projeto->updated_at?->format('d/m/Y H:i') }} por {{ $projeto->updatedBy?->name ?? 'sistema' }}</dd></div>
        </dl>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-medium text-gray-800 mb-3">Tarefas vinculadas</h3>
            @if ($projeto->tarefas->isEmpty())
                <p class="text-sm text-gray-500">Nenhuma tarefa neste projeto.</p>
            @else
                <ul class="divide-y divide-gray-100 text-sm">
                    @foreach ($projeto->tarefas as $tarefa)
                        <li class="py-2 flex justify-between">
                            <span>{{ $tarefa->titulo }}</span>
                            <span class="capitalize text-gray-500">{{ $tarefa->status }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <a href="{{ route('projeto.index') }}" class="text-indigo-600 hover:underline text-sm">← Voltar</a>
    </div>
</x-app-layout>
