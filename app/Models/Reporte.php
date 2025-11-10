<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'tipo',
        'objeto_id',
        'titulo',
        'descripcion',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function mensajes()
    {
        return $this->hasMany(MensajeReporte::class);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en_revision');
    }

    public function scopeResueltos($query)
    {
        return $query->where('estado', 'resuelto');
    }
}