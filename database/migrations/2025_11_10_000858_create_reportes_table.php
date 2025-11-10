<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->enum('tipo', ['producto', 'vendedor', 'pedido', 'tecnico']);
            $table->integer('objeto_id');
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelto'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};