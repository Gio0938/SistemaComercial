# 🚀 Sistema de Gestión Comercial - Laravel

![Laravel](https://img.shields.io/badge/Laravel-10-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat-square&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Status](https://img.shields.io/badge/Status-Production%20Ready-success?style=flat-square)

## 📋 Descripción

Sistema completo de gestión comercial desarrollado en Laravel que permite administrar **productos, servicios, promociones, ventas y servicios técnicos** de manera eficiente. Incluye dashboard administrativo, sistema de reportes, punto de venta (POS) y página pública para clientes.

---

## ✨ Características Principales

### 🛠️ Módulos del Sistema

| Módulo | Descripción |
|--------|-------------|
| **📦 Productos** | CRUD completo con control de inventario, stock, marcas, imágenes |
| **🔧 Servicios** | CRUD completo con tipos (Interno, Externo, Domicilio, Online) |
| **🏷️ Promociones** | Sistema de descuentos (Porcentaje, Fijo, 2x1, 3x2, Envío Gratis) |
| **🛒 Punto de Venta (POS)** | Carrito dinámico, productos periféricos/equipos, cálculo de garantía |
| **🔧 Servicios Técnicos** | Órdenes de servicio con preventivo/correctivo, carrito de servicios |
| **📊 Dashboard** | Estadísticas en tiempo real con métricas clave |
| **📈 Reportes** | Exportación a PDF de productos, servicios, promociones y ventas |
| **🌐 Página Web Pública** | Catálogo dinámico de productos y servicios |
| **👥 Usuarios** | Autenticación con roles (Admin, Empleado) |

### 🎨 Interfaz de Usuario

- ✅ Diseño moderno con Bootstrap 5
- ✅ Checkboxes interactivos para estados
- ✅ Subida de imágenes con vista previa
- ✅ Tablas con paginación y búsqueda
- ✅ Notificaciones y modales de confirmación
- ✅ Formularios con validación en tiempo real
- ✅ Panel de radio buttons dinámicos
- ✅ Carrito de compras interactivo

---

## 🛠️ Stack Tecnológico

### Backend
| Tecnología | Versión |
|------------|---------|
| Laravel | 10.x |
| PHP | 8.1+ |
| MySQL | 8.0+ |
| Eloquent ORM | - |
| Blade | - |
| Laravel DomPDF | ^2.0 |

### Frontend
| Tecnología | Versión |
|------------|---------|
| Bootstrap | 5.3 |
| FontAwesome | 6.0 |
| JavaScript | Vanilla |
| CSS3 | - |

### Herramientas
- Composer
- NPM
- Git
- PHPUnit

---

## 🚀 Instalación Local

### Requisitos Previos

- PHP >= 8.1
- Composer >= 2.5
- MySQL >= 8.0
- Node.js >= 18.x
- Git

### Pasos de Instalación

```bash
# 1. Clonar repositorio
git clone https://github.com/Gio0938/gestion-comercial.git
cd gestion-comercial

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JavaScript
npm install
npm run build

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar base de datos en .env
# DB_DATABASE=gestion_comercial
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Ejecutar migraciones y seeders
php artisan migrate --seed

# 7. Crear enlace simbólico para imágenes
php artisan storage:link

# 8. Iniciar servidor
php artisan serve
```

Acceso al Sistema
URL	Descripción
http://localhost:8000	Página web pública
http://localhost:8000/login	Panel administrativo
Credenciales de acceso:

Email: admin@empresa.com

Contraseña: password

⚙️ Configuración del Sistema
Variables de Entorno (.env)

APP_NAME="Gestión Comercial"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_comercial
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

Configuración de Imágenes

# Crear estructura de carpetas
mkdir -p storage/app/public/{servicios,productos,usuarios}

# Permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

📚 Guía de Uso
Panel Administrativo
Sección	Funcionalidad
Dashboard	Estadísticas generales del sistema
Servicios	CRUD de servicios (crear, editar, eliminar)
Productos	CRUD de productos con control de stock
Promociones	CRUD de promociones con fechas y descuentos
Punto de Venta	Sistema POS con carrito dinámico
Servicios Técnicos	Órdenes de servicio con preventivo/correctivo
Historial de Ventas	Listado de ventas con edición y eliminación
Reportes	Exportación a PDF de todos los módulos
Punto de Venta (POS)
Seleccionar tipo: Periférico o Equipo

Seleccionar producto según categoría

Configurar cantidad y garantía (si aplica)

Agregar al carrito

Procesar venta → genera ticket PDF

Servicios Técnicos
Seleccionar tipo: Preventivo o Correctivo

Completar datos del servicio

Agregar al carrito

Guardar orden → se genera orden de servicio

📁 Estructura de Archivos

gestion-comercial/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ProductoController.php
│   │   ├── ServicioController.php
│   │   ├── PromocionController.php
│   │   ├── VentaController.php
│   │   ├── OrdenServicioController.php
│   │   ├── ReporteController.php
│   │   └── PublicController.php
│   └── Models/
│       ├── User.php
│       ├── Producto.php
│       ├── Servicio.php
│       ├── Promocion.php
│       ├── Venta.php
│       ├── VentaDetalle.php
│       ├── OrdenServicio.php
│       ├── OrdenServicioDetalle.php
│       ├── Cliente.php
│       ├── Marca.php
│       └── Modelo.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── servicios/
│       ├── productos/
│       ├── promociones/
│       ├── ventas/
│       ├── ordenes/
│       ├── reportes/
│       └── public/
├── routes/
│   └── web.php
├── storage/
│   └── app/public/
└── public/

Ejemplo de Modelo


class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idprod';

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'foto',
        'marca', 'disponible', 'stock', 'categoria'
    ];

    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'producto_id');
    }
}

