<?php

namespace Tests\Feature\Conversation;

use App\Modules\Conversation\Models\Conversation;
use App\Modules\Conversation\Models\Message;
use App\Modules\Conversation\Services\TenantConversationReader;
use App\Modules\Lead\Models\LeadProfile;
use App\Modules\Lead\Services\TenantLeadProfileReader;
use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAAccount;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationLeadCoreTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'test-wa-gateway-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config(['whatsapp.gateway_internal_secret' => self::SECRET]);
        config(['queue.default' => 'sync']);
    }

    public function test_new_customer_phone_creates_active_conversation_message_and_lead_profile(): void
    {
        $waAccount = $this->createWaAccount('new-phone');

        $this->signedPostJson('/internal/whatsapp/inbound', $this->payload($waAccount, [
            'provider_message_id' => 'wamid.new-phone-1',
            'customer_phone' => '628123450001',
            'body' => 'Halo admin',
        ]))->assertAccepted();

        $this->assertDatabaseHas('conversations', [
            'tenant_id' => $waAccount->tenant_id,
            'wa_account_id' => $waAccount->id,
            'customer_phone' => '628123450001',
            'status' => Conversation::STATUS_ACTIVE,
            'current_stage' => Conversation::STAGE_NEW_LEAD,
            'active_goal' => Conversation::GOAL_INITIAL_RESPONSE,
            'agent_mode' => Conversation::AGENT_MODE_ACTIVE,
            'memory_mode' => Conversation::MEMORY_MODE_ACTIVE,
        ]);
        $this->assertDatabaseHas('messages', [
            'tenant_id' => $waAccount->tenant_id,
            'direction' => Message::DIRECTION_INBOUND,
            'message_type' => 'text',
            'provider_message_id' => 'wamid.new-phone-1',
            'body' => 'Halo admin',
        ]);
        $this->assertDatabaseHas('lead_profiles', [
            'tenant_id' => $waAccount->tenant_id,
            'customer_phone' => '628123450001',
            'source' => LeadProfile::SOURCE_WHATSAPP,
            'lead_temperature' => LeadProfile::TEMPERATURE_COLD,
        ]);
        $this->assertDatabaseHas('wa_inbound_messages', [
            'provider_message_id' => 'wamid.new-phone-1',
            'processing_status' => WAInboundMessage::STATUS_PROCESSED,
        ]);
    }

    public function test_existing_customer_phone_reuses_active_conversation(): void
    {
        $waAccount = $this->createWaAccount('existing-phone');

        $this->signedPostJson('/internal/whatsapp/inbound', $this->payload($waAccount, [
            'provider_message_id' => 'wamid.existing-phone-1',
            'customer_phone' => '628123450002',
            'body' => 'Pertama',
            'received_at' => '2026-05-06T14:00:00Z',
        ]))->assertAccepted();

        $conversation = Conversation::query()->firstOrFail();

        $this->signedPostJson('/internal/whatsapp/inbound', $this->payload($waAccount, [
            'provider_message_id' => 'wamid.existing-phone-2',
            'customer_phone' => '628123450002',
            'body' => 'Kedua',
            'received_at' => '2026-05-06T14:05:00Z',
        ]))->assertAccepted();

        $this->assertDatabaseCount('conversations', 1);
        $this->assertDatabaseCount('messages', 2);
        $this->assertDatabaseCount('lead_profiles', 1);
        $this->assertTrue($conversation->is(Conversation::query()->firstOrFail()));
        $this->assertSame('2026-05-06 14:05:00', Conversation::query()->firstOrFail()->last_message_at->format('Y-m-d H:i:s'));
    }

    public function test_same_phone_in_different_tenants_creates_isolated_conversations_and_leads(): void
    {
        $tenantAAccount = $this->createWaAccount('tenant-a');
        $tenantBAccount = $this->createWaAccount('tenant-b');

        $this->signedPostJson('/internal/whatsapp/inbound', $this->payload($tenantAAccount, [
            'provider_message_id' => 'wamid.tenant-a-1',
            'customer_phone' => '628123450003',
        ]))->assertAccepted();

        $this->signedPostJson('/internal/whatsapp/inbound', $this->payload($tenantBAccount, [
            'provider_message_id' => 'wamid.tenant-b-1',
            'customer_phone' => '628123450003',
        ]))->assertAccepted();

        $tenantAConversation = Conversation::query()
            ->where('tenant_id', $tenantAAccount->tenant_id)
            ->firstOrFail();
        $tenantBConversation = Conversation::query()
            ->where('tenant_id', $tenantBAccount->tenant_id)
            ->firstOrFail();
        $tenantALead = LeadProfile::query()
            ->where('tenant_id', $tenantAAccount->tenant_id)
            ->firstOrFail();
        $tenantBLead = LeadProfile::query()
            ->where('tenant_id', $tenantBAccount->tenant_id)
            ->firstOrFail();

        $this->assertNotSame($tenantAConversation->id, $tenantBConversation->id);
        $this->assertNotSame($tenantALead->id, $tenantBLead->id);
        $this->assertNull(app(TenantConversationReader::class)->find($tenantAAccount->tenant_id, $tenantBConversation->id));
        $this->assertNull(app(TenantLeadProfileReader::class)->find($tenantAAccount->tenant_id, $tenantBLead->id));
        $this->assertTrue(app(TenantConversationReader::class)->find($tenantAAccount->tenant_id, $tenantAConversation->id)->is($tenantAConversation));
        $this->assertTrue(app(TenantLeadProfileReader::class)->find($tenantAAccount->tenant_id, $tenantALead->id)->is($tenantALead));
    }

    private function createWaAccount(string $slug): WAAccount
    {
        $tenant = Tenant::query()->create([
            'name' => 'Tenant '.$slug,
            'slug' => 'tenant-'.$slug,
            'status' => Tenant::STATUS_TRIAL,
        ]);

        return WAAccount::query()->create([
            'tenant_id' => $tenant->id,
            'phone_number' => '62811'.str_pad((string) $tenant->id, 8, '0', STR_PAD_LEFT),
            'display_name' => 'Tenant '.$slug,
            'status' => WAAccount::STATUS_CONNECTED,
        ]);
    }

    private function payload(WAAccount $waAccount, array $overrides = []): array
    {
        return array_merge([
            'wa_account_id' => $waAccount->id,
            'provider_message_id' => 'wamid.default-'.$waAccount->id,
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
