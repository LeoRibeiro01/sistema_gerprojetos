<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">Execução ágil</p>
                <h2 class="mt-1 text-2xl font-semibold text-slate-900">Quadro Kanban</h2>
            </div>
            <a href="{{ route('tarefas.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                Nova tarefa
            </a>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-100 px-4 py-6 sm:px-6 lg:px-8" x-data="kanbanBoard(@js($tarefas), @js($columns))">
        <div class="mx-auto max-w-[1800px] space-y-5">
            <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('kanban.index') }}" class="rounded-full px-3 py-2 text-sm font-semibold {{ request('projeto_id') ? 'bg-slate-100 text-slate-700' : 'bg-slate-900 text-white' }}">
                            Quadro geral
                        </a>
                        @foreach ($projetos as $projeto)
                            <a href="{{ route('kanban.index', ['projeto_id' => $projeto->id]) }}" class="rounded-full px-3 py-2 text-sm font-semibold {{ request('projeto_id') == $projeto->id ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                {{ $projeto->titulo }}
                            </a>
                        @endforeach
                    </div>

                    @if (request('projeto_id'))
                        <div class="text-sm text-slate-600">
                            Projeto selecionado: <span class="font-semibold text-slate-900">{{ $projetos->firstWhere('id', request('projeto_id'))?->titulo ?? 'Projeto' }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-5">
                    <label class="text-sm font-medium text-slate-700">
                        Projeto
                        <select x-model="filters.projeto_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @change="window.location='{{ route('kanban.index') }}?projeto_id=' + (filters.projeto_id || '')">
                            <option value="">Todos</option>
                            @foreach ($projetos as $projeto)
                                <option value="{{ $projeto->id }}" @selected(request('projeto_id') == $projeto->id)>{{ $projeto->titulo }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Responsável
                        <select x-model="filters.user_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Sprint
                        <select x-model="filters.sprint_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas</option>
                            @foreach ($sprints as $sprint)
                                <option value="{{ $sprint->id }}">{{ $sprint->nome }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Prioridade
                        <select x-model="filters.prioridade" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas</option>
                            @foreach ($prioridades as $prioridade)
                                <option value="{{ $prioridade }}">{{ ucfirst($prioridade) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Tag
                        <input x-model="filters.tag" type="search" placeholder="Ex.: mobile" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </label>
                </div>
                <div class="mt-3 flex items-center justify-between gap-3 text-sm">
                    <p class="text-slate-500"><span x-text="filteredTasks().length"></span> tarefas visíveis</p>
                    <button type="button" class="font-semibold text-indigo-600 hover:text-indigo-500" @click="resetFilters">Limpar filtros</button>
                </div>
            </section>

            <div class="grid gap-4 overflow-x-auto pb-2 xl:grid-cols-6">
                <template x-for="column in columns" :key="column.status">
                    <section class="flex min-h-[34rem] min-w-[17rem] flex-col rounded-xl border border-slate-200 bg-slate-200/70" @dragover.prevent @drop="drop(column.status)">
                        <header class="flex items-center justify-between border-b border-slate-300 px-4 py-3">
                            <h3 class="text-sm font-bold text-slate-800" x-text="column.label"></h3>
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-slate-500" x-text="filteredTasks(column.status).length"></span>
                        </header>
                        <div class="flex flex-1 flex-col gap-3 p-3">
                            <template x-for="task in filteredTasks(column.status)" :key="task.id">
                                <article draggable="true" @dragstart="dragStart(task)" class="cursor-grab rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md active:cursor-grabbing">
                                    <div class="flex items-start justify-between gap-3">
                                        <a :href="`/tarefas/${task.id}`" class="font-semibold leading-5 text-slate-900 hover:text-indigo-600" x-text="task.titulo"></a>
                                        <span class="shrink-0 rounded-full px-2 py-1 text-[10px] font-bold uppercase tracking-wide" :class="priorityClass(task.prioridade)" x-text="task.prioridade"></span>
                                    </div>
                                    <p class="mt-3 line-clamp-2 text-xs leading-5 text-slate-500" x-text="task.descricao || 'Sem descrição.'"></p>
                                    <div class="mt-4 flex flex-wrap gap-2 text-[11px] text-slate-500">
                                        <span x-show="task.projeto" class="rounded bg-slate-100 px-2 py-1" x-text="task.projeto"></span>
                                        <span x-show="task.tipo" class="rounded bg-indigo-50 px-2 py-1 font-medium text-indigo-700" x-text="task.tipo.replace('_', ' ')"></span>
                                        <span x-show="task.tag" class="rounded bg-amber-50 px-2 py-1 font-medium text-amber-700" x-text="`#${task.tag}`"></span>
                                    </div>
                                    <div class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500" x-text="task.responsavel || 'Sem responsável'"></div>
                                </article>
                            </template>
                            <p x-show="filteredTasks(column.status).length === 0" class="rounded-lg border border-dashed border-slate-300 px-3 py-8 text-center text-xs text-slate-500">Nenhuma tarefa nesta coluna.</p>
                        </div>
                    </section>
                </template>
            </div>
        </div>
    </div>

    <script>
        function kanbanBoard(tasks, columns) {
            return {
                tasks,
                columns,
                draggedTask: null,
                filters: {
                    projeto_id: '{{ request('projeto_id') ?? '' }}',
                    user_id: '',
                    sprint_id: '',
                    prioridade: '',
                    tag: '',
                },
                filteredTasks(status = null) {
                    return this.tasks.filter((task) => {
                        const matchesStatus = !status || task.status === status;
                        const matchesProject = !this.filters.projeto_id || String(task.projeto_id ?? '') === String(this.filters.projeto_id);
                        const matchesUser = !this.filters.user_id || String(task.user_id) === String(this.filters.user_id);
                        const matchesSprint = !this.filters.sprint_id || String(task.sprint_id) === String(this.filters.sprint_id);
                        const matchesPriority = !this.filters.prioridade || task.prioridade === this.filters.prioridade;
                        const matchesTag = !this.filters.tag || (task.tag || '').toLowerCase().includes(this.filters.tag.toLowerCase());

                        return matchesStatus && matchesProject && matchesUser && matchesSprint && matchesPriority && matchesTag;
                    });
                },
                resetFilters() {
                    this.filters = { projeto_id: '{{ request('projeto_id') ?? '' }}', user_id: '', sprint_id: '', prioridade: '', tag: '' };
                },
                priorityClass(priority) {
                    return {
                        baixa: 'bg-slate-100 text-slate-600',
                        media: 'bg-blue-50 text-blue-700',
                        alta: 'bg-orange-50 text-orange-700',
                        critica: 'bg-red-50 text-red-700',
                    }[priority] || 'bg-slate-100 text-slate-600';
                },
                dragStart(task) {
                    this.draggedTask = task;
                },
                async drop(status) {
                    if (!this.draggedTask || this.draggedTask.status === status) {
                        return;
                    }

                    const task = this.draggedTask;
                    const previousStatus = task.status;
                    task.status = status;
                    this.draggedTask = null;

                    try {
                        const response = await fetch('{{ route('tarefas.status', ['tarefa' => '__TASK__']) }}'.replace('__TASK__', task.id), {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ status }),
                        });

                        if (!response.ok) {
                            throw new Error('Falha ao atualizar o status.');
                        }
                    } catch (error) {
                        task.status = previousStatus;
                        window.alert(error.message);
                    }
                },
            };
        }
    </script>
</x-app-layout>
