/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - gestion_comercial
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gestion_comercial` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `gestion_comercial`;

/*Table structure for table `clientes` */

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcliente`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `clientes` */

insert  into `clientes`(`idcliente`,`nombre`,`rfc`,`email`,`telefono`,`direccion`,`created_at`,`updated_at`) values 
(9,'dasndjsanjk','jnasjnjkascn',NULL,'989283928',NULL,'2026-03-30 19:34:05','2026-03-30 19:34:05'),
(10,'gguyguygyu','hghvg',NULL,'989283928',NULL,'2026-03-30 21:30:37','2026-03-30 21:30:37'),
(11,'Juan Perez',NULL,NULL,'2281028394',NULL,'2026-03-30 23:34:13','2026-03-30 23:34:13'),
(12,'pedro ramirez',NULL,NULL,'2389405830',NULL,'2026-04-01 00:12:00','2026-04-01 00:12:00'),
(13,'Público en general',NULL,NULL,NULL,NULL,'2026-04-01 00:14:54','2026-04-01 00:14:54'),
(14,'Público en general',NULL,NULL,NULL,NULL,'2026-04-01 00:15:20','2026-04-01 00:15:20');

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `idprod` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `foto` varchar(255) DEFAULT NULL,
  `marca` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Otra marca, 1=Marca propia',
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `numero_piezas` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(100) DEFAULT NULL,
  `tipo_producto` enum('equipo','periferico') DEFAULT NULL,
  `proveedor` varchar(255) DEFAULT NULL,
  `peso` decimal(8,2) DEFAULT NULL COMMENT 'Peso en kg',
  `dimensiones` varchar(100) DEFAULT NULL COMMENT 'Formato: Largo x Ancho x Alto',
  `codigo_barras` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idprod`),
  UNIQUE KEY `uk_codigo_barras` (`codigo_barras`),
  KEY `idx_nombre` (`nombre`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_marca` (`marca`),
  KEY `idx_disponible` (`disponible`),
  KEY `idx_stock` (`stock`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `productos` */

insert  into `productos`(`idprod`,`nombre`,`descripcion`,`precio`,`foto`,`marca`,`disponible`,`destacado`,`numero_piezas`,`stock`,`categoria`,`tipo_producto`,`proveedor`,`peso`,`dimensiones`,`codigo_barras`,`created_at`,`updated_at`) values 
(1,'Laptop Dell XPS 13','Laptop ultradelgada con procesador i7, 16GB RAM, 512GB SSD',1500.00,'productos/aWazxwFDBLtjACaYkIifwK9IA2n6GVgHg2azORqp.png',0,1,1,1,11,'Laptops','equipo','Dell',1.20,'30.2 x 19.9 x 1.5 cm','7891234567890','2026-03-16 20:57:18','2026-03-31 18:48:22'),
(2,'Mouse Inalámbrico Logitech','Mouse ergonómico inalámbrico con duración de batería de 12 meses',45.99,'productos/mouse-logitech.jpg',0,1,0,1,50,'Accesorios','periferico','Logitech',0.10,'10 x 6 x 3 cm','7891234567891','2026-03-16 20:57:18','2026-03-30 17:09:29'),
(3,'Teclado Mecánico Redragon','Teclado mecánico gaming con retroiluminación RGB',89.99,'productos/teclado-redragon.jpg',0,1,0,1,25,'Accesorios','periferico','Redragon',1.10,'44 x 13.5 x 3.6 cm','7891234567892','2026-03-16 20:57:18','2026-03-30 17:09:33'),
(4,'Audífonos Sony WH-1000XM4','Audífonos inalámbricos con cancelación de ruido',299.99,'productos/audifonos-sony.jpg',0,1,0,1,16,'Audio','periferico','Sony',0.25,'18.5 x 16.5 x 7 cm','7891234567893','2026-03-16 20:57:18','2026-03-30 17:09:45'),
(5,'Monitor Samsung 24\"','Monitor Full HD 1920x1080, 75Hz, panel IPS',199.99,'productos/monitor-samsung.jpg',0,1,0,1,27,'Monitores','periferico','Samsung',3.50,'54.3 x 41.1 x 19.3 cm','7891234567894','2026-03-16 20:57:18','2026-04-01 00:15:20'),
(6,'Producto Marca Propia - Mouse Pad','Mouse pad gaming XL con superficie de tela',14.99,'productos/mousepad-propio.jpg',1,1,0,1,100,'Accesorios','periferico','Fabricación propia',0.30,'80 x 30 x 0.3 cm','PROPIO001','2026-03-16 20:57:18','2026-03-30 17:09:37'),
(7,'Producto Marca Propia - Funda Laptop','Funda para laptop 15.6\" impermeable',24.99,'productos/funda-laptop.jpg',1,1,0,1,60,'Accesorios','periferico','Fabricación propia',0.40,'40 x 30 x 2 cm','PROPIO002','2026-03-16 20:57:18','2026-03-30 17:09:41'),
(8,'PC Gamer','PC Ensamblada con procesador Intel I9 de 14th Gen',18978.00,'productos/ppKRwtkaaXM7y4hh3bRtXSJZSkL058OhGaqKnCh3.jpg',0,1,1,7,4,'Computadoras de Escritorio','equipo','Cyberport',NULL,NULL,'7891234567889','2026-03-30 17:10:05','2026-03-31 18:48:29');

/*Table structure for table `promociones` */

DROP TABLE IF EXISTS `promociones`;

CREATE TABLE `promociones` (
  `idpromo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_promocion` enum('Porcentaje','Fijo','2x1','3x2','Envio Gratis') NOT NULL,
  `descuento` decimal(8,2) DEFAULT NULL COMMENT 'Porcentaje o monto fijo',
  `precio_promocional` decimal(10,2) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `limite_usos` int(11) DEFAULT NULL COMMENT 'NULL = ilimitado',
  `usos_actuales` int(11) NOT NULL DEFAULT 0,
  `codigo_promocion` varchar(100) DEFAULT NULL,
  `aplica_todos_servicios` tinyint(1) NOT NULL DEFAULT 0,
  `aplica_todos_productos` tinyint(1) NOT NULL DEFAULT 0,
  `condiciones` text DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idpromo`),
  UNIQUE KEY `uk_codigo_promocion` (`codigo_promocion`),
  KEY `idx_nombre` (`nombre`),
  KEY `idx_tipo_promocion` (`tipo_promocion`),
  KEY `idx_activa` (`activa`),
  KEY `idx_fecha_inicio` (`fecha_inicio`),
  KEY `idx_fecha_fin` (`fecha_fin`),
  KEY `idx_codigo_promocion` (`codigo_promocion`),
  KEY `idx_servicio_id` (`servicio_id`),
  KEY `idx_producto_id` (`producto_id`),
  CONSTRAINT `fk_promociones_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`idprod`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_promociones_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`idserv`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promociones` */

insert  into `promociones`(`idpromo`,`nombre`,`descripcion`,`tipo_promocion`,`descuento`,`precio_promocional`,`fecha_inicio`,`fecha_fin`,`activa`,`limite_usos`,`usos_actuales`,`codigo_promocion`,`aplica_todos_servicios`,`aplica_todos_productos`,`condiciones`,`servicio_id`,`producto_id`,`created_at`,`updated_at`) values 
(1,'Descuento 20% en Mantenimiento','20% de descuento en servicio de mantenimiento de computadoras','Porcentaje',20.00,120.00,'2026-03-16','2026-04-15',1,50,0,'MANT20',0,0,'Válido solo para mantenimiento básico',1,NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(2,'2x1 en Teclados Mecánicos','Lleva 2 teclados mecánicos por el precio de 1','2x1',NULL,NULL,'2026-03-16','2026-03-31',1,20,0,'TEC2X1',0,0,'Solo para el modelo Redragon',NULL,3,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(3,'Envío Gratis en Compras Mayores a $500','Envío gratis a toda la república en compras mayores a $500 MXN','Envio Gratis',NULL,NULL,'2026-03-11','2026-04-10',1,NULL,0,'ENVIO500',0,1,'No aplica para productos voluminosos',NULL,NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(4,'30% Off en Desarrollo Web','30% de descuento en desarrollo de sitios web personalizados','Porcentaje',30.00,700.00,'2026-03-16','2026-05-15',1,10,0,'WEB30',0,0,'Solo para proyectos nuevos',5,NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(5,'3x2 en Mouse Inalámbricos','Compra 3 mouse inalámbricos y paga solo 2','3x2',NULL,NULL,'2026-03-19','2026-04-15',1,15,0,'MOUSE3X2',0,0,'Válido solo para modelo Logitech básico',NULL,2,'2026-03-16 20:57:18','2026-03-16 20:57:18');

/*Table structure for table `servicios` */

DROP TABLE IF EXISTS `servicios`;

CREATE TABLE `servicios` (
  `idserv` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `foto` varchar(255) DEFAULT NULL,
  `tipo_servicio` enum('Interno','Externo','Domicilio','Online') NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `categoria` varchar(100) DEFAULT NULL,
  `duracion` decimal(5,2) DEFAULT NULL,
  `personal_requerido` int(11) DEFAULT 1,
  `materiales_incluidos` tinyint(1) NOT NULL DEFAULT 0,
  `garantia` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idserv`),
  KEY `idx_nombre` (`nombre`),
  KEY `idx_tipo_servicio` (`tipo_servicio`),
  KEY `idx_disponible` (`disponible`),
  KEY `idx_categoria` (`categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `servicios` */

insert  into `servicios`(`idserv`,`nombre`,`descripcion`,`precio`,`foto`,`tipo_servicio`,`disponible`,`destacado`,`categoria`,`duracion`,`personal_requerido`,`materiales_incluidos`,`garantia`,`created_at`,`updated_at`) values 
(1,'Mantenimiento de Computadoras','Servicio completo de mantenimiento preventivo y correctivo para computadoras de escritorio y laptops.',150.00,'servicios/mantenimiento.jpg','Interno',1,0,'Tecnología',2.00,1,1,1,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(2,'Instalación de Redes WiFi','Instalación y configuración de redes WiFi empresariales y domésticas.',300.00,'servicios/redes-wifi.jpg','Externo',1,0,'Tecnología',3.00,2,0,1,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(3,'Asesoría Legal Empresarial','Asesoría legal especializada para empresas, contratos y cumplimiento normativo.',500.00,'servicios/asesoria-legal.jpg','Online',1,0,'Legal',1.50,1,0,0,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(4,'Limpieza de Oficinas','Servicio profesional de limpieza para oficinas y espacios corporativos.',200.00,'servicios/limpieza.jpg','Domicilio',1,0,'Limpieza',4.00,2,1,0,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(5,'Desarrollo Web Personalizado','Desarrollo de sitios web y aplicaciones web a medida.',1000.00,'servicios/desarrollo-web.jpg','Online',1,0,'Desarrollo',40.00,3,0,1,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(6,'Consultoría de Marketing Digital','Estrategias de marketing digital y gestión de redes sociales.',400.00,'servicios/marketing.jpg','Online',1,0,'Marketing',2.00,1,0,0,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(7,'Traducción de Documentos','Traducción profesional de documentos técnicos y comerciales.',120.00,'servicios/traduccion.jpg','Online',1,0,'Traducción',1.00,1,0,0,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(8,'Capacitación en Ofimática','Cursos de capacitación en Office para empresas.',250.00,'servicios/capacitacion.jpg','Interno',1,0,'Capacitación',8.00,1,1,0,'2026-03-16 20:57:18','2026-03-16 20:57:18');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `sessions` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','empleado','cliente') DEFAULT 'empleado',
  `foto` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_rol` (`rol`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id`,`name`,`email`,`password`,`rol`,`foto`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Administrador Sistema','admin@sistema.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','usuarios/admin.jpg',NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(2,'Juan Pérez','juan@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','empleado','usuarios/juan.jpg',NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(3,'María García','maria@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','empleado','usuarios/maria.jpg',NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(4,'Cliente Demo','cliente@demo.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','cliente','usuarios/cliente.jpg',NULL,'2026-03-16 20:57:18','2026-03-16 20:57:18'),
(5,'Giovani','giovani@gmail.com','$2y$12$7HtsZqSft772L5y1xHxpYeZvSCUxHHf0am7RihADsDqLuK5hD/RyK','empleado',NULL,NULL,'2026-03-17 02:59:58','2026-03-17 02:59:58'),
(6,'Giovani Rojas','giovani@aol.com','$2y$12$fgoy9SkYcJn6M58WUkN41esmYk9wgHtrbssMOfTSXb3teAXnooLW2','admin',NULL,NULL,'2026-03-30 17:42:52','2026-03-30 12:35:03');

/*Table structure for table `ventas` */

DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(10) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `tipo_venta` varchar(50) DEFAULT 'mostrador',
  `estado` enum('completada','cancelada','pendiente') DEFAULT 'completada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idventa`),
  UNIQUE KEY `ventas_folio_unique` (`folio`),
  KEY `fk_ventas_usuario` (`iduser`),
  CONSTRAINT `fk_ventas_usuario` FOREIGN KEY (`iduser`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ventas` */

insert  into `ventas`(`idventa`,`folio`,`iduser`,`idcliente`,`subtotal`,`iva`,`total`,`tipo_venta`,`estado`,`created_at`,`updated_at`) values 
(2,'00001',6,9,599.98,96.00,695.98,'mixta','completada','2026-03-30 19:34:05','2026-03-30 19:34:05'),
(3,'00002',6,10,3600.00,576.00,4176.00,'mixta','completada','2026-03-30 21:30:37','2026-03-30 21:30:37'),
(4,'00003',6,11,3000.00,480.00,3480.00,'mixta','completada','2026-03-30 23:34:13','2026-03-30 23:34:13'),
(5,'00004',6,12,99.96,15.99,115.95,'mixta','completada','2026-04-01 00:12:00','2026-04-01 00:12:00'),
(6,'00005',6,13,22773.60,3643.78,26417.38,'mixta','completada','2026-04-01 00:14:55','2026-04-01 00:14:55'),
(7,'00006',6,14,599.97,96.00,695.97,'mixta','completada','2026-04-01 00:15:20','2026-04-01 00:15:20');

/*Table structure for table `ventas_detalles` */

DROP TABLE IF EXISTS `ventas_detalles`;

CREATE TABLE `ventas_detalles` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL DEFAULT 'producto',
  `item_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `garantia` tinyint(1) DEFAULT 0,
  `duracion_garantia` varchar(100) DEFAULT NULL,
  `especificaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`iddetalle`),
  KEY `idx_venta` (`idventa`),
  KEY `idx_item` (`item_type`,`item_id`),
  CONSTRAINT `ventas_detalles_ibfk_1` FOREIGN KEY (`idventa`) REFERENCES `ventas` (`idventa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ventas_detalles` */

insert  into `ventas_detalles`(`iddetalle`,`idventa`,`item_type`,`item_id`,`cantidad`,`precio_unitario`,`subtotal`,`garantia`,`duracion_garantia`,`especificaciones`,`created_at`) values 
(1,2,'producto',4,2,299.99,599.98,0,NULL,'kxlamkmxs','2026-03-30 13:34:05'),
(2,3,'producto',1,2,1800.00,3600.00,1,'2 años de soporte','hkj','2026-03-30 15:30:37'),
(3,4,'producto',1,2,1500.00,3000.00,0,NULL,'Laptop para home office','2026-03-30 17:34:13'),
(4,6,'producto',8,1,22773.60,22773.60,1,'2 años de soporte','ncjnsjcnsjc','2026-03-31 18:14:55'),
(5,7,'producto',5,3,199.99,599.97,0,NULL,'dsskmdls','2026-03-31 18:15:20');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
