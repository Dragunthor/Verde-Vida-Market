<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'estado',
        'total',
        'metodo_pago',
        'notas',
        'fecha_entrega'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha_entrega' => 'datetime'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function ventasVendedor()
    {
        return $this->hasMany(VentaVendedor::class);
    }

    public function resenasProductos()
    {
        return $this->hasMany(ResenaProducto::class);
    }

    public function resenasVendedores()
    {
        return $this->hasMany(ResenaVendedor::class);
    }

    // Scopes
    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Métodos de ayuda
    public function puedeSerCalificado()
    {
        return $this->estado === 'entregado';
    }

    public function productosPorVendedor()
    {
        return $this->detalles->groupBy('producto.vendedor_id');
    }
}