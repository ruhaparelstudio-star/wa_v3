<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SpecGapClosureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_root_route_points_to_health_status(): void
    {
        $this->get('/')
            ->assertRedirect('/health');
    }

    public function test_authenticated_user_can_reach_dashboard_and_logout(): void
    {
        $user = User::query()->create([
            'name' => 'Tenant Admin',
            'email' => 'tenant-admin@example.test',
            'password' => 'secret-password',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);

        $this->post('/login', [
            'email' => 'tenant-admin@example.test',
            'password' => 'secret-password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Dashboard');

        $this->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_superadmin_can_use_web_form_to_create_tenant(): void
    {
        $superadmin = $this->createSuperadmin();

        $this->actingAs($superadmin)
            ->get('/superadmin/tenants/create')
            ->assertOk()
            ->assertSee('Buat tenant');

        $this->actingAs($superadmin)->post('/superadmin/tenants', [
            'tenant_name' => 'Form Tenant',
            'tenant_slug' => 'form-tenant',
            'admin_name' => 'Form Admin',
            'admin_email' => 'form-admin@example.test',
        ])->assertRedirect('/superadmin/tenants/create')
            ->assertSessionHas('activation_link');

        $this->assertDatabaseHas('tenants', [
            'slug' => 'form-tenant',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'form-admin@example.test',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);
    }

    public function test_tenant_admin_cannot_open_superadmin_tenant_form(): void
    {
        $tenantAdmin = User::query()->create([
            'name' => 'Tenant Admin',
            'email' => 'tenant-admin@example.test',
            'password' => 'secret-password',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);

        $this->actingAs($tenantAdmin)
            ->get('/superadmin/tenants/create')
            ->assertForbidden();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        User::query()->create([
            'name' => 'Rate Limited',
            'email' => 'limited@example.test',
            'password' => 'secret-password',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from('/login')->post('/login', [
                'email' => 'limited@example.test',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $this->from('/login')->post('/login', [
            'email' => 'limited@example.test',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    private function createSuperadmin(): User
    {
        return User::query()->create([
            'name' => 'Root Admin',
            'email' => 'root@example.test',
            'password' => 'secret-password',
            'role' => User::ROLE_SUPERADMIN,
        ]);
    }
}
