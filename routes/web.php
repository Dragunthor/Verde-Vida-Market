<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\Admin\ClienteController;

// Página principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Productos públicos
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// Vendedores públicos
Route::get('/vendedores', [VendedorController::class, 'index'])->name('vendedores.index');
Route::get('/vendedores/{id}', [VendedorController::class, 'show'])->name('vendedores.show');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Perfil
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::post('/carrito/actualizar-todo', [CarritoController::class, 'actualizarTodo'])->name('carrito.actualizar-todo');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    // Pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/checkout', [PedidoController::class, 'checkout'])->name('pedidos.checkout');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{id}/confirmacion', [PedidoController::class, 'confirmacion'])->name('pedidos.confirmacion');
    Route::put('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');

    // Vendedor
    Route::get('/vendedor/solicitud', [VendedorController::class, 'solicitud'])->name('vendedor.solicitud');
    Route::post('/vendedor/solicitud', [VendedorController::class, 'enviarSolicitud'])->name('vendedor.enviarSolicitud');
    
    
    // Rutas de vendedor (las verificaciones van en el controlador)
    Route::get('/vendedor/dashboard', [VendedorController::class, 'dashboard'])->name('vendedor.dashboard');
    Route::get('/vendedor/productos', [VendedorController::class, 'productos'])->name('vendedor.productos');
    Route::get('/vendedor/productos/crear', [VendedorController::class, 'crearProducto'])->name('vendedor.productos.crear');
    Route::post('/vendedor/productos', [VendedorController::class, 'guardarProducto'])->name('vendedor.productos.guardar');
    Route::get('/vendedor/productos/{id}/editar', [VendedorController::class, 'editarProducto'])->name('vendedor.productos.editar');
    Route::put('/vendedor/productos/{id}', [VendedorController::class, 'actualizarProducto'])->name('vendedor.productos.actualizar');
    Route::get('/vendedor/pedidos', [VendedorController::class, 'pedidos'])->name('vendedor.pedidos');
    Route::get('/vendedor/ventas', [VendedorController::class, 'ventas'])->name('vendedor.ventas');

    // Admin - las verificaciones van en el controlador
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Vendedores
        Route::get('/vendedores', [AdminController::class, 'vendedores'])->name('vendedores');
        Route::put('/vendedores/{id}/aprobar', [AdminController::class, 'aprobarVendedor'])->name('vendedores.aprobar');
        Route::get('/vendedores/{id}', [AdminController::class, 'mostrarVendedor'])->name('vendedores.show');
        Route::get('/vendedores/{id}/editar', [AdminController::class, 'editarVendedor'])->name('vendedores.edit');
        Route::put('/vendedores/{id}', [AdminController::class, 'actualizarVendedor'])->name('vendedores.update');
        Route::get('/vendedores/{id}/productos', [AdminController::class, 'productosVendedor'])->name('vendedores.productos');
        
        // Productos
        Route::get('/productos', [AdminProductoController::class, 'index'])->name('productos.index');
        Route::get('/productos/pendientes', [AdminProductoController::class, 'pendientes'])->name('productos.pendientes');
        Route::get('/productos/crear', [AdminProductoController::class, 'create'])->name('productos.create');
        Route::post('/productos', [AdminProductoController::class, 'store'])->name('productos.store');
        Route::get('/productos/{id}/editar', [AdminProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{id}', [AdminProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{id}', [AdminProductoController::class, 'destroy'])->name('productos.destroy');
        Route::put('/productos/{id}/aprobar', [AdminProductoController::class, 'aprobar'])->name('productos.aprobar');
        Route::put('/productos/{id}/toggle-activo', [AdminProductoController::class, 'toggleActivo'])->name('productos.toggle-activo');
        
        // Categorías
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
        Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
        
        // Usuarios
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::put('/clientes/{id}/toggle-activo', [ClienteController::class, 'toggleActivo'])->name('clientes.toggle-activo');
        Route::get('/clientes/{id}/pedidos', [ClienteController::class, 'historialPedidos'])->name('clientes.pedidos');
        
        // Pedidos
        Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('pedidos');
        Route::get('/pedidos/{id}', [AdminController::class, 'mostrarPedido'])->name('pedidos.show');
        Route::put('/pedidos/{id}/actualizar', [AdminController::class, 'actualizarPedido'])->name('pedidos.update');
        
        // Reportes
        Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
        Route::get('/reportes/{id}', [AdminController::class, 'mostrarReporte'])->name('reportes.show');
        Route::put('/reportes/{id}/estado', [AdminController::class, 'actualizarEstadoReporte'])->name('reportes.estado');

        // Reseñas
        Route::get('/resenas', [AdminController::class, 'resenas'])->name('resenas');
        Route::put('/resenas/producto/{id}/aprobar', [AdminController::class, 'aprobarResenaProducto'])->name('resenas.producto.aprobar');
        Route::delete('/resenas/producto/{id}/rechazar', [AdminController::class, 'rechazarResenaProducto'])->name('resenas.producto.rechazar');
        Route::put('/resenas/vendedor/{id}/aprobar', [AdminController::class, 'aprobarResenaVendedor'])->name('resenas.vendedor.aprobar');
        Route::delete('/resenas/vendedor/{id}/rechazar', [AdminController::class, 'rechazarResenaVendedor'])->name('resenas.vendedor.rechazar');
        
        // Configuración
        Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('configuracion');
    });
});