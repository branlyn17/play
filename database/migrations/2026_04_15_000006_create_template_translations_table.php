<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->string('locale', 8);
            $table->string('name');
            $table->string('slug');
            $table->string('teaser')->nullable();
            $table->longText('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();

            $table->unique(['template_id', 'locale'], 'tmpl_tr_locale_unique');
            $table->unique(['locale', 'slug'], 'tmpl_tr_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_translations');
    }
};
