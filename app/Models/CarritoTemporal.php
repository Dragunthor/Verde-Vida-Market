<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoTemporal extends Model
{
    use HasFactory;

    protected $table = 'carrito_temporal';

    protected $fillable = [
        'sesion_id',
        'usuario_id',
        'producto_id',
        'cantidad'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function subtotal()
    {
        return $this->cantidad * $this->producto->precio;
    }

    public function scopePorSesion($query, $sesionId)
    {
        return $query->where('sesion_id', $sesionId);
    }

    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }
}