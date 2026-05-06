<?php

use App\Modules\Lead\Models\LeadProfile;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->string('customer_phone', 64);
            $table->string('customer_name')->nullable();
            $table->string('source', 32)->default(LeadProfile::SOURCE_WHATSAPP)->index();
            $table->string('lead_temperature', 16)->default(LeadProfile::TEMPERATURE_COLD)->index();
            $table->json('metadata')->nullable();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('customer_phone');
            $table->unique('conversation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_profiles');
    }
};
