-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2026 a las 01:35:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `barbershop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `codigo_seguridad_token` varchar(100) DEFAULT NULL,
  `fecha_asignacion_cargo` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_administrador`, `codigo_seguridad_token`, `fecha_asignacion_cargo`) VALUES
(1, 'ADM-TOKEN-99X', '2026-05-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `barberos`
--

CREATE TABLE `barberos` (
  `id_barbero` int(11) NOT NULL,
  `porcentaje_comision` decimal(5,2) NOT NULL DEFAULT 50.00,
  `nro_cabina_asignada` int(11) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `barberos`
--

INSERT INTO `barberos` (`id_barbero`, `porcentaje_comision`, `nro_cabina_asignada`) VALUES
(3, 15.00, 1),
(4, 20.00, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_productos`
--

CREATE TABLE `categorias_productos` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_productos`
--

INSERT INTO `categorias_productos` (`id_categoria`, `nombre_categoria`, `descripcion`) VALUES
(1, 'Capilar', 'Productos para el cuidado del cabello y cuero cabelludo'),
(2, 'Barba y Afeitado', 'Insumos para el mantenimiento de barba, bigote y afeitado tradicional'),
(3, 'Herramientas y Desechables', 'Navajas, geles de afeitar, papel de cuello y herramientas operativas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_barbero` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `estado_cita` varchar(20) DEFAULT 'Programada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_cliente`, `id_barbero`, `id_servicio`, `fecha_cita`, `hora_cita`, `estado_cita`) VALUES
(1, 9, 3, 11, '2026-06-15', '18:30:00', 'Finalizada'),
(3, 9, 3, 20, '2026-12-13', '12:13:00', 'Programada'),
(4, 10, 3, 19, '2026-06-15', '12:00:00', 'Confirmada'),
(5, 11, 4, 13, '2026-05-23', '15:30:00', 'Cancelada'),
(6, 11, 3, 14, '2026-02-25', '15:30:00', 'Confirmada'),
(7, 11, 3, 16, '2026-06-14', '12:30:00', 'Confirmada'),
(8, 13, 4, 17, '2026-06-19', '14:00:00', 'Cancelada'),
(9, 13, 3, 10, '2026-06-19', '14:30:00', 'Confirmada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `observaciones_tecnicas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `id_genero`, `nombre_completo`, `celular`, `correo`, `fecha_registro`, `observaciones_tecnicas`) VALUES
(6, 1, 'Juan Carlos Perez', '77711122', 'juan@gmail.com', '2026-06-01', 'Cliente frecuente'),
(7, 1, 'Luis Alberto Gomez', '77733344', 'luis@gmail.com', '2026-06-01', 'Prefiere atención por la tarde'),
(8, 2, 'Maria Fernanda Rojas', '71234567', 'maria@gmail.com', '2026-06-01', 'Cliente VIP'),
(9, 1, 'Carlos Mamani', '70112233', 'carlos@gmail.com', '2026-06-01', ''),
(10, 2, 'Ana Lucia Flores', '76543210', 'ana@gmail.com', '2026-06-01', 'Solicita recordatorio por WhatsApp'),
(11, 1, 'Emiliano Valdez Morales', '64292848', 'emilianovaldezmorales@barbershop.com', NULL, NULL),
(12, 1, 'Luis Lampe Aspiazu', '72053529', 'luislampe@barbershop.bo', NULL, NULL),
(13, 1, 'Wilfredo Mendoza', '7777777', 'wilfredo@barbershop.bo', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_insumos_servicios`
--

CREATE TABLE `consumo_insumos_servicios` (
  `id_consumo` int(11) NOT NULL,
  `id_detalle_servicio` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_usada` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_cajas`
--

CREATE TABLE `control_cajas` (
  `id_caja` int(11) NOT NULL,
  `id_cajero` int(11) NOT NULL,
  `fecha_apertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `monto_apertura_bob` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_cierre_sistema_bob` decimal(10,2) DEFAULT 0.00,
  `monto_cierre_real_bob` decimal(10,2) DEFAULT NULL,
  `estado_caja` varchar(15) DEFAULT 'Abierta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `control_cajas`
--

INSERT INTO `control_cajas` (`id_caja`, `id_cajero`, `fecha_apertura`, `fecha_cierre`, `monto_apertura_bob`, `monto_cierre_sistema_bob`, `monto_cierre_real_bob`, `estado_caja`) VALUES
(1, 2, '2026-06-01 23:12:51', '2026-06-15 13:53:42', 200.00, 2125.00, NULL, 'Cerrada'),
(2, 2, '2026-06-15 14:55:55', NULL, 1995.00, 0.00, NULL, 'Abierta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 4, 4, 2, 50.00, 100.00),
(2, 4, 1, 2, 110.00, 220.00),
(3, 5, 2, 2, 95.00, 190.00),
(4, 5, 1, 2, 110.00, 220.00),
(5, 6, 2, 1, 95.00, 95.00),
(6, 6, 4, 1, 50.00, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta_servicios`
--

CREATE TABLE `detalle_venta_servicios` (
  `id_detalle_servicio` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `id_barbero` int(11) NOT NULL,
  `precio_cobrado_bob` decimal(10,2) NOT NULL,
  `comision_calculada_bob` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id_genero` int(11) NOT NULL,
  `nombre_genero` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id_genero`, `nombre_genero`) VALUES
(2, 'Femenino'),
(1, 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas_productos`
--

CREATE TABLE `marcas_productos` (
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(50) NOT NULL,
  `pais_origen` varchar(50) DEFAULT NULL,
  `procedencia` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas_productos`
--

INSERT INTO `marcas_productos` (`id_marca`, `nombre_marca`, `pais_origen`, `procedencia`) VALUES
(1, 'Elegance', NULL, 'Importado'),
(2, 'Suavecito', NULL, 'Importado'),
(3, 'Sir Fausto', NULL, 'Regional'),
(4, 'Desechables Elite', NULL, 'Nacional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_caja`
--

CREATE TABLE `movimientos_caja` (
  `id_movimiento` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `tipo` enum('ingreso','egreso') NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `usuario` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_caja`
--

INSERT INTO `movimientos_caja` (`id_movimiento`, `id_caja`, `tipo`, `descripcion`, `monto`, `fecha`, `usuario`) VALUES
(1, 1, 'egreso', 'pago de la luz local', 50.00, '2026-06-01 19:14:46', 'Administrador'),
(2, 1, 'egreso', 'pago de servicios de agua', 20.00, '2026-06-03 19:41:53', 'Ignacio Ronald Quispe Mamani'),
(3, 1, 'ingreso', 'pago de un socio', 500.00, '2026-06-10 20:42:16', 'Ignacio Ronald Quispe Mamani'),
(4, 1, 'ingreso', 'apoyo de la mama del jefe', 30.00, '2026-06-15 01:09:59', 'Carlos Cajero Lopez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `precio_costo_bob` decimal(10,2) NOT NULL,
  `precio_venta_publico_bob` decimal(10,2) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `id_marca`, `id_proveedor`, `nombre_producto`, `stock_actual`, `stock_minimo`, `precio_costo_bob`, `precio_venta_publico_bob`) VALUES
(1, 1, 2, 1, 'Pomada Mate Suavecito 4oz', 12, 5, 75.00, 110.00),
(2, 2, 3, 2, 'Óleo Esencial Sir Fausto 30ml', 3, 3, 60.00, 95.00),
(3, 3, 1, 1, 'Gel de Afeitar Elegance 50ml', 2, 5, 45.00, 70.00),
(4, 3, 4, 2, 'Paquete de Filos Dorco (100 u.)', 17, 4, 35.00, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `nit` varchar(20) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `contacto_vendedor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `razon_social`, `nit`, `telefono`, `contacto_vendedor`) VALUES
(1, 'Distribuidora Cosmos S.R.L.', '1020304025', '77223344', 'Juan Carlos Perez'),
(2, 'Insumos Estética Daniel', '5544332201', '60112233', 'Daniel Mamani');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(30) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso global y auditoría total del sistema.'),
(2, 'Cajero', 'Encargado del control de caja, registrar ventas de insumos y cobros.'),
(3, 'Barbero / Peluquero', 'Solo ve su agenda de citas, sus servicios realizados y sus comisiones.'),
(4, 'Cliente', 'Es el cliente y solo puede hacer citas y unas otras cosas mas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(100) NOT NULL,
  `precio_bob` decimal(10,2) NOT NULL,
  `duracion_estimada_min` int(11) NOT NULL
) ;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre_servicio`, `precio_bob`, `duracion_estimada_min`) VALUES
(1, 'Corte Clásico', 25.00, 30),
(2, 'Corte Fade', 35.00, 45),
(3, 'Corte Degradado Premium', 45.00, 60),
(4, 'Despunte / Mantenimiento', 20.00, 20),
(5, 'Peinado y Secado', 30.00, 40),
(6, 'Alisado Masculino', 70.00, 90),
(7, 'Ondas y Estilizado', 50.00, 60),
(8, 'Trenzados', 80.00, 120),
(9, 'Peinado para Evento', 90.00, 120),
(10, 'Barbería Completa', 55.00, 60),
(11, 'Afeitado Tradicional con Navaja', 30.00, 35),
(12, 'Perfilado de Barba', 20.00, 20),
(13, 'Diseño de Barba Premium', 35.00, 40),
(14, 'Corte + Barba', 50.00, 60),
(15, 'Tinte Completo', 120.00, 120),
(16, 'Retoque de Canas', 60.00, 45),
(17, 'Cambio de Tono', 150.00, 150),
(18, 'Balayage', 250.00, 240),
(19, 'Babylights', 220.00, 210),
(20, 'Highlights', 200.00, 180),
(21, 'Reflejos', 180.00, 150),
(22, 'Decoloración Completa', 300.00, 300),
(23, 'Matizador Rubio', 70.00, 45),
(24, 'Matizador Canoso', 70.00, 45),
(25, 'Corrección de Color', 350.00, 360);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `estado_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `id_genero`, `nombre_completo`, `ci`, `celular`, `correo`, `password_hash`, `estado_active`) VALUES
(1, 1, 1, 'Ignacio Ronald Quispe Mamani', '1234567 LP', '71111111', 'ignacio@barbershop.bo', 'admin123', 1),
(2, 2, 1, 'Carlos Cajero Lopez', '7654321', '72222222', 'carlos@barbershop.com', 'caja123', 1),
(3, 3, 1, 'Diego Barbero Silva', '4567891', '73333333', 'diego@barbershop.com', 'barbero123', 1),
(4, 3, 1, 'Pepe Tintaya Choque', '9876543 LP', '75555555', 'pepe@barbershop.com', 'pepe123', 1),
(5, 4, 1, 'Emiliano Valdez Morales', 'CLI-7828', '64292848', 'emilianovaldezmorales@barbershop.com', '$2y$10$/iCRfuYS5C.zjilyUZNLQ.E4EHLolEfiNETvRLKhyEO6GOlbMKzgm', 1),
(6, 4, 1, 'Luis Lampe Aspiazu', '9109141', '72053529', 'luislampe@barbershop.bo', '$2y$10$vC7zguo/vQgl.yLngh0GT.SIKAN7T5D0NqQRH/PhOLt6F6BNwWCVq', 1),
(7, 4, 1, 'Wilfredo Mendoza', 'CLI-9430', '7777777', 'wilfredo@barbershop.bo', '$2y$10$iGHTZjofkKCGdEADUFmxf.0gLVNCGS/29bN8Boz5ZBEK5OPZfI/uK', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_transaccion` timestamp NOT NULL DEFAULT current_timestamp(),
  `metodo_pago` varchar(20) NOT NULL,
  `total_pagado_bob` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_caja`, `id_cliente`, `fecha_transaccion`, `metodo_pago`, `total_pagado_bob`) VALUES
(1, 1, 9, '2026-06-02 01:23:15', 'EFECTIVO', 145.00),
(2, 1, 7, '2026-06-02 18:36:53', 'EFECTIVO', 255.00),
(3, 1, 8, '2026-06-02 23:06:23', 'EFECTIVO', 190.00),
(4, 1, 7, '2026-06-02 23:15:38', 'EFECTIVO', 320.00),
(5, 1, 10, '2026-06-03 23:42:55', 'EFECTIVO', 410.00),
(6, 1, 6, '2026-06-15 05:11:00', 'EFECTIVO', 145.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`);

--
-- Indices de la tabla `barberos`
--
ALTER TABLE `barberos`
  ADD PRIMARY KEY (`id_barbero`),
  ADD UNIQUE KEY `nro_cabina_asignada` (`nro_cabina_asignada`);

--
-- Indices de la tabla `categorias_productos`
--
ALTER TABLE `categorias_productos`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD UNIQUE KEY `uq_barbero_agenda_tiempo` (`id_barbero`,`fecha_cita`,`hora_cita`),
  ADD KEY `fk_citas_clientes` (`id_cliente`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_clientes_generos` (`id_genero`);

--
-- Indices de la tabla `consumo_insumos_servicios`
--
ALTER TABLE `consumo_insumos_servicios`
  ADD PRIMARY KEY (`id_consumo`),
  ADD KEY `fk_consumo_detalle` (`id_detalle_servicio`),
  ADD KEY `fk_consumo_producto` (`id_producto`);

--
-- Indices de la tabla `control_cajas`
--
ALTER TABLE `control_cajas`
  ADD PRIMARY KEY (`id_caja`),
  ADD KEY `fk_cajas_usuarios` (`id_cajero`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_venta_servicios`
--
ALTER TABLE `detalle_venta_servicios`
  ADD PRIMARY KEY (`id_detalle_servicio`),
  ADD KEY `fk_det_ventas` (`id_venta`),
  ADD KEY `fk_det_servicios` (`id_servicio`),
  ADD KEY `fk_det_barberos` (`id_barbero`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id_genero`),
  ADD UNIQUE KEY `nombre_genero` (`nombre_genero`);

--
-- Indices de la tabla `marcas_productos`
--
ALTER TABLE `marcas_productos`
  ADD PRIMARY KEY (`id_marca`),
  ADD UNIQUE KEY `nombre_marca` (`nombre_marca`);

--
-- Indices de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_caja` (`id_caja`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `nombre_producto` (`nombre_producto`),
  ADD KEY `fk_productos_categorias` (`id_categoria`),
  ADD KEY `fk_productos_marcas` (`id_marca`),
  ADD KEY `fk_productos_proveedores` (`id_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `razon_social` (`razon_social`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`),
  ADD UNIQUE KEY `nombre_servicio` (`nombre_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `ci` (`ci`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_usuarios_roles` (`id_rol`),
  ADD KEY `fk_usuarios_generos` (`id_genero`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `fk_ventas_cajas` (`id_caja`),
  ADD KEY `fk_ventas_clientes` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_productos`
--
ALTER TABLE `categorias_productos`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `consumo_insumos_servicios`
--
ALTER TABLE `consumo_insumos_servicios`
  MODIFY `id_consumo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_cajas`
--
ALTER TABLE `control_cajas`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_venta_servicios`
--
ALTER TABLE `detalle_venta_servicios`
  MODIFY `id_detalle_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marcas_productos`
--
ALTER TABLE `marcas_productos`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `fk_administradores_usuarios` FOREIGN KEY (`id_administrador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `barberos`
--
ALTER TABLE `barberos`
  ADD CONSTRAINT `fk_barberos_usuarios` FOREIGN KEY (`id_barbero`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_citas_barberos` FOREIGN KEY (`id_barbero`) REFERENCES `barberos` (`id_barbero`),
  ADD CONSTRAINT `fk_citas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_generos` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id_genero`);

--
-- Filtros para la tabla `consumo_insumos_servicios`
--
ALTER TABLE `consumo_insumos_servicios`
  ADD CONSTRAINT `fk_consumo_detalle` FOREIGN KEY (`id_detalle_servicio`) REFERENCES `detalle_venta_servicios` (`id_detalle_servicio`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_consumo_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `control_cajas`
--
ALTER TABLE `control_cajas`
  ADD CONSTRAINT `fk_cajas_usuarios` FOREIGN KEY (`id_cajero`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `detalle_venta_servicios`
--
ALTER TABLE `detalle_venta_servicios`
  ADD CONSTRAINT `fk_det_barberos` FOREIGN KEY (`id_barbero`) REFERENCES `barberos` (`id_barbero`),
  ADD CONSTRAINT `fk_det_servicios` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`),
  ADD CONSTRAINT `fk_det_ventas` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD CONSTRAINT `movimientos_caja_ibfk_1` FOREIGN KEY (`id_caja`) REFERENCES `control_cajas` (`id_caja`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_productos` (`id_categoria`),
  ADD CONSTRAINT `fk_productos_marcas` FOREIGN KEY (`id_marca`) REFERENCES `marcas_productos` (`id_marca`),
  ADD CONSTRAINT `fk_productos_proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_generos` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id_genero`),
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_cajas` FOREIGN KEY (`id_caja`) REFERENCES `control_cajas` (`id_caja`),
  ADD CONSTRAINT `fk_ventas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
