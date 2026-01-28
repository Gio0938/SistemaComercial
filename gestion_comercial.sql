-- ------------------------------------------------------------
-- BASE DE DATOS: gestion_comercial
-- VERSIÓN: Optimizada según tus modelos
-- ------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS gestion_comercial 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE gestion_comercial;

-- ------------------------------------------------------------
-- TABLA: servicios (AJUSTADA SEGÚN TU MODELO)
-- ------------------------------------------------------------
CREATE TABLE servicios (
    idserv INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto VARCHAR(255),  -- ✅ Campo foto incluido
    tipo_servicio ENUM('Interno','Externo','Domicilio','Online') NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    categoria VARCHAR(100),
    duracion DECIMAL(5,2),
    personal_requerido INT DEFAULT 1,
    materiales_incluidos TINYINT(1) NOT NULL DEFAULT 0,
    garantia TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (idserv),
    INDEX idx_nombre (nombre),
    INDEX idx_tipo_servicio (tipo_servicio),
    INDEX idx_disponible (disponible),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLA: productos (AJUSTADA SEGÚN TU MODELO)
-- ------------------------------------------------------------
CREATE TABLE productos (
    idprod INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto VARCHAR(255),  -- ✅ Campo foto incluido
    marca TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=Otra marca, 1=Marca propia',
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    numero_piezas INT,
    stock INT NOT NULL DEFAULT 0,
    categoria VARCHAR(100),
    proveedor VARCHAR(255),
    peso DECIMAL(8,2) COMMENT 'Peso en kg',
    dimensiones VARCHAR(100) COMMENT 'Formato: Largo x Ancho x Alto',
    codigo_barras VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (idprod),
    UNIQUE KEY uk_codigo_barras (codigo_barras),
    INDEX idx_nombre (nombre),
    INDEX idx_categoria (categoria),
    INDEX idx_marca (marca),
    INDEX idx_disponible (disponible),
    INDEX idx_stock (stock)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLA: promociones (AJUSTADA SEGÚN TU MODELO)
-- ------------------------------------------------------------
CREATE TABLE promociones (
    idpromo INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    tipo_promocion ENUM('Porcentaje','Fijo','2x1','3x2','Envio Gratis') NOT NULL,
    descuento DECIMAL(8,2) COMMENT 'Porcentaje o monto fijo',
    precio_promocional DECIMAL(10,2),
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1,
    limite_usos INT COMMENT 'NULL = ilimitado',
    usos_actuales INT NOT NULL DEFAULT 0,
    codigo_promocion VARCHAR(100),
    aplica_todos_servicios TINYINT(1) NOT NULL DEFAULT 0,
    aplica_todos_productos TINYINT(1) NOT NULL DEFAULT 0,
    condiciones TEXT,
    servicio_id INT,
    producto_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (idpromo),
    UNIQUE KEY uk_codigo_promocion (codigo_promocion),
    INDEX idx_nombre (nombre),
    INDEX idx_tipo_promocion (tipo_promocion),
    INDEX idx_activa (activa),
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_fecha_fin (fecha_fin),
    INDEX idx_codigo_promocion (codigo_promocion),
    INDEX idx_servicio_id (servicio_id),
    INDEX idx_producto_id (producto_id),
    
    CONSTRAINT fk_promociones_servicio 
        FOREIGN KEY (servicio_id) 
        REFERENCES servicios (idserv) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE,
        
    CONSTRAINT fk_promociones_producto 
        FOREIGN KEY (producto_id) 
        REFERENCES productos (idprod) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLA: usuarios (AJUSTADA SEGÚN TU MODELO)
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,  -- ✅ Cambiado de 'nombre' a 'name'
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'empleado', 'cliente') DEFAULT 'empleado',
    foto VARCHAR(255),  -- ✅ Campo foto opcional
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - SERVICIOS (CON FOTOS)
-- ------------------------------------------------------------
INSERT INTO servicios (nombre, descripcion, precio, foto, tipo_servicio, disponible, categoria, duracion, personal_requerido, materiales_incluidos, garantia) VALUES
('Mantenimiento de Computadoras', 'Servicio completo de mantenimiento preventivo y correctivo para computadoras de escritorio y laptops.', 150.00, 'servicios/mantenimiento.jpg', 'Interno', 1, 'Tecnología', 2.0, 1, 1, 1),
('Instalación de Redes WiFi', 'Instalación y configuración de redes WiFi empresariales y domésticas.', 300.00, 'servicios/redes-wifi.jpg', 'Externo', 1, 'Tecnología', 3.0, 2, 0, 1),
('Asesoría Legal Empresarial', 'Asesoría legal especializada para empresas, contratos y cumplimiento normativo.', 500.00, 'servicios/asesoria-legal.jpg', 'Online', 1, 'Legal', 1.5, 1, 0, 0),
('Limpieza de Oficinas', 'Servicio profesional de limpieza para oficinas y espacios corporativos.', 200.00, 'servicios/limpieza.jpg', 'Domicilio', 1, 'Limpieza', 4.0, 2, 1, 0),
('Desarrollo Web Personalizado', 'Desarrollo de sitios web y aplicaciones web a medida.', 1000.00, 'servicios/desarrollo-web.jpg', 'Online', 1, 'Desarrollo', 40.0, 3, 0, 1),
('Consultoría de Marketing Digital', 'Estrategias de marketing digital y gestión de redes sociales.', 400.00, 'servicios/marketing.jpg', 'Online', 1, 'Marketing', 2.0, 1, 0, 0),
('Traducción de Documentos', 'Traducción profesional de documentos técnicos y comerciales.', 120.00, 'servicios/traduccion.jpg', 'Online', 1, 'Traducción', 1.0, 1, 0, 0),
('Capacitación en Ofimática', 'Cursos de capacitación en Office para empresas.', 250.00, 'servicios/capacitacion.jpg', 'Interno', 1, 'Capacitación', 8.0, 1, 1, 0);

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - PRODUCTOS (CON FOTOS)
-- ------------------------------------------------------------
INSERT INTO productos (nombre, descripcion, precio, foto, marca, disponible, numero_piezas, stock, categoria, proveedor, peso, dimensiones, codigo_barras) VALUES
('Laptop Dell XPS 13', 'Laptop ultradelgada con procesador i7, 16GB RAM, 512GB SSD', 1500.00, 'productos/laptop-dell.jpg', 0, 1, 1, 15, 'Electrónica', 'Dell México', 1.2, '30.2 x 19.9 x 1.5 cm', '7891234567890'),
('Mouse Inalámbrico Logitech', 'Mouse ergonómico inalámbrico con duración de batería de 12 meses', 45.99, 'productos/mouse-logitech.jpg', 0, 1, 1, 50, 'Accesorios', 'Logitech', 0.1, '10 x 6 x 3 cm', '7891234567891'),
('Teclado Mecánico Redragon', 'Teclado mecánico gaming con retroiluminación RGB', 89.99, 'productos/teclado-redragon.jpg', 0, 1, 1, 25, 'Accesorios', 'Redragon', 1.1, '44 x 13.5 x 3.6 cm', '7891234567892'),
('Audífonos Sony WH-1000XM4', 'Audífonos inalámbricos con cancelación de ruido', 299.99, 'productos/audifonos-sony.jpg', 0, 1, 1, 18, 'Audio', 'Sony', 0.25, '18.5 x 16.5 x 7 cm', '7891234567893'),
('Monitor Samsung 24"', 'Monitor Full HD 1920x1080, 75Hz, panel IPS', 199.99, 'productos/monitor-samsung.jpg', 0, 1, 1, 30, 'Monitores', 'Samsung', 3.5, '54.3 x 41.1 x 19.3 cm', '7891234567894'),
('Producto Marca Propia - Mouse Pad', 'Mouse pad gaming XL con superficie de tela', 14.99, 'productos/mousepad-propio.jpg', 1, 1, 1, 100, 'Accesorios', 'Fabricación propia', 0.3, '80 x 30 x 0.3 cm', 'PROPIO001'),
('Producto Marca Propia - Funda Laptop', 'Funda para laptop 15.6" impermeable', 24.99, 'productos/funda-laptop.jpg', 1, 1, 1, 60, 'Accesorios', 'Fabricación propia', 0.4, '40 x 30 x 2 cm', 'PROPIO002');

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - PROMOCIONES
-- ------------------------------------------------------------
INSERT INTO promociones (nombre, descripcion, tipo_promocion, descuento, precio_promocional, fecha_inicio, fecha_fin, activa, limite_usos, codigo_promocion, aplica_todos_servicios, aplica_todos_productos, servicio_id, producto_id, condiciones) VALUES
('Descuento 20% en Mantenimiento', '20% de descuento en servicio de mantenimiento de computadoras', 'Porcentaje', 20.00, 120.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1, 50, 'MANT20', 0, 0, 1, NULL, 'Válido solo para mantenimiento básico'),
('2x1 en Teclados Mecánicos', 'Lleva 2 teclados mecánicos por el precio de 1', '2x1', NULL, NULL, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 1, 20, 'TEC2X1', 0, 0, NULL, 3, 'Solo para el modelo Redragon'),
('Envío Gratis en Compras Mayores a $500', 'Envío gratis a toda la república en compras mayores a $500 MXN', 'Envio Gratis', NULL, NULL, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 1, NULL, 'ENVIO500', 0, 1, NULL, NULL, 'No aplica para productos voluminosos'),
('30% Off en Desarrollo Web', '30% de descuento en desarrollo de sitios web personalizados', 'Porcentaje', 30.00, 700.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), 1, 10, 'WEB30', 0, 0, 5, NULL, 'Solo para proyectos nuevos'),
('3x2 en Mouse Inalámbricos', 'Compra 3 mouse inalámbricos y paga solo 2', '3x2', NULL, NULL, DATE_ADD(CURDATE(), INTERVAL 3 DAY), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1, 15, 'MOUSE3X2', 0, 0, NULL, 2, 'Válido solo para modelo Logitech básico');

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - USUARIOS
-- ------------------------------------------------------------
INSERT INTO usuarios (name, email, password, rol, foto) VALUES
('Administrador Sistema', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'usuarios/admin.jpg'),
('Juan Pérez', 'juan@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', 'usuarios/juan.jpg'),
('María García', 'maria@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', 'usuarios/maria.jpg'),
('Cliente Demo', 'cliente@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 'usuarios/cliente.jpg');

-- ------------------------------------------------------------
-- CORRECCIONES NECESARIAS EN TUS MODELOS
-- ------------------------------------------------------------
-- Basado en tu código, necesitas estos ajustes:

/*
1. En User.php - Agregar campo 'foto' al fillable:
protected $fillable = [
    'name',
    'email',
    'password',
    'rol',
    'foto'  // ← Agregar este campo
];

2. En Producto.php - Faltan campos en casts y fillable:
// Agregar 'destacado' si lo usas en las vistas
protected $fillable = [
    // ... tus campos actuales
    'destacado'  // ← Si lo necesitas
];

protected $casts = [
    'marca' => 'boolean',
    'disponible' => 'boolean',
    'destacado' => 'boolean',  // ← Si agregas el campo
    'precio' => 'decimal:2',
    'peso' => 'decimal:2'
];

3. En Servicio.php - Faltan campos en fillable:
protected $fillable = [
    // ... tus campos actuales
    'destacado'  // ← Si lo usas en las vistas
];
*/

-- ------------------------------------------------------------
-- SCRIPT PARA CREAR ESTRUCTURA DE CARPETAS DE IMÁGENES
-- ------------------------------------------------------------
/*
-- En Laravel, ejecutar:
php artisan storage:link

-- Crear carpetas:
mkdir -p storage/app/public/servicios
mkdir -p storage/app/public/productos  
mkdir -p storage/app/public/usuarios

-- Para imágenes de prueba (Linux/Mac):
curl -o storage/app/public/servicios/mantenimiento.jpg https://picsum.photos/400/300?random=1
curl -o storage/app/public/productos/laptop-dell.jpg https://picsum.photos/400/300?random=2
*/

-- ------------------------------------------------------------
-- VERIFICACIÓN
-- ------------------------------------------------------------
SELECT '✅ Base de datos creada según tus modelos' as mensaje;
SELECT 
    (SELECT COUNT(*) FROM servicios) as total_servicios,
    (SELECT COUNT(*) FROM productos) as total_productos,
    (SELECT COUNT(*) FROM promociones) as total_promociones,
    (SELECT COUNT(*) FROM usuarios) as total_usuarios;