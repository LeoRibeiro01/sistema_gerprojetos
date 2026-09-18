@auth
    @php
        $sideLink = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-blue-800 hover:text-white';
        $sideLinkActive = 'flex items-center gap-3 rounded-xl bg-white/15 px-3 py-2.5 text-sm font-semibold text-white shadow-sm ring-1 ring-white/10';
        $mobileLink = 'block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100';
        $mobileLinkActive = 'block rounded-lg bg-indigo-50 px-3 py-2.5 text-sm font-semibold text-indigo-700';
    @endphp

    <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col bg-gradient-to-b from-blue-950 via-blue-900 to-slate-950 text-white lg:flex">
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-400 font-bold text-blue-950">A</div>
            <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-wide">ATLAS</a>
        </div>

        <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto px-3 py-5" aria-label="Navegação principal">
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-blue-300">Visão geral</p>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $sideLinkActive : $sideLink }}">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            @if (auth()->user()->isInternalTeam())
                <a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? $sideLinkActive : $sideLink }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 9.7 9.7 0 0 1-4.4-1.1L3 20l1.2-4A8.5 8.5 0 1 1 21 11.5Z"/></svg>
                    <span class="flex-1">Mensagens</span>
                    @if (auth()->user()->unreadMessagesCount() > 0)<span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">{{ auth()->user()->unreadMessagesCount() }}</span>@endif
                </a>
            @endif
            @if (auth()->user()->hasRole(\App\Enums\UserRole::Client))
                <a href="{{ route('client-portal.index') }}" class="{{ request()->routeIs('client-portal.*') ? $sideLinkActive : $sideLink }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg>Portal do cliente
                </a>
            @endif

            @can('viewAny', \App\Models\Projeto::class)
                <p class="px-3 pb-2 pt-6 text-[11px] font-semibold uppercase tracking-wider text-blue-300">Gestão</p>
                <a href="{{ route('projeto.index') }}" class="{{ request()->routeIs('projeto.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18v13H3z"/><path d="M8 7V4h8v3"/></svg>Projetos</a>
            @endcan
            @can('viewAny', \App\Models\Tarefa::class)
                <a href="{{ route('tarefas.index') }}" class="{{ request()->routeIs('tarefas.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Tarefas</a>
                <a href="{{ route('kanban.index') }}" class="{{ request()->routeIs('kanban.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h4v16H4zM10 4h4v9h-4zM16 4h4v13h-4z"/></svg>Kanban</a>
                <a href="{{ route('sprints.index') }}" class="{{ request()->routeIs('sprints.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h11M4 17h7"/></svg>Sprints</a>
                <a href="{{ route('epics.index') }}" class="{{ request()->routeIs('epics.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5"/></svg>Epics</a>
                <a href="{{ route('user-stories.index') }}" class="{{ request()->routeIs('user-stories.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h14v16H5z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>Stories</a>
                <a href="{{ route('bugs.index') }}" class="{{ request()->routeIs('bugs.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/></svg>Bugs</a>
            @endcan
            @can('viewAny', \App\Models\Projeto::class)
                <p class="px-3 pb-2 pt-6 text-[11px] font-semibold uppercase tracking-wider text-blue-300">Administração</p>
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V5M4 19h16M8 16v-5M12 16V7M16 16v-8"/></svg>Relatórios</a>
            @endcan
            @can('viewAny', \App\Models\User::class)
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? $sideLinkActive : $sideLink }}"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0 1 12 0M16 11a3 3 0 1 0-1-5.8M16 14a5 5 0 0 1 5 5"/></svg>Usuários</a>
            @endcan
        </nav>

        <div class="border-t border-white/10 p-3">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 hover:bg-white/10">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="Foto de {{ auth()->user()->name }}" class="h-9 w-9 rounded-full object-cover">
                <span class="min-w-0 flex-1 truncate text-sm font-medium">{{ auth()->user()->name }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">@csrf<button class="w-full rounded-xl px-3 py-2 text-left text-sm text-blue-200 hover:bg-white/10 hover:text-white">Sair</button></form>
        </div>
    </aside>

    <nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white lg:hidden">
        <div class="flex h-16 items-center justify-between px-4">
            <a href="{{ route('dashboard') }}" class="font-bold tracking-wide text-blue-950">ATLAS</a>
            <button type="button" @click="open = !open" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100" aria-label="Abrir menu">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="open ? 'm6 6 12 12M6 18 18 6' : 'M4 6h16M4 12h16M4 18h16'"/></svg>
            </button>
        </div>
        <div x-show="open" x-cloak class="border-t border-slate-100 bg-white px-3 py-3 shadow-lg">
            <div class="max-h-[calc(100vh-4rem)] space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $mobileLinkActive : $mobileLink }}">Dashboard</a>
                @if (auth()->user()->isInternalTeam())<a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? $mobileLinkActive : $mobileLink }}">Mensagens @if (auth()->user()->unreadMessagesCount())<span class="ml-1 rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white">{{ auth()->user()->unreadMessagesCount() }}</span>@endif</a>@endif
                @if (auth()->user()->hasRole(\App\Enums\UserRole::Client))<a href="{{ route('client-portal.index') }}" class="{{ request()->routeIs('client-portal.*') ? $mobileLinkActive : $mobileLink }}">Portal do cliente</a>@endif
                @can('viewAny', \App\Models\Projeto::class)<a href="{{ route('projeto.index') }}" class="{{ request()->routeIs('projeto.*') ? $mobileLinkActive : $mobileLink }}">Projetos</a>@endcan
                @can('viewAny', \App\Models\Tarefa::class)
                    <a href="{{ route('tarefas.index') }}" class="{{ request()->routeIs('tarefas.*') ? $mobileLinkActive : $mobileLink }}">Tarefas</a><a href="{{ route('kanban.index') }}" class="{{ request()->routeIs('kanban.*') ? $mobileLinkActive : $mobileLink }}">Kanban</a><a href="{{ route('sprints.index') }}" class="{{ request()->routeIs('sprints.*') ? $mobileLinkActive : $mobileLink }}">Sprints</a><a href="{{ route('epics.index') }}" class="{{ request()->routeIs('epics.*') ? $mobileLinkActive : $mobileLink }}">Epics</a><a href="{{ route('user-stories.index') }}" class="{{ request()->routeIs('user-stories.*') ? $mobileLinkActive : $mobileLink }}">Stories</a><a href="{{ route('bugs.index') }}" class="{{ request()->routeIs('bugs.*') ? $mobileLinkActive : $mobileLink }}">Bugs</a>
                @endcan
                @can('viewAny', \App\Models\Projeto::class)<a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? $mobileLinkActive : $mobileLink }}">Relatórios</a>@endcan
                @can('viewAny', \App\Models\User::class)<a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? $mobileLinkActive : $mobileLink }}">Usuários</a>@endcan
                <div class="my-2 border-t border-slate-100"></div><a href="{{ route('profile.edit') }}" class="{{ $mobileLink }}">Perfil</a><form method="POST" action="{{ route('logout') }}">@csrf<button class="{{ $mobileLink }} w-full text-left">Sair</button></form>
            </div>
        </div>
    </nav>
@endauth
