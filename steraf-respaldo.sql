-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-07-2024 a las 20:47:54
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
-- Base de datos: `steraf-respaldo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `mercado_pago_id` varchar(255) DEFAULT NULL,
  `tipo_pago` varchar(50) DEFAULT NULL,
  `pedido_id` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `mercado_pago_id`, `tipo_pago`, `pedido_id`, `usuario_id`, `fecha_compra`, `total`) VALUES
(1, '82519262776', 'account_money', '20658157738', 1, '2024-07-10 19:53:11', 250);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id`, `compra_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 1, 250);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_usuario`
--

CREATE TABLE `informacion_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `comuna` varchar(50) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `info_adicional` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informacion_usuario`
--

INSERT INTO `informacion_usuario` (`id_usuario`, `nombre`, `apellido`, `telefono`, `region`, `comuna`, `calle`, `numero`, `info_adicional`) VALUES
(1, 'Alberto', '', '', 'Valparaiso', 'La calera', '', 0, ''),
(2, 'Elias', 'chacana', '78564879', 'Valparaiso', 'Nogales', 'benjaminsubercaseaux', 29, ''),
(3, 'Elias', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `cantidad`, `descripcion`, `imagen`, `disponible`) VALUES
(1, 'Llave de jardín 1/2', 1, 39, '', 'foto1.png', 1),
(2, 'Llave de paso de gas de 1/2', 4990, 20, NULL, 'foto2.png', 1),
(3, 'llave de bolá de jardín de media pulgada', 2090, 52, NULL, 'foto4.png', 1),
(4, 'pulsera magnética', 1890, 45, NULL, 'foto5.png', 1),
(5, 'Membrana de 2 orejas', 3480, 28, NULL, 'foto3.png', 1),
(6, 'Producto 6', 500, 54, NULL, 'foto6.png', 1),
(7, 'Producto 7', 450, 23, NULL, 'foto7.png', 1),
(8, 'Ajuste de neolita', 20, 43, NULL, 'foto8.png', 1),
(9, 'Producto 9', 850, 45, NULL, 'foto9.png', 1),
(10, 'Producto 10', 520, 12, NULL, 'foto10.png', 1),
(11, 'Teflón', 1990, 23, NULL, 'foto11.png', 1),
(12, 'Sonda de temperatura', 300, 25, 'válvula rango de 120/200 ideal válvula regulación freidora', 'foto12.png', 1),
(13, 'Piloto de gas y horno mas inyector campana', 1990, 0, NULL, 'foto13.png', 1),
(14, 'Pilas alcalinas', 3820, 20, NULL, 'foto14.png', 1),
(15, 'Regulador de caudal calefón jumker', 4570, 0, NULL, 'foto15.png', 1),
(16, 'Prensa estopa', 1990, 0, 'para calefón de 20 y 10', 'foto16.png', 1),
(17, 'Válvula de termostato', 10340, 0, 'Válvula de termostato de control de gas para horno y freidora', 'foto17.png', 1),
(18, 'Microswitch', 2500, 0, 'Microswitch repuesto calefón mademsa vitality', 'foto18.png', 1),
(19, 'Estabilizador de caudal', 4990, 0, 'Estabilizador de caudal tope plástico para calefón', 'foto19.png', 1),
(20, 'Pasta para soldadura', 6690, 0, NULL, 'foto20.png', 1),
(21, 'Caja porta pilas para calefón', 2490, 0, NULL, 'foto21.png', 1),
(22, 'Repuesto de calefón flowswitch con despiche splendid', 13580, 0, NULL, 'foto22.png', 1),
(23, 'Modulo de calefón con válvula tonka', 14980, 0, NULL, 'foto23.png', 1),
(24, 'Membrana plana', 2500, 0, NULL, 'foto25.png', 1),
(25, 'Platillos para calefón', 5500, 0, NULL, 'foto29.png', 1),
(26, 'Pernos de anclaje', 3290, 0, 'Pernos de anclaje para taza de baño', 'foto30.png', 1),
(27, 'Membrana para calefón trotter', 1760, 0, NULL, 'foto26.png', 1),
(28, 'Membrana para calefón', 4990, 0, 'Membrana para calefón junkers de 13 litros', 'foto28.png', 1),
(29, 'Membrana de 54 mililitros', 2490, 0, NULL, 'foto31.png', 1),
(30, 'Membrana para calefón junkers de silicona', 2690, 0, NULL, 'foto34.png', 1),
(31, 'Membrana una oreja de silicona', 2690, 0, NULL, 'foto32.png', 1),
(32, 'Membrana para calefón jumkers', 2690, 0, NULL, 'foto33.png', 1),
(33, 'Membrana para calefón', 3280, 0, NULL, 'foto38.png', 1),
(34, 'Membrana para calefón', 2490, 0, NULL, 'foto35.png', 1),
(35, 'Capuchón de 40MM', 2000, 0, NULL, 'foto36.png', 1),
(36, 'Termocuplas', 1000, 0, NULL, 'foto37.png', 1),
(37, 'Detector de gas', 55000, 0, NULL, 'foto39.png', 1),
(38, 'Mecha estufa kendal', 10000, 0, 'Mecha estufa kendal kpm23 duraheat C230 kerona Wkh 22 23', 'foto40.png', 0),
(39, 'producto', 1, 0, '', 'foto44.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rol`) VALUES
(1, 'Admin'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `signup`
--

CREATE TABLE `signup` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol_usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_usuarios`
--

CREATE TABLE `token_usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fecha_expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol_id`, `fecha_modificacion`) VALUES
(1, 'steraf2024@gmail.com', '$2y$10$/.OX3y2LwjDn8x.3ZommKeJNck3kOxnMsc2F.Y9byIXE7haieAJk6', 1, '2024-07-10 16:05:56'),
(2, 'samuelchacana94@gmail.com', '$2y$10$9L1Pd3XqV6xWLYHE1A4mzOJMoRmPvOeNwc7Yve/5K3k7K4vJl9KiW', 2, NULL),
(3, 'chacanasamuel08@gmail.com', '$2y$10$iMZrUSECwwm2NOqztq6A7ewArAySOAHNfw88M9iv.NjQhNZFJQ7Jm', 2, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `informacion_usuario`
--
ALTER TABLE `informacion_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `token_usuarios`
--
ALTER TABLE `token_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `informacion_usuario`
--
ALTER TABLE `informacion_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `signup`
--
ALTER TABLE `signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `token_usuarios`
--
ALTER TABLE `token_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `detalle_compras_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
