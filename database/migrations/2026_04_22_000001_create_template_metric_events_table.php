<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_metric_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('invitation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type', 24);
            $table->string('locale', 8)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('region_code', 24)->nullable();
            $table->string('region_name')->nullable();
            $table->string('city')->nullable();
            $table->string('timezone')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('accuracy_radius_km')->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->unsignedTinyInteger('ip_version')->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->string('device_type', 32)->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('platform', 64)->nullable();
            $table->text('referrer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();

            $table->index(['template_id', 'event_type', 'occurred_at'], 'tmpl_metric_template_event_idx');
            $table->index(['country_code', 'region_code', 'city'], 'tmpl_metric_location_idx');
            $table->index(['locale', 'event_type'], 'tmpl_metric_locale_event_idx');
            $table->index(['ip_hash', 'occurred_at'], 'tmpl_metric_ip_time_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_metric_events');
    }
};
