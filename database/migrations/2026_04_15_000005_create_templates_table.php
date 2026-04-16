<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('default_locale', 8)->default('es');
            $table->string('preview_image_path')->nullable();
            $table->string('thumbnail_image_path')->nullable();
            $table->string('source_html_path');
            $table->string('source_css_path')->nullable();
            $table->string('source_js_path')->nullable();
            $table->json('editor_schema')->nullable();
            $table->json('default_content')->nullable();
            $table->json('design_tokens')->nullable();
            $table->json('available_fonts')->nullable();
            $table->json('available_colors')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->unsignedBigInteger('use_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['invitation_category_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
