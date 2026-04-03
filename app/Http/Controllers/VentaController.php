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

        // Obtener TODOS los productos
        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->select('idprod', 'nombre', 'precio', 'stock', 'categoria', 'tipo_producto')
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        // ========== CATEGORÍAS PARA PERIFÉRICOS (tipo_producto = 'periferico') ==========
        $categoriasPeriferico = Producto::select('categoria')
            ->where('tipo_producto', 'periferico')
            ->whereNotNull('categoria')
            ->where('disponible', true)
            ->where('stock', '>', 0)
            ->distinct()
            ->pluck('categoria');

        // ========== TIPOS DE EQUIPO (tipo_producto = 'equipo') ==========
        $tiposEquipo = Producto::select('categoria')
            ->where('tipo_producto', 'equipo')
            ->whereNotNull('categoria')
            ->where('disponible', true)
            ->where('stock', '>', 0)
            ->distinct()
            ->pluck('categoria');

        // Generar folio único
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

// API para obtener productos por categoría (PERIFÉRICOS)
    public function getProductosPorCategoria(Request $request)
    {
        $categoria = $request->categoria;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->where('tipo_producto', 'periferico');  // ← Solo periféricos

        if ($categoria && $categoria !== 'todas') {
            $productos->where('categoria', $categoria);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }

// API para obtener productos por tipo de equipo (EQUIPOS)
    /*public function getProductosPorTipoEquipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->where('tipo_producto', 'equipo');  // ← Solo equipos

        if ($tipoEquipo) {
            $productos->where('categoria', $tipoEquipo);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }*/

    // API: Obtener productos por tipo de equipo (PC Escritorio, Laptop)
   public function getProductosPorTipoEquipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0);

        if ($tipoEquipo) {
            $productos->where('categoria', $tipoEquipo);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'nullable|string',
            'cliente_rfc' => 'nullable|string',
            'cliente_telefono' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.nombre' => 'required|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Crear o buscar cliente
            $cliente = null;
            if ($request->cliente_nombre) {
                $cliente = Cliente::create([
                    'nombre' => $request->cliente_nombre,
                    'rfc' => $request->cliente_rfc,
                    'telefono' => $request->cliente_telefono
                ]);
            }

            // Calcular subtotal e IVA
            $subtotal = $request->total;
            $iva = $subtotal * 0.16;
            $total = $subtotal + $iva;

            // Crear venta
            $venta = Venta::create([
                'folio' => $request->folio,
                'iduser' => Auth::id(),
                'idcliente' => $cliente ? $cliente->idcliente : null,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'tipo_venta' => 'mixta',
                'estado' => 'completada'
            ]);

            // Guardar detalles
            foreach ($request->productos as $item) {
                // Buscar el producto por nombre
                $producto = Producto::where('nombre', $item['nombre'])->first();

                if ($producto) {
                    VentaDetalle::create([
                        'idventa' => $venta->idventa,
                        'item_type' => 'producto',
                        'item_id' => $producto->idprod,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                        'subtotal' => $item['cantidad'] * $item['precio'],
                        'garantia' => $item['garantia'] ?? false,
                        'duracion_garantia' => $item['duracion_garantia'] ?? null,
                        'especificaciones' => $item['especificaciones'] ?? null
                    ]);

                    // Actualizar stock
                    $producto->stock -= $item['cantidad'];
                    $producto->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta->idventa,
                'folio' => $venta->folio
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

    // MÉTODO HISTORIAL CON VERIFICACIÓN DIRECTA
    public function historial()
    {
        // VERIFICACIÓN DIRECTA - Sin middleware
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero');
        }

        // Verificar si el usuario es administrador
        // Ajusta 'admin' según el valor que tengas en tu BD
        if (Auth::user()->rol !== 'admin' && Auth::user()->rol !== 'administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder al historial de ventas. Solo administradores.');
        }

        // Si es admin, mostrar el historial
        $ventas = Venta::with(['cliente', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ventas.historial', compact('ventas'));
    }

    public function nuevoFolio()
    {
        $ultimoFolio = Venta::max('folio');
        $nuevoFolio = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 5, '0', STR_PAD_LEFT) : '00001';

        return response()->json(['folio' => $nuevoFolio]);
    }

    /*public function getProductosPorTipoEquipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0);

        // Filtrar por tipo de equipo (Laptop o Computadora de Escritorio)
        if ($tipoEquipo) {
            $productos->where('categoria', $tipoEquipo);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }*/

    public function misVentas()
    {
        $ventas = Venta::with(['detalles.producto'])
            ->where('iduser', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($ventas);
    }

    /**
     * Mostrar formulario para editar una venta
     */
    /**
     * Mostrar formulario para editar una venta
     */
    public function edit($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);

        // Verificar permisos: solo admin puede editar
        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ventas.historial')->with('error', 'No tienes permiso para editar ventas');
        }

        $empleados = User::all();
        $productos = Producto::where('disponible', true)->get();

        return view('ventas.edit', compact('venta', 'empleados', 'productos'));
    }

    /**
     * Actualizar una venta
     */
    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        // Verificar permisos
        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ventas.historial')->with('error', 'No tienes permiso para editar ventas');
        }

        DB::beginTransaction();

        try {
            // Actualizar datos de la venta
            $venta->update([
                'estado' => $request->estado,
                'iduser' => $request->iduser
            ]);

            // Actualizar cliente
            if ($venta->cliente) {
                $venta->cliente->update([
                    'nombre' => $request->cliente_nombre,
                    'rfc' => $request->cliente_rfc,
                    'telefono' => $request->cliente_telefono
                ]);
            }

            // Eliminar productos marcados
            if ($request->productos_eliminados) {
                $eliminados = json_decode($request->productos_eliminados, true);
                foreach ($eliminados as $idDetalle) {
                    $detalle = VentaDetalle::find($idDetalle);
                    if ($detalle) {
                        // Devolver stock
                        $producto = Producto::find($detalle->item_id);
                        if ($producto) {
                            $producto->stock += $detalle->cantidad;
                            $producto->save();
                        }
                        $detalle->delete();
                    }
                }
            }

            // Actualizar productos existentes
            if ($request->productos) {
                foreach ($request->productos as $idDetalle => $productoData) {
                    $detalle = VentaDetalle::find($idDetalle);
                    if ($detalle) {
                        // Devolver stock anterior
                        $productoAnterior = Producto::find($detalle->item_id);
                        if ($productoAnterior) {
                            $productoAnterior->stock += $detalle->cantidad;
                            $productoAnterior->save();
                        }

                        // Actualizar detalle
                        $detalle->update([
                            'item_id' => $productoData['idprod'],
                            'cantidad' => $productoData['cantidad'],
                            'precio_unitario' => $productoData['precio'],
                            'subtotal' => $productoData['cantidad'] * $productoData['precio']
                        ]);

                        // Restar nuevo stock
                        $productoNuevo = Producto::find($productoData['idprod']);
                        if ($productoNuevo) {
                            $productoNuevo->stock -= $productoData['cantidad'];
                            $productoNuevo->save();
                        }
                    }
                }
            }

            // Agregar nuevos productos
            if ($request->productos_nuevos) {
                foreach ($request->productos_nuevos as $nuevo) {
                    $detalle = VentaDetalle::create([
                        'idventa' => $venta->idventa,
                        'item_type' => 'producto',
                        'item_id' => $nuevo['idprod'],
                        'cantidad' => $nuevo['cantidad'],
                        'precio_unitario' => $nuevo['precio'],
                        'subtotal' => $nuevo['cantidad'] * $nuevo['precio']
                    ]);

                    // Restar stock
                    $producto = Producto::find($nuevo['idprod']);
                    if ($producto) {
                        $producto->stock -= $nuevo['cantidad'];
                        $producto->save();
                    }
                }
            }

            // Recalcular totales de la venta
            $nuevoSubtotal = $venta->detalles()->sum('subtotal');
            $nuevoIva = $nuevoSubtotal * 0.16;
            $nuevoTotal = $nuevoSubtotal + $nuevoIva;

            $venta->update([
                'subtotal' => $nuevoSubtotal,
                'iva' => $nuevoIva,
                'total' => $nuevoTotal
            ]);

            DB::commit();

            return redirect()->route('ventas.historial')->with('success', 'Venta actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una venta
     */
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        // Verificar permisos: solo admin puede eliminar
        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ventas.historial')->with('error', 'No tienes permiso para eliminar ventas');
        }

        // Eliminar detalles primero
        $venta->detalles()->delete();

        // Eliminar la venta
        $venta->delete();

        return redirect()->route('ventas.historial')->with('success', 'Venta eliminada exitosamente');
    }
}
