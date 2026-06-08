-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-06-2026 a las 23:39:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `camiseta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camiseta`
--

CREATE TABLE `camiseta` (
  `SKU` int(11) NOT NULL,
  `TITULO` varchar(100) NOT NULL,
  `CLUB` varchar(50) NOT NULL,
  `PAIS` varchar(80) NOT NULL,
  `TIPO` varchar(20) NOT NULL,
  `COLOR` varchar(50) NOT NULL,
  `PRECIO` int(11) NOT NULL,
  `TALLAS` varchar(50) NOT NULL,
  `DETALLES` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `camiseta`
--

INSERT INTO `camiseta` (`SKU`, `TITULO`, `CLUB`, `PAIS`, `TIPO`, `COLOR`, `PRECIO`, `TALLAS`, `DETALLES`) VALUES
(1001, 'Camiseta Local 2025 - Seleccion Chilena', 'Seleccion Chilena', 'Chile', 'Local', 'Rojo y Azul', 45000, 'S,M,L,XL', 'Edicion aniversario 2025'),
(1002, 'Camiseta Visita 2025 - Real Madrid', 'Real Madrid', 'España', 'Visita', 'Blanco', 65000, 'S,M,L,XL,XXL', 'Temporada 2024-2025'),
(1003, 'Camiseta Local 2025 - Colo Colo', 'Colo Colo', 'Chile', 'Local', 'Blanco y Negro', 38000, 'S,M,L,XL', 'Temporada 2025'),
(1004, 'Camiseta Femenino Local - Universidad de Chile', 'Universidad de Chile', 'Chile', 'Femenino Local', 'Azul', 32000, 'XS,S,M,L', 'Edicion femenina 2025');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `camiseta`
--
ALTER TABLE `camiseta`
  ADD PRIMARY KEY (`SKU`),
  ADD UNIQUE KEY `SKU` (`SKU`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
