-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-06-2024 a las 18:28:49
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sis_venta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nombre`, `telefono`, `direccion`, `usuario_id`, `estado`) VALUES
(1, 'Público en General', '123456789', 'S/D', 1, 1),
(2, 'Pedro Pablo Paco Vilca', '952230701', 'Tacna - Tacna', 1, 1),
(4, 'Aaron', '952230701', 'Tacna - Tacna', 1, 0),
(5, 'prueba', '987654321', 'Tacna', 5, 0),
(6, 'Taqueria', '987198377', 'Calle rusia', 1, 0),
(7, 'Crow', '981790313', 'Calle Rusia 1042', 1, 1),
(8, 'Crow programmer', '981790313', 'Mi casa', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'Nido del Cuervo', '981790313', 'crow.programmer.0101@gmail.com', 'Calle Rusia 1042');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_permisos`
--

CREATE TABLE `detalle_permisos` (
  `id` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_permisos`
--

INSERT INTO `detalle_permisos` (`id`, `id_permiso`, `id_usuario`) VALUES
(12, 3, 2),
(13, 4, 2),
(14, 5, 2),
(15, 6, 2),
(16, 1, 1),
(17, 2, 1),
(18, 3, 1),
(19, 4, 1),
(20, 5, 1),
(21, 6, 1),
(24, 6, 3),
(33, 1, 4),
(34, 5, 4),
(35, 6, 4),
(42, 1, 5),
(43, 2, 5),
(44, 3, 5),
(45, 4, 5),
(46, 5, 5),
(47, 6, 5),
(54, 5, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_producto`, `id_venta`, `cantidad`, `precio`) VALUES
(1, 4, 1, 2, '13.00'),
(2, 3, 1, 1, '16.00'),
(3, 4, 2, 3, '13.00'),
(4, 7, 3, 2, '5.00'),
(5, 6, 4, 1, '14.25'),
(6, 1, 5, 1, '15.00'),
(7, 1, 6, 1, '15.00'),
(8, 32, 6, 1, '1.00'),
(9, 31, 7, 1, '15.00'),
(10, 1, 7, 1, '15.00'),
(11, 33, 8, 1, '15.50'),
(12, 1, 9, 1, '15.00'),
(13, 1, 10, 1, '15.00'),
(14, 31, 11, 2, '15.00'),
(15, 2, 12, 1, '20.50'),
(16, 6, 13, 2, '14.25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'configuración'),
(2, 'usuarios'),
(3, 'clientes'),
(4, 'productos'),
(5, 'ventas'),
(6, 'nueva_venta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `codigo` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `codigo`, `descripcion`, `precio`, `existencia`, `usuario_id`, `estado`) VALUES
(2, '67890', 'Piso laminado', '20.50', 149, 1, 1),
(3, '13579', 'Pintura acrílica', '10.99', 200, 1, 1),
(4, '24680', 'Porcelanato', '18.75', 120, 1, 1),
(5, '98765', 'Madera de ingeniería', '12.30', 80, 1, 1),
(6, '11111', 'Cerámica decorativa', '14.25', 87, 1, 1),
(7, '22222', 'Papel pintado', '9.99', 120, 1, 1),
(8, '33333', 'Lámina de PVC', '7.50', 200, 1, 1),
(9, '44444', 'Piedra natural', '25.50', 80, 1, 1),
(10, '55555', 'Loseta vinílica', '11.75', 150, 1, 1),
(11, '66666', 'Tarima flotante', '19.99', 110, 1, 1),
(12, '77777', 'Pintura impermeable', '8.50', 180, 1, 1),
(13, '88888', 'Piso de concreto pulido', '32.50', 70, 1, 1),
(14, '99999', 'Mármol travertino', '42.00', 50, 1, 1),
(15, '10101', 'Tablero de fibra de densidad media', '14.99', 100, 1, 1),
(16, '12121', 'Adoquín de piedra', '18.50', 130, 1, 1),
(17, '13131', 'Placas de yeso laminado', '11.25', 160, 1, 1),
(18, '14141', 'Baldosas hidráulicas', '16.75', 140, 1, 1),
(19, '15151', 'Ladrillo refractario', '21.50', 90, 1, 1),
(20, '16161', 'Madera de roble', '29.99', 120, 1, 1),
(21, '17171', 'Cemento pulido', '10.50', 200, 1, 1),
(22, '18181', 'Paneles de yeso', '8.99', 80, 1, 1),
(23, '19191', 'Láminas de acero inoxidable', '37.50', 150, 1, 1),
(24, '20202', 'Vinilo autoadhesivo', '12.75', 110, 1, 1),
(25, '21212', 'Losa de granito', '28.50', 180, 1, 1),
(26, '22222', 'Piso de resina epoxi', '49.00', 70, 1, 1),
(27, '23232', 'Barniz para madera', '9.50', 50, 1, 1),
(28, '24242', 'Piedra artificial', '24.99', 100, 1, 1),
(29, '25252', 'Placas de cemento', '17.50', 130, 1, 1),
(30, '26262', 'Loseta cerámica', '13.25', 160, 1, 1),
(31, '123321', 'Jabon', '15.00', 2, 1, 1),
(33, '123987123', 'Tacos de Chile', '20.00', 4, 1, 1),
(34, '1974256', 'Cerbeza', '7.50', 20, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `estado`) VALUES
(1, 'Aaron Pedro Paco Ramos', 'ppacoramos@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),
(3, 'Crow', 'crow.programmer.0101@gmail.com', 'crow', 'bbbd53e913a404b04abf373dc1dac49b', 1),
(4, 'Pedro', 'pedrope@hotmail.com', '123', '202cb962ac59075b964b07152d234b70', 1),
(5, 'Prueba', 'prueba@gmail.com', 'prueba', '202cb962ac59075b964b07152d234b70', 1),
(6, '12345', '123@gmail.com', '123', '202cb962ac59075b964b07152d234b70', 1),
(7, 'Aaron Pedro Paco Ramos', 'ppacoramos@gmail.com', 'admin', 'admin', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_cliente`, `total`, `id_usuario`, `fecha`) VALUES
(4, 2, '14.25', 1, '2023-07-02 22:57:34'),
(5, 4, '15.00', 1, '2023-07-02 23:04:56'),
(6, 4, '16.00', 1, '2023-09-11 02:55:12'),
(7, 4, '30.00', 1, '2023-09-11 03:01:34'),
(8, 4, '15.50', 1, '2023-09-11 03:02:57'),
(9, 4, '15.00', 1, '2023-11-06 15:49:04'),
(10, 4, '15.00', 1, '2023-11-06 15:49:32'),
(11, 4, '30.00', 1, '2023-12-01 05:17:02'),
(12, 1, '20.50', 1, '2024-06-11 16:20:15'),
(13, 7, '28.50', 1, '2024-06-11 16:24:16');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
