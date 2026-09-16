<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Relatório — {{ $projeto->titulo }}</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
            <p><span class="text-gray-500">Responsável:</span> {{ $projeto->user->name ?? 'N/A' }}</p>
            <p><span class="text-gray-500">Status:</span> {{ $projeto->status }}</p>
            <p class="text-gray-700">{{ $projeto->descricao }}</p>
            <a href="{{ route('projeto.singleReport', $projeto->id) }}" target="_blank" class="inline-flex rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                Baixar PDF
            </a>
        </div>
    </div>
</x-app-layout>
