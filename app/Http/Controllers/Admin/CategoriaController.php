<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Helpers\ImageServiceHelper;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'required|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $categoriaData = $request->only(['nombre', 'descripcion']);

        if ($request->hasFile('imagen')) {
            $upload = ImageServiceHelper::getInstance()->upload($request->file('imagen'));
            
            if ($upload['success']) {
                $categoriaData['imagen'] = $upload['filename'];
            } else {
                return redirect()->back()
                    ->with('error', 'Error al subir la imagen: ' . $upload['error'])
                    ->withInput();
            }
        }

        Categoria::create($categoriaData);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit($id)
    {
        $categoria = Categoria::withCount('productos')->findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'required|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $categoriaData = $request->only(['nombre', 'descripcion']);

        if ($request->hasFile('imagen')) {
            // Subir nueva imagen
            $upload = ImageServiceHelper::getInstance()->upload($request->file('imagen'));
            
            if ($upload['success']) {
                // Eliminar imagen anterior si existe
                if ($categoria->imagen) {
                    ImageServiceHelper::getInstance()->delete($categoria->imagen);
                }
                $categoriaData['imagen'] = $upload['filename'];
            } else {
                return redirect()->back()
                    ->with('error', 'Error al subir la imagen: ' . $upload['error'])
                    ->withInput();
            }
        }

        $categoria->update($categoriaData);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::withCount('productos')->findOrFail($id);

        // Verificar si hay productos asociados
        if ($categoria->productos_count > 0) {
            return redirect()->route('admin.categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        // Eliminar imagen si existe
        if ($categoria->imagen) {
            ImageServiceHelper::getInstance()->delete($categoria->imagen);
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}