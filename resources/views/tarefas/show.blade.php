<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tarefa->titulo }}</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="taskTimer(@js($activeTimesheet !== null), @js($activeTimesheet?->inicio?->toIso8601String()))">
        <dl class="bg-white rounded-lg shadow-sm divide-y divide-gray-100">
            <div class="px-6 py-4 flex justify-between"><dt class="text-gray-500">Projeto</dt><dd>{{ $tarefa->projeto->titulo ?? 'N/A' }}</dd></div>
            <div class="px-6 py-4 flex justify-between"><dt class="text-gray-500">Responsável</dt><dd>{{ $tarefa->user->name ?? 'N/A' }}</dd></div>
            <div class="px-6 py-4 flex justify-between"><dt class="text-gray-500">Status</dt><dd class="capitalize">{{ $tarefa->status }}</dd></div>
            <div class="px-6 py-4"><dt class="text-gray-500 mb-1">Descrição</dt><dd>{{ $tarefa->descricao ?: '—' }}</dd></div>
            <div class="px-6 py-4 flex justify-between"><dt class="text-gray-500">Última edição</dt><dd>{{ $tarefa->updated_at?->format('d/m/Y H:i') }} por {{ $tarefa->updatedBy?->name ?? 'sistema' }}</dd></div>
        </dl>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h3 class="font-semibold text-slate-900">Dependências</h3>
                <span class="text-sm text-slate-500">{{ $tarefa->dependencias->count() }} vinculadas</span>
            </div>
            <ul class="mt-3 divide-y divide-slate-100 text-sm">
                @forelse ($tarefa->dependencias as $dependency)
                    <li class="flex items-center justify-between gap-3 py-2"><span>{{ $dependency->titulo }}</span><form method="POST" action="{{ route('tarefas.dependencies.destroy', [$tarefa, $dependency]) }}">@csrf @method('DELETE')<button class="text-xs font-semibold text-red-600">Remover</button></form></li>
                @empty
                    <li class="py-2 text-slate-500">Nenhuma dependência cadastrada.</li>
                @endforelse
            </ul>
            @if ($availableDependencies->isNotEmpty())
                <form method="POST" action="{{ route('tarefas.dependencies.store', $tarefa) }}" class="mt-4 flex flex-wrap gap-3">@csrf<select name="depende_de_id" class="min-w-64 flex-1 rounded-md border-slate-300 text-sm"><option value="">Selecione uma tarefa bloqueadora</option>@foreach($availableDependencies as $dependency)<option value="{{ $dependency->id }}">{{ $dependency->titulo }}</option>@endforeach</select><button class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Adicionar</button></form>
            @endif
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Time tracking</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">Horas trabalhadas</h3>
                    <p class="mt-1 text-sm text-slate-500">Registre o tempo real investido nesta tarefa.</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-semibold tabular-nums text-slate-900" x-text="displayTime()">00:00:00</p>
                    <p class="text-xs text-slate-500" x-text="running ? 'Timer em execução' : 'Timer parado'"></p>
                </div>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-md bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Executado</p>
                    <p class="mt-1 text-xl font-semibold text-slate-900">{{ number_format($executedMinutes / 60, 2, ',', '.') }} h</p>
                </div>
                <div class="rounded-md bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Estimado</p>
                    <p class="mt-1 text-xl font-semibold text-slate-900">{{ $tarefa->estimativa_minutos ? number_format($tarefa->estimativa_minutos / 60, 2, ',', '.') . ' h' : 'Não definido' }}</p>
                </div>
            </div>
            @if ($tarefa->estimativa_minutos)
                <div class="mt-4">
                    <div class="mb-1 flex justify-between text-xs text-slate-500"><span>Progresso de esforço</span><span>{{ min(100, round(($executedMinutes / $tarefa->estimativa_minutos) * 100)) }}%</span></div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-indigo-600" style="width: {{ min(100, ($executedMinutes / $tarefa->estimativa_minutos) * 100) }}%"></div></div>
                </div>
            @endif

            <div class="mt-5 flex flex-wrap items-end gap-3">
                <label class="min-w-64 flex-1 text-sm font-medium text-slate-700">Descrição da sessão<input x-model="description" type="text" class="mt-1 block w-full rounded-md border-slate-300 text-sm" placeholder="Ex.: implementação e revisão"></label>
                <label class="flex items-center gap-2 pb-2 text-sm text-slate-600"><input x-model="billable" type="checkbox" class="rounded border-slate-300 text-indigo-600"> Faturável</label>
                <button x-show="!running" type="button" @click="start" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">Iniciar timer</button>
                <button x-show="running" type="button" @click="stop" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Parar timer</button>
            </div>
            <p x-show="error" x-text="error" class="mt-3 text-sm text-red-600"></p>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h3 class="font-semibold text-slate-900">Sessões registradas</h3>
            <div class="mt-3 overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead class="bg-slate-50"><tr><th class="px-3 py-2 text-left">Usuário</th><th class="px-3 py-2 text-left">Início</th><th class="px-3 py-2 text-left">Fim</th><th class="px-3 py-2 text-right">Duração</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($tarefa->timesheets->sortByDesc('inicio') as $timesheet)<tr><td class="px-3 py-2">{{ $timesheet->user->name }}</td><td class="px-3 py-2">{{ $timesheet->inicio->format('d/m/Y H:i') }}</td><td class="px-3 py-2">{{ $timesheet->fim?->format('d/m/Y H:i') ?? 'Em andamento' }}</td><td class="px-3 py-2 text-right">{{ $timesheet->duracao_minutos ? number_format($timesheet->duracao_minutos / 60, 2, ',', '.') . ' h' : '-' }}</td></tr>@empty<tr><td colspan="4" class="px-3 py-5 text-center text-slate-500">Nenhuma sessão registrada.</td></tr>@endforelse</tbody></table></div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Colaboração técnica</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">Comentários</h3>
                </div>
                <span class="text-sm text-slate-500">{{ $tarefa->comments->count() }} registros</span>
            </div>
            <form method="POST" action="{{ route('tarefas.comments.store', $tarefa) }}" class="mt-4 space-y-3">
                @csrf
                <textarea name="conteudo" rows="4" required class="block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escreva uma atualização, use Markdown ou um bloco de código..."></textarea>
                <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Adicionar comentário</button>
            </form>
            <div class="mt-6 space-y-4">
                @forelse($tarefa->comments->sortByDesc('created_at') as $comment)
                    <article class="rounded-md border border-slate-200 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500"><span class="font-semibold text-slate-700">{{ $comment->user->name }}</span><time>{{ $comment->created_at->format('d/m/Y H:i') }}</time></div>
                        <div class="mt-3 prose prose-sm max-w-none text-slate-700">{!! \Illuminate\Support\Str::markdown($comment->conteudo, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
                        @if ($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('tarefas.comments.destroy', [$tarefa, $comment]) }}" class="mt-3"><button class="text-xs font-semibold text-red-600 hover:text-red-500">Remover</button>@csrf @method('DELETE')</form>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Ainda não há comentários nesta tarefa.</p>
                @endforelse
            </div>
        </section>
        <a href="{{ route('tarefas.index') }}" class="inline-block mt-4 text-indigo-600 hover:underline text-sm">← Voltar</a>
    </div>

    <script>
        function taskTimer(running, startedAt) {
            return {
                running,
                startedAt,
                description: '',
                billable: true,
                error: '',
                now: Date.now(),
                init() {
                    window.setInterval(() => { this.now = Date.now(); }, 1000);
                },
                displayTime() {
                    if (!this.running || !this.startedAt) return '00:00:00';
                    const total = Math.max(0, Math.floor((this.now - new Date(this.startedAt).getTime()) / 1000));
                    const hours = String(Math.floor(total / 3600)).padStart(2, '0');
                    const minutes = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
                    const seconds = String(total % 60).padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                },
                async start() {
                    this.error = '';
                    const response = await fetch('{{ route('tarefas.timer.start', $tarefa) }}', { method: 'POST', headers: this.headers(), body: JSON.stringify({ descricao: this.description, billable: this.billable }) });
                    const payload = await response.json();
                    if (!response.ok) { this.error = payload.message || 'Não foi possível iniciar o timer.'; return; }
                    this.startedAt = payload.inicio;
                    this.running = true;
                },
                async stop() {
                    this.error = '';
                    const response = await fetch('{{ route('tarefas.timer.stop', $tarefa) }}', { method: 'POST', headers: this.headers(), body: JSON.stringify({ descricao: this.description }) });
                    const payload = await response.json();
                    if (!response.ok) { this.error = payload.message || 'Não foi possível parar o timer.'; return; }
                    window.location.reload();
                },
                headers() {
                    return { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content };
                },
            };
        }
    </script>
</x-app-layout>
