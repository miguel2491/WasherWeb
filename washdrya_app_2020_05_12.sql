-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-05-2020 a las 23:35:59
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
-- Estructura de tabla para la tabla `agendas`
--

CREATE TABLE `agendas` (
  `id_agenda` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `fecha_agendada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_washer` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `agendas`
--

INSERT INTO `agendas` (`id_agenda`, `id_cliente`, `id_solicitud`, `fecha_agendada`, `id_washer`, `status`, `created_at`) VALUES
(1, 4, 70, '2020-04-30 02:35:07', 1, 8, '2020-04-28 00:46:04'),
(2, 4, 71, '2020-04-30 02:35:07', 1, 8, '2020-04-28 00:51:29'),
(3, 2, 72, '2020-04-28 05:41:17', 0, 1, '2020-04-28 05:41:17'),
(4, 5, 74, '2020-04-28 23:41:29', 0, 1, '2020-04-28 23:41:29'),
(5, 5, 75, '2020-05-03 16:24:03', 0, 1, '2020-05-03 16:24:03'),
(6, 5, 76, '2020-05-03 16:29:15', 0, 1, '2020-05-03 16:29:15'),
(7, 2, 115, '2020-05-14 03:32:54', 1, 1, '2020-05-13 00:42:31');

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

--
-- Volcado de datos para la tabla `autos`
--

INSERT INTO `autos` (`id_auto`, `id_usuario`, `placas`, `modelo`, `ann`, `marca`, `color`, `imagen`, `created_at`) VALUES
(1, 2, 'ghthhj', 'Beat', 2020, 'beat Chevrolet', '9A0F0F', 'http://washdryapp.com/oficial/Autos/', '2020-04-17 03:38:29'),
(2, 4, 'tghuy6856', 'Ecosport', 2004, 'Ford', '5009EA', 'http://washdryapp.com/oficial/Autos/', '2020-04-18 00:25:06'),
(3, 2, 'guk2346', 'Tiida', 2020, 'Nissan Tiida', '9E8E8E', 'http://washdryapp.com/oficial/Autos/', '2020-04-18 17:19:18'),
(5, 2, 'funnu', 'ford', 2020, 'don', 'B385D3', 'http://washdryapp.com/oficial/Autos/', '2020-04-26 20:03:18'),
(6, 5, '34edj', 'vx', 2020, 'polo', '000000', 'http://washdryapp.com/oficial/Autos/', '2020-04-26 20:14:33'),
(7, 12, '1234re', 'vw', 2019, 'seat', '000000', 'http://washdryapp.com/oficial/Autos/', '2020-05-05 04:14:30'),
(8, 12, 'HTTU89', 'vocho', 1992, 'wv', 'EAE7E7', 'http://washdryapp.com/oficial/Autos/', '2020-05-07 00:44:13'),
(9, 14, 'hdysj23', 'versa', 2019, 'Nissan', 'CAA0A0', 'http://washdryapp.com/oficial/Autos/', '2020-05-11 16:54:01');

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
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calificacion` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL,
  `comentario` varchar(255) NOT NULL,
  `tipo_calificacion` char(1) NOT NULL DEFAULT 'W',
  `satus` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id_calificacion`, `id_solicitud`, `calificacion`, `comentario`, `tipo_calificacion`, `satus`, `created_at`) VALUES
