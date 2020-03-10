-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-03-2020 a las 23:37:30
-- Versión del servidor: 5.6.47-cll-lve
-- Versión de PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `washdrya_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autos`
--

CREATE TABLE `autos` (
  `id_auto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `placas` varchar(12) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `ann` year(4) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `color` varchar(200) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacoras_washers`
--

CREATE TABLE `bitacoras_washers` (
  `id_bitacora` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `id_washer` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `comentario` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos`
--

CREATE TABLE `codigos` (
  `id_codigo` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `latitud` varchar(50) NOT NULL,
  `longitud` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id_direccion`, `id_usuario`, `latitud`, `longitud`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 10101, '19.0534365', '-98.2233175', 'yeah', '2020-03-04 15:31:56', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `monto` decimal(18,2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `comentario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `id` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `tipo_vehiculo` varchar(100) NOT NULL,
  `duracion` time NOT NULL,
  `precio` decimal(10,4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes_lavado`
--

CREATE TABLE `paquetes_lavado` (
  `id_paquete` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `paquetes_lavado`
--

INSERT INTO `paquetes_lavado` (`id_paquete`, `nombre`, `descripcion`, `status`) VALUES
(1, 'Lavado Exterior Express', 'Incluye limpieza exterior ecologica de todo el vehiculo, exteriores carroceria', 1),
(2, 'Lavado Completo', 'El Lavado completo incluye todo a mano y en detalle', 1),
(3, 'Encerado', 'Renovación de la capa exterior de pintura con un tratamiento', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idRol` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', '0', '2020-01-04 10:08:00', NULL),
(2, 'Washer', 'Washer usuario', '2020-01-05 19:31:21', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_user`
--

CREATE TABLE `roles_user` (
  `idRolUser` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles_user`
--

INSERT INTO `roles_user` (`idRolUser`, `id_rol`, `id_user`, `created_at`) VALUES
(1, 1, 1, '2020-01-04 10:08:31'),
(2, 1, 5, '2020-01-05 19:08:28'),
(3, 2, 2, '2020-02-26 03:13:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `id_solicitud` int(11) NOT NULL,
  `id_washer` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `latitud` varchar(30) NOT NULL,
  `longitud` varchar(30) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `calificacion` int(2) NOT NULL,
  `comentario` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `google_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `username`, `password`, `email`, `remember_token`, `created_at`, `updated_at`, `name`, `google_id`) VALUES
(1, 'Miguel Ángel Domínguez Serrano', 'admin', '$2y$10$uq/tUDWZI.U3QcY9yYZ.rOQLWRdSFFLDHs73l5F.aquxsdOg.T.A.', 'miguelin2491@gmail.com', 'Ttoy5ah2cOgB17ZoE9hERT20jBWSNg1iJr1aF6rDrY5b1lfzMFdJRO5pVvfb', '2020-01-04 09:54:57', NULL, '', ''),
(2, 'Wash Dry', '108044189762982295907', '$2y$10$oeGDEy5CTkuUpDcae.q.oexq9r6RqEY/82yH4n9L/JkZCaXJeF3dK', 'washdryappssoporte@gmail.com', 'nNEPqLbWbmHEd5Ok6fi3OZg4xTWqAwMqDEsMboPTZZD1xiT2enUBJYtgisO4', '2020-02-26 09:13:57', '2020-02-26 09:13:57', 'Wash Dry', '108044189762982295907');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `washers`
--

CREATE TABLE `washers` (
  `id_washer` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `app` varchar(50) DEFAULT NULL,
  `apm` varchar(50) DEFAULT NULL,
  `pago_status` tinyint(1) NOT NULL DEFAULT '1',
  `status_washer` tinyint(1) NOT NULL DEFAULT '1',
  `monto_pago` decimal(10,2) DEFAULT NULL,
  `calificacion` int(2) DEFAULT '0',
  `foto_ine` varbinary(255) DEFAULT NULL,
  `id_paquete` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `washers`
--

INSERT INTO `washers` (`id_washer`, `id_usuario`, `nombre`, `app`, `apm`, `pago_status`, `status_washer`, `monto_pago`, `calificacion`, `foto_ine`, `id_paquete`, `created_at`) VALUES
(1, 13, 'Salem', 'Salem', 'Salem', 1, 1, NULL, 0, NULL, 2, '2020-03-08 18:35:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autos`
--
ALTER TABLE `autos`
  ADD PRIMARY KEY (`id_auto`);

--
-- Indices de la tabla `bitacoras_washers`
--
ALTER TABLE `bitacoras_washers`
  ADD PRIMARY KEY (`id_bitacora`);

--
-- Indices de la tabla `codigos`
--
ALTER TABLE `codigos`
  ADD PRIMARY KEY (`id_codigo`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_direccion`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paquetes_lavado`
--
ALTER TABLE `paquetes_lavado`
  ADD PRIMARY KEY (`id_paquete`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRol`);

--
-- Indices de la tabla `roles_user`
--
ALTER TABLE `roles_user`
  ADD PRIMARY KEY (`idRolUser`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`id_solicitud`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `washers`
--
ALTER TABLE `washers`
  ADD PRIMARY KEY (`id_washer`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autos`
--
ALTER TABLE `autos`
  MODIFY `id_auto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `bitacoras_washers`
--
ALTER TABLE `bitacoras_washers`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `codigos`
--
ALTER TABLE `codigos`
  MODIFY `id_codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes_lavado`
--
ALTER TABLE `paquetes_lavado`
  MODIFY `id_paquete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles_user`
--
ALTER TABLE `roles_user`
  MODIFY `idRolUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `washers`
--
ALTER TABLE `washers`
  MODIFY `id_washer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
