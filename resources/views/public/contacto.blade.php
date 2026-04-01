@extends('layouts.public')

@section('title', 'Contacto - Gestión Comercial')

@section('content')
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-6">
                <h1 class="mb-4">Contáctanos</h1>
                <p class="lead">Estamos aquí para ayudarte. Contáctanos por cualquier medio.</p>

                <div class="mt-4">
                    <h5><i class="fas fa-map-marker-alt text-primary me-2"></i>Dirección</h5>
                    <p class="text-muted">Av. Principal #123, Ciudad, México</p>

                    <h5><i class="fas fa-phone text-primary me-2"></i>Teléfono</h5>
                    <p class="text-muted">(228) 123-4567</p>

                    <h5><i class="fas fa-envelope text-primary me-2"></i>Email</h5>
                    <p class="text-muted">info@comercial.com</p>

                    <h5><i class="fas fa-clock text-primary me-2"></i>Horario de Atención</h5>
                    <p class="text-muted">Lunes a Viernes: 9:00 AM - 6:00 PM<br>Sábados: 9:00 AM - 2:00 PM</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Envíanos un mensaje</h4>
                        <form action="#" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar Mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
