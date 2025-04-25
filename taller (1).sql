-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2025 a las 16:45:30
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
-- Base de datos: `taller`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `nombre`, `correo`, `telefono`, `direccion`) VALUES
(1, 'TecnoHerramientas S.A.S', 'contacto@tecno.com', '3101234567', 'Cra 10 #15-25'),
(2, 'Soluciones Industriales', 'info@solindus.com', '3170000000', 'Calle 45 #22-33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `nombre`) VALUES
(1, 'Activa'),
(2, 'Vencida'),
(3, 'Suspendida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencia_software`
--

CREATE TABLE `licencia_software` (
  `licencia` int(11) NOT NULL,
  `fec_activacion` date NOT NULL,
  `fec_vencimiento` date NOT NULL,
  `id_tipo_licencia` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencia_software`
--

INSERT INTO `licencia_software` (`licencia`, `fec_activacion`, `fec_vencimiento`, `id_tipo_licencia`, `id_estado`, `id_empresa`) VALUES
(1, '2025-04-20', '2025-04-27', 1, 1, NULL),
(2, '2024-01-01', '2024-04-01', 2, 2, NULL),
(3, '2025-03-10', '2026-03-10', 3, 1, NULL),
(4, '2023-02-01', '2025-02-01', 4, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_repuestos`
--

CREATE TABLE `prestamos_repuestos` (
  `id_prestamo` int(11) NOT NULL,
  `codigo_barras_repuesto` varchar(30) DEFAULT NULL,
  `placa_vehiculo` varchar(10) DEFAULT NULL,
  `fecha_prestamo` date DEFAULT NULL,
  `cantidad_utilizada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos_repuestos`
--

INSERT INTO `prestamos_repuestos` (`id_prestamo`, `codigo_barras_repuesto`, `placa_vehiculo`, `fecha_prestamo`, `cantidad_utilizada`) VALUES
(3, '2345678901', 'sup-456', '2025-04-10', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repuestos`
--

CREATE TABLE `repuestos` (
  `codigo_barras` varchar(30) NOT NULL,
  `nombre_repuesto` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `repuestos`
--

INSERT INTO `repuestos` (`codigo_barras`, `nombre_repuesto`, `descripcion`, `precio`, `cantidad`) VALUES
('1234567890', 'Filtro de aire', 'Filtro para motor de automóvil', 50.00, 10000),
('2345678901', 'Bujías', 'Bujías de encendido para motor', 15.50, 10000),
('3456789012', 'Aceite para motor', 'Aceite sintético para motor de automóvil', 80.00, 10000),
('4567890123', 'Batería', 'Batería para vehículos de 12V', 120.00, 10000),
('5678901234', 'Pastillas de freno', 'Pastillas de freno delanteras para automóvil', 40.00, 10000),
('7703336004959', 'martillo', 'el bueno ', 10000.00, 19999),
('H001', 'Llave inglesa', 'Herramienta ajustable para tuercas y tornillos.', 25000.00, 10),
('H002', 'Destornillador plano', 'Destornillador de cabeza plana, mango ergonómico.', 8000.00, 15),
('H003', 'Destornillador estrella', 'Destornillador tipo Phillips, resistente.', 9000.00, 15),
('H004', 'Martillo', 'Martillo de acero, mango antideslizante.', 12000.00, 12),
('H005', 'Pinzas', 'Pinzas universales para trabajos eléctricos.', 9500.00, 8),
('H006', 'Llave de tubo', 'Llave para tuberías y pernos grandes.', 18000.00, 6),
('H007', 'Taladro inalámbrico', 'Taladro de batería 12V con brocas incluidas.', 120000.00, 4),
('H008', 'Cinta métrica', 'Cinta de medición de 5 metros.', 7000.00, 20),
('H009', 'Gato hidráulico', 'Gato para levantar vehículos de hasta 2 toneladas.', 95000.00, 3),
('H010', 'Cortafrío', 'Herramienta para cortar metales y azulejos.', 15000.00, 7),
('H011', 'Llave Allen', 'Juego de llaves hexagonales.', 11000.00, 10),
('H012', 'Multímetro digital', 'Para medir voltaje, corriente y resistencia.', 35000.00, 5),
('H013', 'Cargador de batería', 'Cargador de baterías de 12V para vehículos.', 78000.00, 2),
('H014', 'Juego de brocas', 'Brocas variadas para taladro.', 25000.00, 6),
('H015', 'Guantes de seguridad', 'Resistentes, antideslizantes y cómodos.', 6000.00, 25),
('H016', 'Extintor', 'Extintor portátil de polvo químico.', 45000.00, 5),
('H017', 'Compresor de aire', 'Para inflar neumáticos y limpieza.', 150000.00, 2),
('H018', 'Lámpara de inspección', 'LED portátil con gancho y base magnética.', 32000.00, 9),
('H019', 'Juego de dados', 'Dados de diferentes medidas con matraca.', 48000.00, 7),
('H020', 'Pistola de impacto', 'Herramienta neumática para aflojar tuercas.', 110000.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`) VALUES
(2, 'Admin'),
(1, 'Super Admin'),
(3, 'User');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_licencia`
--

CREATE TABLE `tipo_licencia` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `vigencia_valor` int(11) DEFAULT NULL,
  `vigencia_unidad` enum('dias','semanas','meses','años') DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_licencia`
--

INSERT INTO `tipo_licencia` (`id`, `descripcion`, `categoria`, `vigencia_valor`, `vigencia_unidad`, `precio`) VALUES
(1, 'Licencia Demo', 'Individual', 7, 'dias', 0.00),
(2, 'Licencia Básica', 'Individual', 3, 'meses', 25.00),
(3, 'Licencia Empresarial', 'Empresarial', 1, 'años', 150.00),
(4, 'Licencia Premium', 'Empresarial', 2, 'años', 280.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` varchar(20) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `nombre`, `telefono`, `correo`, `direccion`, `contrasena`, `id_rol`, `id_empresa`) VALUES
('1001', 'Carlos SuperAdmin', '3120000000', 'superadmin@mail.com', 'Cra 1 #1-1', 'clave123', 1, NULL),
('2002', 'Ana Admin', '3121111111', 'admin@mail.com', 'Cra 2 #2-2', 'admin123', 2, NULL),
('3003', 'Luis Usuario', '3122222222', 'usuario@mail.com', 'Cra 3 #3-3', 'user123', 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `placa_vehiculo` varchar(10) NOT NULL,
  `documento_usuario` varchar(20) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`placa_vehiculo`, `documento_usuario`, `marca`, `modelo`, `anio`) VALUES
('sup-456', '3003', 'susuki', '2020', 1234);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `licencia_software`
--
ALTER TABLE `licencia_software`
  ADD PRIMARY KEY (`licencia`),
  ADD KEY `id_tipo_licencia` (`id_tipo_licencia`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `fk_licencia_empresa` (`id_empresa`);

--
-- Indices de la tabla `prestamos_repuestos`
--
ALTER TABLE `prestamos_repuestos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `codigo_barras_repuesto` (`codigo_barras_repuesto`),
  ADD KEY `placa_vehiculo` (`placa_vehiculo`);

--
-- Indices de la tabla `repuestos`
--
ALTER TABLE `repuestos`
  ADD PRIMARY KEY (`codigo_barras`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipo_licencia`
--
ALTER TABLE `tipo_licencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `fk_usuarios_rol` (`id_rol`),
  ADD KEY `fk_usuarios_empresa` (`id_empresa`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`placa_vehiculo`),
  ADD KEY `documento_usuario` (`documento_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `licencia_software`
--
ALTER TABLE `licencia_software`
  MODIFY `licencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamos_repuestos`
--
ALTER TABLE `prestamos_repuestos`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_licencia`
--
ALTER TABLE `tipo_licencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `licencia_software`
--
ALTER TABLE `licencia_software`
  ADD CONSTRAINT `fk_licencia_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `licencia_software_ibfk_1` FOREIGN KEY (`id_tipo_licencia`) REFERENCES `tipo_licencia` (`id`),
  ADD CONSTRAINT `licencia_software_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`);

--
-- Filtros para la tabla `prestamos_repuestos`
--
ALTER TABLE `prestamos_repuestos`
  ADD CONSTRAINT `prestamos_repuestos_ibfk_1` FOREIGN KEY (`codigo_barras_repuesto`) REFERENCES `repuestos` (`codigo_barras`),
  ADD CONSTRAINT `prestamos_repuestos_ibfk_2` FOREIGN KEY (`placa_vehiculo`) REFERENCES `vehiculos` (`placa_vehiculo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`documento_usuario`) REFERENCES `usuarios` (`documento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
