@extends('layouts.public')

@section('title', $producto->nombre . ' - Gestión Comercial')

@section('content')
    <section class="container py-5">
        <div class="row">
            <div class="col-md-6">
                @if($producto->foto)
                    <img src="{{ asset('storage/' . $producto->foto) }}" class="img-fluid rounded" alt="{{ $producto->nombre }}">
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-box fa-5x text-white"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <h1>{{ $producto->nombre }}</h1>
                <p class="text-muted">{{ $producto->categoria ?? 'Sin categoría' }}</p>
                <h2 class="text-success">${{ number_format($producto->precio, 2) }}</h2>
                <p><strong>Stock disponible:</strong> {{ $producto->stock }} unidades</p>
                <hr>
                <h4>Descripción</h4>
                <p>{{ $producto->descripcion }}</p>
                <a href="/tienda/productos" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Productos
                </a>
            </div>
        </div>
    </section>
@endsection
