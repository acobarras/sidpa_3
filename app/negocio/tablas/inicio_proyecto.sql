-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-06-2022 a las 17:24:52
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inicio_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permisos` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_modulo_hoja` int(11) NOT NULL,
  `nombre_m` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `estado_permisos` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombres` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_tipo_documento` int(100) NOT NULL,
  `num_documento` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(1) NOT NULL,
  `fecha_crea` date NOT NULL,
  `id_usuario_creador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombres`, `apellidos`, `id_tipo_documento`, `num_documento`, `estado`, `fecha_crea`, `id_usuario_creador`) VALUES
(1, 'EDWIN ALEJANDRO', 'RIOS GONZALEZ', 2, '80733735', 1, '2020-11-05', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta_hoja_proyecto`
--

CREATE TABLE `ruta_hoja_proyecto` (
  `id_hoja` int(11) NOT NULL,
  `nombre_hoja` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `posicion` int(11) DEFAULT NULL,
  `titulo` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `referencia_nombre` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo_peticion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `url` varchar(100) CHARACTER SET utf8 NOT NULL,
  `controlador` varchar(100) CHARACTER SET utf8 NOT NULL,
  `metodo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `color_icono` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nuevo` int(2) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_crea` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ruta_hoja_proyecto`
--

INSERT INTO `ruta_hoja_proyecto` (`id_hoja`, `nombre_hoja`, `posicion`, `titulo`, `referencia_nombre`, `tipo_peticion`, `url`, `controlador`, `metodo`, `icono`, `color_icono`, `nuevo`, `estado`, `fecha_crea`) VALUES
(1, NULL, NULL, NULL, NULL, 'post', '/autenticar', 'UsuarioControlador', 'autenticar', 'N/A', NULL, NULL, 1, '2021-01-27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) NOT NULL,
  `usuario` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pasword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_roll` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `estado_usu` int(1) NOT NULL DEFAULT 1,
  `fecha_crea` date NOT NULL,
  `id_usuario_crea` int(11) NOT NULL,
  `ruta_foto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_clave` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `nombre`, `apellido`, `pasword`, `id_roll`, `id_persona`, `estado_usu`, `fecha_crea`, `id_usuario_crea`, `ruta_foto`, `tipo_clave`) VALUES
(1, 'zurdo0703', 'EDWIN', 'RIOS', '0bffb9109a39ac133a7156bd00f73948', 1, 1, 1, '2018-11-21', 1, 'photo.jpg', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permisos`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `ruta_hoja_proyecto`
--
ALTER TABLE `ruta_hoja_proyecto`
  ADD PRIMARY KEY (`id_hoja`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ruta_hoja_proyecto`
--
ALTER TABLE `ruta_hoja_proyecto`
  MODIFY `id_hoja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
