<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión Comercial')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #2c3e50;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: var(--primary);
            padding: 15px 0;
            transition: all 0.3s;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--warning) !important;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success);
        }

        /* Botones */
        .btn-primary {
            background: var(--secondary);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .btn-outline-primary:hover {
            background: var(--secondary);
            border-color: var(--secondary);
        }

        /* Footer */
        .footer {
            background: var(--primary);
            color: white;
            padding: 60px 0 20px;
            margin-top: 60px;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Sección de categorías */
        .category-badge {
            display: inline-block;
            padding: 5px 15px;
            background: var(--light);
            color: var(--primary);
            border-radius: 20px;
            font-size: 0.8rem;
            margin-right: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .category-badge:hover {
            background: var(--secondary);
            color: white;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>

    @stack('styles')
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-store me-2"></i>TecnoShop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('nosotros') ? 'active' : '' }}" href="/nosotros">
                        <i class="fas fa-users me-1"></i>Nosotros
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tienda/productos*') ? 'active' : '' }}" href="/tienda/productos">
                        <i class="fas fa-box me-1"></i>Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tienda/servicios*') ? 'active' : '' }}" href="/tienda/servicios">
                        <i class="fas fa-concierge-bell me-1"></i>Servicios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contacto') ? 'active' : '' }}" href="/contacto">
                        <i class="fas fa-envelope me-1"></i>Contacto
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<main style="margin-top: 76px;">
    @yield('content')
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-store me-2"></i>TecnoShop</h5>
                <p class="text-white-50">Tu mejor opción para productos y servicios de calidad. Ofrecemos lo mejor en tecnología y servicios profesionales.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="/"><i class="fas fa-chevron-right me-2"></i>Inicio</a></li>
                    <li><a href="/tienda/productos"><i class="fas fa-chevron-right me-2"></i>Productos</a></li>
                    <li><a href="/tienda/servicios"><i class="fas fa-chevron-right me-2"></i>Servicios</a></li>
                    <li><a href="/contacto"><i class="fas fa-chevron-right me-2"></i>Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contacto</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i>Av. Principal #123, Ciudad</li>
                    <li><i class="fas fa-phone me-2"></i>(228) 123-4567</li>
                    <li><i class="fas fa-envelope me-2"></i>info@comercial.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">&copy; {{ date('Y') }} Gestión Comercial. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
