-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 26-03-2018 a las 12:12:55
-- Versión del servidor: 5.7.19-log
-- Versión de PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `todosalacancha`
--
CREATE DATABASE IF NOT EXISTS `todosalacancha` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `todosalacancha`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `obtener_condiciones_puntos_fase`$$
CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `obtener_condiciones_puntos_fase` (IN `fase` INT, OUT `puntos_resultado` SMALLINT UNSIGNED, OUT `puntos_marcador` SMALLINT UNSIGNED, OUT `puntos_trivia` SMALLINT UNSIGNED, OUT `puntos_campeon` SMALLINT UNSIGNED, OUT `puntos_goleador` SMALLINT UNSIGNED)  BEGIN
    SELECT puntos_condicion_resultado, puntos_condicion_marcador, puntos_condicion_trivia, puntos_condicion_campeon, puntos_condicion_goleador
    INTO puntos_resultado, puntos_marcador, puntos_trivia, puntos_campeon, puntos_goleador
    FROM puntos_condiciones pc
    WHERE
        CASE
            WHEN fase = 1 THEN fase_id BETWEEN 1 AND 8
            WHEN fase = 12 THEN fase_id = 13
            ELSE fase_id = fase
        END;
END$$

DROP PROCEDURE IF EXISTS `registrar_puntos_campeon`$$
CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_campeon` (IN `equipo` INT)  BEGIN
    CALL obtener_condiciones_puntos_fase(13, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, equipo_id)
    SELECT empleado_id, @puntos_campeon, equipo_id
    FROM equipos e
        JOIN pronosticos_campeon pc ON(team_id = equipo_id)
    WHERE equipo_id = equipo
        AND campeon = '1';
END$$

DROP PROCEDURE IF EXISTS `registrar_puntos_goleador`$$
CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_goleador` (IN `jugador` INT)  BEGIN
    CALL obtener_condiciones_puntos_fase(13, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, jugador_id)
    SELECT empleado_id, @puntos_goleador, id_jugador
    FROM jugadores j
        JOIN pronosticos_goleador pg ON(id_jugador = jugador_id)
    WHERE j.id_jugador = jugador
        AND es_goleador = '1';
END$$

DROP PROCEDURE IF EXISTS `registrar_puntos_partido`$$
CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_partido` (IN `partido` INT, IN `fase` INT)  BEGIN
    CALL obtener_condiciones_puntos_fase(fase, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, partido_id)
    SELECT user_id, @puntos_resultado + IF(pr.home_goals = pa.home_goals AND pr.away_goals = pa.away_goals, @puntos_marcador, 0), pr.match_id
    FROM pronosticos pr
        JOIN partidos pa USING(match_id, resultado)
    WHERE match_id = partido;
END$$

DROP PROCEDURE IF EXISTS `registrar_puntos_trivia`$$
CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_trivia` (IN `trivia` INT, IN `fase` INT)  BEGIN
    DECLARE preguntas TINYINT UNSIGNED;
    CALL obtener_condiciones_puntos_fase(fase, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    SELECT COUNT(*) INTO preguntas FROM trivias_preguntas WHERE id_trivia = trivia;
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, trivia_id)
    SELECT empleado_id, SUM(puntos) * SUM(factor), trivia
    FROM (
        SELECT empleado_id, COALESCE(puntos_empleado_valor,0) AS puntos, 0 AS factor
        FROM puntos_empleados pe
            JOIN partidos p ON(partido_id = match_id)
        WHERE
            CASE
                WHEN fase = 1 THEN stage_id BETWEEN 1 AND 8
                WHEN fase = 12 THEN stage_id BETWEEN 12 AND 13
                ELSE stage_id = fase
            END
        UNION ALL
        SELECT id_empleado AS empleado_id, 0 AS puntos, @puntos_trivia AS factor
        FROM trivias_respuestas_empleados tre
            JOIN trivias_respuestas tr USING(id_respuesta)
            JOIN trivias t USING(id_trivia)
        WHERE id_trivia = trivia
            AND respuesta_correcta = '1'
        GROUP BY 1, id_trivia
        HAVING COUNT(*) = preguntas
    ) puntos_empleados_tmp
    GROUP BY 1
    HAVING SUM(factor) > 0;
END$$

--
-- Funciones
--
DROP FUNCTION IF EXISTS `obtener_resultado_partido`$$
CREATE DEFINER=`todosalacancha`@`localhost` FUNCTION `obtener_resultado_partido` (`goles_local` TINYINT UNSIGNED, `goles_visitante` TINYINT UNSIGNED) RETURNS TINYINT(3) UNSIGNED NO SQL
    DETERMINISTIC
BEGIN
    DECLARE resultado TINYINT;
    IF goles_local > goles_visitante THEN
        SET resultado = 1;
    ELSEIF goles_local < goles_visitante THEN
        SET resultado = 2;
    ELSE
        SET resultado = 0;
    END IF;
    RETURN resultado;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

DROP TABLE IF EXISTS `empleados`;
CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `user` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `pass` char(32) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `correo_e` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `departamento` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ubicacion` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `imagen_perfil` char(36) COLLATE latin1_spanish_ci DEFAULT NULL,
  `state` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0',
  `acepto_bases` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_import`
--

