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
            $table->string('sesion_id');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrito_temporal');
    }
};