(30, 59, 0, '6', 'W', 1, '2020-04-25 20:09:52'),
(31, 59, 0, '6', 'W', 1, '2020-04-25 20:50:08'),
(32, 59, 0, 'v90', 'W', 1, '2020-04-25 21:35:07'),
(33, 64, 0, 'pago bien', 'W', 1, '2020-04-26 15:23:59'),
(34, 66, 0, 'ttrr', 'W', 1, '2020-04-27 15:30:35'),
(35, 78, 0, 'tth', 'W', 1, '2020-05-03 12:02:43'),
(36, 78, 5, 'hy', 'W', 1, '2020-05-03 12:03:43'),
(37, 78, 5, 'gt', 'W', 1, '2020-05-03 12:04:33'),
(38, 84, 0, 'yeah', 'W', 1, '2020-05-03 23:41:35'),
(39, 86, 5, 'buen', 'W', 1, '2020-05-04 23:18:33'),
(40, 88, 5, 'df', 'W', 1, '2020-05-04 23:40:03'),
(41, 89, 0, 'hh', 'W', 1, '2020-05-06 19:42:54'),
(42, 90, 5, 'fgh', 'W', 1, '2020-05-07 18:26:33'),
(43, 93, 3, 'el', 'W', 1, '2020-05-08 09:10:36'),
(44, 94, 0, 'gg', 'W', 1, '2020-05-11 11:09:40'),
(45, 96, 0, 'hgg', 'W', 1, '2020-05-11 11:58:13'),
(46, 98, 0, 'gggg', 'W', 1, '2020-05-11 12:03:05'),
(47, 100, 0, 'hgg', 'W', 1, '2020-05-11 13:17:03'),
(48, 105, 0, 'gsgs', 'W', 1, '2020-05-11 13:36:02'),
(49, 108, 0, 'uuu', 'W', 1, '2020-05-11 23:14:45');

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
(1, 4, '19.0787647', '-98.2473679', 'Casa', '2020-04-18 00:25:20', '0000-00-00 00:00:00'),
(2, 12, '19.0546048939786', '-98.2511490798646', 'casa oficina', '2020-05-07 00:44:32', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `monto` decimal(18,2) NOT NULL,
  `id_washer` int(11) NOT NULL,
  `cambio` decimal(10,2) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` date NOT NULL,
  `comentario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_usuario`, `id_solicitud`, `monto`, `id_washer`, `cambio`, `tipo_pago`, `status`, `created_at`, `comentario`) VALUES
(21, 3, 23, 90.00, 2, 0.00, 'Tarjeta', 1, '2020-05-12', NULL),
(22, 2, 45, 90.00, 2, 0.00, 'Tarjeta', 1, '2020-05-10', NULL),
(23, 2, 47, 350.00, 2, 0.00, 'Tarjeta', 1, '2020-05-05', NULL),
(24, 2, 48, 400.00, 2, 0.00, 'Tarjeta', 1, '2020-05-10', NULL),
(25, 2, 93, 100.00, 2, 0.00, 'Tarjeta', 1, '2020-05-12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `id` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `tipo_vehiculo` varchar(100) NOT NULL,
  `duracion` time NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`id`, `id_paquete`, `tipo_vehiculo`, `duracion`, `precio`, `status`, `created_at`) VALUES
(1, 1, 'Chico', '01:00:00', 50.00, 1, '2020-03-10 21:39:20'),
(2, 1, 'Mediano', '00:58:12', 90.00, 1, '2020-03-11 00:59:53'),
(3, 1, 'Grande', '00:58:12', 100.00, 1, '2020-03-11 00:59:53'),
(4, 1, 'Camioneta', '00:35:56', 150.00, 1, '2020-03-11 01:00:17'),
(5, 2, 'Pequeño', '01:20:00', 90.00, 1, '2020-03-11 01:44:54'),
(6, 2, 'Mediano', '01:20:00', 90.00, 1, '2020-03-11 01:44:54'),
(7, 2, 'Grande', '01:30:00', 110.00, 1, '2020-03-11 01:46:25'),
(8, 2, 'Camioneta', '01:30:00', 110.00, 1, '2020-03-11 01:46:25'),
(9, 3, 'Pequeño', '00:20:00', 250.00, 1, '2020-03-11 01:47:29'),
(10, 3, 'Mediano', '00:25:00', 300.00, 1, '2020-03-11 01:47:29'),
(11, 3, 'Grande', '00:30:00', 350.00, 1, '2020-03-11 01:49:42'),
(12, 3, 'Muy Grande', '00:35:00', 400.00, 1, '2020-03-11 01:49:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes_lavado`
--

CREATE TABLE `paquetes_lavado` (
  `id_paquete` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(18,2) DEFAULT NULL,
  `descripcion` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `paquetes_lavado`
--

INSERT INTO `paquetes_lavado` (`id_paquete`, `nombre`, `precio`, `descripcion`, `status`) VALUES
(1, 'Auto', 90.00, 'Incluye limpieza exterior ecologica de todo el vehiculo, exteriores carroceria', 1),
(2, 'Camioneta', 100.00, 'El Lavado completo incluye todo a mano y en detalle', 1),
(3, 'Camioneta Grande', 125.00, 'Renovación de la capa exterior de pintura con un tratamiento', 1),
(4, 'Moto', 70.00, 'Moto', 1);

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
(2, 'Washer', 'Washer usuario', '2020-01-05 19:31:21', NULL),
(3, 'Cliente', 'Cliente Washer', '2020-03-24 17:52:51', NULL);

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
(1, 3, 2, '2020-04-17 03:35:21'),
(2, 2, 3, '2020-04-17 03:46:57'),
(3, 3, 4, '2020-04-18 00:23:28'),
(4, 2, 5, '2020-04-18 14:42:57'),
(5, 2, 6, '2020-04-18 14:55:09'),
(6, 2, 7, '2020-04-18 14:58:54'),
(7, 2, 8, '2020-04-18 20:33:48'),
(8, 2, 9, '2020-04-23 00:50:50'),
(9, 2, 10, '2020-04-24 15:29:21'),
(10, 3, 11, '2020-05-05 04:11:32'),
(11, 3, 12, '2020-05-05 04:13:15'),
(12, 2, 13, '2020-05-08 13:59:53'),
(13, 3, 14, '2020-05-11 16:51:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `id_solicitud` int(11) NOT NULL,
  `id_washer` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_paquete` int(11) NOT NULL,
  `id_auto` int(11) NOT NULL,
  `fragancia` varchar(150) DEFAULT NULL,
  `latitud` varchar(30) NOT NULL,
  `longitud` varchar(30) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `foto_washer` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `calificacion` int(2) DEFAULT NULL,
  `forma_pago` varchar(150) NOT NULL,
  `cambio` decimal(18,2) DEFAULT '0.00',
  `comentario` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_atendida` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`id_solicitud`, `id_washer`, `id_usuario`, `id_paquete`, `id_auto`, `fragancia`, `latitud`, `longitud`, `direccion`, `foto`, `foto_washer`, `fecha`, `calificacion`, `forma_pago`, `cambio`, `comentario`, `status`, `fecha_atendida`) VALUES
(84, 1, 2, 1, 3, 'Aroma Marino', '-93.1686182', '16.7449283', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588567284-erp.reno2@hotmail.com.jpg', '2020-05-09 17:37:48', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-04 09:41:25'),
(85, 1, 2, 1, 6, 'Aroma carro nuevo', '-98.2512154459772', '19.0545491618075', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588651721-danielglezram95@gmail.com_2.jpg', '2020-05-12 00:24:09', 0, 'Tarjeta', 0.00, 'rr', 2, '2020-05-05 09:08:42'),
(86, 2, 12, 1, 7, 'Aroma carro nuevo', '-98.2511773532823', '19.0546438022623', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588652290-danielglezram95@gmail.com_3.jpg', '2020-05-05 04:18:33', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-05 09:18:11'),
(87, 2, 12, 2, 7, 'Aroma Marino', '-98.2511746237062', '19.0545675145399', NULL, 'no hay aun', NULL, '2020-05-05 04:29:43', 0, 'Tarjeta', 0.00, 'e4', 6, '0000-00-00 00:00:00'),
(88, 2, 12, 2, 7, 'Aroma Marino', '-98.2513028', '19.054479', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588653529-danielglezram95@gmail.com_1.jpg', '2020-05-05 04:40:03', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-05 09:38:50'),
(89, 2, 12, 1, 7, 'Aroma carro nuevo', '-98.25121411107', '19.0545976620941', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588812160-danielglezram95@gmail.com_2.jpg', '2020-05-07 00:42:54', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-07 05:42:41'),
(90, 2, 12, 2, 8, 'Aroma auto de lujo', '-98.2512478190739', '19.0546746118034', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588893965-danielglezram95@gmail.com_4.jpg', '2020-05-07 23:26:33', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-08 04:26:07'),
(91, 2, 12, 3, 7, 'Aroma auto de lujo', '19.0546048939786', '-98.2511490798646', NULL, 'no hay aun', NULL, '2020-05-11 16:04:55', 0, 'Tarjeta', 0.00, 'nunca llegó el bato', 6, '0000-00-00 00:00:00'),
(92, 1, 2, 2, 3, NULL, '-93.1679375372316', '16.744755241104', NULL, NULL, NULL, '2020-05-11 01:41:59', 0, 'Efectivo', 0.00, 'Chuck', 6, '0000-00-00 00:00:00'),
(93, 8, 2, 3, 3, 'Aroma Marino', '-93.1686312', '16.7449311', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1588946638-erp.renow@hotmail.com.jpg', '2020-05-08 14:11:16', 3, 'Tarjeta', 0.00, 'el', 7, '2020-05-08 19:04:01'),
(94, 2, 12, 1, 8, 'Aroma auto de lujo', '19.0546048939786', '-98.2511490798646', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1589213361-danielglezram95@gmail.com.jpg', '2020-05-11 16:09:40', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-11 21:09:22'),
(95, 2, 12, 3, 8, 'Aroma carro nuevo', '-98.2511884121092', '19.054617641504', NULL, NULL, 'http://washdryapp.com/oficial/Autos/1589213611-danielglezram95@gmail.com_1.jpg', '2020-05-11 16:13:31', 0, 'Efectivo', 0.00, NULL, 4, '2020-05-11 21:13:31'),
(96, 2, 14, 1, 9, 'Aroma Marino', '-98.2511787769721', '19.05460108449', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1589216200-danielglezram95@gmail.com_2.jpg', '2020-05-11 16:58:13', 0, 'Tarjeta', 0.00, 'yy', 7, '2020-05-11 21:56:40'),
(97, 2, 14, 3, 9, 'Aroma auto de lujo', '-98.2509901396682', '19.0544522618688', NULL, NULL, NULL, '2020-05-11 17:00:06', 0, 'Efectivo', 0.00, 'jdhr', 6, '0000-00-00 00:00:00'),
(98, 2, 14, 3, 9, 'Aroma auto de lujo', '-98.2511637', '19.0546205', NULL, NULL, 'http://washdryapp.com/oficial/Autos/1589216573-danielglezram95@gmail.com_3.jpg', '2020-05-11 17:03:05', 0, 'Efectivo', 0.00, NULL, 7, '2020-05-11 22:02:55'),
(99, 2, 14, 2, 9, 'Aroma auto de lujo', '-98.2511636322274', '19.0545806409173', NULL, NULL, 'http://washdryapp.com/oficial/Autos/1589216800-danielglezram95@gmail.com_4.jpg', '2020-05-11 17:08:59', 0, 'Efectivo', 0.00, 'gg', 6, '2020-05-11 22:06:40'),
(100, 2, 14, 2, 9, 'Aroma Marino', '-98.2511723', '19.054659', NULL, NULL, 'http://washdryapp.com/oficial/Autos/1589221007-danielglezram95@gmail.com_5.jpg', '2020-05-11 18:17:03', 0, 'Efectivo', 0.00, NULL, 7, '2020-05-11 23:16:47'),
(101, 2, 14, 2, 9, 'Aroma Marino', '-98.2512335650622', '19.0545778946717', NULL, NULL, NULL, '2020-05-11 18:08:27', 0, 'Efectivo', 0.00, '44', 6, '0000-00-00 00:00:00'),
(102, 2, 14, 2, 9, 'Aroma Marino', '-98.25118077908', '19.0545394586786', NULL, 'no hay aun', NULL, '2020-05-11 18:14:35', 0, 'Tarjeta', 0.00, '66', 6, '0000-00-00 00:00:00'),
(103, 2, 14, 2, 9, 'Aroma Marino', '-98.2511174', '19.0546723', NULL, 'no hay aun', NULL, '2020-05-11 18:18:00', 0, 'Tarjeta', 0.00, 'tr', 6, '0000-00-00 00:00:00'),
(104, 2, 14, 2, 9, 'Aroma auto de lujo', '-98.2511944641596', '19.0546117496988', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1589221440-danielglezram95@gmail.com_6.jpg', '2020-05-11 18:37:03', 0, 'Tarjeta', 0.00, 'jdhd', 6, '2020-05-11 23:24:01'),
(105, 2, 14, 2, 9, 'Aroma Marino', '-98.2511676', '19.0546482', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1589222143-danielglezram95@gmail.com_7.jpg', '2020-05-11 18:36:02', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-11 23:35:43'),
(106, 2, 14, 3, 9, 'Aroma Marino', '-98.25099', '19.0545383333333', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1589226502-danielglezram95@gmail.com_8.jpg', '2020-05-12 04:09:20', 0, 'Tarjeta', 0.00, 'bbb', 6, '2020-05-12 00:48:23'),
(107, 2, 14, 3, 9, 'Aroma Marino', '-98.251045', '19.054605', NULL, 'no hay aun', NULL, '2020-05-12 04:09:46', 0, 'Tarjeta', 0.00, 'ggre', 6, '0000-00-00 00:00:00'),
(108, 2, 14, 3, 9, 'Aroma Marino', '-98.250975', '19.05467', NULL, 'no hay aun', 'http://washdryapp.com/oficial/Autos/1589256876-danielglezram95@gmail.com_9.jpg', '2020-05-12 04:14:45', 0, 'Tarjeta', 0.00, NULL, 7, '2020-05-12 09:14:36'),
(109, 2, 14, 2, 9, 'Aroma Marino', '-98.2512274', '19.0545729', NULL, 'no hay aun', NULL, '2020-05-12 04:40:49', 0, 'Tarjeta', 0.00, NULL, 2, '0000-00-00 00:00:00'),
(110, 8, 2, 4, 3, 'Aroma Marino', '-93.1686192', '16.7449432', NULL, NULL, NULL, '2020-05-12 16:42:10', 0, 'Efectivo', 0.00, 'Flores', 6, '0000-00-00 00:00:00'),
(111, 8, 2, 4, 3, 'Aroma Marino', '-93.1686208', '16.7449423', NULL, NULL, 'http://washdryapp.com/oficial/Autos/1589301865-erp.renow@hotmail.com_1.jpg', '2020-05-12 16:44:27', 0, 'Efectivo', 0.00, NULL, 4, '2020-05-12 21:44:27'),
(112, 8, 2, 4, 5, NULL, '-93.1686332', '16.7449308', NULL, NULL, NULL, '2020-05-12 16:59:08', 0, 'Efectivo', 0.00, 'time', 6, '0000-00-00 00:00:00'),
(113, 8, 2, 4, 3, 'Aroma Marino', '-93.1684161359995', '16.7438760525444', NULL, NULL, 'http://washdryapp.com/oficial/Autos/1589302976-erp.renow@hotmail.com_2.jpg', '2020-05-12 17:02:58', 0, 'Efectivo', 0.00, NULL, 4, '2020-05-12 22:02:58'),
(114, 8, 2, 4, 3, 'Aroma auto de lujo', '-93.1688068529668', '16.7450468520472', NULL, 'no hay aun', NULL, '2020-05-12 05:00:00', 0, 'Tarjeta', 0.00, NULL, 1, '0000-00-00 00:00:00'),
(115, 1, 2, 1, 2, NULL, '-98.2473566', '19.078751', NULL, 'NO HAY AUN', NULL, '2020-05-13 03:33:35', 0, 'Tarjeta', 0.00, 'A', 8, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `fecha_nac`, `username`, `password`, `email`, `telefono`, `remember_token`, `created_at`, `updated_at`, `name`, `google_id`, `foto`, `token`) VALUES
(1, 'Miguel Ángel Domínguez Serrano', NULL, 'admin', '$2y$10$PJUZ46qqOICT//KsK7YuDOqUv9jqClItaFSYStFGZ98nXIO2//bqC', 'miguelin249@gmail.com', NULL, 'Ttoy5ah2cOgB17ZoE9hERT20jBWSNg1iJr1aF6rDrY5b1lfzMFdJRO5pVvfb', '2020-01-04 09:54:57', NULL, '', '', '', ''),
(2, 'Ed rawr Ophanim', '2003-12-31', 'Ed', '$2y$10$FkGCclLQ7z7Lhee0k80E0ecWQH5a2dy1Vyz3UJEuUZ0yKTVfp.RCu', 'erp.reno@hotmail.com', NULL, NULL, '2020-04-17 03:35:21', NULL, 'Ed', '121212', 'http://washdryapp.com/oficial/Perfiles/1587094507-washer.jpg', 'cmmtgU3J_As:APA91bHjdh7xt97GgkTYH-2NHbR_LO7NshN9Db-c5m1dT94_fSF5PzMECXadQTEsBL6SlbPL4lcimQFfvTocGkZmxLuIWF2BL8fsMj5dNYeGf7UrZ2hVxtjGkO6pumVJ9BHVitpXZC3M'),
(3, 'Marifer fernandez Rosas', '1991-05-28', 'Marifer', '$2y$10$bY7pSL4dbCpHeER8LAEFH.pI2yDWAYoNRVZ3tmf1v0HvIJAI/P7gS', 'miguelin2491@gmail.com', NULL, NULL, '2020-04-17 03:46:57', NULL, 'Marifer', '1111', 'http://washdryapp.com/oficial/Perfiles/1587095213-washer.jpg', 'dOdrhVHLZrs:APA91bHduDej5h1yz02vERXH3EIIQjcQkRmheZtErThcY5QrW4MOi1EeVcl3k2pswLIhHfbHq2l_szJC3d0oISz1CWd2QOXbUxBgbi-1kZ49nG_71VOhtu3J8gmNRfqK5WKgj02vZWqK'),
(4, 'Yaneli Hernández Escorza', '1993-01-02', 'Yaneli', '$2y$10$nGSBVDHfI6yRjq1YNKcUKezVolM9ehkQOW4BT8ibrwUgcVu6RzdvW', 'yaneli@mail.com', NULL, NULL, '2020-04-18 00:23:28', NULL, 'Yaneli', '121212', 'http://washdryapp.com/oficial/Perfiles/1587169403-washer.jpg', 'd185MZGsAvk:APA91bGjVV92OKdyTsJHGwYATDAWI3Af31pxy6YHG23C6ivyew7AHJNZ7q5qix4vOT0TqoP3677eN6z-GFtflQOqLrlRVxeJ6EERKl-tRZKhm41dOgvZ3kHWGnVlZ6mnQvKu0vfuVJju'),
(5, 'Daniel González Ramírez', '2003-12-15', 'Daniel', '$2y$10$8KmVumnaQgcSNfsqaP7C7ud4yfmAePoHeHyGhVD7Drlhe9ScFyeOC', 'danielglezram95@gmail.com', NULL, NULL, '2020-04-18 14:42:57', NULL, 'Daniel', '1111', 'http://washdryapp.com/oficial/Perfiles/1587220970-washer.jpg', 'faY9tS-1D6M:APA91bErSoYCSy2PVukogeUk11YWNqPjrGf2cPXOZWSXDGEA9ltaFROgiGoJG2mYUkvcv3kgrn4UCBnc8UZDrc-DqCSovITZVYDxIdq2TVIR7K_qczdwW43dgJbpg0f_MNABqmnmq4s8'),
(10, 'EThoP OniOphanim Rawrdriguez', '2003-12-31', 'EThoP', '$2y$10$Eh1QSdvEOhEsoELX3AF7COBSKTtf7AH5dyYwJ4YRtr/73qzMfjTVG', 'erp.reno2@hotmail.com', '9611545746', NULL, '2020-04-24 15:29:21', NULL, 'EThoP', '1111', 'http://washdryapp.com/oficial/Perfiles/1587742161-washer_3.jpg', 'dbHYHMDb270:APA91bFhpIbsGBVtBfKX3JJf64NE-nD_qZLX6l94WS3adDC9AGvr154-pFw9_WEY3lcnPSQRERDXH3NX8MoXOOZXeRCDQ2oGnMDCukNnFopbGBE9zxvGh4wONBriEzD1nWgM-1_tfraC'),
(11, 'luis gerardo Torres salazar', '1999-12-09', 'luis gerardo', '$2y$10$w5X9khHXPJN1WUg/Az3WnO.iXXuKX4DziWIFs/zSPfWf63b9pPCJy', 'luischeer95@gmail.com', NULL, NULL, '2020-05-05 04:11:32', NULL, 'luis gerardo', '121212', 'http://washdryapp.com/oficial/Perfiles/1588651888-washer.jpg', 'dmwlyFY17w0:APA91bEYVQdSVx-rNhP7N7l3V4TzyNg4KucTot6MvK7kO5JF4uNkt-DCnrK3WhmmYrDsvq46DRb31-9t0W27GS3ss4Vsw1Dycz-lysXNx9hjja0bA_-lJHkyKiM3VywLcbujdYjJ23L9'),
(12, 'luis torres salazar', '2003-12-08', 'luis', '$2y$10$qpTSxqXCyXviDE96Lp659.QBSBpwtV/PkchyIGqIPPNRNzPOxPRPe', 'prueba123@gmail.com', NULL, NULL, '2020-05-05 04:13:15', NULL, 'luis', '121212', 'http://washdryapp.com/oficial/Perfiles/1588651991-washer_1.jpg', 'dmwlyFY17w0:APA91bEYVQdSVx-rNhP7N7l3V4TzyNg4KucTot6MvK7kO5JF4uNkt-DCnrK3WhmmYrDsvq46DRb31-9t0W27GS3ss4Vsw1Dycz-lysXNx9hjja0bA_-lJHkyKiM3VywLcbujdYjJ23L9'),
(13, 'ed rod per', '1970-01-01', 'ed', '$2y$10$mFjKqWBUI1J7dCvjyWLXBeWSPEJ5B1lbAwSAzhptt20I.RqgXHAOe', 'erp.renow@hotmail.com', '9611545746', NULL, '2020-05-08 13:59:53', NULL, 'ed', '1111', 'http://washdryapp.com/oficial/Perfiles/1588946391-washer.jpg', 'dX_9Il2IqtA:APA91bF642sfgdrtcQLRfxWojG4B8hmmMbmDrEgrzxrJcX7QIESDoVCH8DScJilkTBe_6RaNz3Z0Sg0G06xvU-HwWiIgvXyyztrStHIFs6oeWNXGqcDarTa49tx9ntL40RhWEUgih-HX'),
(14, 'rolando vera vera', '2003-12-17', 'rolando', '$2y$10$gL6XqQ/quAJiZuP3pIkh9eYVOAuQPA.0vDUNWRM11487nwDytOUd.', 'vera@gmail.com', NULL, NULL, '2020-05-11 16:51:40', NULL, 'rolando', '121212', 'http://washdryapp.com/oficial/Perfiles/1589215899-washer_2.jpg', 'evgF-bf-NG8:APA91bGI5EKmdHLoiEtVzeoKBjP8Vin00x4n908LUb_4LbKAquU2p5g0EFJTe0YmWnBsZ7THKcUovA9Eq_9vuDOjHrG5yOsm99XnyTDSgKDSO07-PPVMgtaawTljKSxLQVt4aXkC3nCb');

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
  `telefono` varchar(12) NOT NULL,
  `pago_status` tinyint(1) NOT NULL DEFAULT '1',
  `status_washer` tinyint(1) NOT NULL DEFAULT '1',
  `monto_pago` decimal(10,2) DEFAULT NULL,
  `calificacion` int(2) DEFAULT '0',
  `foto_ine` varchar(255) DEFAULT NULL,
  `fca_nacimiento` date NOT NULL,
  `id_paquete` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `washers`
--

INSERT INTO `washers` (`id_washer`, `id_usuario`, `nombre`, `app`, `apm`, `telefono`, `pago_status`, `status_washer`, `monto_pago`, `calificacion`, `foto_ine`, `fca_nacimiento`, `id_paquete`, `created_at`) VALUES
(1, 3, 'Marifer', 'fernandez', 'Rosas', '2222558588', 1, 1, NULL, 0, NULL, '1991-05-28', NULL, '2020-04-17 03:46:57'),
(2, 5, 'Daniel', 'González', 'Ramírez', '2225404907', 1, 1, NULL, 0, NULL, '2003-12-15', NULL, '2020-04-18 14:42:57'),
(7, 10, 'EThoP', 'OniOphanim', 'Rawrdriguez', '9611545746', 1, 1, NULL, 0, NULL, '2003-12-31', NULL, '2020-04-24 15:29:21'),
(8, 13, 'ed', 'rod', 'per', '9611545746', 1, 1, NULL, 0, NULL, '1970-01-01', NULL, '2020-05-08 13:59:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agendas`
--
ALTER TABLE `agendas`
  ADD PRIMARY KEY (`id_agenda`);

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
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calificacion`);

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
-- AUTO_INCREMENT de la tabla `agendas`
--
ALTER TABLE `agendas`
  MODIFY `id_agenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `autos`
--
ALTER TABLE `autos`
  MODIFY `id_auto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `bitacoras_washers`
--
ALTER TABLE `bitacoras_washers`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `paquetes_lavado`
--
ALTER TABLE `paquetes_lavado`
  MODIFY `id_paquete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles_user`
--
ALTER TABLE `roles_user`
  MODIFY `idRolUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `washers`
--
ALTER TABLE `washers`
  MODIFY `id_washer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
