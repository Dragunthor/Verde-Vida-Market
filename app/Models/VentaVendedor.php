<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaVendedor extends Model
{
    use HasFactory;

    protected $table = 'ventas_vendedor';

    protected $fillable = [
        'vendedor_id',
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_venta',
        'comision_porcentaje',
        'total_vendedor',
        'estado_pago'
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
        'comision_porcentaje' => 'decimal:2',
        'total_vendedor' => 'decimal:2'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function calcularTotalVendedor()
    {
        $comision = ($this->precio_venta * $this->cantidad * $this->comision_porcentaje) / 100;
        return ($this->precio_venta * $this->cantidad) - $comision;
    }
}