<?php

use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_inbound_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignId('wa_account_id')->constrained('wa_accounts')->cascadeOnDelete();
            $table->string('provider_message_id');
            $table->string('customer_phone', 64);
            $table->string('message_type', 32)->default('text');
            $table->text('body')->nullable();
            $table->json('raw_payload');
            $table->string('processing_status', 32)->default(WAInboundMessage::STATUS_QUEUED)->index();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('provider_message_id');
            $table->index('customer_phone');
            $table->unique(['wa_account_id', 'provider_message_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_inbound_messages');
    }
};
