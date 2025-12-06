-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2025 a las 23:02:38
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `publicar_reseña` (IN `p_idUsuario` INT, IN `p_idContenido` INT, IN `p_comentario` TEXT, IN `p_calificacion` INT, IN `p_tieneSpoiler` TINYINT)   BEGIN
    INSERT INTO `reseña`(idUsuario, idCont, comentario, calificacion, tieneSpoiler)
    VALUES(p_idUsuario, p_idContenido, p_comentario, p_calificacion, p_tieneSpoiler);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido`
--

CREATE TABLE `contenido` (
  `idCont` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `promedio` float NOT NULL,
  `poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contenido`
--

INSERT INTO `contenido` (`idCont`, `titulo`, `tipo`, `promedio`, `poster`) VALUES
(533533, 'TRON: Ares', 'movie', 0, 'https://image.tmdb.org/t/p/w500/dz1PbMrkpVhURKtvv7w2Ib1iZDK.jpg'),
(1084242, 'Zootopia 2', 'movie', 0, '/LrMBxFwnsMgXTnaWqHYinn3vDN.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista`
--

CREATE TABLE `lista` (
  `idLista` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idCont` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lista`
--

INSERT INTO `lista` (`idLista`, `idUsuario`, `idCont`, `tipo`, `fecha`) VALUES
(1, 14, 1196573, 'movie', '2025-12-04 09:00:49'),
(2, 14, 1622, 'tv', '2025-12-04 09:01:40'),
(3, 14, 278, 'movie', '2025-12-04 09:04:22'),
(4, 12, 1208561, 'movie', '2025-12-04 18:38:40'),
(5, 15, 1448560, 'movie', '2025-12-05 19:25:03'),
(6, 15, 1309012, 'movie', '2025-12-05 19:25:12'),
(8, 15, 2734, 'movie', '2025-12-05 19:27:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseña`
--

CREATE TABLE `reseña` (
  `idUsuario` int(11) NOT NULL,
  `idCont` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `calificacion` int(11) NOT NULL,
  `tieneSpoiler` tinyint(1) NOT NULL,
  `fechaC` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reseña`
--

INSERT INTO `reseña` (`idUsuario`, `idCont`, `comentario`, `calificacion`, `tieneSpoiler`, `fechaC`) VALUES
(12, 1033462, 'koiho', 6, 1, '2025-12-05 19:05:14'),
(12, 1083637, 'daewaw', 4, 1, '2025-12-05 19:05:14'),
(12, 1084242, 'muy mala', 5, 1, '2025-12-05 19:05:14'),
(12, 1208561, 'cdasx', 5, 1, '2025-12-05 19:05:14'),
(12, 1363123, 'gola', 6, 1, '2025-12-05 19:05:14'),
(12, 1419406, 'ij', 6, 1, '2025-12-05 19:05:14'),
(13, 967941, 'aburrida', 5, 1, '2025-12-05 19:05:14'),
(14, 431, 'hola mundo', 5, 1, '2025-12-05 19:05:14'),
(14, 1622, 'hola mundo 3', 6, 1, '2025-12-05 19:05:14'),
(14, 1084242, 'muy nueva', 8, 1, '2025-12-05 19:05:14'),
(14, 1180831, 'hola mundo5', 5, 1, '2025-12-05 19:05:14'),
(15, 533533, 'Decente', 5, 0, '2025-12-05 19:17:12'),
(15, 1084242, 'Interesante propuesta pero bien', 7, 0, '2025-12-05 19:05:14'),
(15, 1180831, 'No me gustó mucho', 5, 0, '2025-12-05 20:35:52'),
(16, 1180831, 'q', 10, 0, '2025-12-05 22:00:33');

--
-- Disparadores `reseña`
--
DELIMITER $$
CREATE TRIGGER `actualizar_promedio` AFTER INSERT ON `reseña` FOR EACH ROW BEGIN
    UPDATE contenido
    SET promedio = (
        SELECT AVG(calificacion)
        FROM `reseña`
        WHERE idCont = NEW.idCont
    )
    WHERE idCont = NEW.idCont;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `correo`, `contrasena`, `avatar`) VALUES
(6, 'Aldrin', 'aldrin17@gmail.com', '$2y$10$8B7Yh8kpst1mN4RsNGgvI.mWpWa5fKW8TPxLxnA6AEQxgJtr79E4K', NULL),
(7, 'Erick', 'holaMundo@gmail.com', '$2y$10$WG.ZHKwSftJIAHnB73OPP.PhUYtF8XdzbkrkTGeni6kT05BgDZU.C', NULL),
(8, 'Charly', 'charly@gmail.com', '$2y$10$mBjxT8ahtU49BUERL8nTc.IqkUfMc8SGZ5EVxxHJLafi4HkXgLtmq', NULL),
(10, 'MeCaigoAlMar', 'edwing@gmail.com', '$2y$10$CIosFxJ08WJJrU3fbS5sGOlxoJaCxE4Dvqam0.xjLHkQdYUKzD8Wm', NULL),
(11, 'giuseph', 'junit@gmail.com', '$2y$10$FUyAKeXyDIOSNFvPCni0POpSAbO6sro97ThxGbRGt8xfo7ejaV1V6', NULL),
(12, 'GokuSuperSaiyan', 'usuariofacil@gmail.com', '$2y$10$hz/CEViiJQVo5pTy/eZtaeYdR8s5NXSCUpjN4vfyG2L5g2NV6HPo2', NULL),
(13, 'usuariofacil2@g', 'usuariofacil2@gmail.com', '$2y$10$SPBJT2VZlVr.5b5QkhLGOuCNbOtOopm7NvlQ9NiAusn4UPuz1VHDm', NULL),
(14, 'usuariofacil3@g', 'usuariofacil3@gmail.com', '$2y$10$T4htHhBgrUb/E2w4bCMJcegk6EI3gAUM5Q4uQNQdLRwl8j2tifBk.', NULL),
(15, 'michi', 'michi@gmail.com', '$2y$10$uIm1YGLectERJdI5NyhMauNIXUoIsbSMLIQBAOWAtyI/S0FJbHaqe', 'uploads/avatars/user_15_1764957987.png'),
(16, 'g', 'g@gmail.com', '$2y$10$7IL2KLAr9QOdCrJKQpoYDee1dyYQphmPLtss78g5mZN405xvoaV7.', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contenido`
--
ALTER TABLE `contenido`
  ADD PRIMARY KEY (`idCont`);

--
-- Indices de la tabla `lista`
--
ALTER TABLE `lista`
  ADD PRIMARY KEY (`idLista`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `reseña`
--
ALTER TABLE `reseña`
  ADD PRIMARY KEY (`idUsuario`,`idCont`),
  ADD KEY `idCont` (`idCont`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `correo_unico` (`correo`),
  ADD UNIQUE KEY `usuario_unico` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `lista`
--
ALTER TABLE `lista`
  MODIFY `idLista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lista`
--
ALTER TABLE `lista`
  ADD CONSTRAINT `lista_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reseña`
--
ALTER TABLE `reseña`
  ADD CONSTRAINT `reseña_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
