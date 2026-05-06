<?php

use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAOutboundMessage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_outbound_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignId('wa_account_id')->constrained('wa_accounts')->cascadeOnDelete();
            $table->string('customer_phone', 64);
            $table->string('message_type', 32)->default('text');
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->string('status', 32)->default(WAOutboundMessage::STATUS_QUEUED)->index();
            $table->string('provider_message_id')->nullable()->index();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_outbound_messages');
    }
};
