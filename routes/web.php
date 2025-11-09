<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Página principal
Route::get('/', [ProductoController::class, 'index'])->name('home');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Productos
Route::get('/productos', [ProductoController::class, 'catalog'])->name('productos.catalog');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// Carrito
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');

// Pedidos (sin middleware - se maneja manualmente en los controladores)
Route::get('/checkout', [PedidoController::class, 'checkout'])->name('pedidos.checkout');
Route::post('/checkout', [PedidoController::class, 'procesarPedido']);
Route::get('/pedidos/confirmacion/{id}', [PedidoController::class, 'confirmacion'])->name('pedidos.confirmacion');
Route::get('/pedidos/historial', [PedidoController::class, 'historial'])->name('pedidos.historial');
Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');

// Panel de Administración (sin middleware - se maneja manualmente en los controladores)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Productos
    Route::get('/productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::get('/productos/crear', [AdminController::class, 'crearProducto'])->name('admin.productos.crear');
    Route::get('/productos/editar/{id}', [AdminController::class, 'editarProducto'])->name('admin.productos.editar');
    
    // Categorías
    Route::get('/categorias', [AdminController::class, 'categorias'])->name('admin.categorias');
    Route::get('/categorias/crear', [AdminController::class, 'crearCategoria'])->name('admin.categorias.crear');
    Route::get('/categorias/editar/{id}', [AdminController::class, 'editarCategoria'])->name('admin.categorias.editar');
    
    // Pedidos
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');
    Route::get('/pedidos/gestionar/{id}', [AdminController::class, 'gestionarPedido'])->name('admin.pedidos.gestionar');
    
    // Clientes
    Route::get('/clientes', [AdminController::class, 'clientes'])->name('admin.clientes');
});