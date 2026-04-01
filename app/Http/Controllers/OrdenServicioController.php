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
        return view('ordenes.edit', compact('orden'));
    }

    public function update(Request $request, $id)
    {
        $orden = OrdenServicio::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:Pendiente,En Proceso,Completado,Entregado',
            'diagnostico' => 'nullable|string'
        ]);

        $orden->update([
            'estado' => $request->estado,
            'diagnostico' => $request->diagnostico ?? $orden->diagnostico
        ]);

        return redirect()->route('ordenes.show', $orden->idorden)
            ->with('success', 'Orden actualizada exitosamente');
    }

    public function destroy($id)
    {
        $orden = OrdenServicio::findOrFail($id);
        $orden->delete();

        return redirect()->route('ordenes.index')
            ->with('success', 'Orden eliminada exitosamente');
    }

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
}
