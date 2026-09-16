<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Cliente;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_sees_only_assigned_projects_and_can_approve_delivery(): void
    {
        $cliente = Cliente::create(['nome' => 'Acme', 'email' => 'contato@acme.test']);
        $client = User::factory()->create(['role' => UserRole::Client->value, 'is_admin' => false, 'cliente_id' => $cliente->id]);
        $internal = User::factory()->create(['role' => UserRole::Dev->value, 'is_admin' => false]);
        $visible = Projeto::create(['titulo' => 'Projeto do cliente', 'data_inicio' => now()->toDateString(), 'status' => 'pendente', 'user_id' => $internal->id, 'cliente_id' => $cliente->id]);
        Projeto::create(['titulo' => 'Projeto privado', 'data_inicio' => now()->toDateString(), 'status' => 'pendente', 'user_id' => $internal->id]);

        $response = $this->actingAs($client)->get(route('client-portal.index'));
        $response->assertOk()->assertSee('Projeto do cliente')->assertDontSee('Projeto privado');

        $this->actingAs($client)->post(route('client-portal.approvals.store', $visible), ['status' => 'aprovado', 'comentario' => 'Aprovado.'])->assertRedirect();
        $this->assertDatabaseHas('client_approvals', ['projeto_id' => $visible->id, 'user_id' => $client->id, 'status' => 'aprovado']);
        $this->actingAs($client)->get(route('dashboard'))->assertRedirect(route('client-portal.index'));
    }

    public function test_internal_dashboard_loads_metrics_without_error(): void
    {
        $internal = User::factory()->create(['role' => UserRole::Dev->value, 'is_admin' => false]);

        $this->actingAs($internal)->get(route('dashboard'))->assertOk()->assertSee('Tarefas atrasadas');
    }
}
