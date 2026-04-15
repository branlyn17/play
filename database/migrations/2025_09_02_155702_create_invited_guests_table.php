<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla para los invitados asociados a una invitación específica
        Schema::create('invited_guests', function (Blueprint $table) {

            // Crea la columna 'id' como clave primaria auto-incremental (bigint)
            $table->id();

            // Crea la columna 'invitation_id' como clave foránea que apunta a 'invitations.id'
            // Esto indica a qué invitación pertenece el invitado.
            // 'onDelete("cascade")' significa que si se borra la invitación, todos sus invitados se borrarán automáticamente.
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');

            // Columna para el nombre completo del invitado, ej: "Carlos Pérez"
            $table->string('name');

            // Columna para el correo electrónico del invitado, nullable porque puede no proporcionarlo
            $table->string('email')->nullable();

            // Columna para el número de teléfono del invitado, nullable porque puede no proporcionarlo
            $table->string('phone')->nullable();

            // Estado de la invitación para el invitado
            // 'pending' = invitación enviada pero no respondida
            // 'confirmed' = asistirá
            // 'declined' = no asistirá
            // Por defecto es 'pending'
            $table->enum('status', ['pending', 'confirmed', 'declined'])->default('pending');

            // Asiento asignado al invitado, nullable si no se asigna aún
            $table->string('seat')->nullable();


            // Auditoría / Privacidad
            // Fecha y hora de la última visita del invitado a su invitación
            $table->timestamp('visited_at')->nullable();
            // Fecha y hora en que el invitado respondió (confirmó o declinó)
            $table->timestamp('responded_at')->nullable();
            // Información del último acceso del invitado
            $table->ipAddress('last_ip')->nullable();
            // Información del último agente de usuario (navegador/dispositivo)
            $table->string('last_user_agent')->nullable();

            // Estado lógico para activar/desactivar al invitado sin borrarlo
            $table->softDeletes();

            // Crea automáticamente las columnas 'created_at' y 'updated_at'
            $table->timestamps();

            // Índices para optimizar consultas frecuentes
            // $table->index(['invitation_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invited_guests');
    }
};
