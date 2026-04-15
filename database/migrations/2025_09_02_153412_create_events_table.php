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
        Schema::create('events', function (Blueprint $table) {

            // Crea la columna 'id' como clave primaria auto-incremental (bigint)
            $table->id();

            // Crea la columna 'user_id' como clave foránea que apunta a 'users.id'.
            // Esto indica qué usuario creó el evento.
            // 'onDelete("cascade")' significa que si se borra el usuario, todos sus eventos se borrarán automáticamente.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Columna para el título del evento, ej: "Boda de Ana & David"
            $table->string('title');

            // Columna de texto para una descripción más larga del evento, nullable porque puede no tener descripción.
            $table->text('description')->nullable();

            // Fecha y hora del evento. Ej: '2025-12-14 18:00:00'
            $table->dateTime('event_date');

            // Zona horaria del evento, ej: "America/La_Paz". Importante para manejar zonas horarias
            $table->string('timezone', 64)->default('UTC');

            // Ubicación del evento, ej: "Salón Jardines del Valle, Cochabamba".
            // Nullable porque en algunos casos puede no especificarse.
            $table->string('location')->nullable();

            // Latitud geográfica del evento, formato decimal con 10 dígitos totales y 7 decimales.
            // Nullable si no se quiere especificar coordenadas exactas.
            $table->decimal('latitude', 10, 7)->nullable();

            // Longitud geográfica del evento, formato decimal con 10 dígitos totales y 7 decimales.
            // Nullable igual que la latitud.
            $table->decimal('longitude', 10, 7)->nullable();

            // Capacidad máxima de asistentes, nullable si no hay límite.
            $table->unsignedInteger('capacity')->nullable();

            // Indica si el evento es público (visible para todos) o privado (solo para invitados).
            $table->boolean('is_public')->default(false);

            // Permite marcar eventos como borrados sin eliminarlos físicamente de la base de datos.
            $table->softDeletes(); 

            // Crea automáticamente las columnas 'created_at' y 'updated_at' para llevar registro de cuándo se creó y actualizó el evento.
            $table->timestamps();

            // Índice para optimizar consultas por usuario y fecha del evento.
            // $table->index(['user_id', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
