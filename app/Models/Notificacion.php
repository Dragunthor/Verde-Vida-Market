<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'titulo',
        'mensaje',
        'tipo',
        'leida'
    ];

    protected $casts = [
        'leida' => 'boolean'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('leida', true);
    }

    public function marcarComoLeida()
    {
        $this->update(['leida' => true]);
    }
}