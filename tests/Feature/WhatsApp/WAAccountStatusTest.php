<?php

namespace Tests\Feature\WhatsApp;

use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WAAccountStatusTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'test-wa-gateway-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config(['whatsapp.gateway_internal_secret' => self::SECRET]);
    }

    public function test_wa_account_status_can_be_stored_and_updated(): void
    {
        $waAccount = $this->createWaAccount();

        $this->signedPostJson('/internal/whatsapp/accounts/'.$waAccount->id.'/status', [
            'status' => WAAccount::STATUS_CONNECTED,
            'session_key' => 'tenant-1-primary',
            'metadata' => [
                'gateway_instance' => 'local-dev',
            ],
        ])->assertOk()
            ->assertJsonPath('wa_account_id', $waAccount->id)
            ->assertJsonPath('status', WAAccount::STATUS_CONNECTED);

        $this->assertDatabaseHas('wa_accounts', [
            'id' => $waAccount->id,
            'status' => WAAccount::STATUS_CONNECTED,
        ]);
        $this->assertDatabaseHas('wa_sessions', [
            'wa_account_id' => $waAccount->id,
            'session_key' => 'tenant-1-primary',
            'status' => WAAccount::STATUS_CONNECTED,
        ]);

        $this->signedPostJson('/internal/whatsapp/accounts/'.$waAccount->id.'/status', [
            'status' => WAAccount::STATUS_RECONNECTING,
            'session_key' => 'tenant-1-primary',
        ])->assertOk()
            ->assertJsonPath('status', WAAccount::STATUS_RECONNECTING);

        $this->assertDatabaseCount('wa_sessions', 1);
        $this->assertDatabaseHas('wa_sessions', [
            'session_key' => 'tenant-1-primary',
            'status' => WAAccount::STATUS_RECONNECTING,
        ]);
    }

    private function createWaAccount(): WAAccount
    {
        $tenant = Tenant::query()->create([
            'name' => 'Status Tenant',
            'slug' => 'status-tenant',
            'status' => Tenant::STATUS_TRIAL,
        ]);

        return WAAccount::query()->create([
            'tenant_id' => $tenant->id,
            'phone_number' => '628222222222',
            'display_name' => 'Status Tenant',
            'status' => WAAccount::STATUS_DISCONNECTED,
        ]);
    }

    private function signedPostJson(string $uri, array $payload)
    {
        $content = json_encode($payload, JSON_THROW_ON_ERROR);

        return $this->call('POST', $uri, [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_WA_GATEWAY_SECRET' => self::SECRET,
            'HTTP_X_WA_SIGNATURE' => 'sha256='.hash_hmac('sha256', $content, self::SECRET),
        ], $content);
    }
}
