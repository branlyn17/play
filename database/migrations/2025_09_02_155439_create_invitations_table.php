<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('locale', 8)->default('es');
            $table->uuid('edit_token')->unique();
            $table->uuid('public_token')->unique();
            $table->string('share_slug')->nullable()->unique();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('customization_data')->nullable();
            $table->json('style_overrides')->nullable();
            $table->json('editor_state')->nullable();
            $table->string('rendered_html_path')->nullable();
            $table->string('rendered_html_checksum', 64)->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['event_id', 'status']);
            $table->index(['locale', 'status']);
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
