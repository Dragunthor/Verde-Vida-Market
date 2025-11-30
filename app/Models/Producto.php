<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageServiceHelper;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'vendedor_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'stock',
        'unidad',
        'origen',
        'activo',
        'aprobado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'aprobado' => 'boolean'
    ];

    // Nuevo: Accessor para URL de imagen
    protected $appends = ['imagen_url', 'imagen_thumbnail'];

    // Relaciones (se mantienen igual)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function resenas()
    {
        return $this->hasMany(ResenaProducto::class);
    }

    public function ventasVendedor()
    {
        return $this->hasMany(VentaVendedor::class);
    }

    public function carrito()
    {
        return $this->hasMany(CarritoTemporal::class);
    }

    // Scopes (se mantienen igual)
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeAprobados($query)
    {
        return $query->where('aprobado', true);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopePorVendedor($query, $vendedorId)
    {
        return $query->where('vendedor_id', $vendedorId);
    }

    // Métodos de ayuda (se mantienen igual)
    public function estaDisponible()
    {
        return $this->activo && $this->aprobado && $this->stock > 0;
    }

    public function calificacionPromedio()
    {
        return $this->resenas()->where('aprobado', true)->avg('calificacion') ?: 0;
    }

    public function totalResenas()
    {
        return $this->resenas()->where('aprobado', true)->count();
    }

    // NUEVOS MÉTODOS PARA IMAGEN SERVICE
    public function getImagenUrlAttribute()
    {
        if (!$this->imagen) {
            return null;
        }
        
        return ImageServiceHelper::getInstance()->url($this->imagen);
    }

    public function getImagenThumbnailAttribute()
    {
        if (!$this->imagen) {
            return null;
        }
        
        return ImageServiceHelper::getInstance()->url($this->imagen, 400, 300, 80);
    }

    public function getImagenSmallAttribute()
    {
        if (!$this->imagen) {
            return null;
        }
        
        return ImageServiceHelper::getInstance()->url($this->imagen, 200, 150, 80);
    }
}