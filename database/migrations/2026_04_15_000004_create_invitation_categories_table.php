<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('invitation_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_category_id')->constrained()->onDelete('cascade');
            $table->string('locale', 8);
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();

            $table->unique(['invitation_category_id', 'locale'], 'icat_tr_locale_unique');
            $table->unique(['locale', 'slug'], 'icat_tr_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_category_translations');
        Schema::dropIfExists('invitation_categories');
    }
};
