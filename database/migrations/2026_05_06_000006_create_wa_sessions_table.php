<?php

use App\Modules\WhatsApp\Models\WASession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wa_account_id')->constrained('wa_accounts')->cascadeOnDelete();
            $table->string('session_key')->unique();
            $table->string('status', 32)->default(WASession::STATUS_DISCONNECTED)->index();
            $table->json('metadata')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->index('wa_account_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_sessions');
    }
};
