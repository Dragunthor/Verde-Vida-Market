<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageServiceHelper;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function productosActivos()
    {
        return $this->productos()->where('activo', true)->where('aprobado', true);
    }

    // Nuevo: Método para obtener URL de imagen
    public function getImagenUrlAttribute()
    {
        if (!$this->imagen) {
            return null;
        }
        
        // Usar el Image Service Helper para generar la URL
        return ImageServiceHelper::getInstance()->url($this->imagen);
    }

    // Nuevo: Método para obtener URL de imagen con transformación
    public function getImagenThumbnailAttribute()
    {
        if (!$this->imagen) {
            return null;
        }
        
        return ImageServiceHelper::getInstance()->url($this->imagen, 300, 200);
    }
}