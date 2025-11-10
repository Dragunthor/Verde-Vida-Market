<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResenaVendedor extends Model
{
    use HasFactory;

    protected $table = 'resenas_vendedores';

    protected $fillable = [
        'vendedor_id',
        'usuario_id',
        'pedido_id',
        'calificacion',
        'comentario',
        'aprobado'
    ];

    protected $casts = [
        'aprobado' => 'boolean'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
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
}