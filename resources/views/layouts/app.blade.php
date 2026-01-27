<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión Comercial')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --light-bg: #f8f9fa;
        }
        .sidebar {
            background: var(--primary);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
        }
        .sidebar .nav-link.active {
            background: var(--success);
        }
        .main-content {
            background: var(--light-bg);
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stat-card {
            text-align: center;
            padding: 20px;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .table-actions {
            white-space: nowrap;
        }
        .checkbox-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .bg-purple { background-color: #6f42c1 !important; }
        .bg-orange { background-color: #fd7e14 !important; }
        .bg-pink { background-color: #e83e8c !important; }
        .bg-teal { background-color: #20c997 !important; }
        .badge-status {
            font-size: 0.75em;
        }
        .quick-actions .btn {
            transition: all 0.3s;
        }
        .quick-actions .btn:hover {
            transform: scale(1.05);
        }
        .list-group-item {
            border: none;
            border-bottom: 1px solid #eee;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar">
            <div class="position-sticky">
                <div class="p-3 text-center">
                    <h4><i class="fas fa-chart-line me-2"></i>Gestión Comercial</h4>
                    <small class="text-light">Sistema Integral</small>
                    <div class="mt-2">
                        <i class="fas fa-user me-1"></i>
                        <span class="text-light">
                            {{ Auth::user()->name ?? 'Usuario' }}
                        </span>
                    </div>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('servicios*') ? 'active' : '' }}" href="{{ route('servicios.index') }}">
                            <i class="fas fa-concierge-bell me-2"></i>Servicios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('productos*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                            <i class="fas fa-box me-2"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('promociones*') ? 'active' : '' }}" href="{{ route('promociones.index') }}">
                            <i class="fas fa-tags me-2"></i>Promociones
                        </a>
                    </li>
                </ul>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 mt-3">
                        <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                    </button>
                </form>

                <!-- Información del Sistema -->
                <div class="p-3 mt-5">
                    <small class="text-light opacity-75">
                        <i class="fas fa-info-circle me-1"></i>
                        Sistema v5.7<br>
                        <i class="fas fa-database me-1"></i>
                        {{ \App\Models\Servicio::count() + \App\Models\Producto::count() + \App\Models\Promocion::count() }} registros
                    </small>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 main-content">
            <div class="p-4">
                <!-- Alertas de Sesión -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-ocultar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });

    // Confirmación para eliminaciones
    function confirmDelete(message = '¿Estás seguro de eliminar este registro?') {
        return confirm(message);
    }
</script>
@stack('scripts')
</body>
</html>
