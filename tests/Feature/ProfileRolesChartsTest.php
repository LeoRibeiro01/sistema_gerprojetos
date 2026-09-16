<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\DashboardChart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileRolesChartsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar_from_profile(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => UserRole::Dev->value]);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->create('avatar.png', 10, 'image/png'),
        ])->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_admin_can_change_another_users_role(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value, 'is_admin' => true]);
        $developer = User::factory()->create(['role' => UserRole::Dev->value, 'is_admin' => false]);

        $this->actingAs($admin)->put(route('users.update', $developer), [
            'name' => $developer->name,
            'email' => $developer->email,
            'role' => UserRole::Manager->value,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $developer->id, 'role' => UserRole::Manager->value, 'is_admin' => false]);
    }

    public function test_internal_user_can_create_and_export_a_dashboard_chart(): void
    {
        $user = User::factory()->create(['role' => UserRole::Dev->value, 'is_admin' => false]);

        $this->actingAs($user)->post(route('dashboard.charts.store'), [
            'titulo' => 'Produtividade',
            'metrica' => 'productivity',
            'tipo' => 'pie',
            'cor' => '#0ea5e9',
        ])->assertRedirect();

        $chart = DashboardChart::firstOrFail();
        $this->actingAs($user)->get(route('dashboard.charts.pdf', $chart))->assertOk()->assertHeader('content-type', 'application/pdf');
    }
}
