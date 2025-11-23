<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'direccion',
        'rol',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function perfilVendedor()
    {
        return $this->hasOne(VendedorPerfil::class, 'usuario_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'vendedor_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

    public function resenasProductos()
    {
        return $this->hasMany(ResenaProducto::class, 'usuario_id');
    }

    public function resenasVendedores()
    {
        return $this->hasMany(ResenaVendedor::class, 'usuario_id');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public function carrito()
    {
        return $this->hasMany(CarritoTemporal::class, 'usuario_id');
    }

    // Scopes
    public function scopeVendedores($query)
    {
        return $query->where('rol', 'vendedor');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Métodos de ayuda
    public function esVendedor()
    {
        return $this->rol === 'vendedor';
    }

    public function esAdmin()
    {
        return $this->rol === 'admin';
    }

    public function esCliente()
    {
        return $this->rol === 'cliente';
    }
    // En Usuario.php
    public function puedeCalificarProducto($productoId)
    {
        // Verificar si el usuario ha comprado este producto y está entregado
        return $this->pedidos()
            ->whereHas('detalles', function($query) use ($productoId) {
                $query->where('producto_id', $productoId);
            })
            ->where('estado', 'entregado')
            ->exists() 
            && !$this->resenasProductos()
                ->where('producto_id', $productoId)
                ->exists();
    }

    public function puedeCalificarVendedor($vendedorId)
    {
        // Verificar si el usuario ha comprado productos de este vendedor
        return $this->pedidos()
            ->whereHas('detalles.producto', function($query) use ($vendedorId) {
                $query->where('vendedor_id', $vendedorId);
            })
            ->where('estado', 'entregado')
            ->exists()
            && !$this->resenasVendedores()
                ->where('vendedor_id', $vendedorId)
                ->exists();
    }
}