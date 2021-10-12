-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2021 a las 01:54:06
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `doña_julia_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `clave` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `mail`, `sexo`, `clave`) VALUES
(1, 'julia@mail.com', 'femenino', '1234'),
(2, 'maria@empleada', 'femenino', '12345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hortalizas`
--

CREATE TABLE `hortalizas` (
  `id` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `hortalizas`
--

INSERT INTO `hortalizas` (`id`, `precio`, `nombre`, `foto`, `tipo`) VALUES
(1, 90, 'rojo', 'verdesito.jpg', 'morron'),
(4, 30, 'naranjita', 'naranjita.jpg', 'zanahoria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `idHortaliza` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `idHortaliza`, `idEmpleado`, `fecha`, `cantidad`, `foto`) VALUES
(1, 1, 1, '2013-10-21', 3, '1.jfif'),
(2, 1, 1, '2013-10-21', 5, '1.jfif'),
(3, 1, 2, '2013-10-21', 5, '1.jfif'),
(4, 4, 2, '2013-10-21', 5, '4.jfif'),
(5, 4, 2, '2013-10-21', 5, '4.jfif'),
(6, 4, 2, '2013-10-21', 5, '4.jfif'),
(7, 4, 2, '2013-10-21', 5, '4maria@empleada.jfif'),
(8, 4, 1, '0000-00-00', 5, '4julia@mail.com.jfif'),
(9, 1, 1, '0000-00-00', 10, '1julia@mail.com.jfif'),
(10, 1, 1, '2021-10-13', 10, '1julia@mail.com.jfif');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `hortalizas`
--
ALTER TABLE `hortalizas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `hortalizas`
--
ALTER TABLE `hortalizas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
