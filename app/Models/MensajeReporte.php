<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajeReporte extends Model
{
    use HasFactory;

    protected $table = 'mensajes_reportes';

    protected $fillable = [
        'reporte_id',
        'usuario_id',
        'mensaje'
    ];

    public function reporte()
    {
        return $this->belongsTo(Reporte::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}