<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use App\Models\OrdenServicioDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Marca;
use App\Models\User;
use App\Models\Modelo;

class OrdenServicioController extends Controller
{
    public function index()
    {
        $ordenes = OrdenServicio::with('detalles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ordenes.index', compact('ordenes'));
    }

    public function create()
    {
        $ultimoFolio = OrdenServicio::max('folio');
        $nuevoFolio = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 6, '0', STR_PAD_LEFT) : '000001';

        // Obtener todas las marcas (para el select)
        $marcas = Marca::orderBy('nombre')->get();

        return view('ordenes.create', compact('nuevoFolio', 'marcas'));
    }

// API: Obtener marcas por tipo de equipo
    public function getMarcasPorTipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $marcas = Marca::where('tipo_equipo', $tipoEquipo)
            ->orderBy('nombre')
            ->get(['idmarca', 'nombre']);

        return response()->json($marcas);
    }

// API: Obtener modelos por marca
    public function getModelosPorMarca(Request $request)
    {
        $idmarca = $request->idmarca;

        $modelos = Modelo::where('idmarca', $idmarca)
            ->orderBy('nombre')
            ->get(['idmodelo', 'nombre']);

        return response()->json($modelos);
    }



    public function show($id)
    {
        $orden = OrdenServicio::with('detalles')->findOrFail($id);
        return view('ordenes.show', compact('orden'));
    }

    public function edit($id)
    {
        $orden = OrdenServicio::with('detalles')->findOrFail($id);

        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ordenes.index')->with('error', 'No tienes permiso para editar');
        }

        $usuarios = User::all();

        return view('ordenes.edit', compact('orden', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $orden = OrdenServicio::findOrFail($id);

        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ordenes.index')->with('error', 'No tienes permiso para editar');
        }

        DB::beginTransaction();

        try {
            // Actualizar datos principales
            $orden->update([
                'estado' => $request->estado,
                'tecnico_nombre' => $request->tecnico_nombre,
                'cliente_nombre' => $request->cliente_nombre,
                'cliente_rfc' => $request->cliente_rfc,
                'cliente_telefono' => $request->cliente_telefono,
                'cliente_email' => $request->cliente_email,
                'equipo_tipo' => $request->equipo_tipo,
                'equipo_marca' => $request->equipo_marca,
                'equipo_modelo' => $request->equipo_modelo,
                'equipo_serie' => $request->equipo_serie,
                'especificaciones' => $request->especificaciones,
                'diagnostico' => $request->diagnostico
            ]);

            // Eliminar servicios marcados
            if ($request->servicios_eliminados) {
                $eliminados = json_decode($request->servicios_eliminados, true);
                foreach ($eliminados as $idDetalle) {
                    OrdenServicioDetalle::find($idDetalle)->delete();
                }
            }

            // Actualizar servicios existentes
            if ($request->servicios) {
                foreach ($request->servicios as $idDetalle => $data) {
                    $detalle = OrdenServicioDetalle::find($idDetalle);
                    if ($detalle) {
                        $subtotal = ($data['costo_hr'] * $data['horas']) + ($data['costo_refaccion'] ?? 0);
                        $detalle->update([
                            'tipo' => $data['tipo'],
                            'servicio_nombre' => $data['servicio_nombre'],
                            'costo_hr' => $data['costo_hr'],
                            'horas' => $data['horas'],
                            'refaccion_nombre' => $data['refaccion_nombre'] ?? null,
                            'costo_refaccion' => $data['costo_refaccion'] ?? 0,
                            'subtotal' => $subtotal
                        ]);
                    }
                }
            }

            // Agregar nuevos servicios
            if ($request->servicios_nuevos) {
                foreach ($request->servicios_nuevos as $nuevo) {
                    $subtotal = ($nuevo['costo_hr'] * $nuevo['horas']) + ($nuevo['costo_refaccion'] ?? 0);
                    OrdenServicioDetalle::create([
                        'idorden' => $orden->idorden,
                        'tipo' => $nuevo['tipo'],
                        'servicio_nombre' => $nuevo['servicio_nombre'],
                        'costo_hr' => $nuevo['costo_hr'],
                        'horas' => $nuevo['horas'],
                        'refaccion_nombre' => $nuevo['refaccion_nombre'] ?? null,
                        'costo_refaccion' => $nuevo['costo_refaccion'] ?? 0,
                        'subtotal' => $subtotal
                    ]);
                }
            }

            // Recalcular total
            $nuevoTotal = $orden->detalles()->sum('subtotal');
            $orden->update(['total' => $nuevoTotal]);

            DB::commit();

            return redirect()->route('ordenes.show', $orden->idorden)->with('success', 'Orden actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /*public function destroy($id)
    {
        $orden = OrdenServicio::findOrFail($id);
        $orden->delete();

        return redirect()->route('ordenes.index')
            ->with('success', 'Orden eliminada exitosamente');
    }*/

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string',
            'equipo_tipo' => 'required',
            'detalles' => 'required|array|min:1',
            'total' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {
            $orden = OrdenServicio::create([
                'folio' => $request->folio,
                'fecha' => now(),
                'tecnico_nombre' => Auth::user()->name,
                'tecnico_email' => Auth::user()->email,
                'cliente_nombre' => $request->cliente_nombre,
                'cliente_rfc' => $request->cliente_rfc,
                'cliente_email' => $request->cliente_email,
                'cliente_telefono' => $request->cliente_telefono,
                'equipo_tipo' => $request->equipo_tipo,
                'equipo_marca' => $request->equipo_marca,
                'equipo_modelo' => $request->equipo_modelo,
                'equipo_serie' => $request->equipo_serie,
                'especificaciones' => $request->especificaciones,
                'diagnostico' => $request->diagnostico,
                'estado' => 'Pendiente',
                'total' => $request->total
            ]);

            foreach ($request->detalles as $detalle) {
                OrdenServicioDetalle::create([
                    'idorden' => $orden->idorden,
                    'tipo' => strtolower($detalle['tipo']),
                    'servicio_nombre' => $detalle['servicio_nombre'],
                    'costo_hr' => $detalle['costo_hr'],
                    'horas' => $detalle['horas'],
                    'refaccion_nombre' => $detalle['refaccion_nombre'],
                    'costo_refaccion' => $detalle['costo_refaccion'],
                    'subtotal' => $detalle['subtotal']
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Orden guardada']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function misOrdenes()
    {
        try {
            $ordenes = OrdenServicio::withCount('detalles')
                ->where('tecnico_nombre', Auth::user()->name)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return response()->json($ordenes);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function nuevoFolio()
    {
        $ultimoFolio = OrdenServicio::max('folio');
        $nuevoFolio = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 6, '0', STR_PAD_LEFT) : '000001';

        return response()->json(['folio' => $nuevoFolio]);
    }

    public function destroy($id)
    {
        try {
            $orden = OrdenServicio::findOrFail($id);

            // Verificar permisos: solo admin puede eliminar
            if (!Auth::user()->esAdmin()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para eliminar órdenes'], 403);
            }

            // Eliminar detalles primero
            $orden->detalles()->delete();

            // Eliminar la orden
            $orden->delete();

            return response()->json(['success' => true, 'message' => 'Orden eliminada']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
