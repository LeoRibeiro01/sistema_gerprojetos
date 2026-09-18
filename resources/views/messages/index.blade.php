<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-slate-100 p-3 sm:p-5">
        <section class="mx-auto flex h-[calc(100vh-5.5rem)] max-w-7xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60">
            <aside id="contacts-sidebar" class="{{ $selectedUser ? 'hidden md:flex' : 'flex' }} w-full shrink-0 flex-col border-r border-slate-200 bg-white md:w-[340px]">
                <div class="border-b border-slate-100 px-5 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $currentUser->avatarUrl() }}" alt="Foto de {{ $currentUser->name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-indigo-50">
                            <div><h1 class="text-base font-bold text-slate-900">Mensagens</h1><p class="text-xs text-slate-500">Equipe interna</p></div>
                        </div>
                        <button type="button" data-focus-search class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-indigo-600" aria-label="Nova conversa">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </button>
                    </div>
                    <label class="relative mt-4 block">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input id="contact-search" type="search" placeholder="Buscar conversa" class="w-full rounded-xl border-0 bg-slate-100 py-2.5 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500">
                    </label>
                </div>
                <div class="min-h-0 flex-1 overflow-y-auto">
                    @forelse ($conversationUsers as $contact)
                        @php($summary = $contactSummaries->get($contact->id, ['lastMessage' => null, 'unreadCount' => 0]))
                        @php($lastMessage = $summary['lastMessage'])
                        <a href="{{ route('messages.index', ['user' => $contact->id]) }}" data-contact-card data-contact-name="{{ strtolower($contact->name) }}" class="flex items-center gap-3 border-b border-slate-100 px-5 py-3.5 transition hover:bg-slate-50 {{ $selectedUser?->id === $contact->id ? 'bg-indigo-50/70' : '' }}">
                            <div class="relative shrink-0"><img src="{{ $contact->avatarUrl() }}" alt="Foto de {{ $contact->name }}" class="h-11 w-11 rounded-full object-cover"><span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2"><p class="truncate text-sm font-semibold text-slate-800">{{ $contact->name }}</p>@if ($lastMessage)<time class="shrink-0 text-[11px] text-slate-400">{{ $lastMessage->created_at->isToday() ? $lastMessage->created_at->format('H:i') : $lastMessage->created_at->format('d/m') }}</time>@endif</div>
                                <div class="mt-1 flex items-center gap-2"><p class="min-w-0 flex-1 truncate text-xs {{ $summary['unreadCount'] ? 'font-semibold text-slate-700' : 'text-slate-500' }}">{{ $lastMessage?->body ?: ($lastMessage?->attachment_type === 'audio' ? 'Áudio' : ($lastMessage ? 'Imagem' : 'Inicie uma conversa')) }}</p>@if ($summary['unreadCount'])<span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-bold text-white">{{ $summary['unreadCount'] }}</span>@endif</div>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-12 text-center"><div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-400"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 9.7 9.7 0 0 1-4.4-1.1L3 20l1.2-4A8.5 8.5 0 1 1 21 11.5Z"/></svg></div><p class="mt-3 text-sm font-medium text-slate-700">Nenhum contato disponível</p><p class="mt-1 text-xs leading-5 text-slate-500">Quando outro integrante entrar na equipe, ele aparecerá aqui.</p></div>
                    @endforelse
                </div>
            </aside>
            <main id="chat-window" class="{{ $selectedUser ? 'flex' : 'hidden md:flex' }} min-w-0 flex-1 flex-col bg-slate-50">
                @if ($selectedUser)
                    <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:px-5">
                        <div class="flex min-w-0 items-center gap-3">
                            <button type="button" data-show-contacts class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 md:hidden" aria-label="Ver conversas"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg></button>
                            <div class="relative shrink-0"><img src="{{ $selectedUser->avatarUrl() }}" alt="Foto de {{ $selectedUser->name }}" class="h-10 w-10 rounded-full object-cover"><span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span></div>
                            <div class="min-w-0"><h2 class="truncate text-sm font-bold text-slate-900">{{ $selectedUser->name }}</h2><p class="text-xs text-emerald-600">Disponível</p></div>
                        </div>
                        <span class="hidden rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 sm:inline">Conversa privada</span>
                    </header>
                    <div id="messages-thread" class="min-h-0 flex-1 space-y-3 overflow-y-auto bg-[radial-gradient(circle_at_top,_#fff_0%,_#f8fafc_45%,_#eef2ff_100%)] p-4 sm:p-6" data-user-id="{{ $selectedUser->id }}" data-last-id="{{ $messages->last()?->id ?? 0 }}">
                        <div data-empty-state class="{{ $messages->isNotEmpty() ? 'hidden' : 'flex' }} h-full items-center justify-center"><p class="rounded-full bg-white px-4 py-2 text-xs text-slate-500 shadow-sm ring-1 ring-slate-100">Esta é uma conversa privada. Envie uma mensagem para começar.</p></div>
                        @foreach ($messages as $message)
                            @php($isMine = $message->sender_id === $currentUser->id)
                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                                <div class="max-w-[85%] rounded-2xl px-4 py-3 shadow-sm sm:max-w-[72%] {{ $isMine ? 'rounded-br-md bg-indigo-600 text-white' : 'rounded-bl-md bg-white text-slate-800 ring-1 ring-slate-100' }}">
                                    @if ($message->attachment_path)
                                        @if ($message->attachment_type === 'audio')<audio controls preload="metadata" class="mb-2 h-9 max-w-full" src="{{ Storage::disk('public')->url($message->attachment_path) }}">Seu navegador não suporta áudio.</audio>
                                        @else
                                            <div class="mb-2">
                                                <a href="{{ Storage::disk('public')->url($message->attachment_path) }}" target="_blank" rel="noopener" title="Abrir imagem em tamanho original"><img src="{{ Storage::disk('public')->url($message->attachment_path) }}" alt="Imagem anexada" class="max-h-72 rounded-xl object-cover transition hover:opacity-90"></a>
                                                <a href="{{ route('messages.attachments.download', $message) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold {{ $isMine ? 'text-indigo-100 hover:text-white' : 'text-indigo-600 hover:text-indigo-800' }}">Baixar imagem <span aria-hidden="true">↓</span></a>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($message->body)<p class="whitespace-pre-wrap break-words text-sm leading-6">{{ $message->body }}</p>@endif
                                    <div class="mt-1.5 flex justify-end gap-1 text-[10px] {{ $isMine ? 'text-indigo-100' : 'text-slate-400' }}"><time>{{ $message->created_at->format('H:i') }}</time>@if ($isMine)<span aria-label="{{ $message->read_at ? 'Lida' : 'Enviada' }}">{{ $message->read_at ? '✓✓' : '✓' }}</span>@endif</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <form id="message-form" action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" class="border-t border-slate-200 bg-white p-3 sm:p-4">
                        @csrf<input type="hidden" name="recipient_id" value="{{ $selectedUser->id }}">
                        <div id="attachment-preview" class="mb-2 hidden items-center justify-between rounded-xl bg-indigo-50 px-3 py-2 text-xs text-indigo-800"><span class="truncate" data-attachment-name></span><button type="button" data-remove-attachment class="ml-3 font-semibold hover:text-indigo-950">Remover</button></div>
                        <div class="flex items-end gap-2 sm:gap-3">
                            <label class="flex h-11 w-11 shrink-0 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-indigo-600" title="Anexar imagem ou áudio"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.4 11.6-8.8 8.8a6 6 0 0 1-8.5-8.5l9.5-9.5a4 4 0 0 1 5.7 5.7l-9.5 9.5a2 2 0 0 1-2.8-2.8l8.8-8.8"/></svg><input id="message-attachment" type="file" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp,audio/mpeg,audio/wav,audio/ogg,audio/webm,audio/mp4,audio/x-m4a,audio/aac" class="sr-only"></label>
                            <button id="voice-record-btn" type="button" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-indigo-600" aria-label="Gravar áudio"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0M12 17v4M8 21h8"/></svg></button>
                            <textarea id="message-body" name="body" rows="1" maxlength="20000" placeholder="Escreva uma mensagem" class="max-h-32 min-h-[44px] flex-1 resize-none rounded-xl border-0 bg-slate-100 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500"></textarea>
                            <button id="send-message-btn" type="submit" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50" aria-label="Enviar mensagem"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></button>
                        </div><p id="message-feedback" class="mt-2 hidden text-xs" aria-live="polite"></p>
                    </form>
                @else
                    <div class="m-auto max-w-sm px-6 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 9.7 9.7 0 0 1-4.4-1.1L3 20l1.2-4A8.5 8.5 0 1 1 21 11.5Z"/></svg></div><h2 class="mt-4 font-bold text-slate-900">Suas mensagens</h2><p class="mt-2 text-sm leading-6 text-slate-500">Escolha um integrante da equipe para iniciar uma conversa.</p></div>
                @endif
            </main>
        </section>
    </div>
    <script>
        (() => {
            const currentUserId = {{ $currentUser->id }}, thread = document.getElementById('messages-thread'), search = document.getElementById('contact-search'), sidebar = document.getElementById('contacts-sidebar'), chat = document.getElementById('chat-window');
            document.querySelector('[data-focus-search]')?.addEventListener('click', () => search?.focus());
            search?.addEventListener('input', () => { const term = search.value.trim().toLocaleLowerCase(); document.querySelectorAll('[data-contact-card]').forEach((item) => item.classList.toggle('hidden', !item.dataset.contactName.includes(term))); });
            document.querySelector('[data-show-contacts]')?.addEventListener('click', () => { sidebar.classList.remove('hidden'); chat.classList.add('hidden'); });
            const bottom = () => { if (thread) thread.scrollTop = thread.scrollHeight; }; bottom();
            const node = (message) => {
                const mine = Number(message.sender_id) === currentUserId, row = document.createElement('div'), bubble = document.createElement('div');
                row.className = `flex ${mine ? 'justify-end' : 'justify-start'}`; row.dataset.messageId = message.id;
                bubble.className = `max-w-[85%] rounded-2xl px-4 py-3 shadow-sm sm:max-w-[72%] ${mine ? 'rounded-br-md bg-indigo-600 text-white' : 'rounded-bl-md bg-white text-slate-800 ring-1 ring-slate-100'}`;
                if (message.attachment_url) {
                    if (message.attachment_type === 'audio') { const audio = document.createElement('audio'); audio.controls = true; audio.preload = 'metadata'; audio.className = 'mb-2 h-9 max-w-full'; audio.src = message.attachment_url; bubble.append(audio); }
                    else {
                        const attachment = document.createElement('div'), imageLink = document.createElement('a'), image = document.createElement('img');
                        attachment.className = 'mb-2'; imageLink.href = message.attachment_url; imageLink.target = '_blank'; imageLink.rel = 'noopener'; image.title = 'Abrir imagem em tamanho original'; image.src = message.attachment_url; image.alt = 'Imagem anexada'; image.className = 'max-h-72 rounded-xl object-cover transition hover:opacity-90'; imageLink.append(image); attachment.append(imageLink);
                        if (message.attachment_download_url) { const download = document.createElement('a'); download.href = message.attachment_download_url; download.textContent = 'Baixar imagem ↓'; download.className = `mt-2 inline-flex text-xs font-semibold ${mine ? 'text-indigo-100 hover:text-white' : 'text-indigo-600 hover:text-indigo-800'}`; attachment.append(download); }
                        bubble.append(attachment);
                    }
                }
                if (message.body) { const body = document.createElement('p'); body.className = 'whitespace-pre-wrap break-words text-sm leading-6'; body.textContent = message.body; bubble.append(body); }
                const meta = document.createElement('div'); meta.className = `mt-1.5 flex justify-end gap-1 text-[10px] ${mine ? 'text-indigo-100' : 'text-slate-400'}`; meta.textContent = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) + (mine ? (message.read_at ? ' ✓✓' : ' ✓') : ''); bubble.append(meta); row.append(bubble); return row;
            };
            const add = (messages) => { if (!thread) return; messages.forEach((message) => { if (thread.querySelector(`[data-message-id="${message.id}"]`)) return; thread.querySelector('[data-empty-state]')?.classList.add('hidden'); thread.append(node(message)); thread.dataset.lastId = message.id; }); bottom(); };
            if (thread) window.setInterval(async () => { try { const r = await fetch(`{{ route('messages.poll') }}?user=${thread.dataset.userId}&since=${thread.dataset.lastId}`, {headers: {Accept: 'application/json'}}); if (r.ok) add((await r.json()).messages || []); } catch (_) {} }, 4000);
            const form = document.getElementById('message-form'), input = document.getElementById('message-body'), attachment = document.getElementById('message-attachment'), preview = document.getElementById('attachment-preview'), feedback = document.getElementById('message-feedback'), send = document.getElementById('send-message-btn'), record = document.getElementById('voice-record-btn'); let recorder, stream, chunks = [];
            const notify = (text, error = false) => { feedback.textContent = text; feedback.className = `mt-2 text-xs ${error ? 'text-rose-600' : 'text-emerald-600'}`; };
            const updatePreview = () => { const file = attachment?.files?.[0]; preview?.classList.toggle('hidden', !file); preview?.classList.toggle('flex', !!file); preview?.querySelector('[data-attachment-name]')?.replaceChildren(file?.name || ''); };
            attachment?.addEventListener('change', updatePreview); document.querySelector('[data-remove-attachment]')?.addEventListener('click', () => { attachment.value = ''; updatePreview(); });
            input?.addEventListener('input', () => { input.style.height = 'auto'; input.style.height = `${Math.min(input.scrollHeight, 128)}px`; }); input?.addEventListener('keydown', (event) => { if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); form?.requestSubmit(); } });
            record?.addEventListener('click', async () => { if (recorder?.state === 'recording') { recorder.stop(); return; } if (!navigator.mediaDevices?.getUserMedia || !window.MediaRecorder) return notify('Gravação de áudio não é compatível com este navegador.', true); try { stream = await navigator.mediaDevices.getUserMedia({audio: true}); recorder = new MediaRecorder(stream); chunks = []; recorder.ondataavailable = (event) => event.data.size && chunks.push(event.data); recorder.onstop = () => { const file = new File([new Blob(chunks, {type: recorder.mimeType || 'audio/webm'})], `audio-${Date.now()}.webm`, {type: recorder.mimeType || 'audio/webm'}), transfer = new DataTransfer(); transfer.items.add(file); attachment.files = transfer.files; stream.getTracks().forEach((track) => track.stop()); record.classList.remove('bg-rose-100', 'text-rose-600'); updatePreview(); notify('Áudio pronto para envio.'); }; recorder.start(); record.classList.add('bg-rose-100', 'text-rose-600'); notify('Gravando áudio… clique novamente para finalizar.'); } catch (_) { notify('Não foi possível acessar o microfone.', true); } });
            form?.addEventListener('submit', async (event) => { event.preventDefault(); if (!input.value.trim() && !attachment.files.length) return notify('Escreva uma mensagem ou adicione um anexo.', true); send.disabled = true; try { const r = await fetch(form.action, {method: 'POST', headers: {Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content}, body: new FormData(form)}), payload = await r.json(); if (!r.ok) throw new Error(payload.message || Object.values(payload.errors || {}).flat()[0] || 'Não foi possível enviar a mensagem.'); add([payload.message]); form.reset(); input.style.height = 'auto'; updatePreview(); input.focus(); feedback.classList.add('hidden'); } catch (error) { notify(error.message, true); } finally { send.disabled = false; } });
        })();
    </script>
</x-app-layout>
