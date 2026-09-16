<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->isInternalTeam())
                @include('dashboard_metrics')
                @include('dashboard_charts')
            @endif
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @can('viewAny', App\Models\Projeto::class)
                    <a href="{{ route('projeto.index') }}" class="block rounded-lg bg-white p-6 shadow-sm border border-gray-100 hover:border-indigo-200">
                        <p class="text-sm text-gray-500">Projetos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Projeto::count() }}</p>
                    </a>
                @endcan
                @can('viewAny', App\Models\Tarefa::class)
                    <a href="{{ route('tarefas.index') }}" class="block rounded-lg bg-white p-6 shadow-sm border border-gray-100 hover:border-indigo-200">
                        <p class="text-sm text-gray-500">Tarefas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Tarefa::count() }}</p>
                    </a>
                @endcan
                @can('viewAny', App\Models\Projeto::class)
                    <a href="{{ route('reports.index') }}" class="block rounded-lg bg-white p-6 shadow-sm border border-gray-100 hover:border-indigo-200">
                        <p class="text-sm text-gray-500">Relatórios</p>
                        <p class="text-lg font-medium text-indigo-600 mt-2">Abrir →</p>
                    </a>
                @endcan
            </div>
            <p class="mt-6 text-sm text-gray-600">Olá, {{ auth()->user()->name }} ({{ auth()->user()->role }}).</p>
        </div>
    </div>

    @if (!$dailyCheckin)
        <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 px-4" x-cloak>
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl" @click.outside="open = false">
                <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Daily async</p><h3 class="mt-1 text-xl font-semibold text-slate-900">Como está o seu dia?</h3></div><button type="button" @click="open = false" class="text-2xl leading-none text-slate-400">&times;</button></div>
                <form method="POST" action="{{ route('daily-checkins.store') }}" class="mt-5 space-y-4">@csrf<div><x-input-label for="ontem" value="O que você fez ontem?" /><textarea id="ontem" name="ontem" rows="3" required class="mt-1 block w-full rounded-md border-slate-300"></textarea></div><div><x-input-label for="hoje" value="O que fará hoje?" /><textarea id="hoje" name="hoje" rows="3" required class="mt-1 block w-full rounded-md border-slate-300"></textarea></div><div><x-input-label for="impedimentos" value="Há impedimentos?" /><textarea id="impedimentos" name="impedimentos" rows="2" class="mt-1 block w-full rounded-md border-slate-300"></textarea></div><div class="flex justify-end gap-3"><button type="button" @click="open = false" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Agora não</button><button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Registrar daily</button></div></form>
            </div>
        </div>
    @endif
</x-app-layout>
