<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
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
    </div>
</x-app-layout>
