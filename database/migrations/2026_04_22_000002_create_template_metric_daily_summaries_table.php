<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_metric_daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->date('metric_date');
            $table->string('event_type', 24);
            $table->string('locale', 8)->default('');
            $table->string('country_code', 2)->default('');
            $table->string('region_code', 24)->default('');
            $table->string('city')->default('');
            $table->unsignedBigInteger('total')->default(0);
            $table->timestamps();

            $table->unique(
                ['template_id', 'metric_date', 'event_type', 'locale', 'country_code', 'region_code', 'city'],
                'tmpl_metric_daily_unique'
            );
            $table->index(['metric_date', 'event_type'], 'tmpl_metric_daily_date_event_idx');
            $table->index(['country_code', 'region_code', 'city'], 'tmpl_metric_daily_location_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_metric_daily_summaries');
    }
};
