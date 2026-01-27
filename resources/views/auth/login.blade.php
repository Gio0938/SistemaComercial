<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: #333;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-container {
            background: #fee;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #721c24;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .error-message:last-child {
            margin-bottom: 0;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e1e1e1;
        }

        .form-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 40px;
        }

        .input-icon::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .email-icon::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z'/%3E%3C/svg%3E");
        }

        .password-icon::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM12 17c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM15.1 8H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z'/%3E%3C/svg%3E");
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <h2>Bienvenido</h2>
        <p>Ingresa a tu cuenta para continuar</p>
    </div>

    @if ($errors->any())
        <div class="error-container">
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <div class="input-icon email-icon">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="tu@correo.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-icon password-icon">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Ingresa tu contraseña"
                    required
                >
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn-login">
                Iniciar Sesión
            </button>
        </div>
    </form>

    <div class="form-footer">
        <a href="{{ route('register') }}">¿No tienes cuenta? Registrate</a>
    </div>
</div>

<script>
    // Efectos adicionales con JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-control');

        inputs.forEach(input => {
            // Efecto al enfocar
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            // Efecto al perder el foco
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Efecto de carga del botón
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            btn.innerHTML = 'Iniciando sesión...';
            btn.disabled = true;
        });
    });
</script>
</body>
</html>
