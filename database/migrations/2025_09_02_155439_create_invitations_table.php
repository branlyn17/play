<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {

            // Crea la columna 'id' como clave primaria auto-incremental (bigint)
            $table->id();

            // Crea la columna 'event_id' como clave foránea que apunta a 'events.id'
            // Esto indica a qué evento pertenece la invitación.
            // 'onDelete("cascade")' significa que si se borra el evento, todas sus invitaciones se borrarán automáticamente.
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // Columna para el título de la invitación, ej: "Invitación - Boda de Ana & David"
            $table->string('title');

            // Columna de texto para una descripción más larga de la invitación
            // Nullable porque puede que algunas invitaciones no tengan descripción
            $table->text('description')->nullable();


            // Ruta donde se guardará el archivo HTML generado
            // Nullable porque puede que aún no se haya generado el archivo
            $table->string('file_path')->nullable();

            // URL publicada de la invitación si se comparte bajo nuestro dominio
            // Nullable porque la invitación puede estar solo descargable
            $table->string('published_url')->nullable();

            // Columna para el idioma/localización de la invitación
            $table->string('locale', 10)->nullable();     // ej 'es', 'en', 'pt-BR'


            // Monetización
            // Precio en la moneda especificada
            $table->decimal('price', 12, 2)->nullable();

            // Moneda en formato ISO 4217, ej: 'USD', 'EUR', 'MXN'
            $table->string('currency', 3)->nullable(); // ISO 4217

            // Booleano para indicar si la invitación es de pago o gratuita
            $table->boolean('is_paid')->default(false);



            // Booleano para indicar si la invitación está activa o no
            // Por ejemplo, se puede desactivar mientras se edita o si expira
            $table->boolean('is_active')->default(true);


            $table->softDeletes();

            // Crea automáticamente las columnas 'created_at' y 'updated_at'
            // Para llevar registro de cuándo se creó y actualizó la invitación
            $table->timestamps();

            // Índices para optimizar consultas frecuentes
            // $table->index(['event_id', 'is_active']);
            // $table->index(['uuid']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
