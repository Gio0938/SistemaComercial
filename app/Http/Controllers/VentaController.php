<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Cliente;
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
    public function getProductosPorTipoEquipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->where('tipo_producto', 'equipo');  // ← Solo equipos

        if ($tipoEquipo) {
            $productos->where('categoria', $tipoEquipo);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }

    // API: Obtener productos por tipo de equipo (PC Escritorio, Laptop)
   /* public function getProductosPorTipoEquipo(Request $request)
    {
        $tipoEquipo = $request->tipo_equipo;

        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0);

        if ($tipoEquipo) {
            $productos->where('categoria', $tipoEquipo);
        }

        return response()->json($productos->get(['idprod', 'nombre', 'precio', 'stock', 'categoria']));
    }*/

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

}
