<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        abort_unless($currentUser && $currentUser->isInternalTeam(), 403, 'Você não tem acesso às mensagens internas.');

        $conversationUsers = $this->contactsFor($currentUser)->get();
        $selectedUser = $request->filled('user')
            ? $conversationUsers->firstWhere('id', (int) $request->query('user'))
            : $conversationUsers->first();

        abort_if($request->filled('user') && !$selectedUser, 404);

        $messages = collect();
        $contactSummaries = collect();

        if (Schema::hasTable('messages')) {
            if ($selectedUser) {
                $this->markConversationAsRead($currentUser->id, $selectedUser->id);
            }

            $relatedMessages = Message::where('sender_id', $currentUser->id)
                ->orWhere('recipient_id', $currentUser->id)
                ->latest('id')
                ->get();

            $unreadCounts = $relatedMessages
                ->where('recipient_id', $currentUser->id)
                ->whereNull('read_at')
                ->groupBy('sender_id')
                ->map->count();

            $contactSummaries = $conversationUsers->mapWithKeys(function (User $contact) use ($relatedMessages, $unreadCounts, $currentUser) {
                $lastMessage = $relatedMessages->first(fn (Message $message) =>
                    $message->sender_id === $contact->id || $message->recipient_id === $contact->id
                );

                return [$contact->id => [
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCounts->get($contact->id, 0),
                ]];
            });

            if ($selectedUser) {
                $messages = $this->conversationQuery($currentUser->id, $selectedUser->id)
                    ->oldest('id')
                    ->get();
            }
        }

        return view('messages.index', compact('conversationUsers', 'selectedUser', 'messages', 'currentUser', 'contactSummaries'));
    }

    public function store(Request $request)
    {
        $currentUser = auth()->user();
        abort_unless($currentUser && $currentUser->isInternalTeam(), 403, 'Você não tem acesso às mensagens internas.');

        $request->validate([
            'recipient_id' => ['required', 'integer'],
            'body' => ['nullable', 'string', 'max:20000'],
            'attachment' => [
                'nullable',
                'file',
                'max:25600',
                function (string $attribute, $file, \Closure $fail): void {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $audioExtensions = ['mp3', 'mpeg', 'wav', 'ogg', 'oga', 'webm', 'm4a', 'aac', 'mp4', 'opus'];

                    if (!in_array($extension, [...$imageExtensions, ...$audioExtensions], true)) {
                        $fail('O anexo deve ser uma imagem ou um arquivo de áudio compatível.');
                        return;
                    }

                    if (in_array($extension, $imageExtensions, true) && !str_starts_with(strtolower($file->getMimeType() ?? ''), 'image/')) {
                        $fail('O anexo de imagem é inválido.');
                    }
                },
            ],
        ]);

        $recipient = $this->contactsFor($currentUser)->find($request->integer('recipient_id'));
        abort_unless($recipient, 404);

        if (blank($request->body) && !$request->hasFile('attachment')) {
            return back()->withErrors(['body' => 'Escreva uma mensagem ou anexe uma imagem/áudio.']);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('messages', 'public');
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = strtolower($file->getMimeType() ?? '');

            $audioExtensions = ['mp3', 'mpeg', 'wav', 'ogg', 'oga', 'webm', 'm4a', 'aac', 'mp4', 'opus'];
            $audioMimeTypes = ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/webm', 'audio/mp4', 'audio/x-m4a', 'audio/aac', 'audio/opus'];

            $attachmentType = in_array($extension, $audioExtensions, true) || in_array($mimeType, $audioMimeTypes, true)
                ? 'audio'
                : 'image';
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id,
            'body' => filled($request->body) ? trim($request->body) : null,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->messagePayload($message),
            ]);
        }

        return redirect()->route('messages.index', ['user' => $recipient->id])
            ->with('success', 'Mensagem enviada.');
    }

    public function downloadAttachment(Message $message)
    {
        $currentUser = auth()->user();
        abort_unless($currentUser && $currentUser->isInternalTeam(), 403, 'Você não tem acesso às mensagens internas.');
        abort_unless(
            $message->attachment_type === 'image'
                && $message->attachment_path
                && in_array($currentUser->id, [$message->sender_id, $message->recipient_id], true),
            403
        );
        abort_unless(Storage::disk('public')->exists($message->attachment_path), 404);

        $extension = pathinfo($message->attachment_path, PATHINFO_EXTENSION) ?: 'jpg';

        return Storage::disk('public')->download($message->attachment_path, "imagem-mensagem-{$message->id}.{$extension}");
    }

    public function poll(Request $request)
    {
        $currentUser = auth()->user();
        abort_unless($currentUser && $currentUser->isInternalTeam(), 403, 'Você não tem acesso às mensagens internas.');

        if (!Schema::hasTable('messages')) {
            return response()->json(['messages' => []]);
        }

        $recipientId = (int) $request->query('user');
        $lastId = (int) $request->query('since', 0);

        abort_unless($this->contactsFor($currentUser)->whereKey($recipientId)->exists(), 404);

        $messages = $this->conversationQuery($currentUser->id, $recipientId)
            ->when($lastId > 0, fn ($query) => $query->where('id', '>', $lastId))
            ->oldest('id')
            ->get();

        $this->markConversationAsRead($currentUser->id, $recipientId);

        return response()->json(['messages' => $messages->map(fn (Message $message) => $this->messagePayload($message))->values()]);
    }

    public function stream(Request $request)
    {
        $currentUser = auth()->user();
        abort_unless($currentUser && $currentUser->isInternalTeam(), 403, 'Você não tem acesso às mensagens internas.');

        if (!Schema::hasTable('messages')) {
            return response()->json(['messages' => []]);
        }

        set_time_limit(0);

        $recipientId = (int) $request->query('user', $currentUser->id);
        $lastId = (int) $request->query('since', 0);

        abort_unless($this->contactsFor($currentUser)->whereKey($recipientId)->exists(), 404);

        return response()->stream(function () use ($currentUser, $recipientId, $lastId) {
            $lastEventId = $lastId;
            $iterations = 0;

            while ($iterations < 30) {
                $messages = Message::where(function ($query) use ($currentUser, $recipientId) {
                        $query->where('sender_id', $currentUser->id)->where('recipient_id', $recipientId);
                    })
                    ->orWhere(function ($query) use ($currentUser, $recipientId) {
                        $query->where('sender_id', $recipientId)->where('recipient_id', $currentUser->id);
                    })
                    ->when($lastEventId > 0, fn ($query) => $query->where('id', '>', $lastEventId))
                    ->orderBy('created_at')
                    ->get();

                if ($messages->isNotEmpty()) {
                    $lastEventId = max($lastEventId, (int) $messages->max('id'));

                    $payload = ['messages' => $messages->values()->all()];
                    echo "event: messages\n";
                    echo 'data: ' . json_encode($payload) . "\n\n";
                    ob_flush();
                    flush();

                    Message::where('recipient_id', $currentUser->id)
                        ->where('sender_id', $recipientId)
                        ->whereNull('read_at')
                        ->update(['read_at' => now()]);

                    break;
                }

                if (connection_aborted()) {
                    break;
                }

                sleep(2);
                $iterations++;
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function contactsFor(User $user): Builder
    {
        return User::query()
            ->whereKeyNot($user->id)
            ->where(function (Builder $query) {
                $query->where('is_admin', true)
                    ->orWhereIn('role', ['admin', 'pm', 'manager', 'scrum_master', 'dev']);
            })
            ->orderBy('name');
    }

    private function conversationQuery(int $currentUserId, int $contactId): Builder
    {
        return Message::where(function (Builder $query) use ($currentUserId, $contactId) {
            $query->where('sender_id', $currentUserId)->where('recipient_id', $contactId);
        })->orWhere(function (Builder $query) use ($currentUserId, $contactId) {
            $query->where('sender_id', $contactId)->where('recipient_id', $currentUserId);
        });
    }

    private function markConversationAsRead(int $currentUserId, int $contactId): void
    {
        Message::where('recipient_id', $currentUserId)
            ->where('sender_id', $contactId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    private function messagePayload(Message $message): array
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'recipient_id' => $message->recipient_id,
            'body' => $message->body,
            'attachment_type' => $message->attachment_type,
            'attachment_url' => $message->attachment_path ? Storage::disk('public')->url($message->attachment_path) : null,
            'attachment_download_url' => $message->attachment_type === 'image' ? route('messages.attachments.download', $message) : null,
            'read_at' => $message->read_at?->toIso8601String(),
            'created_at' => $message->created_at->toIso8601String(),
        ];
    }
}
