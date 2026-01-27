<?php
namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo_servicio' => 'required|in:Interno,Externo,Domicilio,Online',
            'categoria' => 'nullable|string|max:100',
            'duracion' => 'nullable|numeric|min:0',
            'personal_requerido' => 'nullable|integer|min:0'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('servicios', 'public');
        }

        Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'foto' => $fotoPath,
            'tipo_servicio' => $request->tipo_servicio,
            'disponible' => $request->has('disponible'),
            'categoria' => $request->categoria,
            'duracion' => $request->duracion,
            'personal_requerido' => $request->personal_requerido,
            'materiales_incluidos' => $request->has('materiales_incluidos'),
            'garantia' => $request->has('garantia')
        ]);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio creado exitosamente!');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo_servicio' => 'required|in:Interno,Externo,Domicilio,Online',
            'categoria' => 'nullable|string|max:100',
            'duracion' => 'nullable|numeric|min:0',
            'personal_requerido' => 'nullable|integer|min:0'
        ]);

        $fotoPath = $servicio->foto;
        if ($request->hasFile('foto')) {
            if ($servicio->foto) {
                Storage::disk('public')->delete($servicio->foto);
            }
            $fotoPath = $request->file('foto')->store('servicios', 'public');
        }

        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'foto' => $fotoPath,
            'tipo_servicio' => $request->tipo_servicio,
            'disponible' => $request->has('disponible'),
            'categoria' => $request->categoria,
            'duracion' => $request->duracion,
            'personal_requerido' => $request->personal_requerido,
            'materiales_incluidos' => $request->has('materiales_incluidos'),
            'garantia' => $request->has('garantia')
        ]);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado exitosamente!');
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->foto) {
            Storage::disk('public')->delete($servicio->foto);
        }

        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado exitosamente!');
    }
}
