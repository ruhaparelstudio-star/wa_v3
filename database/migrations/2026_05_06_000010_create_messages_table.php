<?php

use App\Modules\Conversation\Models\Message;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('wa_inbound_message_id')->nullable()->constrained('wa_inbound_messages')->nullOnDelete();
            $table->string('direction', 16)->index();
            $table->string('message_type', 32)->default(Message::TYPE_TEXT);
            $table->text('body')->nullable();
            $table->string('provider_message_id')->nullable()->index();
            $table->timestamp('occurred_at')->nullable()->index();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('conversation_id');
            $table->unique('wa_inbound_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
