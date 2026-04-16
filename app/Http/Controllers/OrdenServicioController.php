<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use App\Models\OrdenServicioDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Marca;
use App\Models\Modelo;

class OrdenServicioController extends Controller
{
    public function index()
    {
        // ✅ Cargar detalles para calcular totales preventivo/correctivo en la vista
        $ordenes = OrdenServicio::with('detalles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ordenes.index', compact('ordenes'));
    }

    public function create()
    {
        $ultimoFolio = OrdenServicio::max('folio');
        $nuevoFolio  = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 6, '0', STR_PAD_LEFT) : '000001';
        $marcas      = Marca::orderBy('nombre')->get();

        return view('ordenes.create', compact('nuevoFolio', 'marcas'));
    }

    public function getMarcasPorTipo(Request $request)
    {
        $marcas = Marca::where('tipo_equipo', $request->tipo_equipo)
            ->orderBy('nombre')
            ->get(['idmarca', 'nombre']);

        return response()->json($marcas);
    }

    public function getModelosPorMarca(Request $request)
    {
        $modelos = Modelo::where('idmarca', $request->idmarca)
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

        $marcas     = Marca::orderBy('nombre')->get();
        $nuevoFolio = $orden->folio;

        // ✅ Construir carrito con TODOS los campos que necesita el blade JS
        $carritoExistente = [];
        foreach ($orden->detalles as $detalle) {
            $carritoExistente[] = [
                'tipo'             => $detalle->tipo,           // 'preventivo' o 'correctivo'
                'tipo_mostrar'     => $detalle->tipo === 'preventivo' ? 'Preventivo' : 'Correctivo',
                'servicio_nombre'  => $detalle->servicio_nombre,
                'costo_hr'         => (float) $detalle->costo_hr,
                'horas'            => (float) $detalle->horas,
                'refaccion_nombre' => $detalle->refaccion_nombre,
                'costo_refaccion'  => (float) $detalle->costo_refaccion,
                'diagnostico'      => $detalle->diagnostico ?? '',
                'subtotal'         => (float) $detalle->subtotal,
            ];
        }

        return view('ordenes.create', compact('orden', 'marcas', 'nuevoFolio', 'carritoExistente'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string',
            'equipo_tipo'    => 'required',
            'detalles'       => 'required|array|min:1',
            'total'          => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $orden = OrdenServicio::create([
                'folio'            => $request->folio,
                'fecha'            => now(),
                'tecnico_nombre'   => Auth::user()->name,
                'tecnico_email'    => Auth::user()->email,
                'cliente_nombre'   => $request->cliente_nombre,
                'cliente_rfc'      => $request->cliente_rfc,
                'cliente_email'    => $request->cliente_email,
                'cliente_telefono' => $request->cliente_telefono,
                'equipo_tipo'      => $request->equipo_tipo,
                'equipo_marca'     => $request->equipo_marca,
                'equipo_modelo'    => $request->equipo_modelo,
                'equipo_serie'     => $request->equipo_serie,
                'especificaciones' => $request->especificaciones,
                'diagnostico'      => $request->diagnostico,
                'estado'           => 'Pendiente',
                'total'            => $request->total,
            ]);

            foreach ($request->detalles as $detalle) {
                OrdenServicioDetalle::create([
                    'idorden'          => $orden->idorden,
                    'tipo'             => strtolower($detalle['tipo']),
                    'servicio_nombre'  => $detalle['servicio_nombre'],
                    'costo_hr'         => $detalle['costo_hr'],
                    'horas'            => $detalle['horas'],
                    'refaccion_nombre' => $detalle['refaccion_nombre'] ?? null,
                    'costo_refaccion'  => $detalle['costo_refaccion'] ?? 0,
                    'diagnostico'      => $detalle['diagnostico'] ?? null,  // ✅ guardar diagnóstico por detalle
                    'subtotal'         => $detalle['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Orden guardada',
                'orden_id' => $orden->idorden,
                'folio'    => $orden->folio,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $orden = OrdenServicio::findOrFail($id);

            if (!Auth::user()->esAdmin()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            DB::beginTransaction();

            // Eliminar detalles actuales
            $orden->detalles()->delete();

            // Insertar nuevos detalles con subtotal recalculado
            $total = 0;
            foreach ($request->detalles as $detalle) {
                $subtotal = ((float) $detalle['costo_hr'] * (float) $detalle['horas'])
                    + ((float) ($detalle['costo_refaccion'] ?? 0));
                $total   += $subtotal;

                OrdenServicioDetalle::create([
                    'idorden'          => $orden->idorden,
                    'tipo'             => strtolower($detalle['tipo']),  // ✅ normalizar a minúscula
                    'servicio_nombre'  => $detalle['servicio_nombre'],
                    'costo_hr'         => $detalle['costo_hr'],
                    'horas'            => $detalle['horas'],
                    'refaccion_nombre' => $detalle['refaccion_nombre'] ?? null,
                    'costo_refaccion'  => $detalle['costo_refaccion'] ?? 0,
                    'diagnostico'      => $detalle['diagnostico'] ?? null,  // ✅ guardar diagnóstico por detalle
                    'subtotal'         => $subtotal,
                ]);
            }

            // Actualizar todos los campos de la orden
            $orden->update([
                'estado'           => $request->estado ?? $orden->estado,
                'cliente_nombre'   => $request->cliente_nombre,
                'cliente_rfc'      => $request->cliente_rfc,
                'cliente_telefono' => $request->cliente_telefono,
                'cliente_email'    => $request->cliente_email,
                'equipo_tipo'      => $request->equipo_tipo,
                'equipo_marca'     => $request->equipo_marca,
                'equipo_modelo'    => $request->equipo_modelo,
                'equipo_serie'     => $request->equipo_serie,
                'especificaciones' => $request->especificaciones,
                'diagnostico'      => $request->diagnostico,
                'total'            => $total,
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Orden actualizada exitosamente',
                'folio'    => $orden->folio,
                'orden_id' => $orden->idorden,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
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
        $nuevoFolio  = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 6, '0', STR_PAD_LEFT) : '000001';

        return response()->json(['folio' => $nuevoFolio]);
    }

    public function destroy($id)
    {
        try {
            $orden = OrdenServicio::findOrFail($id);

            if (!Auth::user()->esAdmin()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            $orden->detalles()->delete();
            $orden->delete();

            return response()->json(['success' => true, 'message' => 'Orden eliminada']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
