<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Productos destacados para el inicio
        $productosDestacados = Producto::where('disponible', true)
            ->where('destacado', true)
            ->limit(4)
            ->get();

        // Servicios destacados para el inicio
        $serviciosDestacados = Servicio::where('disponible', true)
            ->where('destacado', true)
            ->limit(4)
            ->get();

        // Promociones activas
        $promocionesActivas = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->limit(3)
            ->get();

        return view('public.index', compact('productosDestacados', 'serviciosDestacados', 'promocionesActivas'));
    }

    public function productos(Request $request)
    {
        $query = Producto::where('disponible', true);

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Búsqueda
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }

        $productos = $query->orderBy('nombre')->paginate(12);

        // Obtener categorías para el filtro
        $categorias = Producto::where('disponible', true)
            ->select('categoria')
            ->distinct()
            ->pluck('categoria');

        return view('public.productos', compact('productos', 'categorias'));
    }

    public function servicios(Request $request)
    {
        $query = Servicio::where('disponible', true);

        // Filtro por tipo de servicio
        if ($request->filled('tipo')) {
            $query->where('tipo_servicio', $request->tipo);
        }

        // Búsqueda
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }

        $servicios = $query->orderBy('nombre')->paginate(12);

        // Obtener tipos para el filtro
        $tipos = Servicio::where('disponible', true)
            ->select('tipo_servicio')
            ->distinct()
            ->pluck('tipo_servicio');

        return view('public.servicios', compact('servicios', 'tipos'));
    }

    public function productoDetalle($id)
    {
        $producto = Producto::where('disponible', true)->findOrFail($id);

        // Productos relacionados (misma categoría)
        $relacionados = Producto::where('disponible', true)
            ->where('categoria', $producto->categoria)
            ->where('idprod', '!=', $id)
            ->limit(4)
            ->get();

        return view('public.producto-detalle', compact('producto', 'relacionados'));
    }

    public function servicioDetalle($id)
    {
        $servicio = Servicio::where('disponible', true)->findOrFail($id);

        // Servicios relacionados (mismo tipo)
        $relacionados = Servicio::where('disponible', true)
            ->where('tipo_servicio', $servicio->tipo_servicio)
            ->where('idserv', '!=', $id)
            ->limit(4)
            ->get();

        return view('public.servicio-detalle', compact('servicio', 'relacionados'));
    }

    public function nosotros()
    {
        return view('public.nosotros');
    }

    public function contacto()
    {
        return view('public.contacto');
    }
}
