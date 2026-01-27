<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria' => 'nullable|string|max:100',
            'numero_piezas' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'proveedor' => 'nullable|string|max:255',
            'peso' => 'nullable|numeric|min:0',
            'dimensiones' => 'nullable|string|max:100',
            'codigo_barras' => 'nullable|string|max:100'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('productos', 'public');
        }

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'foto' => $fotoPath,
            'marca' => $request->has('marca'),
            'disponible' => $request->has('disponible'),
            'numero_piezas' => $request->numero_piezas,
            'stock' => $request->stock,
            'categoria' => $request->categoria,
            'proveedor' => $request->proveedor,
            'peso' => $request->peso,
            'dimensiones' => $request->dimensiones,
            'codigo_barras' => $request->codigo_barras
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente!');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria' => 'nullable|string|max:100',
            'numero_piezas' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'proveedor' => 'nullable|string|max:255',
            'peso' => 'nullable|numeric|min:0',
            'dimensiones' => 'nullable|string|max:100',
            'codigo_barras' => 'nullable|string|max:100'
        ]);

        $fotoPath = $producto->foto;
        if ($request->hasFile('foto')) {
            if ($producto->foto) {
                Storage::disk('public')->delete($producto->foto);
            }
            $fotoPath = $request->file('foto')->store('productos', 'public');
        }

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'foto' => $fotoPath,
            'marca' => $request->has('marca'),
            'disponible' => $request->has('disponible'),
            'numero_piezas' => $request->numero_piezas,
            'stock' => $request->stock,
            'categoria' => $request->categoria,
            'proveedor' => $request->proveedor,
            'peso' => $request->peso,
            'dimensiones' => $request->dimensiones,
            'codigo_barras' => $request->codigo_barras
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente!');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->foto) {
            Storage::disk('public')->delete($producto->foto);
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente!');
    }
}
