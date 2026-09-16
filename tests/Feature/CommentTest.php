<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_internal_user_can_add_markdown_comment_with_mentions(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $projeto = Projeto::create(['titulo' => 'Projeto', 'data_inicio' => now()->toDateString(), 'status' => 'pendente', 'user_id' => $user->id]);
        $tarefa = Tarefa::create(['titulo' => 'Tarefa', 'data_inicio' => now()->toDateString(), 'status' => Tarefa::STATUS_BACKLOG, 'tipo' => 'feature', 'prioridade' => 'media', 'projeto_id' => $projeto->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('tarefas.comments.store', $tarefa), ['conteudo' => "**Revisão** concluída. @maria\n```php\nreturn true;\n```"])->assertRedirect();

        $comment = Comment::firstOrFail();
        $this->assertSame(['maria'], $comment->mencoes);
        $this->assertSame($user->id, $comment->user_id);
        $this->actingAs($user)->get(route('tarefas.show', $tarefa))->assertOk();
    }
}