DROP TABLE IF EXISTS `empleados_import`;
CREATE TABLE `empleados_import` (
  `id_empresa` int(11) NOT NULL,
  `linea` mediumint(8) UNSIGNED NOT NULL,
  `user` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `pass` char(32) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `correo_e` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `departamento` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ubicacion` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

DROP TABLE IF EXISTS `empresas`;
CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL,
  `empresa` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `bases_condiciones` text COLLATE latin1_spanish_ci,
  `url` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `is_trivia` enum('0','1') COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas_imagenes`
--

DROP TABLE IF EXISTS `empresas_imagenes`;
CREATE TABLE `empresas_imagenes` (
  `id_empresa` int(11) NOT NULL,
  `tipo_imagen` enum('11','12','21','22','31','32','41','42','51','52','61','62','71','72','81','82') COLLATE latin1_spanish_ci NOT NULL COMMENT 'XY => X = 1:logo, 2:premio ... 8:slider#5; Y = 1:computadora, 2:móviles',
  `nombre_archivo` varchar(15) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

DROP TABLE IF EXISTS `equipos`;
CREATE TABLE `equipos` (
  `team_id` int(20) NOT NULL,
  `name` varchar(64) COLLATE latin1_spanish_ci NOT NULL,
  `country` char(3) COLLATE latin1_spanish_ci NOT NULL,
  `team_url` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `group_order` int(11) NOT NULL,
  `campeon` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0',
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`team_id`, `name`, `country`, `team_url`, `group_order`, `campeon`, `wwhen`) VALUES
(1, 'Rusia', 'RUS', 'http://es.fifa.com/worldcup/teams/team=43965/index.html', 1, '0', '2018-03-15 14:42:11'),
(2, 'Arabia Saudita', 'KSA', 'http://es.fifa.com/worldcup/teams/team=43835/index.html', 1, '0', '2018-03-13 16:47:35'),
(3, 'Egipto', 'EGY', 'http://es.fifa.com/worldcup/teams/team=43855/index.html', 1, '0', '2018-03-12 01:41:54'),
(4, 'Uruguay', 'URU', 'http://es.fifa.com/worldcup/teams/team=43930/index.html', 1, '0', '2018-03-12 01:41:54'),
(5, 'Portugal', 'POR', 'http://es.fifa.com/worldcup/teams/team=43963/index.html', 2, '0', '2018-03-12 01:41:54'),
(6, 'España', 'ESP', 'http://es.fifa.com/worldcup/teams/team=43969/index.html', 2, '0', '2018-03-19 07:24:11'),
(7, 'Marruecos', 'MAR', 'http://es.fifa.com/worldcup/teams/team=43872/index.html', 2, '0', '2018-03-12 01:41:54'),
(8, 'Irán', 'IRN', 'http://es.fifa.com/worldcup/teams/team=43817/index.html', 2, '0', '2018-03-12 01:57:51'),
(9, 'Francia', 'FRA', 'http://es.fifa.com/worldcup/teams/team=43946/index.html', 3, '0', '2018-03-12 01:41:54'),
(10, 'Australia', 'AUS', 'http://es.fifa.com/worldcup/teams/team=43976/index.html', 3, '0', '2018-03-12 01:41:54'),
(11, 'Perú', 'PER', 'http://es.fifa.com/worldcup/teams/team=43929/index.html', 3, '0', '2018-03-13 16:44:58'),
(12, 'Dinamarca', 'DEN', 'http://es.fifa.com/worldcup/teams/team=43941/index.html', 3, '0', '2018-03-12 01:41:54'),
(13, 'Argentina', 'ARG', 'http://es.fifa.com/worldcup/teams/team=43922/index.html', 4, '0', '2018-03-19 07:24:47'),
(14, 'Islandia', 'ISL', 'http://es.fifa.com/worldcup/teams/team=43951/index.html', 4, '0', '2018-03-12 01:41:54'),
(15, 'Croacia', 'CRO', 'http://es.fifa.com/worldcup/teams/team=43938/index.html', 4, '0', '2018-03-12 01:41:54'),
(16, 'Nigeria', 'NEG', 'http://es.fifa.com/worldcup/teams/team=43876/index.html', 4, '0', '2018-03-12 01:41:54'),
(17, 'Brasil', 'BRA', 'http://es.fifa.com/worldcup/teams/team=43924/index.html', 5, '0', '2018-03-12 01:41:54'),
(18, 'Suiza', 'SUI', 'http://es.fifa.com/worldcup/teams/team=43971/index.html', 5, '0', '2018-03-12 01:41:54'),
(19, 'Costa Rica', 'CRC', 'http://es.fifa.com/worldcup/teams/team=43901/index.html', 5, '0', '2018-03-12 01:41:54'),
(20, 'Serbia', 'SRB', 'http://es.fifa.com/worldcup/teams/team=1902465/index.html', 5, '0', '2018-03-12 01:41:54'),
(21, 'Alemania', 'ALE', 'http://es.fifa.com/worldcup/teams/team=43948/index.html', 6, '0', '2018-03-12 01:41:54'),
(22, 'Mexico', 'MEX', 'http://es.fifa.com/worldcup/teams/team=43911/index.html', 6, '0', '2018-03-12 01:41:54'),
(23, 'Suecia', 'SWE', 'http://es.fifa.com/worldcup/teams/team=43970/index.html', 6, '0', '2018-03-12 01:41:54'),
(24, 'República de Corea', 'KOR', 'http://es.fifa.com/worldcup/teams/team=43822/index.html', 6, '0', '2018-03-12 01:58:16'),
(25, 'Bélgica', 'BEL', 'http://es.fifa.com/worldcup/teams/team=43935/index.html', 7, '0', '2018-03-12 01:58:22'),
(26, 'Panamá', 'PAN', 'http://es.fifa.com/worldcup/teams/team=43914/index.html', 7, '0', '2018-03-12 01:58:30'),
(27, 'Túnez', 'TUN', 'http://es.fifa.com/worldcup/teams/team=43888/index.html', 7, '0', '2018-03-12 01:58:36'),
(28, 'Inglaterra', 'ENG', 'http://es.fifa.com/worldcup/teams/team=43942/index.html', 7, '0', '2018-03-12 01:41:54'),
(29, 'Polonia', 'POL', 'http://es.fifa.com/worldcup/teams/team=43962/index.html', 8, '0', '2018-03-12 01:41:54'),
(30, 'Senegal', 'SEN', 'http://es.fifa.com/worldcup/teams/team=43879/index.html', 8, '0', '2018-03-12 01:41:54'),
(31, 'Colombia', 'COL', 'http://es.fifa.com/worldcup/teams/team=43926/index.html', 8, '0', '2018-03-12 01:41:54'),
(32, 'Japón', 'JAP', 'http://es.fifa.com/worldcup/teams/team=43819/index.html', 8, '0', '2018-03-12 01:58:42'),
(33, 'Ganador Grupo A', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(34, 'Ganador Grupo B', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(35, 'Ganador Grupo C', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(36, 'Ganador Grupo D', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(37, 'Ganador Grupo E', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(38, 'Ganador Grupo F', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(39, 'Ganador Grupo G', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(40, 'Ganador Grupo H', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(41, 'Segundo de Grupo A', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(42, 'Segundo de Grupo B', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(43, 'Segundo de Grupo C', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(44, 'Segundo de Grupo D', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(45, 'Segundo de Grupo E', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(46, 'Segundo de Grupo F', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(47, 'Segundo de Grupo G', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(48, 'Segundo de Grupo H', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(49, 'Ganador Partido 49', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(50, 'Ganador Partido 50', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(51, 'Ganador Partido 51', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(52, 'Ganador Partido 52', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(53, 'Ganador Partido 53', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(54, 'Ganador Partido 54', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(55, 'Ganador Partido 55', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(56, 'Ganador Partido 56', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(57, 'Ganador Partido 57', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(58, 'Ganador Partido 58', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(59, 'Ganador Partido 59', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(60, 'Ganador Partido 60', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(61, 'Perdedor Partido 61', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(62, 'Perdedor Partido 62', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(63, 'Ganador Partido 61', 'xxx', '', 0, '0', '2018-03-12 01:41:54'),
(64, 'Ganador Partido 62', 'xxx', '', 0, '0', '2018-03-12 01:41:54');

--
-- Disparadores `equipos`
--
DROP TRIGGER IF EXISTS `registrar_puntos_campeon`;
DELIMITER $$
CREATE TRIGGER `registrar_puntos_campeon` AFTER UPDATE ON `equipos` FOR EACH ROW BEGIN
    IF NEW.campeon = '1' THEN
        CALL registrar_puntos_campeon(NEW.team_id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_grupo`
--

DROP TABLE IF EXISTS `equipo_grupo`;
CREATE TABLE `equipo_grupo` (
  `equipo_grupo_id` int(20) NOT NULL,
  `team_id` int(20) NOT NULL,
  `grupo_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fases`
--

DROP TABLE IF EXISTS `fases`;
CREATE TABLE `fases` (
  `stage_id` int(11) NOT NULL,
  `stage_name` varchar(32) COLLATE latin1_spanish_ci NOT NULL,
  `is_group` tinyint(1) NOT NULL,
  `sort_order` tinyint(4) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `fases`
--

INSERT INTO `fases` (`stage_id`, `stage_name`, `is_group`, `sort_order`, `wwhen`) VALUES
(1, 'Grupo A', 1, 1, '2014-01-17 10:03:57'),
(2, 'Grupo B', 1, 2, '2014-01-17 10:04:06'),
(3, 'Grupo C', 1, 3, '2014-01-17 10:04:15'),
(4, 'Grupo D', 1, 4, '2014-01-17 10:04:24'),
(5, 'Grupo E', 1, 5, '2014-01-17 10:04:33'),
(6, 'Grupo F', 1, 6, '2014-01-17 10:04:44'),
(7, 'Grupo G', 1, 7, '2014-01-17 10:05:07'),
(8, 'Grupo H', 1, 8, '2014-01-17 10:05:22'),
(9, 'Octavos de Final', 0, 9, '2014-01-17 10:05:34'),
(10, 'Cuartos de Final', 0, 10, '2014-01-17 10:05:46'),
(11, 'Semi Finales', 0, 11, '2014-01-17 10:05:58'),
(12, 'Tercer Puesto', 0, 12, '2014-01-17 10:07:01'),
(13, 'Final', 0, 13, '2014-01-17 10:07:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gropus`
--

DROP TABLE IF EXISTS `gropus`;
CREATE TABLE `gropus` (
  `grupo_id` int(20) NOT NULL,
  `grupo` varchar(200) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadores`
--

DROP TABLE IF EXISTS `jugadores`;
CREATE TABLE `jugadores` (
  `id_jugador` int(11) NOT NULL,
  `nombre_jugador` varchar(60) COLLATE latin1_spanish_ci NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `goles` smallint(2) NOT NULL DEFAULT '0',
  `es_goleador` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Disparadores `jugadores`
--
DROP TRIGGER IF EXISTS `registrar_puntos_goleador`;
DELIMITER $$
CREATE TRIGGER `registrar_puntos_goleador` AFTER UPDATE ON `jugadores` FOR EACH ROW BEGIN
    IF NEW.es_goleador = '1' THEN
        CALL registrar_puntos_goleador(NEW.id_jugador);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecanica_juego`
--

DROP TABLE IF EXISTS `mecanica_juego`;
CREATE TABLE `mecanica_juego` (
  `id` int(20) NOT NULL,
  `rules` text COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `mod_modulegroupcode` varchar(25) CHARACTER SET utf8 NOT NULL,
  `mod_modulegroupname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `mod_modulecode` varchar(25) CHARACTER SET utf8 NOT NULL,
  `mod_modulename` varchar(50) CHARACTER SET utf8 NOT NULL,
  `mod_modulegrouporder` int(3) NOT NULL,
  `mod_moduleorder` int(3) NOT NULL,
  `mod_modulepagename` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `module`
--

INSERT INTO `module` (`mod_modulegroupcode`, `mod_modulegroupname`, `mod_modulecode`, `mod_modulename`, `mod_modulegrouporder`, `mod_moduleorder`, `mod_modulepagename`) VALUES
('CHECKOUT', 'Checkout', 'PAYMENT', 'Payment', 3, 2, 'payment.php'),
('CHECKOUT', 'Checkout', 'SHIPPING', 'Shipping', 3, 1, 'shipping.php'),
('CHECKOUT', 'Checkout', 'TAX', 'Tax', 3, 3, 'tax.php'),
('INVT', 'Inventory', 'PURCHASES', 'Purchases', 2, 1, 'purchases.php'),
('INVT', 'Inventory', 'SALES', 'Sales', 2, 3, 'sales.php'),
('INVT', 'Inventory', 'STOCKS', 'Stocks', 2, 2, 'stocks.php');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidos`
--

DROP TABLE IF EXISTS `partidos`;
CREATE TABLE `partidos` (
  `match_id` int(11) NOT NULL,
  `match_no` int(11) NOT NULL,
  `kickoff` datetime NOT NULL,
  `home_team_id` int(11) NOT NULL,
  `away_team_id` int(11) NOT NULL,
  `home_goals` int(11) NOT NULL,
  `away_goals` int(11) NOT NULL,
  `home_penalties` int(11) NOT NULL,
  `away_penalties` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `is_result` tinyint(1) NOT NULL DEFAULT '0',
  `extra_time` tinyint(1) NOT NULL DEFAULT '0',
  `stage_id` int(11) NOT NULL,
  `scored` tinyint(1) NOT NULL DEFAULT '0',
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `resultado` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:empate, 1:local, 2:visitante'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `partidos`
--

INSERT INTO `partidos` (`match_id`, `match_no`, `kickoff`, `home_team_id`, `away_team_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `venue_id`, `is_result`, `extra_time`, `stage_id`, `scored`, `wwhen`, `resultado`) VALUES
(1, 1, '2018-06-14 12:00:00', 1, 2, 0, 0, 0, 0, 8, 0, 0, 1, 0, '2018-03-15 15:54:54', 0),
(2, 2, '2018-06-15 09:00:00', 3, 4, 0, 0, 0, 0, 4, 0, 0, 1, 0, '2018-03-15 15:25:45', 0),
(3, 3, '2018-06-15 12:00:00', 7, 8, 0, 0, 0, 0, 1, 0, 0, 2, 0, '2018-01-19 00:01:18', 0),
(4, 4, '2018-06-15 15:00:00', 5, 6, 0, 0, 0, 0, 5, 0, 0, 2, 0, '2018-01-19 00:02:05', 0),
(5, 5, '2018-06-16 07:00:00', 9, 10, 0, 0, 0, 0, 6, 0, 0, 3, 0, '2018-01-31 19:22:36', 0),
(6, 6, '2018-06-16 10:00:00', 13, 14, 0, 0, 0, 0, 11, 0, 0, 4, 0, '2018-01-19 00:03:49', 0),
(7, 7, '2018-06-16 13:00:00', 11, 12, 0, 0, 0, 0, 13, 0, 0, 3, 0, '2018-01-19 00:04:50', 0),
(8, 8, '2018-06-16 21:00:00', 15, 16, 0, 0, 0, 0, 2, 0, 0, 4, 0, '2018-01-19 00:06:16', 0),
(9, 9, '2018-06-17 09:00:00', 19, 20, 0, 0, 0, 0, 9, 0, 0, 5, 0, '2018-01-19 18:34:44', 0),
(10, 10, '2018-06-17 12:00:00', 21, 22, 0, 0, 0, 0, 8, 0, 0, 6, 0, '2018-01-31 19:22:41', 0),
(11, 11, '2018-06-17 15:00:00', 17, 18, 0, 0, 0, 0, 10, 0, 0, 5, 0, '2018-01-19 18:36:49', 0),
(12, 12, '2018-06-18 09:00:00', 23, 24, 0, 0, 0, 0, 7, 0, 0, 6, 0, '2018-02-06 20:57:28', 0),
(13, 13, '2018-06-18 12:00:00', 25, 26, 0, 0, 0, 0, 5, 0, 0, 7, 0, '2018-01-19 18:55:11', 0),
(14, 14, '2018-06-18 15:00:00', 27, 28, 0, 0, 0, 0, 3, 0, 0, 7, 0, '2018-02-06 20:57:36', 0),
(15, 15, '2018-06-19 09:00:00', 31, 32, 0, 0, 0, 0, 13, 0, 0, 8, 0, '2018-01-19 18:57:38', 0),
(16, 16, '2018-06-19 12:00:00', 29, 30, 0, 0, 0, 0, 11, 0, 0, 8, 0, '2018-01-19 18:58:36', 0),
(17, 17, '2018-06-19 15:00:00', 1, 3, 0, 0, 0, 0, 1, 0, 0, 1, 0, '2018-01-19 18:59:16', 0),
(18, 18, '2018-06-20 09:00:00', 5, 7, 0, 0, 0, 0, 8, 0, 0, 2, 0, '2018-01-19 19:01:16', 0),
(19, 19, '2018-06-20 12:00:00', 4, 2, 0, 0, 0, 0, 10, 0, 0, 1, 0, '2018-01-19 19:02:07', 0),
(20, 20, '2018-06-20 15:00:00', 8, 6, 0, 0, 0, 0, 6, 0, 0, 2, 0, '2018-01-19 19:03:18', 0),
(21, 21, '2018-06-21 09:00:00', 12, 10, 0, 0, 0, 0, 9, 0, 0, 3, 0, '2018-01-19 19:04:11', 0),
(22, 22, '2018-06-21 12:00:00', 9, 11, 0, 0, 0, 0, 4, 0, 0, 3, 0, '2018-01-19 20:18:31', 0),
(23, 23, '2018-06-21 15:00:00', 13, 15, 0, 0, 0, 0, 7, 0, 0, 4, 0, '2018-01-19 20:19:17', 0),
(24, 24, '2018-06-22 09:00:00', 17, 19, 0, 0, 0, 0, 12, 0, 0, 5, 0, '2018-01-19 20:20:27', 0),
(25, 25, '2018-06-22 12:00:00', 16, 14, 0, 0, 0, 0, 3, 0, 0, 4, 0, '2018-01-19 20:21:10', 0),
(26, 26, '2018-06-22 15:00:00', 20, 18, 0, 0, 0, 0, 2, 0, 0, 5, 0, '2018-01-19 20:21:52', 0),
(27, 27, '2018-06-23 09:00:00', 25, 27, 0, 0, 0, 0, 11, 0, 0, 7, 0, '2018-01-19 20:22:53', 0),
(28, 28, '2018-06-23 12:00:00', 24, 22, 0, 0, 0, 0, 10, 0, 0, 6, 0, '2018-01-19 20:24:39', 0),
(29, 29, '2018-06-23 15:00:00', 21, 23, 0, 0, 0, 0, 5, 0, 0, 6, 0, '2018-01-19 20:25:18', 0),
(30, 30, '2018-06-24 09:00:00', 28, 26, 0, 0, 0, 0, 7, 0, 0, 7, 0, '2018-01-19 20:26:04', 0),
(31, 31, '2018-06-24 12:00:00', 32, 30, 0, 0, 0, 0, 4, 0, 0, 8, 0, '2018-01-19 20:26:44', 0),
(32, 32, '2018-06-24 15:00:00', 29, 31, 0, 0, 0, 0, 6, 0, 0, 8, 0, '2018-01-19 20:27:30', 0),
(33, 33, '2018-06-25 11:00:00', 4, 1, 0, 0, 0, 0, 9, 0, 0, 1, 0, '2018-01-19 20:28:45', 0),
(34, 34, '2018-06-25 11:00:00', 2, 3, 0, 0, 0, 0, 3, 0, 0, 1, 0, '2018-01-19 20:29:27', 0),
(35, 35, '2018-06-25 15:00:00', 8, 5, 0, 0, 0, 0, 13, 0, 0, 2, 0, '2018-01-19 20:30:15', 0),
(36, 36, '2018-06-25 15:00:00', 6, 7, 0, 0, 0, 0, 2, 0, 0, 2, 0, '2018-01-19 20:30:58', 0),
(37, 37, '2018-06-26 11:00:00', 12, 9, 0, 0, 0, 0, 8, 0, 0, 3, 0, '2018-01-19 20:31:34', 0),
(38, 38, '2018-06-26 11:00:00', 10, 11, 0, 0, 0, 0, 5, 0, 0, 3, 0, '2018-01-19 20:32:13', 0),
(39, 39, '2018-06-26 15:00:00', 16, 13, 0, 0, 0, 0, 1, 0, 0, 4, 0, '2018-01-19 20:32:53', 0),
(40, 40, '2018-06-26 15:00:00', 14, 15, 0, 0, 0, 0, 10, 0, 0, 4, 0, '2018-01-19 20:33:20', 0),
(41, 41, '2018-06-27 11:00:00', 22, 23, 0, 0, 0, 0, 4, 0, 0, 6, 0, '2018-01-19 20:34:29', 0),
(42, 42, '2018-06-27 11:00:00', 24, 21, 0, 0, 0, 0, 6, 0, 0, 6, 0, '2018-01-19 20:35:05', 0),
(43, 43, '2018-06-27 15:00:00', 20, 17, 0, 0, 0, 0, 11, 0, 0, 5, 0, '2018-01-19 20:35:37', 0),
(44, 44, '2018-06-27 15:00:00', 18, 19, 0, 0, 0, 0, 7, 0, 0, 5, 0, '2018-01-19 20:36:16', 0),
(45, 45, '2018-06-28 11:00:00', 32, 29, 0, 0, 0, 0, 3, 0, 0, 8, 0, '2018-01-19 20:36:51', 0),
(46, 46, '2018-06-28 11:00:00', 30, 31, 0, 0, 0, 0, 9, 0, 0, 8, 0, '2018-01-19 20:37:25', 0),
(47, 47, '2018-06-28 15:00:00', 26, 27, 0, 0, 0, 0, 13, 0, 0, 7, 0, '2018-01-19 20:37:59', 0),
(48, 48, '2018-06-28 15:00:00', 28, 25, 0, 0, 0, 0, 2, 0, 0, 7, 0, '2018-01-19 20:38:41', 0),
(49, 49, '2018-06-30 11:00:00', 35, 44, 0, 0, 0, 0, 2, 0, 0, 9, 0, '2018-01-31 19:22:28', 0),
(50, 50, '2018-06-30 15:00:00', 33, 42, 0, 0, 0, 0, 5, 0, 0, 9, 0, '2018-01-19 20:42:51', 0),
(51, 51, '2018-07-01 11:00:00', 34, 41, 0, 0, 0, 0, 8, 0, 0, 9, 0, '2018-01-19 20:43:24', 0),
(52, 52, '2018-07-01 15:00:00', 36, 43, 0, 0, 0, 0, 7, 0, 0, 9, 0, '2018-01-19 20:44:06', 0),
(53, 53, '2018-07-02 11:00:00', 37, 46, 0, 0, 0, 0, 9, 0, 0, 9, 0, '2018-01-19 20:44:53', 0),
(54, 54, '2018-07-02 15:00:00', 39, 48, 0, 0, 0, 0, 10, 0, 0, 9, 0, '2018-01-19 20:45:22', 0),
(55, 55, '2018-07-03 11:00:00', 38, 45, 0, 0, 0, 0, 1, 0, 0, 9, 0, '2018-01-19 20:46:02', 0),
(56, 56, '2018-07-03 15:00:00', 40, 47, 0, 0, 0, 0, 11, 0, 0, 9, 0, '2018-01-19 20:46:46', 0),
(57, 57, '2018-07-06 11:00:00', 49, 50, 0, 0, 0, 0, 7, 0, 0, 10, 0, '2018-01-19 20:47:41', 0),
(58, 58, '2018-07-06 15:00:00', 53, 54, 0, 0, 0, 0, 6, 0, 0, 10, 0, '2018-01-19 20:48:37', 0),
(59, 59, '2018-07-07 11:00:00', 55, 56, 0, 0, 0, 0, 9, 0, 0, 10, 0, '2018-01-19 20:49:19', 0),
(60, 60, '2018-07-07 15:00:00', 51, 52, 0, 0, 0, 0, 5, 0, 0, 10, 0, '2018-01-19 20:49:52', 0),
(61, 61, '2018-07-10 15:00:00', 57, 58, 0, 0, 0, 0, 5, 0, 0, 11, 0, '2018-01-19 20:51:12', 0),
(62, 62, '2018-07-11 15:00:00', 59, 60, 0, 0, 0, 0, 8, 0, 0, 11, 0, '2018-01-19 20:52:04', 0),
(63, 63, '2018-07-14 11:00:00', 61, 62, 0, 0, 0, 0, 1, 0, 0, 12, 0, '2018-01-19 20:53:22', 0),
(64, 64, '2018-07-15 12:00:00', 21, 13, 1, 3, 0, 0, 8, 0, 0, 13, 1, '2018-02-23 22:39:02', 0);

--
-- Disparadores `partidos`
--
DROP TRIGGER IF EXISTS `actualizar_resultado`;
DELIMITER $$
CREATE TRIGGER `actualizar_resultado` BEFORE UPDATE ON `partidos` FOR EACH ROW BEGIN
    IF NEW.scored = 1 THEN
        SET NEW.resultado = obtener_resultado_partido(NEW.home_goals, NEW.away_goals);
    ELSE
        SET NEW.resultado = 0;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `registrar_puntos_partido`;
DELIMITER $$
CREATE TRIGGER `registrar_puntos_partido` AFTER UPDATE ON `partidos` FOR EACH ROW BEGIN
    IF NEW.scored = 1 THEN
        CALL registrar_puntos_partido(NEW.match_id, NEW.stage_id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pronosticos`
--

DROP TABLE IF EXISTS `pronosticos`;
CREATE TABLE `pronosticos` (
  `prediction_id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `match_id` int(11) NOT NULL,
  `home_goals` int(11) NOT NULL,
  `away_goals` int(11) NOT NULL,
  `home_penalties` tinyint(4) DEFAULT NULL,
  `away_penalties` tinyint(4) DEFAULT NULL,
  `points` tinyint(4) DEFAULT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `resultado` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:empate, 1:local, 2:visitante'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Disparadores `pronosticos`
--
DROP TRIGGER IF EXISTS `actualizar_resultado_pronostico`;
DELIMITER $$
CREATE TRIGGER `actualizar_resultado_pronostico` BEFORE UPDATE ON `pronosticos` FOR EACH ROW BEGIN
    SET NEW.resultado = obtener_resultado_partido(NEW.home_goals, NEW.away_goals);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `registrar_resultado_pronostico`;
DELIMITER $$
CREATE TRIGGER `registrar_resultado_pronostico` BEFORE INSERT ON `pronosticos` FOR EACH ROW BEGIN
    SET NEW.resultado = obtener_resultado_partido(NEW.home_goals, NEW.away_goals);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pronosticos_campeon`
--

DROP TABLE IF EXISTS `pronosticos_campeon`;
CREATE TABLE `pronosticos_campeon` (
  `empleado_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pronosticos_goleador`
--

DROP TABLE IF EXISTS `pronosticos_goleador`;
CREATE TABLE `pronosticos_goleador` (
  `empleado_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_condiciones`
--

DROP TABLE IF EXISTS `puntos_condiciones`;
CREATE TABLE `puntos_condiciones` (
  `puntos_condicion_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `puntos_condicion_resultado` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar resultado de partido',
  `puntos_condicion_marcador` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar marcador exacto de partido',
  `puntos_condicion_trivia` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'factor de multiplicación para puntos adicionales por fase',
  `puntos_condicion_campeon` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar campeón',
  `puntos_condicion_goleador` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar goleador'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `puntos_condiciones`
--

INSERT INTO `puntos_condiciones` (`puntos_condicion_id`, `fase_id`, `puntos_condicion_resultado`, `puntos_condicion_marcador`, `puntos_condicion_trivia`, `puntos_condicion_campeon`, `puntos_condicion_goleador`) VALUES
(1, 1, 20, 30, 1, 0, 0),
(2, 9, 20, 30, 1, 0, 0),
(3, 10, 20, 30, 1, 0, 0),
(4, 11, 20, 30, 1, 0, 0),
(5, 13, 20, 30, 1, 200, 200);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_empleados`
--

DROP TABLE IF EXISTS `puntos_empleados`;
CREATE TABLE `puntos_empleados` (
  `puntos_empleado_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `puntos_empleado_valor` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos',
  `partido_id` int(11) DEFAULT NULL COMMENT 'partido otorga puntos',
  `trivia_id` int(11) DEFAULT NULL COMMENT 'trivia otorga puntos',
  `equipo_id` int(11) DEFAULT NULL COMMENT 'campeón otorga puntos',
  `jugador_id` int(11) DEFAULT NULL COMMENT 'goleador otorga puntos'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_rolecode` varchar(50) CHARACTER SET utf8 NOT NULL,
  `role_rolename` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`role_rolecode`, `role_rolename`) VALUES
('ADMIN', 'Administrator'),
('SUPERADMIN', 'Super Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_rights`
--

DROP TABLE IF EXISTS `role_rights`;
CREATE TABLE `role_rights` (
  `rr_rolecode` varchar(50) CHARACTER SET utf8 NOT NULL,
  `rr_modulecode` varchar(25) CHARACTER SET utf8 NOT NULL,
  `rr_create` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `rr_edit` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `rr_delete` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `rr_view` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `role_rights`
--

INSERT INTO `role_rights` (`rr_rolecode`, `rr_modulecode`, `rr_create`, `rr_edit`, `rr_delete`, `rr_view`) VALUES
('ADMIN', 'PAYMENT', 'no', 'no', 'no', 'yes'),
('ADMIN', 'PURCHASES', 'yes', 'yes', 'yes', 'yes'),
('ADMIN', 'SALES', 'no', 'no', 'no', 'no'),
('ADMIN', 'SHIPPING', 'yes', 'yes', 'yes', 'yes'),
('ADMIN', 'STOCKS', 'no', 'no', 'no', 'yes'),
('ADMIN', 'TAX', 'no', 'no', 'no', 'no'),
('SUPERADMIN', 'PAYMENT', 'yes', 'yes', 'yes', 'yes'),
('SUPERADMIN', 'PURCHASES', 'yes', 'yes', 'yes', 'yes'),
('SUPERADMIN', 'SALES', 'yes', 'yes', 'yes', 'yes'),
('SUPERADMIN', 'SHIPPING', 'yes', 'yes', 'yes', 'yes'),
('SUPERADMIN', 'STOCKS', 'yes', 'yes', 'yes', 'yes'),
('SUPERADMIN', 'TAX', 'yes', 'yes', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

DROP TABLE IF EXISTS `sedes`;
CREATE TABLE `sedes` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stadium` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tz_offset` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`venue_id`, `venue_name`, `venue_url`, `stadium`, `tz_offset`, `wwhen`) VALUES
(1, 'San Petesburgo', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5031303/index.html', 'San Petersburgo', -3, '2018-01-18 06:00:00'),
(2, 'Kaliningrado', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5000437/index.html', 'Kaliningrado', -3, '2014-01-24 22:57:28'),
(3, 'Volgogrado Arena', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5000569/index.html', 'Volgogrado Arena', -3, '2018-01-18 06:00:00'),
(4, 'Ekaterimburgo Arena', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5031304/index.html', 'Ekaterimburgo Arena', -3, '2018-01-18 06:00:00'),
(5, 'Estadio Fisht', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5031302/index.html', 'Estadio Fisht', -3, '2018-01-18 06:00:00'),
(6, 'Kazán Arena', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5028773/index.html', 'Kazán Arena', -3, '2018-01-18 06:00:00'),
(7, 'Estadio de Nizhni Nóvgorod', '', 'Estadio de Nizhni Nóvgorod', -3, '2018-01-18 06:00:00'),
(8, 'Estadio Luzhnikí', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=810/index.html', 'Estadio Luzhnikí', -3, '2018-01-18 06:00:00'),
(9, 'Samara Arena', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5001246/index.html', 'Samara Arena', -3, '2018-01-18 06:00:00'),
(10, 'Rostov Arena', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5000547/index.html', 'Rostov Arena', -3, '2018-01-18 06:00:00'),
(11, 'Estadio del Spartak', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5030706/index.html', 'Estadio del Spartak', -3, '2018-01-18 06:00:00'),
(12, 'Estadio de San Petersburgo', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5031303/index.html', 'Estadio de San Petersburgo', -3, '2018-01-18 06:00:00'),
(13, 'Mordovia Arena', 'http://es.fifa.com/worldcup/destination/stadiums/stadium=5031301/index.html', 'Mordovia Arena', -3, '2018-01-18 06:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_users`
--

DROP TABLE IF EXISTS `system_users`;
CREATE TABLE `system_users` (
  `u_userid` int(11) NOT NULL,
  `u_username` varchar(100) CHARACTER SET utf8 NOT NULL,
  `u_password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `u_rolecode` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `system_users`
--

INSERT INTO `system_users` (`u_userid`, `u_username`, `u_password`, `u_rolecode`) VALUES
(1, 'admin', '123456', 'SUPERADMIN'),
(2, 'ronaldo', 'ronaldo', 'ADMIN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trivias`
--

DROP TABLE IF EXISTS `trivias`;
CREATE TABLE `trivias` (
  `id_trivia` int(11) NOT NULL,
  `inicio` datetime NOT NULL,
  `vencimiento` datetime NOT NULL,
  `id_fase` smallint(6) NOT NULL,
  `finalizada` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Disparadores `trivias`
--
DROP TRIGGER IF EXISTS `registrar_puntos_trivias`;
DELIMITER $$
CREATE TRIGGER `registrar_puntos_trivias` AFTER UPDATE ON `trivias` FOR EACH ROW BEGIN
    IF NEW.finalizada = '1' THEN
        CALL registrar_puntos_trivia(NEW.id_trivia, NEW.id_fase);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trivias_preguntas`
--

DROP TABLE IF EXISTS `trivias_preguntas`;
CREATE TABLE `trivias_preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `id_trivia` int(11) NOT NULL,
  `orden` tinyint(4) NOT NULL,
  `pregunta` varchar(150) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trivias_respuestas`
--

DROP TABLE IF EXISTS `trivias_respuestas`;
CREATE TABLE `trivias_respuestas` (
  `id_respuesta` int(11) NOT NULL,
  `id_trivia` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `orden` tinyint(4) NOT NULL,
  `respuesta` varchar(60) COLLATE latin1_spanish_ci NOT NULL,
  `respuesta_correcta` enum('0','1') COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trivias_respuestas_empleados`
--

DROP TABLE IF EXISTS `trivias_respuestas_empleados`;
CREATE TABLE `trivias_respuestas_empleados` (
  `id_respuesta` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `empresa` (`empresa`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Indices de la tabla `empresas_imagenes`
--
ALTER TABLE `empresas_imagenes`
  ADD PRIMARY KEY (`id_empresa`,`tipo_imagen`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`team_id`);

--
-- Indices de la tabla `equipo_grupo`
--
ALTER TABLE `equipo_grupo`
  ADD PRIMARY KEY (`equipo_grupo_id`);

--
-- Indices de la tabla `fases`
--
ALTER TABLE `fases`
  ADD PRIMARY KEY (`stage_id`);

--
-- Indices de la tabla `gropus`
--
ALTER TABLE `gropus`
  ADD PRIMARY KEY (`grupo_id`);

--
-- Indices de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id_jugador`);

--
-- Indices de la tabla `mecanica_juego`
--
ALTER TABLE `mecanica_juego`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`mod_modulegroupcode`,`mod_modulecode`),
  ADD UNIQUE KEY `mod_modulecode` (`mod_modulecode`);

--
-- Indices de la tabla `partidos`
--
ALTER TABLE `partidos`
  ADD PRIMARY KEY (`match_id`);

--
-- Indices de la tabla `pronosticos`
--
ALTER TABLE `pronosticos`
  ADD PRIMARY KEY (`prediction_id`),
  ADD UNIQUE KEY `idx_pred_um` (`user_id`,`match_id`);

--
-- Indices de la tabla `pronosticos_campeon`
--
ALTER TABLE `pronosticos_campeon`
  ADD PRIMARY KEY (`empleado_id`,`equipo_id`);

--
-- Indices de la tabla `pronosticos_goleador`
--
ALTER TABLE `pronosticos_goleador`
  ADD PRIMARY KEY (`empleado_id`,`jugador_id`);

--
-- Indices de la tabla `puntos_condiciones`
--
ALTER TABLE `puntos_condiciones`
  ADD PRIMARY KEY (`puntos_condicion_id`);

--
-- Indices de la tabla `puntos_empleados`
--
ALTER TABLE `puntos_empleados`
  ADD PRIMARY KEY (`puntos_empleado_id`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_rolecode`);

--
-- Indices de la tabla `role_rights`
--
ALTER TABLE `role_rights`
  ADD PRIMARY KEY (`rr_rolecode`,`rr_modulecode`),
  ADD KEY `rr_modulecode` (`rr_modulecode`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`venue_id`);

--
-- Indices de la tabla `system_users`
--
ALTER TABLE `system_users`
  ADD PRIMARY KEY (`u_userid`),
  ADD KEY `u_rolecode` (`u_rolecode`);

--
-- Indices de la tabla `trivias`
--
ALTER TABLE `trivias`
  ADD PRIMARY KEY (`id_trivia`);

--
-- Indices de la tabla `trivias_preguntas`
--
ALTER TABLE `trivias_preguntas`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indices de la tabla `trivias_respuestas`
--
ALTER TABLE `trivias_respuestas`
  ADD PRIMARY KEY (`id_respuesta`);

--
-- Indices de la tabla `trivias_respuestas_empleados`
--
ALTER TABLE `trivias_respuestas_empleados`
  ADD PRIMARY KEY (`id_respuesta`,`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `team_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de la tabla `equipo_grupo`
--
ALTER TABLE `equipo_grupo`
  MODIFY `equipo_grupo_id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `fases`
--
ALTER TABLE `fases`
  MODIFY `stage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `gropus`
--
ALTER TABLE `gropus`
  MODIFY `grupo_id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `mecanica_juego`
--
ALTER TABLE `mecanica_juego`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `partidos`
--
ALTER TABLE `partidos`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de la tabla `pronosticos`
--
ALTER TABLE `pronosticos`
  MODIFY `prediction_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `puntos_condiciones`
--
ALTER TABLE `puntos_condiciones`
  MODIFY `puntos_condicion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `puntos_empleados`
--
ALTER TABLE `puntos_empleados`
  MODIFY `puntos_empleado_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `system_users`
--
ALTER TABLE `system_users`
  MODIFY `u_userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `trivias`
--
ALTER TABLE `trivias`
  MODIFY `id_trivia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `trivias_preguntas`
--
ALTER TABLE `trivias_preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `trivias_respuestas`
--
ALTER TABLE `trivias_respuestas`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `role_rights`
--
ALTER TABLE `role_rights`
  ADD CONSTRAINT `role_rights_ibfk_1` FOREIGN KEY (`rr_rolecode`) REFERENCES `role` (`role_rolecode`) ON UPDATE CASCADE,
  ADD CONSTRAINT `role_rights_ibfk_2` FOREIGN KEY (`rr_modulecode`) REFERENCES `module` (`mod_modulecode`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `system_users`
--
ALTER TABLE `system_users`
  ADD CONSTRAINT `system_users_ibfk_1` FOREIGN KEY (`u_rolecode`) REFERENCES `role` (`role_rolecode`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
