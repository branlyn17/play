<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('event_id')->constrained('templates')->nullOnDelete();
            $table->index(['template_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropIndex(['template_id', 'locale']);
            $table->dropConstrainedForeignId('template_id');
        });
    }
};
