<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_messages_require_authentication(): void
    {
        $this->get(route('messages.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_send_message_to_colleague(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);

        $this->actingAs($sender)
            ->post(route('messages.store'), [
                'recipient_id' => $recipient->id,
                'body' => 'Oi, tudo bem?',
            ])
            ->assertRedirect(route('messages.index', ['user' => $recipient->id]));

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'body' => 'Oi, tudo bem?',
        ]);
    }

    public function test_messages_default_to_first_contact_when_no_user_is_selected(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);

        $this->actingAs($sender)
            ->get(route('messages.index'))
            ->assertOk()
            ->assertViewHas('selectedUser', fn ($selectedUser) => $selectedUser->id === $recipient->id);
    }

    public function test_authenticated_user_can_send_message_by_ajax_json_request(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);

        $this->actingAs($sender)
            ->withHeaders(['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'])
            ->post(route('messages.store'), [
                'recipient_id' => $recipient->id,
                'body' => 'Mensagem via AJAX',
            ])
            ->assertOk()
            ->assertJsonPath('message.body', 'Mensagem via AJAX');
    }

    public function test_authenticated_user_can_poll_new_messages_for_conversation(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);

        $this->actingAs($sender)->post(route('messages.store'), [
            'recipient_id' => $recipient->id,
            'body' => 'Mensagem nova',
        ]);

        $this->actingAs($sender)
            ->getJson(route('messages.poll', ['user' => $recipient->id]))
            ->assertOk()
            ->assertJsonFragment(['body' => 'Mensagem nova']);
    }

    public function test_authenticated_user_can_send_voice_note_attachment_with_webm_audio(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);

        $this->actingAs($sender)
            ->post(route('messages.store'), [
                'recipient_id' => $recipient->id,
                'body' => '',
                'attachment' => UploadedFile::fake()->create('voice-note.webm', 1024, 'audio/webm'),
            ])
            ->assertRedirect(route('messages.index', ['user' => $recipient->id]));

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'attachment_type' => 'audio',
        ]);
    }

    public function test_browser_recorded_audio_is_accepted_when_its_mime_type_is_generic(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);

        $this->actingAs($sender)
            ->post(route('messages.store'), [
                'recipient_id' => $recipient->id,
                'attachment' => UploadedFile::fake()->create('browser-recording.webm', 256, 'application/octet-stream'),
            ])
            ->assertRedirect(route('messages.index', ['user' => $recipient->id]));

        $this->assertDatabaseHas('messages', ['sender_id' => $sender->id, 'attachment_type' => 'audio']);
    }

    public function test_both_participants_can_download_an_attached_image_but_others_cannot(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $recipient = User::factory()->create(['role' => 'pm', 'is_admin' => false]);
        $otherUser = User::factory()->create(['role' => 'manager', 'is_admin' => false]);
        $message = Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'attachment_path' => 'messages/example-image.jpg',
            'attachment_type' => 'image',
        ]);

        \Illuminate\Support\Facades\Storage::fake('public');
        \Illuminate\Support\Facades\Storage::disk('public')->put($message->attachment_path, 'image-content');

        $this->actingAs($sender)->get(route('messages.attachments.download', $message))->assertOk();
        $this->actingAs($recipient)->get(route('messages.attachments.download', $message))->assertOk();
        $this->actingAs($otherUser)->get(route('messages.attachments.download', $message))->assertForbidden();
    }

    public function test_internal_messages_cannot_be_sent_to_client_accounts(): void
    {
        $sender = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $client = User::factory()->create(['role' => 'client', 'is_admin' => false]);

        $this->actingAs($sender)
            ->post(route('messages.store'), [
                'recipient_id' => $client->id,
                'body' => 'Mensagem indevida',
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing('messages', ['sender_id' => $sender->id, 'recipient_id' => $client->id]);
    }
}
