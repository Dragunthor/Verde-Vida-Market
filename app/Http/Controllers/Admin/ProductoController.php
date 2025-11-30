<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Usuario;
use App\Helpers\ImageServiceHelper;

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

        $data['vendedor_id'] = auth()->id();
        $data['activo'] = $request->has('activo');
        $data['aprobado'] = $request->has('aprobado');

        // Imagen usando Image Service Helper
        if ($request->hasFile('imagen')) {
            $upload = ImageServiceHelper::getInstance()->upload($request->file('imagen'));
            
            if ($upload['success']) {
                $data['imagen'] = $upload['filename'];
            } else {
                return redirect()->back()
                    ->with('error', 'Error al subir la imagen: ' . $upload['error'])
                    ->withInput();
            }
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

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string',
            'origen' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'vendedor_id' => 'nullable|exists:usuarios,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp,tiff|max:2048'
        ]);

        $data = $request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'unidad', 
            'origen', 'categoria_id', 'vendedor_id'
        ]);

        $data['activo'] = $request->has('activo');
        $data['aprobado'] = $request->has('aprobado');

        if (empty($data['vendedor_id'])) {
            $data['vendedor_id'] = auth()->id();
        }

        // Manejar la imagen usando Image Service Helper
        if ($request->hasFile('imagen')) {
            $upload = ImageServiceHelper::getInstance()->upload($request->file('imagen'));
            
            if ($upload['success']) {
                // Eliminar la imagen anterior si existe
                if ($producto->imagen) {
                    ImageServiceHelper::getInstance()->delete($producto->imagen);
                }
                
                // Guardar la nueva imagen
                $data['imagen'] = $upload['filename'];
            } else {
                return redirect()->back()
                    ->with('error', 'Error al subir la imagen: ' . $upload['error'])
                    ->withInput();
            }
        }

        $producto->update($data);

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
            ImageServiceHelper::getInstance()->delete($producto->imagen);
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