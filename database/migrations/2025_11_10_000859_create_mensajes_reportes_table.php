<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mensajes_reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reportes')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->text('mensaje');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensajes_reportes');
    }
};