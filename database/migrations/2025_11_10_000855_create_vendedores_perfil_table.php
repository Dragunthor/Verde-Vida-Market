<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendedores_perfil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->text('direccion')->nullable();
            $table->enum('metodos_entrega', ['recogida', 'delivery', 'ambos'])->default('recogida');
            $table->string('horario_atencion')->nullable();
            $table->integer('calificacion_promedio')->default(0);
            $table->integer('total_ventas')->default(0);
            $table->boolean('activo_vendedor')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendedores_perfil');
    }
};