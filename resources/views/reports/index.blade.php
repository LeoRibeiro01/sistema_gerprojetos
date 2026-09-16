<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Relatórios</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="bg-white rounded-lg shadow-sm p-6 space-y-3">
            <p class="text-gray-600 text-sm">Exportações em PDF protegidas por autenticação.</p>
            <a href="{{ route('projetos.report') }}" target="_blank" class="inline-flex rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                PDF — todos os projetos
            </a>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-medium text-gray-800 mb-3">Pré-visualização por projeto</h3>
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach (\App\Models\Projeto::orderBy('titulo')->get() as $projeto)
                    <li class="py-2 flex justify-between items-center">
                        <span>{{ $projeto->titulo }}</span>
                        <span class="space-x-3">
                            <a href="{{ route('reports.show', $projeto->id) }}" class="text-indigo-600 hover:underline">Detalhes</a>
                            <a href="{{ route('projeto.singleReport', $projeto->id) }}" target="_blank" class="text-red-600 hover:underline">PDF</a>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
