<?php
// app/Http/Controllers/PromocionController.php
namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Servicio;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promociones = Promocion::with(['servicio', 'producto'])->latest()->get();
        return view('promociones.index', compact('promociones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicios = Servicio::where('disponible', true)->get();
        $productos = Producto::where('disponible', true)->get();
        return view('promociones.create', compact('servicios', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_promocion' => 'required|in:Porcentaje,Fijo,2x1,3x2,Envio Gratis',
            'descuento' => 'nullable|numeric|min:0',
            'precio_promocional' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'limite_usos' => 'nullable|integer|min:1',
            'codigo_promocion' => 'nullable|string|max:100|unique:promociones,codigo_promocion',
            'condiciones' => 'nullable|string',
            'servicio_id' => 'nullable|exists:servicios,idserv',
            'producto_id' => 'nullable|exists:productos,idprod'
        ]);

        // Crear promoción
        Promocion::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo_promocion' => $request->tipo_promocion,
            'descuento' => $request->descuento,
            'precio_promocional' => $request->precio_promocional,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'activa' => $request->has('activa'),
            'limite_usos' => $request->limite_usos,
            'usos_actuales' => 0,
            'codigo_promocion' => $request->codigo_promocion,
            'aplica_todos_servicios' => $request->has('aplica_todos_servicios'),
            'aplica_todos_productos' => $request->has('aplica_todos_productos'),
            'condiciones' => $request->condiciones,
            'servicio_id' => $request->servicio_id,
            'producto_id' => $request->producto_id
        ]);

        return redirect()->route('promociones.index')
            ->with('success', 'Promoción creada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promocion $promocione)
    {
        $promocione->load(['servicio', 'producto']);
        return view('promociones.show', compact('promocione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promocion $promocione)
    {
        $servicios = Servicio::where('disponible', true)->get();
        $productos = Producto::where('disponible', true)->get();
        $promocione->load(['servicio', 'producto']);
        return view('promociones.edit', compact('promocione', 'servicios', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promocion $promocione)
    {
        // Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_promocion' => 'required|in:Porcentaje,Fijo,2x1,3x2,Envio Gratis',
            'descuento' => 'nullable|numeric|min:0',
            'precio_promocional' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'limite_usos' => 'nullable|integer|min:1',
            'codigo_promocion' => 'nullable|string|max:100|unique:promociones,codigo_promocion,' . $promocione->idpromo . ',idpromo',
            'condiciones' => 'nullable|string',
            'servicio_id' => 'nullable|exists:servicios,idserv',
            'producto_id' => 'nullable|exists:productos,idprod'
        ]);

        // Actualizar promoción
        $promocione->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo_promocion' => $request->tipo_promocion,
            'descuento' => $request->descuento,
            'precio_promocional' => $request->precio_promocional,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'activa' => $request->has('activa'),
            'limite_usos' => $request->limite_usos,
            'codigo_promocion' => $request->codigo_promocion,
            'aplica_todos_servicios' => $request->has('aplica_todos_servicios'),
            'aplica_todos_productos' => $request->has('aplica_todos_productos'),
            'condiciones' => $request->condiciones,
            'servicio_id' => $request->servicio_id,
            'producto_id' => $request->producto_id
        ]);

        return redirect()->route('promociones.index')
            ->with('success', 'Promoción actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promocion $promocione)
    {
        $promocione->delete();

        return redirect()->route('promociones.index')
            ->with('success', 'Promoción eliminada exitosamente!');
    }

    /**
     * Activar/Desactivar promoción
     */
    public function toggle(Promocion $promocione)
    {
        $promocione->update(['activa' => !$promocione->activa]);

        $estado = $promocione->activa ? 'activada' : 'desactivada';
        return redirect()->route('promociones.index')
            ->with('success', "Promoción {$estado} exitosamente!");
    }
}
