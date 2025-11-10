<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

// Página principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Productos
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// Vendedores
Route::get('/vendedores', [VendedorController::class, 'index'])->name('vendedores.index');
Route::get('/vendedores/{id}', [VendedorController::class, 'show'])->name('vendedores.show');

// Rutas protegidas (solo auth básico)
Route::middleware(['auth'])->group(function () {
    // Perfil
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');

    // Pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

    // Vendedor
    Route::get('/vendedor/solicitud', [VendedorController::class, 'solicitud'])->name('vendedor.solicitud');
    Route::post('/vendedor/solicitud', [VendedorController::class, 'enviarSolicitud'])->name('vendedor.enviarSolicitud');
    
    // Rutas de vendedor - las verificaciones van en el controlador
    Route::get('/vendedor/dashboard', [VendedorController::class, 'dashboard'])->name('vendedor.dashboard');
    Route::get('/vendedor/productos', [VendedorController::class, 'productos'])->name('vendedor.productos');
    Route::get('/vendedor/productos/crear', [VendedorController::class, 'crearProducto'])->name('vendedor.productos.crear');
    Route::post('/vendedor/productos', [VendedorController::class, 'guardarProducto'])->name('vendedor.productos.guardar');
    Route::get('/vendedor/productos/{id}/editar', [VendedorController::class, 'editarProducto'])->name('vendedor.productos.editar');
    Route::put('/vendedor/productos/{id}', [VendedorController::class, 'actualizarProducto'])->name('vendedor.productos.actualizar');
    Route::get('/vendedor/pedidos', [VendedorController::class, 'pedidos'])->name('vendedor.pedidos');
    Route::get('/vendedor/ventas', [VendedorController::class, 'ventas'])->name('vendedor.ventas');

    // Admin - las verificaciones van en el controlador
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/vendedores', [AdminController::class, 'vendedores'])->name('admin.vendedores');
    Route::get('/admin/productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::put('/admin/vendedores/{id}/aprobar', [AdminController::class, 'aprobarVendedor'])->name('admin.vendedores.aprobar');
    Route::put('/admin/productos/{id}/aprobar', [AdminController::class, 'aprobarProducto'])->name('admin.productos.aprobar');
});