<?php

namespace Tests\Feature\WhatsApp;

use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Jobs\ProcessInboundWhatsAppMessage;
use App\Modules\WhatsApp\Models\WAAccount;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GatewayInboundTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'test-wa-gateway-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config(['whatsapp.gateway_internal_secret' => self::SECRET]);
        Queue::fake();
    }

    public function test_inbound_endpoint_rejects_requests_without_secret_and_signature(): void
    {
        $waAccount = $this->createWaAccount();

        $this->postJson('/internal/whatsapp/inbound', $this->payload($waAccount))
            ->assertForbidden();

        $this->assertDatabaseCount('wa_inbound_messages', 0);
        Queue::assertNothingPushed();
    }

    public function test_inbound_endpoint_accepts_valid_simulated_payload_and_stores_raw_message(): void
    {
        $waAccount = $this->createWaAccount();
        $payload = $this->payload($waAccount, [
            'provider_message_id' => 'wamid.valid-1',
            'body' => 'Halo, ada paket wedding?',
        ]);

        $this->signedPostJson('/internal/whatsapp/inbound', $payload)
            ->assertAccepted()
            ->assertJson([
                'accepted' => true,
                'duplicate' => false,
            ]);

        $this->assertDatabaseHas('wa_inbound_messages', [
            'tenant_id' => $waAccount->tenant_id,
            'wa_account_id' => $waAccount->id,
            'provider_message_id' => 'wamid.valid-1',
            'customer_phone' => '6281234567890',
            'body' => 'Halo, ada paket wedding?',
            'processing_status' => WAInboundMessage::STATUS_QUEUED,
        ]);

        $message = WAInboundMessage::query()->firstOrFail();
        $this->assertSame($payload['provider_message_id'], $message->raw_payload['provider_message_id']);
        $this->assertSame($payload['body'], $message->raw_payload['body']);

        Queue::assertPushed(ProcessInboundWhatsAppMessage::class, 1);
    }

    public function test_duplicate_provider_message_is_ignored_and_not_queued_twice(): void
    {
        $waAccount = $this->createWaAccount();
        $payload = $this->payload($waAccount, [
            'provider_message_id' => 'wamid.duplicate-1',
        ]);

        $this->signedPostJson('/internal/whatsapp/inbound', $payload)
            ->assertAccepted()
            ->assertJsonPath('duplicate', false);

        $this->signedPostJson('/internal/whatsapp/inbound', array_merge($payload, [
            'body' => 'Pesan retry dari provider',
        ]))
            ->assertOk()
            ->assertJsonPath('duplicate', true);

        $this->assertDatabaseCount('wa_inbound_messages', 1);
        $this->assertDatabaseHas('wa_inbound_messages', [
            'provider_message_id' => 'wamid.duplicate-1',
            'body' => 'Halo',
        ]);
        Queue::assertPushed(ProcessInboundWhatsAppMessage::class, 1);
    }

    private function createWaAccount(): WAAccount
    {
        $tenant = Tenant::query()->create([
            'name' => 'WA Tenant',
            'slug' => 'wa-tenant',
            'status' => Tenant::STATUS_TRIAL,
        ]);

        return WAAccount::query()->create([
            'tenant_id' => $tenant->id,
            'phone_number' => '628111111111',
            'display_name' => 'WA Tenant',
            'status' => WAAccount::STATUS_CONNECTED,
        ]);
    }

    private function payload(WAAccount $waAccount, array $overrides = []): array
    {
        return array_merge([
            'wa_account_id' => $waAccount->id,
            'provider_message_id' => 'wamid.default',
            'customer_phone' => '6281234567890',
            'message_type' => 'text',
            'body' => 'Halo',
            'received_at' => '2026-05-06T14:00:00Z',
        ], $overrides);
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
