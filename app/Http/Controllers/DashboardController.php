<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Estadísticas principales
        $totalServicios = Servicio::count();
        $serviciosActivos = Servicio::where('disponible', true)->count();
        $totalProductos = Producto::count();
        $productosEnStock = Producto::where('stock', '>', 0)->count();
        $totalPromociones = Promocion::count();
        $promocionesActivas = Promocion::activas()->count();

        // Datos para gráficos y listas
        $serviciosRecientes = Servicio::latest()->take(5)->get();
        $productosRecientes = Producto::latest()->take(5)->get();
        $promocionesRecientes = Promocion::with(['servicio', 'producto'])->latest()->take(5)->get();

        // Estadísticas por tipo de servicio
        $estadisticasServicios = Servicio::groupBy('tipo_servicio')
            ->selectRaw('tipo_servicio, count(*) as total')
            ->get();

        // Productos con stock bajo
        $productosStockBajo = Producto::where('stock', '<=', 5)->where('stock', '>', 0)->get();

        // Promociones por estado
        $promocionesPorEstado = [
            'activas' => Promocion::activas()->count(),
            'programadas' => Promocion::proximas()->count(),
            'expiradas' => Promocion::expiradas()->count(),
            'inactivas' => Promocion::where('activa', false)->count()
        ];

        return view('dashboard', compact(
            'totalServicios',
            'serviciosActivos',
            'totalProductos',
            'productosEnStock',
            'totalPromociones',
            'promocionesActivas',
            'serviciosRecientes',
            'productosRecientes',
            'promocionesRecientes',
            'estadisticasServicios',
            'productosStockBajo',
            'promocionesPorEstado'
        ));
    }

}
