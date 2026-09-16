<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_require_authentication(): void
    {
        $this->get(route('reports.index'))->assertRedirect(route('login'));
        $this->get(route('projetos.report'))->assertRedirect(route('login'));
    }

    public function test_authenticated_internal_user_can_open_reports_index(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);

        $this->actingAs($user)
            ->get(route('reports.index'))
            ->assertOk();
    }
}
