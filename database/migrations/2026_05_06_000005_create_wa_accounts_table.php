<?php

use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->string('provider', 32)->default(WAAccount::PROVIDER_BAILEYS);
            $table->string('phone_number', 64)->nullable();
            $table->string('display_name')->nullable();
            $table->string('status', 32)->default(WAAccount::STATUS_DISCONNECTED)->index();
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('last_status_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->unique(['provider', 'phone_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_accounts');
    }
};
