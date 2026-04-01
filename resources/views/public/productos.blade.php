@extends('layouts.public')

@section('title', 'Productos - Gestión Comercial')

@section('content')
    <section class="container py-5">
        <h1 class="text-center mb-4">Nuestros Productos</h1>
        <p class="text-center text-muted mb-5">Descubre nuestra amplia variedad de productos de calidad</p>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex flex-wrap">
                    <a href="/tienda/productos" class="category-badge {{ !request('categoria') ? 'bg-primary text-white' : '' }}">
                        Todos
                    </a>
                    @foreach($categorias as $cat)
                        <a href="/tienda/productos?categoria={{ $cat }}"
                           class="category-badge {{ request('categoria') == $cat ? 'bg-primary text-white' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <form method="GET" action="/tienda/productos">
                    <div class="input-group">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar productos..." value="{{ request('buscar') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Productos -->
        <div class="row">
            @forelse($productos as $producto)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($producto->foto)
                            <img src="{{ asset('storage/' . $producto->foto) }}" class="card-img-top" alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-box fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($producto->descripcion, 60) }}</p>
                            <p class="card-price">${{ number_format($producto->precio, 2) }}</p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="/tienda/productos/{{ $producto->idprod }}" class="btn btn-primary w-100">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <h3>No hay productos</h3>
                </div>
            @endforelse
        </div>

        {{ $productos->links() }}
    </section>
@endsection
