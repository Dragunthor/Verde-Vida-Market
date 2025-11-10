<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendedorPerfil extends Model
{
    use HasFactory;

    protected $table = 'vendedores_perfil';

    protected $fillable = [
        'usuario_id',
        'descripcion',
        'direccion',
        'metodos_entrega',
        'horario_atencion',
        'calificacion_promedio',
        'total_ventas',
        'activo_vendedor'
    ];

    protected $casts = [
        'activo_vendedor' => 'boolean'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function productos()
    {
        return $this->hasManyThrough(Producto::class, Usuario::class, 'id', 'vendedor_id', 'usuario_id');
    }

    public function ventas()
    {
        return $this->hasManyThrough(VentaVendedor::class, Usuario::class, 'id', 'vendedor_id', 'usuario_id');
    }

    public function resenas()
    {
        return $this->hasMany(ResenaVendedor::class, 'vendedor_id', 'usuario_id');
    }

    public function actualizarCalificacion()
    {
        $calificacion = $this->resenas()->where('aprobado', true)->avg('calificacion');
        $this->update(['calificacion_promedio' => round($calificacion) ?: 0]);
    }

    public function actualizarTotalVentas()
    {
        $totalVentas = $this->ventas()->count();
        $this->update(['total_ventas' => $totalVentas]);
    }
}