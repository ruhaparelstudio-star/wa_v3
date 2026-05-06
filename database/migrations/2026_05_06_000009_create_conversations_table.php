<?php

use App\Modules\Conversation\Models\Conversation;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignId('wa_account_id')->constrained('wa_accounts')->cascadeOnDelete();
            $table->string('customer_phone', 64);
            $table->string('status', 32)->default(Conversation::STATUS_ACTIVE)->index();
            $table->string('current_stage', 64)->default(Conversation::STAGE_NEW_LEAD);
            $table->string('active_goal', 64)->default(Conversation::GOAL_INITIAL_RESPONSE);
            $table->string('agent_mode', 32)->default(Conversation::AGENT_MODE_ACTIVE)->index();
            $table->string('memory_mode', 32)->default(Conversation::MEMORY_MODE_ACTIVE)->index();
            $table->timestamp('last_message_at')->nullable()->index();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('wa_account_id');
            $table->index('customer_phone');
            $table->index(['tenant_id', 'wa_account_id', 'customer_phone', 'status'], 'conversations_active_lookup_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
