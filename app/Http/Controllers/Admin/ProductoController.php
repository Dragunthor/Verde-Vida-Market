<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    // Verificación interna para admin
    private function verificarAdmin()
    {
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado. Debes ser administrador.');
        }
        return null;
    }

    public function index(Request $request)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $query = Producto::with(['categoria', 'vendedor']);

        // Aplicar filtros
        if ($request->has('estado') && $request->estado != '') {
            if ($request->estado == 'aprobado') {
                $query->where('aprobado', true);
            } elseif ($request->estado == 'pendiente') {
                $query->where('aprobado', false);
            } elseif ($request->estado == 'inactivo') {
                $query->where('activo', false);
            }
        }

        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->has('vendedor') && $request->vendedor != '') {
            $query->where('vendedor_id', $request->vendedor);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(10);
        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        return view('admin.productos.index', compact(
            'productos', 
            'categorias', 
            'vendedores'
        ));
    }

    public function create()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        return view('admin.productos.create', compact('categorias', 'vendedores'));
    }

    public function edit($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        return view('admin.productos.edit', compact('producto', 'categorias', 'vendedores'));
    }

    public function store(Request $request)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string',
            'origen' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp,tiff|max:2048'
        ]);

        $data = $request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'unidad', 
            'origen', 'categoria_id'
        ]);

        // Usar el ID del admin como vendedor por defecto
        $data['vendedor_id'] = auth()->id();

        // Campos booleanos
        $data['activo'] = $request->has('activo');
        $data['aprobado'] = $request->has('aprobado');

        // Imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function update(Request $request, $id)
{
    $verificacion = $this->verificarAdmin();
    if ($verificacion) return $verificacion;

    $producto = Producto::findOrFail($id);

    \Log::info('=== INICIANDO ACTUALIZACIÓN DE PRODUCTO ===');
    \Log::info('Producto ID: ' . $id);
    \Log::info('Datos del request:', $request->all());
    \Log::info('¿Tiene archivo imagen?: ' . ($request->hasFile('imagen') ? 'Sí' : 'No'));

    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'unidad' => 'required|string',
        'origen' => 'required|string|max:255',
        'categoria_id' => 'required|exists:categorias,id',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp,tiff|max:2048'
    ]);

    // Preparar datos
    $data = $request->only([
        'nombre', 'descripcion', 'precio', 'stock', 'unidad', 
        'origen', 'categoria_id'
    ]);

    // Usar el ID del admin como vendedor por defecto
    $data['vendedor_id'] = auth()->id();

    // Manejar campos booleanos
    $data['activo'] = $request->has('activo');
    $data['aprobado'] = $request->has('aprobado');

    \Log::info('Datos preparados antes de imagen:', $data);

    // Reemplaza solo la sección de manejo de imagen con esto:
if ($request->hasFile('imagen')) {
    error_log('=== PROCESANDO IMAGEN CON MÉTODO ALTERNATIVO ===');
    
    $imagen = $request->file('imagen');
    
    // Crear nombre único
    $nombreArchivo = 'producto_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
    error_log('Nombre archivo: ' . $nombreArchivo);
    
    // Ruta completa
    $rutaDestino = storage_path('app/public/productos/' . $nombreArchivo);
    error_log('Ruta destino: ' . $rutaDestino);
    
    // Mover el archivo manualmente
    try {
        // Asegurar que el directorio existe
        if (!is_dir(storage_path('app/public/productos'))) {
            mkdir(storage_path('app/public/productos'), 0755, true);
        }
        
        // Mover el archivo
        $movido = $imagen->move(storage_path('app/public/productos'), $nombreArchivo);
        
        if ($movido) {
            $data['imagen'] = 'productos/' . $nombreArchivo;
            error_log('Imagen movida exitosamente: ' . $data['imagen']);
            
            // Verificar que el archivo existe
            $existe = file_exists($rutaDestino) ? 'SÍ' : 'NO';
            error_log('¿Archivo existe después de mover?: ' . $existe);
        } else {
            error_log('ERROR: No se pudo mover el archivo');
        }
        
    } catch (\Exception $e) {
        error_log('EXCEPCIÓN al mover imagen: ' . $e->getMessage());
    }
}

    \Log::info('Datos finales para actualizar:', $data);

    // Actualizar el producto
    try {
        $updated = $producto->update($data);
        \Log::info('¿Actualización exitosa?: ' . ($updated ? 'Sí' : 'No'));
        
        // Recargar el producto para ver los cambios
        $producto->refresh();
        \Log::info('Producto después de actualizar - Imagen: ' . $producto->imagen);
        
    } catch (\Exception $e) {
        \Log::error('Error al actualizar producto: ' . $e->getMessage());
    }

    \Log::info('=== FINALIZANDO ACTUALIZACIÓN ===');

    return redirect()->route('admin.productos.edit', $producto->id)
        ->with('success', 'Producto actualizado correctamente.');
}

    public function destroy($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $producto = Producto::findOrFail($id);

        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function aprobar($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $producto = Producto::findOrFail($id);
        $producto->update(['aprobado' => true]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto aprobado exitosamente.');
    }

    public function pendientes()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $productos = Producto::with(['categoria', 'vendedor'])
            ->where('aprobado', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        return view('admin.productos.index', compact(
            'productos', 
            'categorias', 
            'vendedores'
        ));
    }

    public function toggleActivo($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $producto = Producto::findOrFail($id);
        $producto->update(['activo' => !$producto->activo]);

        $estado = $producto->activo ? 'activado' : 'desactivado';

        return redirect()->route('admin.productos.index')
            ->with('success', "Producto {$estado} exitosamente.");
    }
}