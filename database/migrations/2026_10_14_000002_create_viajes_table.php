<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('folio');
            $table->string('cliente');
            $table->string('origen');
            $table->string('destino');
            $table->string('carga')->nullable();
            $table->date('fecha');
            $table->time('hora');
            $table->decimal('espera_hrs', 4, 1)->default(0);
            $table->string('unidad');
            $table->string('chofer');
            $table->decimal('distancia_km', 7, 1);
            $table->decimal('precio', 10, 2);
            $table->decimal('costo_diesel', 10, 2);
            $table->decimal('casetas', 10, 2)->default(0);
            $table->decimal('viatico', 10, 2);
            $table->decimal('desgaste', 10, 2);
            // cotizado → en_ruta → entregado → aprobado → facturado → cobrado
            $table->string('estado')->default('en_ruta');
            $table->unsignedTinyInteger('avance')->default(0);
            $table->string('token_aprobacion', 40)->unique();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamp('aprobado_at')->nullable();
            $table->string('aprobado_por')->nullable();
            $table->timestamp('facturado_at')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->timestamp('cobrado_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'folio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
