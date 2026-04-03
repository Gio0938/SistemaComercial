# 🚀 Sistema de Gestión Comercial - Laravel

![Laravel](https://img.shields.io/badge/Laravel-10-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat-square&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Status](https://img.shields.io/badge/Status-Production%20Ready-success?style=flat-square)

---

## 📋 Descripción

Sistema de gestión comercial desarrollado en Laravel que permite administrar **productos, servicios, promociones, ventas y servicios técnicos** de forma eficiente.  
Incluye panel administrativo, sistema de reportes, punto de venta (POS) y página pública para clientes.

---

## ✨ Características Principales

### 🛠️ Módulos del Sistema

| Módulo | Descripción |
|--------|-------------|
| 📦 Productos | CRUD con control de inventario, stock, marcas e imágenes |
| 🔧 Servicios | CRUD con tipos (Interno, Externo, Domicilio, Online) |
| 🏷️ Promociones | Descuentos (porcentaje, fijo, 2x1, 3x2, envío gratis) |
| 🛒 Punto de Venta (POS) | Carrito dinámico y cálculo de garantía |
| 🔧 Servicios Técnicos | Órdenes preventivas y correctivas |
| 📊 Dashboard | Métricas en tiempo real |
| 📈 Reportes | Exportación a PDF |
| 🌐 Página Pública | Catálogo dinámico |
| 👥 Usuarios | Roles: Admin y Empleado |

---

## 🎨 Interfaz de Usuario

- Diseño moderno con Bootstrap 5  
- Subida de imágenes con vista previa  
- Tablas con búsqueda y paginación  
- Notificaciones y modales  
- Validación en tiempo real  
- Carrito interactivo  

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

### Requisitos

- PHP >= 8.1  
- Composer >= 2.5  
- MySQL >= 8.0  
- Node.js >= 18  
- Git  

### Pasos

```bash
git clone https://github.com/Gio0938/gestion-comercial.git
cd gestion-comercial

composer install

npm install
npm run build

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan storage:link

php artisan serve
```

### Acceso

| URL | Descripción |
|-----|------------|
| http://localhost:8000 | Página pública |
| http://localhost:8000/login | Panel administrativo |

**Credenciales:**
- Email: admin@empresa.com  
- Contraseña: password  

---

## ⚙️ Configuración

### Variables `.env`

```env
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
```

---

## 📚 Guía de Uso

### Panel Administrativo

| Sección | Función |
|--------|--------|
| Dashboard | Estadísticas |
| Productos | Gestión de productos |
| Servicios | Gestión de servicios |
| Promociones | Gestión de descuentos |
| POS | Ventas |
| Servicios Técnicos | Órdenes |
| Reportes | Exportación |

---

## 📁 Estructura del Proyecto

```
app/
 ├── Http/Controllers/
 ├── Models/
database/
resources/views/
routes/web.php
storage/
public/
```

---

## 🗄️ Base de Datos

Tablas principales:

- usuarios  
- clientes  
- productos  
- servicios  
- promociones  
- ventas  
- ventas_detalles  
- ordenes_servicio  
- ordenes_servicio_detalles  

---

## 🔌 API

### Login

```json
POST /api/login
{
  "email": "admin@empresa.com",
  "password": "password"
}
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 🚀 Producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔒 Seguridad

- Hash de contraseñas (bcrypt)  
- Protección CSRF  
- Validación de datos  
- Protección contra XSS  
- Roles de usuario  

---

## 🤝 Contribución

1. Fork del proyecto  
2. Crear rama  
3. Commit  
4. Push  
5. Pull Request  

---

## 📄 Licencia

MIT

---

## 📞 Contacto

**Giovani Rojas**  
GitHub: https://github.com/Gio0938  

---

## 📊 Estado

| Módulo | Estado |
|--------|--------|
| Productos | ✅ |
| Servicios | ✅ |
| Promociones | ✅ |
| POS | ✅ |
| Servicios Técnicos | ✅ |
| Reportes | ✅ |

---

⭐ Si te sirve el proyecto, dale estrella en GitHub.
