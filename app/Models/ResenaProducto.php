<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResenaProducto extends Model
{
    use HasFactory;

    protected $table = 'resenas_productos';

    protected $fillable = [
        'producto_id',
        'usuario_id',
        'pedido_id',
        'calificacion',
        'comentario',
        'aprobado'
    ];

    protected $casts = [
        'aprobado' => 'boolean'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function scopeAprobados($query)
    {
        return $query->where('aprobado', true);
    }

    public function scopePendientes($query)
    {
        return $query->where('aprobado', false);
    }
}