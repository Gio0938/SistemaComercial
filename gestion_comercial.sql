-- ------------------------------------------------------------
-- BASE DE DATOS: gestion_comercial
-- AUTOR: Sistema de Gestión Comercial
-- FECHA: Creación completa del sistema
-- ------------------------------------------------------------

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS gestion_comercial 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE gestion_comercial;

-- ------------------------------------------------------------
-- TABLA: servicios
-- ------------------------------------------------------------
CREATE TABLE servicios (
    idserv INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto VARCHAR(255),
    tipo_servicio ENUM('Interno','Externo','Domicilio','Online') NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    destacado TINYINT(1) NOT NULL DEFAULT 0,
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
    INDEX idx_destacado (destacado),
    INDEX idx_categoria (categoria),
    INDEX idx_precio (precio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLA: productos
-- ------------------------------------------------------------
CREATE TABLE productos (
    idprod INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto VARCHAR(255),
    marca TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=Otra marca, 1=Marca propia',
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    destacado TINYINT(1) NOT NULL DEFAULT 0,
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
    INDEX idx_destacado (destacado),
    INDEX idx_stock (stock),
    INDEX idx_precio (precio),
    INDEX idx_proveedor (proveedor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLA: promociones
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
    destacado TINYINT(1) NOT NULL DEFAULT 0,
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
    INDEX idx_destacado (destacado),
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_fecha_fin (fecha_fin),
    INDEX idx_codigo_promocion (codigo_promocion),
    INDEX idx_servicio_id (servicio_id),
    INDEX idx_producto_id (producto_id),
    INDEX idx_fechas_vigencia (fecha_inicio, fecha_fin, activa),
    
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
-- TABLA: usuarios (Opcional - Para futura autenticación)
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'empleado', 'cliente') DEFAULT 'empleado',
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - SERVICIOS
-- ------------------------------------------------------------
INSERT INTO servicios (nombre, descripcion, precio, tipo_servicio, disponible, destacado, categoria, duracion, personal_requerido, materiales_incluidos, garantia) VALUES
('Mantenimiento de Computadoras', 'Servicio completo de mantenimiento preventivo y correctivo para computadoras de escritorio y laptops.', 150.00, 'Interno', 1, 1, 'Tecnología', 2.0, 1, 1, 1),
('Instalación de Redes WiFi', 'Instalación y configuración de redes WiFi empresariales y domésticas.', 300.00, 'Externo', 1, 0, 'Tecnología', 3.0, 2, 0, 1),
('Asesoría Legal Empresarial', 'Asesoría legal especializada para empresas, contratos y cumplimiento normativo.', 500.00, 'Online', 1, 1, 'Legal', 1.5, 1, 0, 0),
('Limpieza de Oficinas', 'Servicio profesional de limpieza para oficinas y espacios corporativos.', 200.00, 'Domicilio', 1, 0, 'Limpieza', 4.0, 2, 1, 0),
('Desarrollo Web Personalizado', 'Desarrollo de sitios web y aplicaciones web a medida.', 1000.00, 'Online', 1, 1, 'Desarrollo', 40.0, 3, 0, 1),
('Consultoría de Marketing Digital', 'Estrategias de marketing digital y gestión de redes sociales.', 400.00, 'Online', 1, 0, 'Marketing', 2.0, 1, 0, 0),
('Traducción de Documentos', 'Traducción profesional de documentos técnicos y comerciales.', 120.00, 'Online', 1, 0, 'Traducción', 1.0, 1, 0, 0),
('Capacitación en Ofimática', 'Cursos de capacitación en Office para empresas.', 250.00, 'Interno', 1, 0, 'Capacitación', 8.0, 1, 1, 0);

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - PRODUCTOS
-- ------------------------------------------------------------
INSERT INTO productos (nombre, descripcion, precio, marca, disponible, destacado, numero_piezas, stock, categoria, proveedor, peso, dimensiones, codigo_barras) VALUES
('Laptop Dell XPS 13', 'Laptop ultradelgada con procesador i7, 16GB RAM, 512GB SSD', 1500.00, 0, 1, 1, 1, 15, 'Electrónica', 'Dell México', 1.2, '30.2 x 19.9 x 1.5 cm', '7891234567890'),
('Mouse Inalámbrico Logitech', 'Mouse ergonómico inalámbrico con duración de batería de 12 meses', 45.99, 0, 1, 0, 1, 50, 'Accesorios', 'Logitech', 0.1, '10 x 6 x 3 cm', '7891234567891'),
('Teclado Mecánico Redragon', 'Teclado mecánico gaming con retroiluminación RGB', 89.99, 0, 1, 1, 1, 25, 'Accesorios', 'Redragon', 1.1, '44 x 13.5 x 3.6 cm', '7891234567892'),
('Audífonos Sony WH-1000XM4', 'Audífonos inalámbricos con cancelación de ruido', 299.99, 0, 1, 1, 1, 18, 'Audio', 'Sony', 0.25, '18.5 x 16.5 x 7 cm', '7891234567893'),
('Monitor Samsung 24"', 'Monitor Full HD 1920x1080, 75Hz, panel IPS', 199.99, 0, 1, 0, 1, 30, 'Monitores', 'Samsung', 3.5, '54.3 x 41.1 x 19.3 cm', '7891234567894'),
('Impresora HP LaserJet', 'Impresora láser monocromática, wifi, impresión a doble cara', 189.99, 0, 1, 0, 1, 12, 'Impresoras', 'HP', 7.8, '38 x 36 x 25 cm', '7891234567895'),
('Disco Duro Externo Seagate 2TB', 'Disco duro externo USB 3.0, portable', 79.99, 0, 1, 0, 1, 40, 'Almacenamiento', 'Seagate', 0.16, '11.5 x 8 x 1.5 cm', '7891234567896'),
('Webcam Logitech C920', 'Webcam HD 1080p con micrófono estéreo integrado', 69.99, 0, 1, 0, 1, 35, 'Video', 'Logitech', 0.15, '9.5 x 2.5 x 2.5 cm', '7891234567897'),
('Router WiFi TP-Link Archer', 'Router WiFi 6 de doble banda, 1.5Gbps', 99.99, 0, 1, 0, 1, 20, 'Redes', 'TP-Link', 0.5, '24.5 x 16.4 x 3.3 cm', '7891234567898'),
('Tablet Samsung Galaxy Tab S7', 'Tablet Android 11", 128GB, S-Pen incluido', 649.99, 0, 1, 1, 1, 8, 'Tablets', 'Samsung', 0.5, '25.3 x 16.5 x 0.6 cm', '7891234567899'),
('Producto de Marca Propia - Mouse Pad', 'Mouse pad gaming XL con superficie de tela', 14.99, 1, 1, 0, 1, 100, 'Accesorios', 'Fabricación propia', 0.3, '80 x 30 x 0.3 cm', 'PROPIO001'),
('Producto de Marca Propia - Funda Laptop', 'Funda para laptop 15.6" impermeable', 24.99, 1, 1, 0, 1, 60, 'Accesorios', 'Fabricación propia', 0.4, '40 x 30 x 2 cm', 'PROPIO002');

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - PROMOCIONES
-- ------------------------------------------------------------
INSERT INTO promociones (nombre, descripcion, tipo_promocion, descuento, precio_promocional, fecha_inicio, fecha_fin, activa, destacado, limite_usos, codigo_promocion, aplica_todos_servicios, aplica_todos_productos, servicio_id, producto_id, condiciones) VALUES
('Descuento 20% en Mantenimiento', '20% de descuento en servicio de mantenimiento de computadoras', 'Porcentaje', 20.00, 120.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1, 1, 50, 'MANT20', 0, 0, 1, NULL, 'Válido solo para mantenimiento básico'),
('2x1 en Teclados Mecánicos', 'Lleva 2 teclados mecánicos por el precio de 1', '2x1', NULL, NULL, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 1, 1, 20, 'TEC2X1', 0, 0, NULL, 3, 'Solo para el modelo Redragon'),
('Envío Gratis en Compras Mayores a $500', 'Envío gratis a toda la república en compras mayores a $500 MXN', 'Envio Gratis', NULL, NULL, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 1, 0, NULL, 'ENVIO500', 0, 1, NULL, NULL, 'No aplica para productos voluminosos'),
('30% Off en Desarrollo Web', '30% de descuento en desarrollo de sitios web personalizados', 'Porcentaje', 30.00, 700.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), 1, 1, 10, 'WEB30', 0, 0, 5, NULL, 'Solo para proyectos nuevos'),
('3x2 en Mouse Inalámbricos', 'Compra 3 mouse inalámbricos y paga solo 2', '3x2', NULL, NULL, DATE_ADD(CURDATE(), INTERVAL 3 DAY), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1, 0, 15, 'MOUSE3X2', 0, 0, NULL, 2, 'Válido solo para modelo Logitech básico'),
('Descuento $100 en Laptops', 'Descuento de $100 MXN en todas las laptops Dell', 'Fijo', 100.00, 1400.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 45 DAY), 1, 1, 8, 'LAPTOP100', 0, 0, NULL, 1, 'Solo para modelos en stock'),
('Promoción de Invierno - 15% en Todo', '15% de descuento en todos nuestros servicios', 'Porcentaje', 15.00, NULL, '2024-12-01', '2024-12-31', 1, 1, NULL, 'INVIERNO15', 1, 0, NULL, NULL, 'No acumulable con otras promociones'),
('Pack Office Básico', 'Mouse + Teclado + Mouse Pad con 25% de descuento', 'Porcentaje', 25.00, NULL, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 20 DAY), 1, 0, 30, 'PACKOFFICE25', 0, 0, NULL, NULL, 'Incluye productos 2, 3 y 11'),
('Promoción Expirada - Test', 'Promoción para testing de reportes', 'Porcentaje', 10.00, NULL, '2024-01-01', '2024-01-31', 0, 0, 100, 'EXPIRADA10', 0, 1, NULL, NULL, 'Promoción de prueba expirada'),
('Promoción Próxima - Black Friday', '50% de descuento en productos seleccionados', 'Porcentaje', 50.00, NULL, DATE_ADD(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 1, 1, NULL, 'BLACK50', 0, 1, NULL, NULL, 'Válido solo durante Black Friday');

-- ------------------------------------------------------------
-- DATOS DE PRUEBA - USUARIOS (Opcional)
-- ------------------------------------------------------------
INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES
('Administrador Sistema', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Juan Pérez - Empleado', 'juan@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', 1),
('María García - Ventas', 'maria@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', 1),
('Cliente Demo', 'cliente@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 1);

-- ------------------------------------------------------------
-- VISTAS ÚTILES PARA REPORTES
-- ------------------------------------------------------------
CREATE VIEW vista_promociones_activas AS
SELECT 
    p.*,
    s.nombre as servicio_nombre,
    pr.nombre as producto_nombre,
    CASE 
        WHEN p.fecha_fin < CURDATE() THEN 'Expirada'
        WHEN p.fecha_inicio > CURDATE() THEN 'Próxima'
        WHEN p.activa = 1 THEN 'Activa'
        ELSE 'Inactiva'
    END as estado_vigencia,
    DATEDIFF(p.fecha_fin, CURDATE()) as dias_restantes
FROM promociones p
LEFT JOIN servicios s ON p.servicio_id = s.idserv
LEFT JOIN productos pr ON p.producto_id = pr.idprod
WHERE p.activa = 1;

CREATE VIEW vista_inventario_productos AS
SELECT 
    idprod,
    nombre,
    categoria,
    precio,
    stock,
    CASE 
        WHEN stock = 0 THEN 'Agotado'
        WHEN stock < 10 THEN 'Stock Bajo'
        ELSE 'Stock Normal'
    END as estado_stock,
    (precio * stock) as valor_inventario,
    proveedor
FROM productos
WHERE disponible = 1
ORDER BY stock ASC;

CREATE VIEW vista_servicios_disponibles AS
SELECT 
    idserv,
    nombre,
    tipo_servicio,
    categoria,
    precio,
    duracion,
    CASE 
        WHEN destacado = 1 THEN 'Destacado'
        ELSE 'Normal'
    END as estado_destacado,
    CASE 
        WHEN materiales_incluidos = 1 THEN 'Sí'
        ELSE 'No'
    END as incluye_materiales,
    CASE 
        WHEN garantia = 1 THEN 'Sí'
        ELSE 'No'
    END as incluye_garantia
FROM servicios
WHERE disponible = 1
ORDER BY destacado DESC, precio ASC;

CREATE VIEW vista_promociones_vigentes AS
SELECT 
    p.idpromo,
    p.nombre,
    p.tipo_promocion,
    p.descuento,
    p.fecha_inicio,
    p.fecha_fin,
    p.codigo_promocion,
    CASE 
        WHEN p.aplica_todos_servicios = 1 THEN 'Todos los Servicios'
        WHEN p.aplica_todos_productos = 1 THEN 'Todos los Productos'
        WHEN p.servicio_id IS NOT NULL THEN CONCAT('Servicio: ', s.nombre)
        WHEN p.producto_id IS NOT NULL THEN CONCAT('Producto: ', pr.nombre)
        ELSE 'Sin definir'
    END as aplicacion,
    p.usos_actuales,
    p.limite_usos
FROM promociones p
LEFT JOIN servicios s ON p.servicio_id = s.idserv
LEFT JOIN productos pr ON p.producto_id = pr.idprod
WHERE p.activa = 1 
    AND p.fecha_inicio <= CURDATE() 
    AND p.fecha_fin >= CURDATE();

-- ------------------------------------------------------------
-- PROCEDIMIENTOS ALMACENADOS PARA REPORTES
-- ------------------------------------------------------------
DELIMITER //

CREATE PROCEDURE sp_obtener_estadisticas_generales()
BEGIN
    -- Servicios
    SELECT 
        (SELECT COUNT(*) FROM servicios) as total_servicios,
        (SELECT COUNT(*) FROM servicios WHERE disponible = 1) as servicios_activos,
        (SELECT COUNT(*) FROM servicios WHERE destacado = 1) as servicios_destacados,
        (SELECT AVG(precio) FROM servicios WHERE disponible = 1) as precio_promedio_servicios;
    
    -- Productos
    SELECT 
        (SELECT COUNT(*) FROM productos) as total_productos,
        (SELECT COUNT(*) FROM productos WHERE disponible = 1) as productos_activos,
        (SELECT COUNT(*) FROM productos WHERE stock < 10 AND stock > 0) as productos_stock_bajo,
        (SELECT COUNT(*) FROM productos WHERE stock = 0) as productos_agotados,
        (SELECT SUM(precio * stock) FROM productos) as valor_total_inventario,
        (SELECT AVG(precio) FROM productos WHERE disponible = 1) as precio_promedio_productos;
    
    -- Promociones
    SELECT 
        (SELECT COUNT(*) FROM promociones) as total_promociones,
        (SELECT COUNT(*) FROM promociones WHERE activa = 1 AND fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE()) as promociones_activas,
        (SELECT COUNT(*) FROM promociones WHERE fecha_inicio > CURDATE()) as promociones_proximas,
        (SELECT COUNT(*) FROM promociones WHERE fecha_fin < CURDATE()) as promociones_expiradas,
        (SELECT SUM(usos_actuales) FROM promociones) as usos_totales_promociones;
END //

CREATE PROCEDURE sp_productos_stock_bajo(IN limite INT)
BEGIN
    SELECT 
        idprod,
        nombre,
        categoria,
        stock,
        precio,
        (precio * stock) as valor_inventario,
        CASE 
            WHEN stock = 0 THEN 'URGENTE - Agotado'
            WHEN stock < 5 THEN 'ALTO - Stock muy bajo'
            WHEN stock < limite THEN 'MEDIO - Stock bajo'
            ELSE 'NORMAL'
        END as nivel_alerta
    FROM productos
    WHERE stock < limite
    ORDER BY stock ASC;
END //

CREATE PROCEDURE sp_promociones_por_vencer(IN dias INT)
BEGIN
    SELECT 
        p.idpromo,
        p.nombre,
        p.tipo_promocion,
        p.fecha_fin,
        DATEDIFF(p.fecha_fin, CURDATE()) as dias_restantes,
        p.usos_actuales,
        p.limite_usos,
        CASE 
            WHEN p.aplica_todos_servicios = 1 THEN 'Todos Servicios'
            WHEN p.aplica_todos_productos = 1 THEN 'Todos Productos'
            WHEN p.servicio_id IS NOT NULL THEN CONCAT('Servicio: ', s.nombre)
            WHEN p.producto_id IS NOT NULL THEN CONCAT('Producto: ', pr.nombre)
            ELSE 'General'
        END as aplicacion
    FROM promociones p
    LEFT JOIN servicios s ON p.servicio_id = s.idserv
    LEFT JOIN productos pr ON p.producto_id = pr.idprod
    WHERE p.activa = 1 
        AND p.fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL dias DAY)
    ORDER BY p.fecha_fin ASC;
END //

DELIMITER ;

-- ------------------------------------------------------------
-- TRIGGERS PARA MANTENER INTEGRIDAD
-- ------------------------------------------------------------
DELIMITER //

-- Trigger para actualizar stock mínimo automáticamente
CREATE TRIGGER tr_productos_stock_minimo 
BEFORE UPDATE ON productos
FOR EACH ROW
BEGIN
    -- Si el stock cambia y es menor a 5, registrar alerta (simulación)
    IF NEW.stock < 5 AND OLD.stock >= 5 THEN
        -- Aquí podrías insertar en una tabla de alertas
        SET @mensaje_alerta = CONCAT('Alerta: Producto ', NEW.nombre, ' tiene stock bajo (', NEW.stock, ' unidades)');
        -- En un sistema real, insertarías en una tabla de notificaciones
    END IF;
END //

-- Trigger para desactivar promociones automáticamente al expirar
CREATE TRIGGER tr_promociones_auto_desactivar 
BEFORE UPDATE ON promociones
FOR EACH ROW
BEGIN
    -- Si la fecha de fin ya pasó, desactivar automáticamente
    IF NEW.fecha_fin < CURDATE() THEN
        SET NEW.activa = 0;
    END IF;
END //

-- Trigger para validar que no haya promociones duplicadas en las mismas fechas
CREATE TRIGGER tr_validar_promocion_solapada 
BEFORE INSERT ON promociones
FOR EACH ROW
BEGIN
    DECLARE solapadas INT;
    
    -- Verificar si hay promociones que se solapan para el mismo servicio/producto
    SELECT COUNT(*) INTO solapadas
    FROM promociones
    WHERE activa = 1
        AND (
            (servicio_id = NEW.servicio_id AND servicio_id IS NOT NULL) OR
            (producto_id = NEW.producto_id AND producto_id IS NOT NULL) OR
            (aplica_todos_servicios = 1 AND NEW.aplica_todos_servicios = 1) OR
            (aplica_todos_productos = 1 AND NEW.aplica_todos_productos = 1)
        )
        AND (
            (NEW.fecha_inicio BETWEEN fecha_inicio AND fecha_fin) OR
            (NEW.fecha_fin BETWEEN fecha_inicio AND fecha_fin) OR
            (fecha_inicio BETWEEN NEW.fecha_inicio AND NEW.fecha_fin)
        )
        AND idpromo != NEW.idpromo;
    
    IF solapadas > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Ya existe una promoción activa para este item en las fechas seleccionadas';
    END IF;
END //

DELIMITER ;

-- ------------------------------------------------------------
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- ------------------------------------------------------------
CREATE INDEX idx_productos_stock_bajo ON productos(stock) WHERE stock < 10;
CREATE INDEX idx_promociones_vigentes ON promociones(activa, fecha_inicio, fecha_fin);
CREATE INDEX idx_servicios_categoria_precio ON servicios(categoria, precio);
CREATE INDEX idx_productos_categoria_precio ON productos(categoria, precio);

-- ------------------------------------------------------------
-- TABLA DE AUDITORÍA (Opcional para tracking de cambios)
-- ------------------------------------------------------------
CREATE TABLE auditoria_cambios (
    id INT NOT NULL AUTO_INCREMENT,
    tabla_afectada VARCHAR(50) NOT NULL,
    id_registro INT NOT NULL,
    accion ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    datos_anteriores JSON,
    datos_nuevos JSON,
    usuario VARCHAR(255),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    INDEX idx_tabla_fecha (tabla_afectada, fecha),
    INDEX idx_accion_fecha (accion, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- CONFIGURACIÓN DE PERMISOS (Ejemplo básico)
-- ------------------------------------------------------------
-- Crear usuario para la aplicación (ajusta la contraseña)
CREATE USER 'app_gestion'@'localhost' IDENTIFIED BY 'PasswordSeguro123!';
GRANT SELECT, INSERT, UPDATE, DELETE ON gestion_comercial.* TO 'app_gestion'@'localhost';
FLUSH PRIVILEGES;

-- ------------------------------------------------------------
-- MENSAJE FINAL
-- ------------------------------------------------------------
SELECT '✅ Base de datos creada exitosamente!' as mensaje;
SELECT 
    (SELECT COUNT(*) FROM servicios) as total_servicios,
    (SELECT COUNT(*) FROM productos) as total_productos,
    (SELECT COUNT(*) FROM promociones) as total_promociones,
    (SELECT COUNT(*) FROM usuarios) as total_usuarios;