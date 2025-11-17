<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrito_temporal', function (Blueprint $table) {
            $table->id();
            $table->string('sesion_id')->nullable(); // Para usuarios no autenticados
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->timestamps();

            // Índices para mejor performance
            $table->index(['sesion_id', 'usuario_id']);
            $table->unique(['sesion_id', 'producto_id']); // Evitar duplicados por sesión
            $table->unique(['usuario_id', 'producto_id']); // Evitar duplicados por usuario
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrito_temporal');
    }
};