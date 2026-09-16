<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar usuário</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
            @csrf
            @method('PUT')
            <div class="flex items-center gap-4"><img src="{{ $user->avatarUrl() }}" alt="Foto de {{ $user->name }}" class="h-16 w-16 rounded-full object-cover"><div class="flex-1"><x-input-label for="avatar" value="Substituir foto" /><input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm text-slate-600" /><x-input-error :messages="$errors->get('avatar')" class="mt-2" /></div></div>
            <div>
                <x-input-label for="name" value="Nome" />
                <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $user->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email', $user->email)" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="role" value="Papel" />
                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach (\App\Enums\UserRole::cases() as $role)
                        <option value="{{ $role->value }}" @selected(old('role', $user->role) === $role->value)>{{ strtoupper($role->value) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password" value="Nova senha (opcional)" />
                <x-text-input id="password" type="password" name="password" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password_confirmation" value="Confirmar senha" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="block mt-1 w-full" />
            </div>
            <x-primary-button>Atualizar</x-primary-button>
        </form>
    </div>
</x-app-layout>
