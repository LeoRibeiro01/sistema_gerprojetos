<?php

namespace Tests\Feature;

use App\Models\DailyCheckin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyCheckinTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_one_daily_checkin(): void
    {
        $user = User::factory()->create(['role' => 'dev', 'is_admin' => false]);
        $payload = ['ontem' => 'Implementei a API.', 'hoje' => 'Vou revisar o PR.', 'impedimentos' => 'Nenhum.'];

        $this->actingAs($user)->post(route('daily-checkins.store'), $payload)->assertRedirect();
        $this->actingAs($user)->post(route('daily-checkins.store'), array_merge($payload, ['hoje' => 'Vou testar o fluxo.']))->assertRedirect();

        $this->assertSame(1, DailyCheckin::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('daily_checkins', ['user_id' => $user->id, 'hoje' => 'Vou testar o fluxo.']);
    }
}
