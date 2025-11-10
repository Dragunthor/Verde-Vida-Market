<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resenas_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->integer('calificacion');
            $table->text('comentario')->nullable();
            $table->boolean('aprobado')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resenas_productos');
    }
};