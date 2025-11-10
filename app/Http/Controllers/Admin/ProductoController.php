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
    public function index(Request $request)
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

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

        // Contador de productos pendientes para el sidebar
        $productosPendientesCount = Producto::where('aprobado', false)->count();

        return view('admin.productos.index', compact(
            'productos', 
            'categorias', 
            'vendedores',
            'productosPendientesCount'
        ));
    }

    public function create()
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        return view('admin.productos.create', compact('categorias', 'vendedores'));
    }

    public function store(Request $request)
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string|max:50',
            'origen' => 'required|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'vendedor_id' => 'nullable|exists:usuarios,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'activo' => 'boolean',
            'aprobado' => 'boolean'
        ]);

        $productoData = $request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'unidad', 'origen', 
            'categoria_id', 'vendedor_id'
        ]);

        // Convertir checkboxes a boolean
        $productoData['activo'] = $request->has('activo');
        $productoData['aprobado'] = $request->has('aprobado');

        // Si no se asigna vendedor, es del administrador
        if (!$request->vendedor_id) {
            $productoData['vendedor_id'] = null;
        }

        if ($request->hasFile('imagen')) {
            $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($productoData);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit($id)
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        return view('admin.productos.edit', compact('producto', 'categorias', 'vendedores'));
    }

    public function update(Request $request, $id)
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string|max:50',
            'origen' => 'required|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'vendedor_id' => 'nullable|exists:usuarios,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'activo' => 'boolean',
            'aprobado' => 'boolean'
        ]);

        $productoData = $request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'unidad', 'origen', 
            'categoria_id', 'vendedor_id'
        ]);

        // Convertir checkboxes a boolean
        $productoData['activo'] = $request->has('activo');
        $productoData['aprobado'] = $request->has('aprobado');

        // Si no se asigna vendedor, es del administrador
        if (!$request->vendedor_id) {
            $productoData['vendedor_id'] = null;
        }

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($productoData);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

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
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $producto = Producto::findOrFail($id);
        $producto->update(['aprobado' => true]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto aprobado exitosamente.');
    }

    public function pendientes()
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $productos = Producto::with(['categoria', 'vendedor'])
            ->where('aprobado', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->get();

        $productosPendientesCount = Producto::where('aprobado', false)->count();

        return view('admin.productos.index', compact(
            'productos', 
            'categorias', 
            'vendedores',
            'productosPendientesCount'
        ));
    }

    public function toggleActivo($id)
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        $producto = Producto::findOrFail($id);
        $producto->update(['activo' => !$producto->activo]);

        $estado = $producto->activo ? 'activado' : 'desactivado';

        return redirect()->route('admin.productos.index')
            ->with('success', "Producto {$estado} exitosamente.");
    }
}