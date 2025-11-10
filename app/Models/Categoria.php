<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}