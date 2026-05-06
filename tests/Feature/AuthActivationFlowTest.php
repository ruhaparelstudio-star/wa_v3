<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Activation\Models\ActivationToken;
use App\Modules\Activation\Services\ActivationService;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthActivationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_tenant_and_activation_token(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'Acme Wedding',
            'admin_name' => 'Acme Admin',
            'admin_email' => 'admin@acme.test',
        ]);

        $response->assertCreated()
            ->assertJsonPath('tenant.name', 'Acme Wedding')
            ->assertJsonPath('tenant.status', Tenant::STATUS_PENDING_ACTIVATION)
            ->assertJsonPath('tenant_admin.role', User::ROLE_TENANT_ADMIN)
            ->assertJsonStructure([
                'activation_link',
                'activation_expires_at',
            ]);

        $this->assertDatabaseHas('tenants', [
            'name' => 'Acme Wedding',
            'slug' => 'acme-wedding',
            'status' => Tenant::STATUS_PENDING_ACTIVATION,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@acme.test',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);
        $this->assertDatabaseCount('activation_tokens', 1);
        $this->assertDatabaseHas('tenant_user', [
            'role' => User::ROLE_TENANT_ADMIN,
        ]);
    }

    public function test_activation_token_is_secure_and_stored_as_hash(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'Secure Bridal',
            'admin_name' => 'Secure Admin',
            'admin_email' => 'admin@secure.test',
        ]);

        $plainToken = basename((string) parse_url($response->json('activation_link'), PHP_URL_PATH));
        $activationToken = ActivationToken::query()->firstOrFail();

        $this->assertGreaterThanOrEqual(80, strlen($plainToken));
        $this->assertNotSame($plainToken, $activationToken->token_hash);
        $this->assertSame(hash('sha256', $plainToken), $activationToken->token_hash);
        $this->assertTrue($activationToken->expires_at->isFuture());
    }

    public function test_activation_link_can_set_password_and_tenant_admin_can_login(): void
    {
        $superadmin = $this->createSuperadmin();
        $response = $this->actingAs($superadmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'Login Bridal',
            'admin_name' => 'Login Admin',
            'admin_email' => 'admin@login.test',
        ]);
        $plainToken = basename((string) parse_url($response->json('activation_link'), PHP_URL_PATH));

        $this->get('/activate/'.$plainToken)
            ->assertOk()
            ->assertSee('Aktivasi tenant');

        $this->post('/activate/'.$plainToken, [
            'name' => 'Activated Admin',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect('/login');

        $tenant = Tenant::query()->firstOrFail();
        $tenantAdmin = User::query()->where('email', 'admin@login.test')->firstOrFail();
        $activationToken = ActivationToken::query()->firstOrFail();

        $this->assertSame(Tenant::STATUS_TRIAL, $tenant->refresh()->status);
        $this->assertSame('Activated Admin', $tenantAdmin->name);
        $this->assertTrue(Hash::check('secret-password', $tenantAdmin->password));
        $this->assertNotNull($activationToken->refresh()->used_at);

        $this->post('/login', [
            'email' => 'admin@login.test',
            'password' => 'secret-password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($tenantAdmin);
    }

    public function test_expired_token_is_rejected(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Expired Tenant',
            'slug' => 'expired-tenant',
            'status' => Tenant::STATUS_PENDING_ACTIVATION,
        ]);
        $tenantAdmin = User::query()->create([
            'name' => 'Expired Admin',
            'email' => 'expired@test.local',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);
        $tenant->users()->attach($tenantAdmin->id, ['role' => User::ROLE_TENANT_ADMIN]);

        $activationLink = app(ActivationService::class)->createLink($tenant, $tenantAdmin, now()->subMinute());

        $this->post('/activate/'.$activationLink->plainToken, [
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertSessionHasErrors('token');

        $this->assertNull($activationLink->activationToken->refresh()->used_at);
        $this->assertSame(Tenant::STATUS_PENDING_ACTIVATION, $tenant->refresh()->status);
    }

    public function test_used_token_cannot_be_reused(): void
    {
        $superadmin = $this->createSuperadmin();
        $response = $this->actingAs($superadmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'Used Token Bridal',
            'admin_name' => 'Used Token Admin',
            'admin_email' => 'admin@used-token.test',
        ]);
        $plainToken = basename((string) parse_url($response->json('activation_link'), PHP_URL_PATH));

        $this->post('/activate/'.$plainToken, [
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect('/login');

        $this->post('/activate/'.$plainToken, [
            'password' => 'another-secret',
            'password_confirmation' => 'another-secret',
        ])->assertSessionHasErrors('token');
    }

    public function test_tenant_admin_cannot_create_another_tenant(): void
    {
        $tenantAdmin = User::query()->create([
            'name' => 'Tenant Admin',
            'email' => 'tenant-admin@test.local',
            'password' => 'secret-password',
            'role' => User::ROLE_TENANT_ADMIN,
        ]);

        $this->actingAs($tenantAdmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'Forbidden Tenant',
            'admin_name' => 'Forbidden Admin',
            'admin_email' => 'forbidden@test.local',
        ])->assertForbidden();

        $this->assertDatabaseMissing('tenants', [
            'name' => 'Forbidden Tenant',
        ]);
    }

    public function test_tenant_admin_belongs_only_to_assigned_tenant(): void
    {
        $superadmin = $this->createSuperadmin();

        $first = $this->actingAs($superadmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'First Tenant',
            'admin_name' => 'First Admin',
            'admin_email' => 'first@test.local',
        ]);
        $second = $this->actingAs($superadmin)->postJson('/superadmin/tenants', [
            'tenant_name' => 'Second Tenant',
            'admin_name' => 'Second Admin',
            'admin_email' => 'second@test.local',
        ]);

        $firstAdmin = User::query()->where('email', 'first@test.local')->firstOrFail();
        $firstTenantId = $first->json('tenant.id');
        $secondTenantId = $second->json('tenant.id');

        $this->assertTrue($firstAdmin->tenants()->whereKey($firstTenantId)->exists());
        $this->assertFalse($firstAdmin->tenants()->whereKey($secondTenantId)->exists());
    }

    private function createSuperadmin(): User
    {
        return User::query()->create([
            'name' => 'Root Admin',
            'email' => 'root@test.local',
            'password' => 'secret-password',
            'role' => User::ROLE_SUPERADMIN,
        ]);
    }
}
