<nav x-data="{ open: false }" class="bg-slate-800 border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-white font-semibold tracking-tight">
                        ATLAS
                    </a>
                </div>

                @auth
                    <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex items-center">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                        @if (auth()->user()->isInternalTeam())
                            <a href="{{ route('messages.index') }}" class="relative inline-flex items-center gap-2 text-sm font-medium text-slate-200 hover:text-white {{ request()->routeIs('messages.*') ? 'text-white' : '' }}">
                                Mensagens
                                @if (auth()->user()->unreadMessagesCount() > 0)
                                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ auth()->user()->unreadMessagesCount() }}</span>
                                @endif
                            </a>
                        @endif
                        @can('viewAny', App\Models\Projeto::class)
                            <x-nav-link :href="route('projeto.index')" :active="request()->routeIs('projeto.*')">
                                Projetos
                            </x-nav-link>
                        @endcan
                        @if (auth()->user()->hasRole(App\Enums\UserRole::Client))
                            <x-nav-link :href="route('client-portal.index')" :active="request()->routeIs('client-portal.*')">
                                Portal do cliente
                            </x-nav-link>
                        @endif
                        @can('viewAny', App\Models\Tarefa::class)
                            <x-nav-link :href="route('tarefas.index')" :active="request()->routeIs('tarefas.*')">
                                Tarefas
                            </x-nav-link>
                            <x-nav-link :href="route('kanban.index')" :active="request()->routeIs('kanban.*')">
                                Kanban
                            </x-nav-link>
                            <x-nav-link :href="route('sprints.index')" :active="request()->routeIs('sprints.*')">
                                Sprints
                            </x-nav-link>
                            <x-nav-link :href="route('epics.index')" :active="request()->routeIs('epics.*')">
                                Epics
                            </x-nav-link>
                            <x-nav-link :href="route('user-stories.index')" :active="request()->routeIs('user-stories.*')">
                                Stories
                            </x-nav-link>
                            <x-nav-link :href="route('bugs.index')" :active="request()->routeIs('bugs.*')">
                                Bugs
                            </x-nav-link>
                        @endcan
                        @can('viewAny', App\Models\Projeto::class)
                            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                                Relatórios
                            </x-nav-link>
                        @endcan
                        @can('viewAny', App\Models\User::class)
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                Usuários
                            </x-nav-link>
                        @endcan
                    </div>
                @endauth
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-200 hover:text-white focus:outline-none transition">
                                <img src="{{ Auth::user()->avatarUrl() }}" alt="Foto de {{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Perfil
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Sair
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-slate-200 hover:text-white me-4">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-slate-200 hover:text-white">Cadastro</a>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900">
        @auth
            <div class="pt-2 pb-3 space-y-1 px-2">
                @if (auth()->user()->hasRole(App\Enums\UserRole::Client))
                    <x-responsive-nav-link :href="route('client-portal.index')" :active="request()->routeIs('client-portal.*')">Portal do cliente</x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
                @if (auth()->user()->isInternalTeam())
                    <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        Mensagens
                        @if (auth()->user()->unreadMessagesCount() > 0)
                            <span class="ml-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ auth()->user()->unreadMessagesCount() }}</span>
                        @endif
                    </x-responsive-nav-link>
                @endif
                @can('viewAny', App\Models\Projeto::class)
                    <x-responsive-nav-link :href="route('projeto.index')" :active="request()->routeIs('projeto.*')">Projetos</x-responsive-nav-link>
                @endcan
                @can('viewAny', App\Models\Tarefa::class)
                    <x-responsive-nav-link :href="route('tarefas.index')" :active="request()->routeIs('tarefas.*')">Tarefas</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('kanban.index')" :active="request()->routeIs('kanban.*')">Kanban</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('sprints.index')" :active="request()->routeIs('sprints.*')">Sprints</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('epics.index')" :active="request()->routeIs('epics.*')">Epics</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('user-stories.index')" :active="request()->routeIs('user-stories.*')">Stories</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('bugs.index')" :active="request()->routeIs('bugs.*')">Bugs</x-responsive-nav-link>
                @endcan
            </div>
        @endauth
    </div>
</nav>
