-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 05:52:40
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
-- Base de datos: `escine`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `correo`, `contrasena`) VALUES
(6, 'Aldrin', 'aldrin17@gmail.com', '$2y$10$8B7Yh8kpst1mN4RsNGgvI.mWpWa5fKW8TPxLxnA6AEQxgJtr79E4K'),
(7, 'Erick', 'holaMundo@gmail.com', '$2y$10$WG.ZHKwSftJIAHnB73OPP.PhUYtF8XdzbkrkTGeni6kT05BgDZU.C'),
(8, 'Charly', 'charly@gmail.com', '$2y$10$mBjxT8ahtU49BUERL8nTc.IqkUfMc8SGZ5EVxxHJLafi4HkXgLtmq'),
(10, 'MeCaigoAlMar', 'edwing@gmail.com', '$2y$10$CIosFxJ08WJJrU3fbS5sGOlxoJaCxE4Dvqam0.xjLHkQdYUKzD8Wm'),
(11, 'giuseph', 'junit@gmail.com', '$2y$10$FUyAKeXyDIOSNFvPCni0POpSAbO6sro97ThxGbRGt8xfo7ejaV1V6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo_unico` (`correo`),
  ADD UNIQUE KEY `usuario_unico` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
