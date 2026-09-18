<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-4 flex items-center gap-4"><img src="{{ $user->avatarUrl() }}" alt="Foto de {{ $user->name }}" class="h-20 w-20 rounded-full object-cover"><div><p class="font-semibold text-slate-900">{{ $user->name }}</p><p class="text-sm text-slate-500">{{ $user->email }}</p></div></div>
        <dl class="bg-white rounded-lg shadow-sm divide-y divide-gray-100">
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-gray-500">E-mail</dt>
                <dd class="font-medium">{{ $user->email }}</dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-gray-500">Papel</dt>
                <dd class="font-medium uppercase">{{ $user->role }}</dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-gray-500">Admin</dt>
                <dd class="font-medium">{{ $user->isAdmin() ? 'Sim' : 'Não' }}</dd>
            </div>
        </dl>

        @if (auth()->user()->canViewUsers() || auth()->id() === $user->id)
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold text-slate-900">Dailies</h3>
                    <span class="rounded-full bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700">{{ $dailyCheckins->count() }} registros</span>
                </div>

                @forelse ($dailyCheckins as $daily)
                    <article class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-800">{{ \Illuminate\Support\Facades\Date::parse($daily->data)->format('d/m/Y') }}</p>
                            <span class="text-xs uppercase tracking-wide text-slate-500">{{ $user->name }}</span>
                        </div>
                        <div class="space-y-3 text-sm text-slate-700">
                            <div><p class="font-semibold text-slate-900">Ontem</p><p>{{ $daily->ontem }}</p></div>
                            <div><p class="font-semibold text-slate-900">Hoje</p><p>{{ $daily->hoje }}</p></div>
                            @if ($daily->impedimentos)
                                <div><p class="font-semibold text-slate-900">Impedimentos</p><p>{{ $daily->impedimentos }}</p></div>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Nenhum daily registrado para este usuário até o momento.</p>
                @endforelse
            </section>
        @endif
    </div>
</x-app-layout>
