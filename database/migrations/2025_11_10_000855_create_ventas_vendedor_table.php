<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ventas_vendedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('usuarios');
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('comision_porcentaje', 5, 2)->default(10.00);
            $table->decimal('total_vendedor', 10, 2);
            $table->enum('estado_pago', ['pendiente', 'pagado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas_vendedor');
    }
};