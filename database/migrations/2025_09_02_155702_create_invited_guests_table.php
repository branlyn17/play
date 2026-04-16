<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invited_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->uuid('access_token')->nullable()->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->unsignedTinyInteger('guest_count')->default(1);
            $table->enum('status', ['pending', 'viewed', 'confirmed', 'declined'])->default('pending');
            $table->string('seat_label')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->ipAddress('last_ip')->nullable();
            $table->string('last_user_agent')->nullable();
            $table->json('response_payload')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['invitation_id', 'status']);
            $table->index(['email', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invited_guests');
    }
};
