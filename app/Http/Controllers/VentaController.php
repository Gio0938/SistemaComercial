<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function create()
    {
        $empleado = Auth::user();

        if (!$empleado) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero');
        }

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->select('idprod', 'nombre', 'precio', 'stock', 'categoria', 'tipo_producto')
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        $categoriasPeriferico = Producto::select('categoria')
            ->where('tipo_producto', 'periferico')
            ->whereNotNull('categoria')
            ->where('disponible', true)
            ->where('stock', '>', 0)
            ->distinct()
            ->pluck('categoria');

        $tiposEquipo = Producto::select('categoria')
            ->where('tipo_producto', 'equipo')
            ->whereNotNull('categoria')
            ->where('disponible', true)
            ->where('stock', '>', 0)
            ->distinct()
            ->pluck('categoria');

        $ultimoFolio = Venta::max('folio');
        $nuevoFolio = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 5, '0', STR_PAD_LEFT) : '00001';

        return view('ventas.pos', compact(
            'productos',
            'categoriasPeriferico',
            'tiposEquipo',
            'nuevoFolio',
            'empleado'
        ));
    }

    public function getProductosPorCategoria(Request $request)
    {
        $categoria = $request->categoria;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->where('tipo_producto', 'periferico');

        if ($categoria && $categoria !== 'todas') {
            $productos->where('categoria', $categoria);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }

    public function getProductosPorTipoEquipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->where('tipo_producto', 'equipo');

        if ($tipoEquipo) {
            $productos->where('categoria', $tipoEquipo);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre'       => 'nullable|string',
            'cliente_rfc'          => 'nullable|string',
            'cliente_telefono'     => 'nullable|string',
            'productos'            => 'required|array|min:1',
            'productos.*.idprod'   => 'required|integer',
            'productos.*.nombre'   => 'required|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio'   => 'required|numeric|min:0',
            'total'                => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            $cliente = null;
            if ($request->cliente_nombre && $request->cliente_nombre !== 'Público en general') {
                $cliente = Cliente::create([
                    'nombre'   => $request->cliente_nombre,
                    'rfc'      => $request->cliente_rfc,
                    'telefono' => $request->cliente_telefono
                ]);
            }

            $subtotal = $request->total;
            $iva      = $subtotal * 0.16;
            $total    = $subtotal + $iva;

            $venta = Venta::create([
                'folio'      => $request->folio,
                'iduser'     => Auth::id(),
                'idcliente'  => $cliente ? $cliente->idcliente : null,
                'subtotal'   => $subtotal,
                'iva'        => $iva,
                'total'      => $total,
                'tipo_venta' => 'mixta',
                'estado'     => 'completada'
            ]);

            foreach ($request->productos as $item) {
                // ✅ CORREGIDO: buscar por idprod, no por nombre
                $producto = Producto::find($item['idprod']);

                if ($producto) {
                    VentaDetalle::create([
                        'idventa'          => $venta->idventa,
                        'item_type'        => 'producto',
                        'item_id'          => $producto->idprod,
                        'cantidad'         => $item['cantidad'],
                        'precio_unitario'  => $item['precio'],
                        'subtotal'         => $item['cantidad'] * $item['precio'],
                        'garantia'         => $item['garantia'] ?? false,
                        'duracion_garantia'=> $item['duracion_garantia'] ?? null,
                        'especificaciones' => $item['especificaciones'] ?? null
                    ]);

                    $producto->stock -= $item['cantidad'];
                    $producto->save();
                }
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Venta registrada exitosamente',
                'venta_id' => $venta->idventa,
                'folio'    => $venta->folio
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ticket($id)
    {
        $venta = Venta::with(['cliente', 'usuario', 'detalles'])->findOrFail($id);
        return view('ventas.ticket', compact('venta'));
    }

    public function historial()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero');
        }

        if (Auth::user()->rol !== 'admin' && Auth::user()->rol !== 'administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder al historial de ventas. Solo administradores.');
        }

        $ventas = Venta::with(['cliente', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ventas.historial', compact('ventas'));
    }

    public function nuevoFolio()
    {
        $ultimoFolio = Venta::max('folio');
        $nuevoFolio  = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 5, '0', STR_PAD_LEFT) : '00001';

        return response()->json(['folio' => $nuevoFolio]);
    }

    public function misVentas()
    {
        $ventas = Venta::with(['detalles.producto'])
            ->where('iduser', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($ventas);
    }

    public function edit($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);

        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ventas.historial')->with('error', 'No tienes permiso');
        }

        // ✅ Incluir productos ya en la venta aunque tengan stock 0
        $productosEnVenta = $venta->detalles->pluck('item_id')->toArray();

        $productos = Producto::where('disponible', true)
            ->where(function ($q) use ($productosEnVenta) {
                $q->where('stock', '>', 0)
                    ->orWhereIn('idprod', $productosEnVenta);
            })
            ->select('idprod', 'nombre', 'precio', 'stock', 'categoria', 'tipo_producto')
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        $categoriasPeriferico = Producto::select('categoria')
            ->where('tipo_producto', 'periferico')
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria');

        $tiposEquipo = Producto::select('categoria')
            ->where('tipo_producto', 'equipo')
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria');

        $empleado    = Auth::user();
        $nuevoFolio  = $venta->folio;

        $carritoExistente = [];
        foreach ($venta->detalles as $detalle) {
            if (!$detalle->producto) continue;

            $esEquipo = in_array($detalle->producto->categoria, ['Laptop', 'Computadora de Escritorio']);

            $carritoExistente[] = [
                'idprod'            => $detalle->item_id,
                'nombre'            => $detalle->producto->nombre,
                'categoria'         => $detalle->producto->categoria,
                'cantidad'          => $detalle->cantidad,
                'precio'            => (float) $detalle->precio_unitario,
                'subtotal'          => (float) $detalle->subtotal,
                'garantia'          => (bool) ($detalle->garantia ?? false),
                'duracion_garantia' => $detalle->duracion_garantia,
                'especificaciones'  => $detalle->especificaciones ?? '',
                'tipo'              => $esEquipo ? 'Equipo' : 'Periférico'
            ];
        }

        return view('ventas.pos', compact(
            'productos',
            'categoriasPeriferico',
            'tiposEquipo',
            'nuevoFolio',
            'empleado',
            'carritoExistente',
            'venta'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $venta = Venta::with('detalles')->findOrFail($id);

            if (!Auth::user()->esAdmin()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            DB::beginTransaction();

            // Devolver stock de los productos actuales antes de reemplazarlos
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->item_id);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }

            // Eliminar detalles actuales
            $venta->detalles()->delete();

            // Guardar nuevos productos
            $subtotal = 0;
            foreach ($request->productos as $item) {
                $producto = Producto::find($item['idprod']);
                if ($producto) {
                    $subtotalItem = $item['cantidad'] * $item['precio'];
                    $subtotal    += $subtotalItem;

                    VentaDetalle::create([
                        'idventa'          => $venta->idventa,
                        'item_type'        => 'producto',
                        'item_id'          => $producto->idprod,
                        'cantidad'         => $item['cantidad'],
                        'precio_unitario'  => $item['precio'],
                        'subtotal'         => $subtotalItem,
                        'garantia'         => $item['garantia'] ?? false,
                        'duracion_garantia'=> $item['duracion_garantia'] ?? null,
                        'especificaciones' => $item['especificaciones'] ?? ''
                    ]);

                    $producto->stock -= $item['cantidad'];
                    $producto->save();
                }
            }

            // Actualizar o crear cliente
            if ($venta->cliente) {
                $venta->cliente->update([
                    'nombre'   => $request->cliente_nombre,
                    'rfc'      => $request->cliente_rfc,
                    'telefono' => $request->cliente_telefono
                ]);
            } elseif ($request->cliente_nombre && $request->cliente_nombre !== 'Público en general') {
                $cliente = Cliente::create([
                    'nombre'   => $request->cliente_nombre,
                    'rfc'      => $request->cliente_rfc,
                    'telefono' => $request->cliente_telefono
                ]);
                $venta->idcliente = $cliente->idcliente;
                $venta->save();
            }

            // Recalcular totales
            $iva   = $subtotal * 0.16;
            $total = $subtotal + $iva;

            $venta->update([
                'subtotal' => $subtotal,
                'iva'      => $iva,
                'total'    => $total
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Venta actualizada exitosamente',
                'folio'    => $venta->folio,
                'venta_id' => $venta->idventa
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ✅ CORREGIDO: retorna JSON en lugar de redirect para que el JS lo maneje
    public function destroy($id)
    {
        $venta = Venta::with('detalles')->findOrFail($id);

        if (!Auth::user()->esAdmin()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
        }

        DB::beginTransaction();

        try {
            // Devolver stock antes de eliminar
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->item_id);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Venta eliminada exitosamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
