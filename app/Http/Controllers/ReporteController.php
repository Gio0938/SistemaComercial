<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        $stats = [
            'total_servicios' => Servicio::count(),
            'servicios_activos' => Servicio::where('disponible', true)->count(),
            'servicios_destacados' => Servicio::where('destacado', true)->count(),
            'total_productos' => Producto::count(),
            'productos_activos' => Producto::where('disponible', true)->count(),
            'productos_stock_bajo' => Producto::where('stock', '<', 10)->count(),
            'productos_agotados' => Producto::where('stock', 0)->count(),
            'total_promociones' => Promocion::count(),
            'promociones_activas' => Promocion::where('activa', true)
                ->where('fecha_fin', '>=', now())
                ->where('fecha_inicio', '<=', now())
                ->count(),
            'promociones_proximas' => Promocion::where('fecha_inicio', '>', now())->count(),
            'total_ventas' => Venta::count(),
            'ventas_hoy' => Venta::whereDate('created_at', today())->count(),
            'ingresos_totales' => Venta::sum('total'),
            'ingresos_hoy' => Venta::whereDate('created_at', today())->sum('total'),
        ];

        return view('reportes.index', compact('stats'));
    }

    public function servicios(Request $request)
    {
        $query = Servicio::query();

        if ($request->filled('tipo_servicio')) {
            $query->where('tipo_servicio', $request->tipo_servicio);
        }

        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible);
        }

        $servicios = $query->get();

        $estadisticas = [
            'total_activos' => Servicio::where('disponible', true)->count(),
            'total_destacados' => Servicio::where('destacado', true)->count(),
            'precio_promedio' => Servicio::avg('precio'),
            'por_tipo' => Servicio::groupBy('tipo_servicio')
                ->select('tipo_servicio', DB::raw('count(*) as total'))
                ->get(),
        ];

        return view('reportes.servicios', compact('servicios', 'estadisticas'));
    }

    public function productos(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('stock')) {
            if ($request->stock == 'bajo') {
                $query->where('stock', '<', 10);
            } elseif ($request->stock == 'agotado') {
                $query->where('stock', 0);
            }
        }

        $productos = $query->get();

        $estadisticas = [
            'total_valor' => $productos->sum(function($p) { return $p->precio * $p->stock; }),
            'precio_promedio' => $productos->avg('precio'),
            'total_productos' => $productos->count(),
            'productos_stock_bajo' => $productos->where('stock', '<', 10)->count(),
            'productos_agotados' => $productos->where('stock', 0)->count(),
        ];

        $categorias = Producto::select('categoria')->distinct()->pluck('categoria');

        return view('reportes.productos', compact('productos', 'estadisticas', 'categorias'));
    }

    public function promociones(Request $request)
    {
        $query = Promocion::with(['servicio', 'producto']);

        if ($request->filled('tipo_promocion')) {
            $query->where('tipo_promocion', $request->tipo_promocion);
        }

        if ($request->filled('activa')) {
            if ($request->activa == 'si') {
                $query->where('activa', true)
                    ->where('fecha_fin', '>=', now())
                    ->where('fecha_inicio', '<=', now());
            }
        }

        $promociones = $query->get();

        $estadisticas = [
            'activas' => Promocion::where('activa', true)
                ->where('fecha_fin', '>=', now())
                ->where('fecha_inicio', '<=', now())
                ->count(),
            'proximas' => Promocion::where('fecha_inicio', '>', now())->count(),
            'expiradas' => Promocion::where('fecha_fin', '<', now())->count(),
            'por_tipo' => Promocion::groupBy('tipo_promocion')
                ->select('tipo_promocion', DB::raw('count(*) as total'))
                ->get(),
        ];

        return view('reportes.promociones', compact('promociones', 'estadisticas'));
    }

    public function ventas(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario']);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->filled('empleado')) {
            $query->where('iduser', $request->empleado);
        }

        $ventas = $query->orderBy('created_at', 'desc')->paginate(20);

        $estadisticas = [
            'total_ventas' => $query->count(),
            'total_ingresos' => $query->sum('total'),
            'promedio_venta' => $query->avg('total'),
            'ventas_hoy' => Venta::whereDate('created_at', today())->count(),
            'ingresos_hoy' => Venta::whereDate('created_at', today())->sum('total'),
        ];

        $productosMasVendidos = VentaDetalle::select('item_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->where('item_type', 'producto')
            ->groupBy('item_id')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->with('producto')
            ->get();

        $empleados = User::where('rol', 'empleado')->orWhere('rol', 'admin')->get();

        return view('reportes.ventas', compact('ventas', 'estadisticas', 'productosMasVendidos', 'empleados'));
    }

    public function exportarProductosPDF()
    {
        $productos = Producto::all();
        $pdf = Pdf::loadView('reportes.pdf.productos', compact('productos'));
        return $pdf->download('reporte-productos-' . date('Y-m-d') . '.pdf');
    }

    public function exportarVentasPDF(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario']);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $ventas = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reportes.pdf.ventas', compact('ventas'));
        return $pdf->download('reporte-ventas-' . date('Y-m-d') . '.pdf');
    }

    public function exportarServiciosPDF()
    {
        $servicios = Servicio::all();
        $pdf = Pdf::loadView('reportes.pdf.servicios', compact('servicios'));
        return $pdf->download('reporte-servicios-' . date('Y-m-d') . '.pdf');
    }

    public function exportarPromocionesPDF()
    {
        $promociones = Promocion::all();
        $pdf = Pdf::loadView('reportes.pdf.promociones', compact('promociones'));
        return $pdf->download('reporte-promociones-' . date('Y-m-d') . '.pdf');
    }
}