🗄️ Esquema de Base de Datos
Tablas Principales
Tabla	Descripción
usuarios	Usuarios del sistema (admin, empleado)
clientes	Datos de clientes
productos	Catálogo de productos
servicios	Catálogo de servicios
promociones	Promociones y descuentos
ventas	Cabecera de ventas
ventas_detalles	Detalle de productos vendidos
ordenes_servicio	Cabecera de órdenes de servicio
ordenes_servicio_detalles	Detalle de servicios realizados
marcas	Marcas de equipos
modelos	Modelos de equipos por marca


Diagrama de Relaciones

usuarios ──┐
          ├── ventas ── ventas_detalles ── productos
clientes ─┘

usuarios ──┐
          ├── ordenes_servicio ── ordenes_servicio_detalles
clientes ─┘

productos ──┐
           ├── promociones
servicios ─┘

🔌 Endpoints API
Autenticación

POST /api/login
Content-Type: application/json

{
    "email": "admin@empresa.com",
    "password": "password"
}

Productos

GET    /api/productos     # Listar productos
GET    /api/productos/{id} # Ver producto

Servicios

GET    /api/servicios     # Listar servicios
GET    /api/servicios/{id} # Ver servicio

🧪 Testing y Calidad


# Ejecutar todos los tests
php artisan test

# Tests de características
php artisan test --testsuite=Feature

# Tests unitarios
php artisan test --testsuite=Unit

🚀 Despliegue en Producción
Servidor Recomendado
SO: Ubuntu 22.04 LTS

Web Server: Nginx

PHP: PHP-FPM 8.2

Database: MySQL 8.0

Cache: Redis

Pasos de Despliegue

# 1. Optimizar aplicación
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Configurar permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 3. Configurar variables de producción
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

🔒 Mejores Prácticas de Seguridad
✅ Autenticación con hash bcrypt

✅ Protección CSRF en formularios

✅ Validación de entrada de datos

✅ SQL injection protection (Eloquent)

✅ XSS protection (Blade escaping)

✅ Rate limiting en rutas sensibles

✅ Permisos por roles (Admin/Empleado)

🤝 Guía de Contribución
Fork el proyecto

Crear rama de feature (git checkout -b feature/nueva-funcionalidad)

Commit cambios (git commit -m 'Agregar nueva funcionalidad')

Push a la rama (git push origin feature/nueva-funcionalidad)

Abrir Pull Request

📄 Licencia
Este proyecto está bajo la Licencia MIT. Ver archivo LICENSE para más detalles.

📞 Contacto y Soporte
Desarrollador
Nombre: Giovani Rojas

GitHub: @Gio0938

Email: giovani.rojas@empresa.com

Soporte Técnico
Issues: GitHub Issues

Documentación: Wiki del Proyecto

🙏 Agradecimientos
Laravel Community - Por el increíble framework

Bootstrap Team - Por el sistema de componentes

Contribuidores - Por su valioso tiempo y esfuerzo

📊 Estado del Proyecto
Módulo	Estado
Productos	✅ Completado
Servicios	✅ Completado
Promociones	✅ Completado
Punto de Venta	✅ Completado
Servicios Técnicos	✅ Completado
Reportes	✅ Completado
Página Web Pública	✅ Completado
Autenticación	✅ Completado
⭐ Si este proyecto te fue útil, ¡dale una estrella en GitHub!

https://api.star-history.com/svg?repos=Gio0938/gestion-comercial&type=Date

Desarrollado con ❤️ usando Laravel

