-- phpMyAdmin SQL Dump
-- version 4.7.8
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 04, 2018 at 12:00 AM
-- Server version: 5.6.39
-- PHP Version: 7.0.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todosalacancha`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `obtener_condiciones_puntos_fase` (IN `fase` INT, OUT `puntos_resultado` SMALLINT UNSIGNED, OUT `puntos_marcador` SMALLINT UNSIGNED, OUT `puntos_trivia` SMALLINT UNSIGNED, OUT `puntos_campeon` SMALLINT UNSIGNED, OUT `puntos_goleador` SMALLINT UNSIGNED)  BEGIN
    SELECT puntos_condicion_resultado, puntos_condicion_marcador, puntos_condicion_trivia, puntos_condicion_campeon, puntos_condicion_goleador
    INTO puntos_resultado, puntos_marcador, puntos_trivia, puntos_campeon, puntos_goleador
    FROM puntos_condiciones pc
    WHERE
        CASE
            WHEN fase BETWEEN 1 AND 8 THEN fase_id = 1
            WHEN fase BETWEEN 12 AND 13 THEN fase_id = 13
            ELSE fase_id = fase
        END;
END$$

CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_campeon` (IN `equipo` INT)  BEGIN
    CALL obtener_condiciones_puntos_fase(13, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, equipo_id)
    SELECT empleado_id, @puntos_campeon, equipo_id
    FROM equipos e
        JOIN pronosticos_campeon pc ON(team_id = equipo_id)
    WHERE equipo_id = equipo
        AND campeon = '1';
END$$

CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_goleador` (IN `jugador` INT)  BEGIN
    CALL obtener_condiciones_puntos_fase(13, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, jugador_id)
    SELECT empleado_id, @puntos_goleador, id_jugador
    FROM jugadores j
        JOIN pronosticos_goleador pg ON(id_jugador = jugador_id)
    WHERE j.id_jugador = jugador
        AND es_goleador = '1';
END$$

CREATE DEFINER=`todosalacancha`@`localhost` PROCEDURE `registrar_puntos_partido` (IN `partido` INT, IN `fase` INT)  BEGIN
    CALL obtener_condiciones_puntos_fase(fase, @puntos_resultado, @puntos_marcador, @puntos_trivia, @puntos_campeon, @puntos_goleador);
    INSERT INTO puntos_empleados (empleado_id, puntos_empleado_valor, partido_id)
    SELECT user_id, @puntos_resultado + IF(pr.home_goals = pa.home_goals AND pr.away_goals = pa.away_goals, @puntos_marcador, 0), pr.match_id
    FROM pronosticos pr
        JOIN partidos pa USING(match_id, resultado)
    WHERE match_id = partido;
END$$

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
                WHEN fase = 13 THEN stage_id BETWEEN 12 AND 13
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
-- Functions
--
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
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `user` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `pass` char(32) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `correo_e` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `departamento` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ubicacion` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `imagen_perfil` char(40) COLLATE latin1_spanish_ci DEFAULT NULL,
  `state` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0',
  `acepto_bases` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `id_empresa`, `user`, `pass`, `nombre`, `apellido`, `correo_e`, `departamento`, `ubicacion`, `imagen_perfil`, `state`, `acepto_bases`) VALUES
(1, 2, '123456', 'e10adc3949ba59abbe56e057f20f883e', 'test', 'test', NULL, 'test departamento', 'test departamento', NULL, '1', '1'),
(2, 2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'test nombre nombre', 'test apellido apellido', NULL, 'test departamento', 'test departamento', '12ec495b01260069affe9765135ffeb2.jpg', '1', '1'),
(3, 2, 'user1', '96e79218965eb72c92a549dd5a330112', 'test', 'test', NULL, 'test departamento', 'test departamento', 'e33344760ac70f1ff2835cce4e971413.png', '1', '1'),
(4, 2, 'user2', 'e10adc3949ba59abbe56e057f20f883e', 'test', 'test', NULL, 'test departamento', 'test departamento', NULL, '1', '0'),
(5, 2, 'user3', 'e10adc3949ba59abbe56e057f20f883e', 'test', 'test', NULL, 'test departamento', 'test departamento', NULL, '1', '0'),
(6, 2, 'user4', 'e10adc3949ba59abbe56e057f20f883e', 'test', 'test', NULL, 'test departamento', 'test departamento', NULL, '1', '0'),
(7, 2, 'user5', 'e10adc3949ba59abbe56e057f20f883e', 'test', 'test', NULL, 'test departamento', 'test departamento', NULL, '1', '0'),
(8, 2, 'test', '4d5e2a885578299e5a5902ad295447c6', 'test', 'test', NULL, 'test departamento', 'test', 'dde686aaf0b00f9e89d1d57fb6bcd795.JPG', '1', '0'),
(9, 2, 'rmurer', '65b29136c1a8233c271b454e9e6f626d', 'rmurer', 'rmurer', NULL, 'rmurer', 'rmurer', 'fac6c145176f99b5841cbf9831a400a5.png', '1', '1'),
(10, 3, 'rmurer0', 'd2b2e8c33733d466f65c5fe6107f7532', '1111111', '222222', NULL, NULL, NULL, '38070b786fa1fe8b9d31ba2765b41a3d.jpg', '1', '1'),
(11, 2, 'rmurer1', '4fe19cd4958dc0fc183d80ab29974320', 'Roberto', 'Murer1tiene', '', 'Sistemas', 'Antártida Argentina', '0b1db21186a9d5aa6a15a527376e788a.png', '1', '1'),
(12, 2, 'rmurer2', 'dad5c54790ecc613d1498dd8e38397d3', 'Roberto', 'Murer1notiene', '', 'Sistemas', 'Antártida Argentina', NULL, '1', '1'),
(13, 2, 'rmurer3', 'a8019576a9909d8130c275fe2389cf9b', 'Roberto', 'Murer1tiene', NULL, 'Sistemas', 'Antártida Argentina', '283743e0035cb37767f6627bb7990c96.jpg', '1', '1'),
(14, 2, 'rmurer4', 'e161fbdef6143dc3f8fb0de784ddaed5', 'Roberto', 'Murer1tiene', NULL, 'Sistemas', 'Antártida Argentina', 'f40abc22b4d6073cabe400fbec2d854f.jpg', '1', '1'),
(32779, 3, 'prueba', 'c893bad68927b457dbed39460e6afd62', 'Prueba', 'Prueba', NULL, NULL, NULL, NULL, '1', '1'),
(32780, 2, 'csegovia', 'b6ddd114ece667c7e1853573bab19cc2', 'Carlos', 'Google', NULL, NULL, NULL, NULL, '1', '0'),
(32781, 4, 'csegovia', '82574d4ded06eb79b999769a3ea73845', 'asdfsadfs', 'asdfsadfasd', NULL, NULL, NULL, NULL, '1', '0'),
(32782, 3, 'repetido', '7a6391c889bfc2196f8821c5aaab6be6', 'repetido', 'repetido', '', 'repetido', 'repetido', NULL, '1', '0'),
(32783, 2, 'repetido', '7a6391c889bfc2196f8821c5aaab6be6', 'repetido', 'repetido', '', 'repetido', 'repetido', NULL, '1', '0'),
(32784, 4, 'repetido', '7a6391c889bfc2196f8821c5aaab6be6', 'repetido', 'repetido', '', 'repetido', 'repetido', NULL, '1', '0'),
(32785, 2, 'rmurer7', 'e0899c2bc437fa85c0c940ac20083a3c', 'Roberto', 'Murer1notiene', 'carlos@gmail.com', 'Sistemas', 'Antártida Argentina', 'aba18f2c1b38e33cb46030a5cddc3cbb.jpg', '1', '1'),
(32786, 2, 'rmurer8', '1decadab82a332dadfcdcc7d92ea066a', 'Roberto', 'Murer1notiene', '', 'Sistemas', 'Antártida Argentina', NULL, '1', '1'),
(32787, 2, 'rmurer9', '675eed3f071e634f6a063193b2fe74d3', 'Roberto', 'Murer1tiene', 'carlos@gmail.com', 'Sistemas', 'Antártida Argentina', 'fede097792dab7b0f7ed62d26dc01355.jpg', '1', '1'),
(32788, 17, 'rmurer10', '91862323786649af7e3449118fbb60db', 'Roberto', 'Murer1tiene', '', 'Sistemas', 'Antártida Argentina', '1856eb1edbe8fc2fd11fb85be93af2d6.jpeg', '1', '1'),
(32792, 5, 'rmurer', '65b29136c1a8233c271b454e9e6f626d', 'Roberto', 'Murer', 'robertmurer@gmail.com', 'Area de sistemas', 'Buenos Aires', 'faa9fd51c19c3133414972084a8904a2.png', '1', '1'),
(32793, 2, 'usuario300', 'a4a97ffc170ec7ab32b85b2129c69c50', 'nombre', 'apellido', 'usuario300@gmail.com', 'departamento1', 'ubicaciï¿½n1', NULL, '1', '0'),
(32794, 2, 'usuario301', '10dea63031376352d413a8e530654b8b', 'nombre', 'apellido', 'usuario301@gmail.com', 'departamento2', 'ubicaciï¿½n2', NULL, '1', '0'),
(32795, 2, 'usuario302', '35559e8b5732fbd5029bef54aeab7a21', 'nombre', 'apellido', 'usuario302@gmail.com', 'departamento3', 'ubicaciï¿½n3', NULL, '1', '0'),
(32796, 2, 'usuario303', 'c707dce7b5a990e349c873268cf5a968', 'nombre', 'apellido', 'usuario303@gmail.com', 'departamento4', 'ubicaciï¿½n4', NULL, '1', '0'),
(32797, 2, 'usuario304', '9d4ba1ec63d70f19106c2aec14926374', 'nombre', 'apellido', 'usuario304@gmail.com', 'departamento5', 'ubicaciï¿½n5', NULL, '1', '0'),
(32798, 2, 'usuario305', 'e5c4bd895be104cc1a928687c7fc922a', 'nombre', 'apellido', 'usuario305@gmail.com', 'departamento6', 'ubicaciï¿½n6', NULL, '1', '0'),
(32799, 2, 'usuario306', '8b33d112c1a64f9fb374eb87b98990cf', 'nombre', 'apellido', 'usuario306@gmail.com', 'departamento7', 'ubicaciï¿½n7', NULL, '1', '0'),
(32800, 2, 'usuario307', '7d905fbdc246912149bf8bdb2c43efd8', 'nombre', 'apellido', 'usuario307@gmail.com', 'departamento8', 'ubicaciï¿½n8', NULL, '1', '0'),
(32801, 2, 'usuario308', '68bfccf877bb29c1698663b8f6920a20', 'nombre', 'apellido', 'usuario308@gmail.com', 'departamento9', 'ubicaciï¿½n9', NULL, '1', '0'),
(32802, 2, 'usuario309', 'e823d38e2018737a77b4b9bf3e94c697', 'nombre', 'apellido', 'usuario309@gmail.com', 'departamento10', 'ubicaciï¿½n10', NULL, '1', '0'),
(32803, 2, 'usuario310', 'df83832006c3ed2ecd41b30ac17135ba', 'nombre', 'apellido', 'usuario310@gmail.com', 'departamento11', 'ubicaciï¿½n11', NULL, '1', '0'),
(32804, 2, 'usuario311', 'e96f92777862ceec18f357286c8e9a25', 'nombre', 'apellido', 'usuario311@gmail.com', 'departamento12', 'ubicaciï¿½n12', NULL, '1', '0'),
(32805, 2, 'usuario312', '9e2edf423232eaae1dacb2f75e893238', 'nombre', 'apellido', 'usuario312@gmail.com', 'departamento13', 'ubicaciï¿½n13', NULL, '1', '0'),
(32806, 2, 'usuario313', '03dd756b38c0670f58784289db2de843', 'nombre', 'apellido', 'usuario313@gmail.com', 'departamento14', 'ubicaciï¿½n14', NULL, '1', '0'),
(32807, 2, 'usuario314', '7cc93421caac896a47e28dbca3e4bf3a', 'nombre', 'apellido', 'usuario314@gmail.com', 'departamento15', 'ubicaciï¿½n15', NULL, '1', '0'),
(32808, 2, 'usuario315', 'aa0ec9ab40020a7204d82a3816887e34', 'nombre', 'apellido', 'usuario315@gmail.com', 'departamento16', 'ubicaciï¿½n16', NULL, '1', '0'),
(32809, 2, 'usuario316', 'ebf5fb5d1b229d775d5008a8d28e480c', 'nombre', 'apellido', 'usuario316@gmail.com', 'departamento17', 'ubicaciï¿½n17', NULL, '1', '0'),
(32810, 2, 'usuario317', '9accf2548fc55b618861aa3bbe7a40f5', 'nombre', 'apellido', 'usuario317@gmail.com', 'departamento18', 'ubicaciï¿½n18', NULL, '1', '0'),
(32811, 2, 'usuario318', '5e4ae615649f44d0301c7ef1ea29431c', 'nombre', 'apellido', 'usuario318@gmail.com', 'departamento19', 'ubicaciï¿½n19', NULL, '1', '0'),
(32812, 2, 'usuario319', 'f61f5003a1f953afd964f6e36d1110e9', 'nombre', 'apellido', 'usuario319@gmail.com', 'departamento20', 'ubicaciï¿½n20', NULL, '1', '0'),
(32813, 2, 'usuario320', '9e78ace5ffcc133fec631f919f27e62e', 'nombre', 'apellido', 'usuario320@gmail.com', 'departamento21', 'ubicaciï¿½n21', NULL, '1', '0'),
(32814, 2, 'usuario321', '3461f0f301ddfca9fdd89bab7d7ed845', 'nombre', 'apellido', 'usuario321@gmail.com', 'departamento22', 'ubicaciï¿½n22', NULL, '1', '0'),
(32815, 2, 'usuario322', '8d5f8bee06411cb1e189c294d437556c', 'nombre', 'apellido', 'usuario322@gmail.com', 'departamento23', 'ubicaciï¿½n23', NULL, '1', '0'),
(32816, 2, 'usuario323', '8982c5012464a59c7682ca7c93b4288f', 'nombre', 'apellido', 'usuario323@gmail.com', 'departamento24', 'ubicaciï¿½n24', NULL, '1', '0'),
(32817, 2, 'usuario324', '2f968fd9e044d0aeb3d396abcbf4dcc4', 'nombre', 'apellido', 'usuario324@gmail.com', 'departamento25', 'ubicaciï¿½n25', NULL, '1', '0'),
(32818, 2, 'usuario325', '3edf4c0533caea3af27af063a8553b11', 'nombre', 'apellido', 'usuario325@gmail.com', 'departamento26', 'ubicaciï¿½n26', NULL, '1', '0'),
(32819, 2, 'usuario326', '793b0aab1969bcc27a7e6de325f6136f', 'nombre', 'apellido', 'usuario326@gmail.com', 'departamento27', 'ubicaciï¿½n27', NULL, '1', '0'),
(32820, 2, 'usuario327', '2357a0480d583d11529ce50948e5b9d5', 'nombre', 'apellido', 'usuario327@gmail.com', 'departamento28', 'ubicaciï¿½n28', NULL, '1', '0'),
(32821, 2, 'usuario328', '8e5a7b2e2ba198fef4c7578608ee4830', 'nombre', 'apellido', 'usuario328@gmail.com', 'departamento29', 'ubicaciï¿½n29', NULL, '1', '0'),
(32822, 2, 'usuario329', '7e441a6c2bc9032c62765c0d1caf383f', 'nombre', 'apellido', 'usuario329@gmail.com', 'departamento30', 'ubicaciï¿½n30', NULL, '1', '0'),
(32823, 2, 'usuario330', '53bf93cc9b0c6f191cf4d1997bc065b8', 'nombre', 'apellido', 'usuario330@gmail.com', 'departamento31', 'ubicaciï¿½n31', NULL, '1', '0'),
(32824, 2, 'usuario331', 'f13b6c98aae222e3e3186e4aa91793dd', 'nombre', 'apellido', 'usuario331@gmail.com', 'departamento32', 'ubicaciï¿½n32', NULL, '1', '0'),
(32825, 2, 'usuario332', '7773171a0230788432b2f6f6bd6cd89f', 'nombre', 'apellido', 'usuario332@gmail.com', 'departamento33', 'ubicaciï¿½n33', NULL, '1', '0'),
(32826, 2, 'usuario333', 'deb10b676b85b8a517df92bea6712d9d', 'nombre', 'apellido', 'usuario333@gmail.com', 'departamento34', 'ubicaciï¿½n34', NULL, '1', '0'),
(32827, 2, 'usuario334', 'bae90f0f0d2f24687d773383ef48fff7', 'nombre', 'apellido', 'usuario334@gmail.com', 'departamento35', 'ubicaciï¿½n35', NULL, '1', '0'),
(32828, 2, 'usuario335', '046d76e5c42885d5a0f2115b3e4e1fd6', 'nombre', 'apellido', 'usuario335@gmail.com', 'departamento36', 'ubicaciï¿½n36', NULL, '1', '0'),
(32829, 2, 'usuario336', 'db2a5589215de37b0626599d2658e521', 'nombre', 'apellido', 'usuario336@gmail.com', 'departamento37', 'ubicaciï¿½n37', NULL, '1', '0'),
(32830, 2, 'usuario337', 'cc0bf7854d28f8ea008960e8faa7a3bf', 'nombre', 'apellido', 'usuario337@gmail.com', 'departamento38', 'ubicaciï¿½n38', NULL, '1', '0'),
(32831, 2, 'usuario338', '93bd5d5bb0b9f117b3f723d533603e00', 'nombre', 'apellido', 'usuario338@gmail.com', 'departamento39', 'ubicaciï¿½n39', NULL, '1', '0'),
(32832, 2, 'usuario339', '1daf7d005e596d1d16a8fb2a4d735b23', 'nombre', 'apellido', 'usuario339@gmail.com', 'departamento40', 'ubicaciï¿½n40', NULL, '1', '0'),
(32833, 2, 'usuario341', 'd57568936e676e5a7b53f9379832667c', 'nombre', 'apellido', 'usuario341@gmail.com', 'departamento42', 'ubicaciï¿½n42', NULL, '1', '0'),
(32834, 2, 'usuario343', '1695872dc3ecc303c013f257f312a8b6', 'nombre', 'apellido', 'usuario343@gmail.com', 'departamento44', 'ubicaciï¿½n44', NULL, '1', '0'),
(32835, 2, 'usuario344', 'ddb9c7d2522d3687de72cdb5a7f557ba', 'nombre', 'apellido', 'usuario344@gmail.com', 'departamento45', 'ubicaciï¿½n45', NULL, '1', '0'),
(32836, 2, 'usuario345', '3ef5bc0473b4e2f41dac96ad4ef1114e', 'nombre', 'apellido', 'usuario345@gmail.com', 'departamento46', 'ubicaciï¿½n46', NULL, '1', '0'),
(32837, 2, 'usuario346', '2daf2c205b4a8184bacb60b4977e9be1', 'nombre', 'apellido', 'usuario346@gmail.com', 'departamento47', 'ubicaciï¿½n47', NULL, '1', '0'),
(32838, 2, 'usuario347', 'e960a1ab1ffa8f657aba1e6fcf54b28c', 'nombre', 'apellido', 'usuario347@gmail.com', 'departamento48', 'ubicaciï¿½n48', NULL, '1', '0'),
(32839, 2, 'usuario348', '5ea5849aacd2e9d5d1fc4cd6cc5798a8', 'nombre', 'apellido', 'usuario348@gmail.com', 'departamento49', 'ubicaciï¿½n49', NULL, '1', '0'),
(32840, 2, 'usuario349', '8d284d0c6596839eaa80b0dcb37dcf9b', 'nombre', 'apellido', 'usuario349@gmail.com', 'departamento50', 'ubicaciï¿½n50', NULL, '1', '0'),
(32841, 2, 'usuario350', '9a900f0e752fab53468e1d781eb3ef23', 'nombre', 'apellido', 'usuario350@gmail.com', 'departamento51', 'ubicaciï¿½n51', NULL, '1', '0'),
(32842, 2, 'usuario351', '3016a4f557bc29b37e0593a024464479', 'nombre', 'apellido', 'usuario351@gmail.com', 'departamento52', 'ubicaciï¿½n52', NULL, '1', '0'),
(32843, 2, 'usuario352', '698eb1c2004dee6250385fc14a7356da', 'nombre', 'apellido', 'usuario352@gmail.com', 'departamento53', 'ubicaciï¿½n53', NULL, '1', '0'),
(32844, 2, 'usuario353', '6a1b2c501650bc0fbdc356aae0f418fe', 'nombre', 'apellido', 'usuario353@gmail.com', 'departamento54', 'ubicaciï¿½n54', NULL, '1', '0'),
(32845, 2, 'usuario354', '2a7223a0fb67b225cf55ff9d003b0ead', 'nombre', 'apellido', 'usuario354@gmail.com', 'departamento55', 'ubicaciï¿½n55', NULL, '1', '0'),
(32846, 2, 'usuario355', 'af3d9df9d68709bcf782acf1424184df', 'nombre', 'apellido', 'usuario355@gmail.com', 'departamento56', 'ubicaciï¿½n56', NULL, '1', '0'),
(32847, 2, 'usuario356', 'afd0268782578840c4bba98529c80f59', 'nombre', 'apellido', 'usuario356@gmail.com', 'departamento57', 'ubicaciï¿½n57', NULL, '1', '0'),
(32848, 2, 'usuario357', '7dac1d0219a7efefa8715d743c04f1ab', 'nombre', 'apellido', 'usuario357@gmail.com', 'departamento58', 'ubicaciï¿½n58', NULL, '1', '0'),
(32849, 2, 'usuario358', '330caaac6f56e8207190be9caeabcb58', 'nombre', 'apellido', 'usuario358@gmail.com', 'departamento59', 'ubicaciï¿½n59', NULL, '1', '0'),
(32850, 2, 'usuario360', '6e5212e93ea85fb58d24a8ff8f951d1e', 'nombre', 'apellido', 'usuario360@gmail.com', 'departamento61', 'ubicaciï¿½n61', NULL, '1', '0'),
(32851, 2, 'usuario361', '1697c941637aa677138eeff03b5eafa6', 'nombre', 'apellido', 'usuario361@gmail.com', 'departamento62', 'ubicaciï¿½n62', NULL, '1', '0'),
(32852, 2, 'usuario362', '3b33f597e68d8d65961c41bae2a413bc', 'nombre', 'apellido', 'usuario362@gmail.com', 'departamento63', 'ubicaciï¿½n63', NULL, '1', '0'),
(32853, 2, 'usuario363', 'bde81178e6a31d8bd42fa1337a4b0497', 'nombre', 'apellido', 'usuario363@gmail.com', 'departamento64', 'ubicaciï¿½n64', NULL, '1', '0'),
(32854, 2, 'usuario365', '97a98e18aac58fe5f7ffa7853459fe2d', 'nombre', 'apellido', '', '', '', NULL, '1', '0'),
(32855, 2, 'usuario366', '98ab4dc31efd6d97a755fea61ef063d4', 'nombre', 'apellido', 'usuario366@gmail.com', 'departamento67', 'ubicaciï¿½n67', NULL, '1', '0'),
(32856, 2, 'usuario367', '580b2368d5c0bc794b4a8e6ce4033774', 'nombre', 'apellido', 'usuario367@gmail.com', 'departamento68', 'ubicaciï¿½n68', NULL, '1', '0'),
(32857, 2, 'usuario368', 'e0400893f9e3b115b31aed83175f0617', 'nombre', 'apellido', 'usuario368@gmail.com', 'departamento69', 'ubicaciï¿½n69', NULL, '1', '0'),
(32858, 2, 'usuario369', 'ed4d8f8e9780665f6e9fb6e89c8e40fe', 'nombre', 'apellido', 'usuario369@gmail.com', 'departamento70', 'ubicaciï¿½n70', NULL, '1', '0'),
(32859, 2, 'usuario370', 'b9aba9070dfa06d41fa77f19a4c13f83', 'nombre', 'apellido', 'usuario370@gmail.com', 'departamento71', 'ubicaciï¿½n71', NULL, '1', '0'),
(32860, 2, 'usuario371', '386e4556d1a97ee9a1223c4db1ab1cc6', 'nombre', 'apellido', 'usuario371@gmail.com', 'departamento72', 'ubicaciï¿½n72', NULL, '1', '0'),
(32861, 2, 'usuario372', 'b2e11fa83a5d763c34f20d06c9ef8db7', 'nombre', 'apellido', 'usuario372@gmail.com', 'departamento73', 'ubicaciï¿½n73', NULL, '1', '0'),
(32862, 2, 'usuario373', '05b85a18f65bd946c22ccd0f2885e78c', 'nombre', 'apellido', 'usuario373@gmail.com', 'departamento74', 'ubicaciï¿½n74', NULL, '1', '0'),
(32863, 2, 'usuario374', '7a4c84409dab289421d5c31d96071cbb', 'nombre', 'apellido', 'usuario374@gmail.com', 'departamento75', 'ubicaciï¿½n75', NULL, '1', '0'),
(32864, 2, 'usuario375', 'c11539caf2e996357640cba9ead6ea50', 'nombre', 'apellido', 'usuario375@gmail.com', 'departamento76', 'ubicaciï¿½n76', NULL, '1', '0'),
(32865, 2, 'usuario376', 'c49539cf0faa67707ba52b6df04c1ffe', 'nombre', 'apellido', 'usuario376@gmail.com', 'departamento77', 'ubicaciï¿½n77', NULL, '1', '0'),
(32866, 2, 'usuario377', 'ac9262d0c93623f0bc6bd245dbbd38c2', 'nombre', 'apellido', 'usuario377@gmail.com', 'departamento78', 'ubicaciï¿½n78', NULL, '1', '0'),
(32867, 2, 'usuario378', 'be8cc5d7b1d0467e1819cc89dbb3bfc4', 'nombre', 'apellido', 'usuario378@gmail.com', 'departamento79', 'ubicaciï¿½n79', NULL, '1', '0'),
(32868, 2, 'usuario379', '5b47479bb86933fd09541dfed4c8a3d3', 'nombre', 'apellido', 'usuario379@gmail.com', 'departamento80', 'ubicaciï¿½n80', NULL, '1', '0'),
(32869, 2, 'usuario380', '6dd061b57002f56d9ea37a65697b3a6a', 'nombre', 'apellido', 'usuario380@gmail.com', 'departamento81', 'ubicaciï¿½n81', NULL, '1', '0'),
(32870, 2, 'usuario381', '9df35fb4a149015aa46523c99dd7a5c7', 'nombre', 'apellido', 'usuario381@gmail.com', 'departamento82', 'ubicaciï¿½n82', NULL, '1', '0'),
(32871, 2, 'usuario382', 'b51ecf882882918ba3f5529d9b43ce0e', 'nombre', 'apellido', 'usuario382@gmail.com', 'departamento83', 'ubicaciï¿½n83', NULL, '1', '0'),
(32872, 2, 'usuario383', '29056c53babd20adfb82e76217536dca', 'nombre', 'apellido', 'usuario383@gmail.com', 'departamento84', 'ubicaciï¿½n84', NULL, '1', '0'),
(32873, 2, 'usuario384', '6a6eec0cbc995e4ce5ba259dede02f6c', 'nombre', 'apellido', 'usuario384@gmail.com', 'departamento85', 'ubicaciï¿½n85', NULL, '1', '0'),
(32874, 2, 'usuario385', 'dabf18e58f1122289b8d21dc37d55468', 'nombre', 'apellido', 'usuario385@gmail.com', 'departamento86', 'ubicaciï¿½n86', NULL, '1', '0'),
(32875, 2, 'usuario386', '01b2141a2629b78952d27c17d2e9b4de', 'nombre', 'apellido', 'usuario386@gmail.com', 'departamento87', 'ubicaciï¿½n87', NULL, '1', '0'),
(32876, 2, 'usuario387', '2fea079d09a7cec46f23b8d5b2985775', 'nombre', 'apellido', 'usuario387@gmail.com', 'departamento88', 'ubicaciï¿½n88', NULL, '1', '0'),
(32877, 2, 'usuario388', '7090feb70da521864a189e4c8c9b266d', 'nombre', 'apellido', 'usuario388@gmail.com', 'departamento89', 'ubicaciï¿½n89', NULL, '1', '0'),
(32878, 2, 'usuario389', '80abb937dc7f0b18cdd52138bc23268b', 'nombre', 'apellido', 'usuario389@gmail.com', 'departamento90', 'ubicaciï¿½n90', NULL, '1', '0'),
(32879, 2, 'usuario390', '0b7ec8820f8f045de236de7bdacd457f', 'nombre', 'apellido', 'usuario390@gmail.com', 'departamento91', 'ubicaciï¿½n91', NULL, '1', '0'),
(32880, 2, 'usuario391', '18e2e3e02cbdb6cfa877731635bf6430', 'nombre', 'apellido', 'usuario391@gmail.com', 'departamento92', 'ubicaciï¿½n92', NULL, '1', '0'),
(32881, 2, 'usuario392', '44373a1b92125ff9f8fb5bc97c084bf7', 'nombre', 'apellido', 'usuario392@gmail.com', 'departamento93', 'ubicaciï¿½n93', NULL, '1', '0'),
(32882, 2, 'usuario393', 'a6e3d42c297a599eb1f0e1e60ef50d18', 'nombre', 'apellido', 'usuario393@gmail.com', 'departamento94', 'ubicaciï¿½n94', NULL, '1', '0'),
(32883, 2, 'usuario394', 'f2807ebb5148c6afe0b8b8e96018fc18', 'nombre', 'apellido', 'usuario394@gmail.com', 'departamento95', 'ubicaciï¿½n95', NULL, '1', '0'),
(32884, 2, 'usuario395', '1c99b15e6460cd373fc27ec12e4ba6e4', 'nombre', 'apellido', 'usuario395@gmail.com', 'departamento96', 'ubicaciï¿½n96', NULL, '1', '0'),
(32885, 2, 'usuario396', 'c00988d910aa706209c4f3a9ad9c2446', 'nombre', 'apellido', 'usuario396@gmail.com', 'departamento97', 'ubicaciï¿½n97', NULL, '1', '0'),
(32886, 2, 'usuario397', 'd5f732c0a1774d7f68b13893a3ed23c3', 'nombre', 'apellido', 'usuario397@gmail.com', 'departamento98', 'ubicaciï¿½n98', NULL, '1', '0'),
(32887, 2, 'usuario398', '02c6fc8e5eddb6e0b100668a353e8b52', 'nombre', 'apellido', 'usuario99@gmail.com', 'departamento99', 'ubicaciï¿½n99', NULL, '1', '0'),
(32920, 5, 'csegovia', 'b6ddd114ece667c7e1853573bab19cc2', 'Carlos', 'Segovia', NULL, NULL, NULL, NULL, '1', '0'),
(32922, 2, 'todosalacancha', '82401ddf60f99508e9b123d3840073b5', 'Todos A', 'La Cancha', NULL, NULL, NULL, NULL, '1', '1'),
(32923, 2, 'testerpenaflor', '82401ddf60f99508e9b123d3840073b5', 'Ignacio', 'Maqueda', 'ignacio.maqueda@grupopenaflor.com.ar', 'Recursos Humanos', 'Oficinas Vte Lopez', NULL, '1', '0'),
(32924, 2, 'testerhsbc', '5818c381891abd3ecb061cc7eb1652a5', 'Clarisa', 'Gidekel', 'clarisa.gidekel@hsbc.com.ar', 'Recursos Humanos', 'Lezama', NULL, '1', '1'),
(32925, 2, 'testerwordline', '7acb5d354f3a2f9f0d74f6d595a6e326', 'Palacios', 'Carolina', 'carolina.palacios@worldline.com', 'Recursos Humanos', 'Oficinas DOT', NULL, '1', '0'),
(32926, 2, 'testerdespegar', 'c7ef8145b94379e2c7c6a56c1d6029bd', 'Micaela', 'Giardinelli', 'micaela.giardinelli@despegar.com', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '1'),
(32927, 2, 'testerloma', '7af1924acef11c3fb8e1549bd46d0938', 'Daniel', 'Raso', 'CRaso@intercement.com', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '1'),
(32928, 2, 'testermcd', '72f777f80c33b49486e57432436611cc', 'Agustina', 'Lucchetti', NULL, 'Recursos Humanos', 'Buenos Aires', NULL, '1', '1'),
(32929, 2, 'testerguru', 'bf98c5ec9c285b6de95a42d531fc0da4', 'Soledad', 'Rutilli', NULL, 'Recursos Humanos', 'Buenos Aires', NULL, '1', '0'),
(32930, 2, 'testerbrig', 'abf70214c47b1effbbcb7e5a046a01b5', 'Nazareno', 'del castillo', 'Nazareno.DelCastillo@Brightstar.com', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '0'),
(32931, 2, 'testerclorox', '142be8f4b2da3faa7935ed49ddfca437', 'Solange', 'Sanchez', NULL, NULL, NULL, NULL, '1', '1'),
(32932, 2, 'rmurer20', 'f56e2c5a5d4592c63b972ae63f4e42c6', 'aaa', 'bbb', NULL, NULL, NULL, NULL, '1', '1'),
(32933, 4, 'rmurer', '65b29136c1a8233c271b454e9e6f626d', '123123123', '132123123', NULL, NULL, NULL, NULL, '1', '1'),
(32934, 2, 'rmurer11', 'c85b31d6e66a5afffefe8e1321a04fb7', 'asdfasdfa', 'asdfa', NULL, NULL, NULL, NULL, '1', '1'),
(32935, 2, 'testerchevron', 'da51bc528610e27a726e45dcd47e19ed', 'Agustina', 'Gagliardi', 'gagl@chevron.com', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '1'),
(32936, 2, 'testersgs', 'b88164f56dfcd1854de3e8fb89308059', 'Josefina', 'Garrido', 'Josefina.Garrido@sgs.com', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '1'),
(32937, 2, 'testerhipo', 'f3fb38f85e61a1bb2ead94918a0d51e9', 'Micaela', 'Ferrofino', 'MMFERROFINOLOPEZ@hipotecario.com.ar', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '0'),
(32938, 2, 'rmurer12', 'f4393187cc4403b8c67813e9243095b4', 'rmurer12', 'rmurer12', NULL, NULL, NULL, NULL, '1', '1'),
(32939, 2, 'testermccain', '746144f7ba5b4032d4f382a2b08ab8d3', 'Ana', 'Suda', NULL, 'Recursos Humanos', 'Buenos Aires', NULL, '1', '0'),
(32940, 2, 'valetester', '62a4c68b47ad19f1afbed750010ded5e', 'Valeria', 'Agulla', 'vagulla@beinspiringtools.com', 'Recursos Humanos', 'Barcelona', NULL, '1', '1'),
(32941, 2, 'espetester', '2aef89323ee206ca31386b9351a5e398', 'Esperanza', 'Gonzalez', 'egonzalez@beinspiringtools.com', 'Recursos Humanos', 'Buenos Aires', NULL, '1', '0'),
(32942, 2, 'empleado', '088ef99bff55c67dc863f83980a66a9b', 'empleado', 'google', NULL, NULL, NULL, NULL, '1', '1'),
(32943, 2, 'testerhpad', 'badc157adc0f8bcb5300a13a0b473c13', 'Martina', 'Delgado', NULL, 'Recursos Humanos', 'Buenos Aires', NULL, '1', '0'),
(32944, 2, 'testerpena', 'ad02fea921ad7598cf198cddde98dd63', 'Ignacio', 'Maqueda', NULL, 'Recursos Humanos', 'Buenos Aires', NULL, '1', '1'),
(32945, 16, 'Alejandro', '82401ddf60f99508e9b123d3840073b5', 'Alejandro', 'Gargano', 'alejandromgargano@gmail.com', 'Esperanza', 'Centro', NULL, '1', '0'),
(32946, 16, 'Andres', '82401ddf60f99508e9b123d3840073b5', 'Andres', 'Agulla', 'aagulla@hotmail.com', 'Vale', 'Barcelona', NULL, '1', '0'),
(32947, 16, 'Ariel', '82401ddf60f99508e9b123d3840073b5', 'Ariel', 'Dhart', 'ariel.duhart@gmail.com', 'Nadia', 'San Isidro', NULL, '1', '0'),
(32948, 16, 'Bruno', '82401ddf60f99508e9b123d3840073b5', 'Bruno', 'Coppola', 'brunoasecas@gmail.com', 'Bruno', 'Centro', NULL, '1', '0'),
(32949, 16, 'Camila', '82401ddf60f99508e9b123d3840073b5', 'Camila', 'Bastard', 'alimac167@hotmail.com', 'Clara', 'Centro', NULL, '1', '0'),
(32950, 16, 'Clara', '82401ddf60f99508e9b123d3840073b5', 'Clara', 'Tobar', 'ctobar@beinspirintools.com', 'Clara', 'Centro', NULL, '1', '0'),
(32951, 16, 'Diana', '82401ddf60f99508e9b123d3840073b5', 'Diana', 'Delgado', 'diana93.delgado@gmail.com', 'Fede', 'San Isidro', NULL, '1', '0'),
(32952, 16, 'Diego', 'ec15915cee95b74e90764a8ebb35ffb8', 'Diego', 'Roch', 'diego.roch@drofar.com.ar', 'Bruno', 'Centro', 'aaab7aedde3d71ff3090a91dcb8b32fd.jpg', '1', '1'),
(32953, 16, 'Esperanza', '82401ddf60f99508e9b123d3840073b5', 'Esperanza', 'Gonzalez', 'egonzalez@beinspiringtools.com', 'Esperanza', 'Centro', NULL, '1', '0'),
(32954, 16, 'Federico', '82401ddf60f99508e9b123d3840073b5', 'Federico', 'Bouzas', 'fbouzas@beinspiringtools.com', 'Fede', 'San Isidro', NULL, '1', '0'),
(32955, 16, 'Fernanda', '82401ddf60f99508e9b123d3840073b5', 'Fernanda', 'Maina', 'maina.nanda@hotmail.com.ar', 'Jopi', 'San Isidro', NULL, '1', '0'),
(32956, 16, 'Fernando', '82401ddf60f99508e9b123d3840073b5', 'Fernando', 'Riccio', 'friccio@morisan.com.ar', 'Bruno', 'Centro', NULL, '1', '0'),
(32957, 16, 'Giuliano', '82401ddf60f99508e9b123d3840073b5', 'Giuliano', 'Laudani', 'giulianolaudani@gmail.com', 'Jopi', 'San Isidro', NULL, '1', '0'),
(32958, 16, 'Javier', '82401ddf60f99508e9b123d3840073b5', 'Javier', 'Dubravka', 'dyddistribuidora@hotmail.com', 'Bruno', 'Centro', NULL, '1', '0'),
(32959, 16, 'Jennifer', '82401ddf60f99508e9b123d3840073b5', 'Jennifer', 'Finelli', 'jenniferfinelli16@hotmail.com', 'Fede', 'San Isidro', NULL, '1', '0'),
(32960, 16, 'Joan', '82401ddf60f99508e9b123d3840073b5', 'Joan', 'Marin', 'jomamaga@gmail.com', 'Vale', 'Barcelona', NULL, '1', '0'),
(32961, 16, 'Jopi', 'f7236291359919691fa0f8d007c23989', 'Jopi', 'Maina', 'mjmaina@beinspiringtools.com', 'Jopi', 'San Isidro', NULL, '1', '1'),
(32962, 16, 'Jorge', '82401ddf60f99508e9b123d3840073b5', 'Jorge', 'Sandas', 'jj_sanda@yahoo.com.ar', 'Bruno', 'Centro', NULL, '1', '0'),
(32963, 16, 'Julian', '82401ddf60f99508e9b123d3840073b5', 'Julian', 'Barcelo', 'julian_1906@hotmail.com', 'Nadia', 'San Isidro', NULL, '1', '0'),
(32964, 16, 'Laura', '82401ddf60f99508e9b123d3840073b5', 'Laura', 'Piccaluga', 'lpiccaluga@beinspiringtools.com', 'Laura', 'San Isidro', NULL, '1', '0'),
(32965, 16, 'Luciano1', '82401ddf60f99508e9b123d3840073b5', 'Luciano', 'Neo', 'luchoneo@hotmail.com', 'Fede', 'San Isidro', NULL, '1', '0'),
(32966, 16, 'Luciano2', '82401ddf60f99508e9b123d3840073b5', 'Luciano', 'Posada', 'lucianoposada88@gmail.com', 'Clara', 'Centro', NULL, '1', '0'),
(32967, 16, 'Manuela', '82401ddf60f99508e9b123d3840073b5', 'Manuela', 'Duhau', 'manueladuhau@gmail.com', 'Clara', 'Centro', NULL, '1', '0'),
(32968, 16, 'Mariana', '82401ddf60f99508e9b123d3840073b5', 'Mariana', 'Piccaluga', 'Marianapiccaluga@hotmail.com', 'Laura', 'San Isidro', NULL, '1', '0'),
(32969, 16, 'Nadia', '82401ddf60f99508e9b123d3840073b5', 'Nadia', 'Elizondo', 'nelizondo@beinspiringtools.com', 'Nadia', 'San Isidro', NULL, '1', '0'),
(32970, 16, 'Nicolas', '82401ddf60f99508e9b123d3840073b5', 'Nicolas', 'Irigoytia', 'nicolasirigoytia@hotmail.com', 'Nadia', 'San Isidro', NULL, '1', '0'),
(32971, 16, 'Pablo', '82401ddf60f99508e9b123d3840073b5', 'Pablo', 'Piccaluga', 'Pablopiccaluga@hotmail.com', 'Laura', 'San Isidro', NULL, '1', '0'),
(32972, 16, 'Piagon', '82401ddf60f99508e9b123d3840073b5', 'Pia', 'Gonzalez', 'piamgonzalez@gmail.com', 'Esperanza', 'Centro', NULL, '1', '0'),
(32973, 16, 'Sebastian', '82401ddf60f99508e9b123d3840073b5', 'Sebastian', 'Romano', 'Cbaromano@gmail.com', 'Laura', 'San Isidro', NULL, '1', '0'),
(32974, 16, 'Tomas', '82401ddf60f99508e9b123d3840073b5', 'Tomas', 'Olmos', 'tomasolmos@gmail.com', 'Bruno', 'Centro', NULL, '1', '0'),
(32975, 16, 'Valeria', '82401ddf60f99508e9b123d3840073b5', 'Valeria', 'Agulla', 'vagulla@beinspiringtools.com', 'Vale', 'Barcelona', NULL, '1', '0'),
(32976, 2, 'test012345678901234567890123456789012345678901234567890123456789', '05a671c66aefea124cc08b76ea6d30bb', 'test', 'test', 'test@test.com', 'test', 'test', NULL, '1', '1'),
(32977, 2, 'robertmurer@gmail.com', '9ce98b66ba3847c5a67a55b76cdd4294', 'Roberto', 'Murer', 'robertmurer@gmail.com', 'Sistemas', 'Buenos Aires', NULL, '1', '1'),
(32978, 2, 'mjmaina@beinspiringtools.com', '82401ddf60f99508e9b123d3840073b5', 'Jopi', 'Maina', 'mjmaina@beinspiringtools.com', 'Jopi', 'San Isidro', NULL, '1', '1'),
(32979, 2, 'giulianolaudani@gmail.com', '82401ddf60f99508e9b123d3840073b5', 'Giuliano', 'Laudani', 'giulianolaudani@gmail.com', 'Jopi', 'San Isidro', NULL, '1', '0'),
(32980, 2, 'maina.nanda@hotmail.com.ar', '82401ddf60f99508e9b123d3840073b5', 'Fernanda', 'Maina', 'maina.nanda@hotmail.com.ar', 'Jopi', 'San Isidro', NULL, '1', '0'),
(32981, 2, 'diana93.delgado@gmail.com', '82401ddf60f99508e9b123d3840073b5', 'Diana', 'Delgado', 'diana93.delgado@gmail.com', 'Fede', 'San Isidro', NULL, '1', '0'),
(32982, 17, 'carlos', 'dc599a9972fde3045dab59dbd1ae170b', 'Carlos Clemente', 'Segovia Rocha', 'carlos@gmail.com', 'Sistemas', 'CABA', 'fc17bff0d615a8c15be5246a590dc54b.jpg', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `empleados_import`
--

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
-- Table structure for table `empresas`
--

CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL,
  `empresa` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `bases_condiciones` text COLLATE latin1_spanish_ci,
  `url` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `is_trivia` enum('0','1') COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `empresa`, `descripcion`, `bases_condiciones`, `url`, `is_trivia`) VALUES
(2, 'google', 'BANNER BANNER \r\ntest google empresa \r\nBANNER BANNER asdfas\r\ndescripción \r\nBANNER BANNER \r\npara el banner header\r\nprueba con los cambios de imágenes en la edición de empresas...\r\nTest agregando mas texto y \r\nmás texto y \r\nmás\r\n!!!!\r\n?????', 'BASES Y CONDICIONES\r\n1. El Juego \r\nEl juego consiste en sumar la mayor cantidad de puntos a través de la suma del puntaje asignado por XXXX (en adelante \"organizador\") a los participantes en función de su rendimiento individual a lo largo de la vigencia del JUEGO (desde el inicio hasta el fin de la Copa Mundial Rusia 2018 conforme los términos de la suma de los puntos. \r\n\r\n2. Participación y Registración\r\nEs condición para participar del JUEGO ser empleado activo de la empresa, de sus afiliadas o contratados por agencia de servicios eventuales, tanto al momento de la inscripción, como al finalizar el JUEGO. Para participar del JUEGO el empleado debe estar registrado en el sitio web www.todosalacancha.com/XXXX y haber aceptado las Bases y Condiciones. Para registrarse, el sistema solicitará ingresar un usuario y una contraseña con lo que se verá registrada su participación en la competencia. Los Participantes deberán cumplir con todo lo dispuesto en las bases y Condiciones y sus eventuales modificaciones durante toda la vigencia del JUEGO. Caso contrario, podrá ser dado de baja. Se aceptará solamente una participación por persona.\r\n\r\n3. Vigencia\r\nEl JUEGO, comienza el día 14 de junio del año 2018 y finaliza el 16 de julio del mismo año. Los participantes podrán ingresar y efectuar sus pronósticos a partir de la puesta en funcionamiento del sistema, el día 1º de Junio del año 2018. Sin perjuicio de ello, el Organizador se reservará el derecho a modificar la fecha mencionada. El Organizador comunicará las fechas de inicio del juego a través de los medios de comunicación institucionales de los que dispone.\r\n\r\n4. Forma de participar \r\nUna vez que se ingresa, cada participante tiene que completar los resultados de los partidos que se disputan en la Copa del Mundo y en función de los aciertos suman puntos. Al ingresar al juego y aceptar la participación cada jugador participará de forma individual.\r\nEn cada fase, excepto en la final, se habilitará la posibilidad de contestar una TRIVIA y duplicar el puntaje acumulado en dicha fase. Sólo se duplicará el puntaje acumulado en la fase si se contestan correctamente TODAS las preguntas de la trivia.\r\n\r\n5. Mecánica del Juego \r\nUna vez completa la grilla de los partidos, los puntos se contabilizarán de la siguiente manera:\r\n\"	Por acertar como salió el partido se asignan 20 puntos.\r\n\"	Por acertar el resultado exacto (Ej.: 3-1) se asignan 30 puntos extra (total 50 puntos).\r\n\"	Por trivia contestada correctamente se duplica el puntaje de la fase. \r\n\"	Por acertar el goleador del torneo: 200 puntos.\r\n\"	Por acertar el campeón del mundo: 200 puntos.\r\n\r\nEl torneo se dividirá en 5 etapas. El participante con mayor cantidad de puntos por etapa se consagrará como ganador de la etapa en cuestión.\r\n\r\nEl tiempo límite para ingresar resultados y/o realizar modificaciones es de 24 horas antes del horario en que está pautado el partido.\r\n\r\nEn todos los casos la validez del partido para contabilizar los puntos es de 90 minutos o 120 si se extiende a un alargue. Si el partido se define por penales, el resultado será empate (el resultado exacto será como haya terminado el partido empatado). No se tienen en cuenta la instancia definición por penales.\r\n\r\n6. Ganadores\r\nEl ganador del JUEGO será el participante que mayor cantidad de puntos hubiera alcanzado en el ranking general y surgirá de la suma de puntajes totales: pronósticos de todos los partidos, puntaje de las trivias pronóstico del campeón y de goleador.\r\nTambién habrá ganadores de cada una de las etapas.\r\nLos ganadores se publicarán en el sitio de forma automática.\r\nEn caso de empate, tanto en las fases como en la participación general, se llevará a cabo un sorteo entre los participantes del cual surgirá el ganador final. El sorteo estará a cargo del organizador. \r\n\r\n7.  Premios \r\nLos premios serán asignados al \"participante\" que mayor cantidad de puntos hubiera alcanzado dentro del período considerado. Habrá premio para los participantes que mayor puntaje obtengan en cada una de las fases correspondiente a cada uno de las etapas:\r\n-Fase 1: Fase de grupos del jueves 14 de junio al XXXXX inclusive. En total XX partidos. Correspondientes a la fase de grupos.\r\n-Etapa 2: del XXXXX de junio al XXXX  inclusive. En total 8 partidos. Corresponde a los octavos de final.\r\n-Etapa 3: del xxxxxxx al XXXXXX inclusive. En total XX partidos. Correspondientes a cuartos de final.\r\n-Etapa 4: del xxxxxxx al XXXXXX inclusive. En total XX partidos. Correspondientes a semifinales.\r\n-Etapa 5: del xxxxxxx al XXXXXX inclusive. En total XX partidos. Correspondientes a la final.\r\n\r\nExistirá un premio final al cierre del \"juego\". El premio final se asignará al ganador de todo el \"juego\" y surgirá de la suma de puntajes totales: pronósticos de todos los partidos, pronóstico del campeón y de goleador.\r\n\r\nEl listado de los premios correspondiente a las distintas fases será publicado en www.todosalacancha.com/XXXXX y en la Intranet del organizador. \r\n\r\n8. Derechos de Imagen \r\nEl organizador podrá requerir la presencia del ganador, así como también publicar sus datos personales e incluso exhibir su imagen (foto y/o video) mediante cualquier acción publicitaria y a través de los medios de difusión que considere convenientes.\r\nLa mera participación en el presente juego implica necesariamente la autorización del participante en tal sentido, sin que se le deba por ello contraprestación alguna.\r\n9. Condiciones Generales \r\nLa participación en el Juego implica el pleno conocimiento y aceptación de este Reglamento y de los requisitos para participar en el mismo. El \"organizador\" se reserva el derecho a modificar, ampliar y/o aclarar el presente reglamento y/o cualquiera de los procedimientos allí previstos. Ante cuestiones dudosas, las decisiones del \"organizador\" de cualquier tipo, serán inapelables y no darán derecho a reclamos de ninguna naturaleza.\r\n\r\nLa presente actividad no reviste la calidad de juego o sorteo previstos en el artículo 10 de la Ley 22.802 toda vez que la participación en el mismo no se condiciona en modo alguno a la contratación de servicio alguno.', 'google', '1'),
(3, 'fallcut', 'description Se usa en el top banner', 'Bases y condiciones para esta empresa fallcut', 'fallcut', '1'),
(4, 'Test Empresa Name', 'afdafd asdf asdf', 'asfdasd fa', 'testempresa', '1'),
(5, 'marvel', 'Esta es la empresa Marvel.\r\nBienvenidos\r\nEsto se usará en el banner top', 'Bases y condiciones empresa Marvel.\r\nBienvenidos a Marvel...asdffasd', 'marvel', '0'),
(6, 'NombreDeMiEmpresa', 'descripción de la Empresa nueva\r\n!?\r\n!?', NULL, 'URL_Empresa', '1'),
(8, 'Prueba Prueba', 'prueba', 'bases', 'prueba2', '1'),
(15, 'MiEmpresa', 'alskdjflkasdjfklsadfsda', NULL, 'urlempresa', '1'),
(16, 'Be Inspiring Tools', 'Entendemos la motivación de las personas como motor de las empresas. Desarrollamos programas de motivación, reconocimientos e incentivos para equipos de trabajo. Aportamos creatividad, tecnología y servicio, para garantizar el éxito de los proyectos!', 'BASES Y CONDICIONES\r\n1. El Juego \r\nEl juego consiste en sumar la mayor cantidad de puntos a través de la suma del puntaje asignado por XXXX (en adelante \"organizador\") a los participantes en función de su rendimiento individual a lo largo de la vigencia del JUEGO (desde el inicio hasta el fin de la Copa Mundial Rusia 2018 conforme los términos de la suma de los puntos. \r\n\r\n2. Participación y Registración\r\nEs condición para participar del JUEGO ser empleado activo de la empresa, de sus afiliadas o contratados por agencia de servicios eventuales, tanto al momento de la inscripción, como al finalizar el JUEGO. Para participar del JUEGO el empleado debe estar registrado en el sitio web www.todosalacancha.com/XXXX y haber aceptado las Bases y Condiciones. Para registrarse, el sistema solicitará ingresar un usuario y una contraseña con lo que se verá registrada su participación en la competencia. Los Participantes deberán cumplir con todo lo dispuesto en las bases y Condiciones y sus eventuales modificaciones durante toda la vigencia del JUEGO. Caso contrario, podrá ser dado de baja. Se aceptará solamente una participación por persona.\r\n\r\n3. Vigencia\r\nEl JUEGO, comienza el día 14 de junio del año 2018 y finaliza el 16 de julio del mismo año. Los participantes podrán ingresar y efectuar sus pronósticos a partir de la puesta en funcionamiento del sistema, el día 1º de Junio del año 2018. Sin perjuicio de ello, el Organizador se reservará el derecho a modificar la fecha mencionada. El Organizador comunicará las fechas de inicio del juego a través de los medios de comunicación institucionales de los que dispone.\r\n\r\n4. Forma de participar \r\nUna vez que se ingresa, cada participante tiene que completar los resultados de los partidos que se disputan en la Copa del Mundo y en función de los aciertos suman puntos. Al ingresar al juego y aceptar la participación cada jugador participará de forma individual.\r\nEn cada fase, excepto en la final, se habilitará la posibilidad de contestar una TRIVIA y duplicar el puntaje acumulado en dicha fase. Sólo se duplicará el puntaje acumulado en la fase si se contestan correctamente TODAS las preguntas de la trivia.\r\n\r\n5. Mecánica del Juego \r\nUna vez completa la grilla de los partidos, los puntos se contabilizarán de la siguiente manera:\r\n\"	Por acertar como salió el partido se asignan 20 puntos.\r\n\"	Por acertar el resultado exacto (Ej.: 3-1) se asignan 30 puntos extra (total 50 puntos).\r\n\"	Por trivia contestada correctamente se duplica el puntaje de la fase. \r\n\"	Por acertar el goleador del torneo: 200 puntos.\r\n\"	Por acertar el campeón del mundo: 200 puntos.\r\n\r\nEl torneo se dividirá en 5 etapas. El participante con mayor cantidad de puntos por etapa se consagrará como ganador de la etapa en cuestión.\r\n\r\nEl tiempo límite para ingresar resultados y/o realizar modificaciones es de 24 horas antes del horario en que está pautado el partido.\r\n\r\nEn todos los casos la validez del partido para contabilizar los puntos es de 90 minutos o 120 si se extiende a un alargue. Si el partido se define por penales, el resultado será empate (el resultado exacto será como haya terminado el partido empatado). No se tienen en cuenta la instancia definición por penales.\r\n\r\n6. Ganadores\r\nEl ganador del JUEGO será el participante que mayor cantidad de puntos hubiera alcanzado en el ranking general y surgirá de la suma de puntajes totales: pronósticos de todos los partidos, puntaje de las trivias pronóstico del campeón y de goleador.\r\nTambién habrá ganadores de cada una de las etapas.\r\nLos ganadores se publicarán en el sitio de forma automática.\r\nEn caso de empate, tanto en las fases como en la participación general, se llevará a cabo un sorteo entre los participantes del cual surgirá el ganador final. El sorteo estará a cargo del organizador. \r\n\r\n7.  Premios \r\nLos premios serán asignados al \"participante\" que mayor cantidad de puntos hubiera alcanzado dentro del período considerado. Habrá premio para los participantes que mayor puntaje obtengan en cada una de las fases correspondiente a cada uno de las etapas:\r\n-Fase 1: Fase de grupos del jueves 14 de junio al XXXXX inclusive. En total XX partidos. Correspondientes a la fase de grupos.\r\n-Etapa 2: del XXXXX de junio al XXXX  inclusive. En total 8 partidos. Corresponde a los octavos de final.\r\n-Etapa 3: del xxxxxxx al XXXXXX inclusive. En total XX partidos. Correspondientes a cuartos de final.\r\n-Etapa 4: del xxxxxxx al XXXXXX inclusive. En total XX partidos. Correspondientes a semifinales.\r\n-Etapa 5: del xxxxxxx al XXXXXX inclusive. En total XX partidos. Correspondientes a la final.\r\n\r\nExistirá un premio final al cierre del \"juego\". El premio final se asignará al ganador de todo el \"juego\" y surgirá de la suma de puntajes totales: pronósticos de todos los partidos, pronóstico del campeón y de goleador.\r\n\r\nEl listado de los premios correspondiente a las distintas fases será publicado en www.todosalacancha.com/XXXXX y en la Intranet del organizador. \r\n\r\n8. Derechos de Imagen \r\nEl organizador podrá requerir la presencia del ganador, así como también publicar sus datos personales e incluso exhibir su imagen (foto y/o video) mediante cualquier acción publicitaria y a través de los medios de difusión que considere convenientes.\r\nLa mera participación en el presente juego implica necesariamente la autorización del participante en tal sentido, sin que se le deba por ello contraprestación alguna.\r\n9. Condiciones Generales \r\nLa participación en el Juego implica el pleno conocimiento y aceptación de este Reglamento y de los requisitos para participar en el mismo. El \"organizador\" se reserva el derecho a modificar, ampliar y/o aclarar el presente reglamento y/o cualquiera de los procedimientos allí previstos. Ante cuestiones dudosas, las decisiones del \"organizador\" de cualquier tipo, serán inapelables y no darán derecho a reclamos de ninguna naturaleza.\r\n\r\nLa presente actividad no reviste la calidad de juego o sorteo previstos en el artículo 10 de la Ley 22.802 toda vez que la participación en el mismo no se condiciona en modo alguno a la contratación de servicio alguno.', 'BIT', '1'),
(17, 'sietepuentes', 'Esta es la descripción de la empresa que tiene que salir en el banner.\r\nEsta es la descripción de la empresa que tiene que salir en el banner.\r\nEsta es la descripción de la empresa que tiene que salir en el banner.\r\nEsta es la descripción de la empresa qu', 'Bases y condiciones de la Empresa.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.\r\nAca van las reglas y normas de como se va a jugar.', '7puentes', '1');

-- --------------------------------------------------------

--
-- Table structure for table `empresas_imagenes`
--

CREATE TABLE `empresas_imagenes` (
  `id_empresa` int(11) NOT NULL,
  `tipo_imagen` enum('11','12','21','22','31','32','41','42','51','52','61','62','71','72','81','82') COLLATE latin1_spanish_ci NOT NULL COMMENT 'XY => X = 1:logo, 2:premio ... 8:slider#5; Y = 1:computadora, 2:móviles',
  `nombre_archivo` varchar(15) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `empresas_imagenes`
--

INSERT INTO `empresas_imagenes` (`id_empresa`, `tipo_imagen`, `nombre_archivo`) VALUES
(2, '11', 'pc_logo.png'),
(2, '12', 'm_logo.png'),
(2, '21', 'pc_premio.jpg'),
(2, '22', 'm_premio.jpg'),
(2, '31', 'pc_banner.png'),
(2, '32', 'm_banner.png'),
(2, '41', 'pc_slider1.jpg'),
(2, '42', 'm_slider1.jpg'),
(2, '51', 'pc_slider2.jpg'),
(2, '52', 'm_slider2.jpg'),
(2, '61', 'pc_slider3.jpg'),
(2, '62', 'm_slider3.jpg'),
(2, '71', 'pc_slider4.jpg'),
(2, '72', 'm_slider4.jpg'),
(2, '81', 'pc_slider5.jpg'),
(2, '82', 'm_slider5.jpg'),
(3, '11', 'pc_logo.jpg'),
(3, '12', 'm_logo.jpg'),
(3, '21', 'pc_premio.png'),
(3, '22', 'm_premio.jpg'),
(3, '31', 'pc_banner.png'),
(3, '32', 'm_banner.jpg'),
(3, '41', 'pc_slider1.jpg'),
(3, '42', 'm_slider1.jpg'),
(4, '11', 'pc_logo.png'),
(4, '21', 'pc_premio.png'),
(4, '31', 'pc_banner.png'),
(5, '11', 'pc_logo.png'),
(5, '21', 'pc_premio.jpg'),
(5, '22', 'm_premio.jpg'),
(5, '31', 'pc_banner.png'),
(5, '32', 'm_banner.png'),
(5, '41', 'pc_slider1.jpg'),
(5, '42', 'm_slider1.jpg'),
(6, '11', 'pc_logo.png'),
(6, '21', 'pc_premio.jpg'),
(6, '22', 'm_premio.png'),
(8, '11', 'pc_logo.jpg'),
(8, '21', 'pc_premio.jpg'),
(8, '22', 'm_premio.png'),
(15, '11', 'pc_logo.jpg'),
(15, '21', 'pc_premio.png'),
(15, '22', 'm_premio.jpg'),
(16, '11', 'pc_logo.png'),
(16, '21', 'pc_premio.jpg'),
(16, '22', 'm_premio.jpg'),
(16, '51', 'pc_slider2.jpg'),
(16, '52', 'm_slider2.jpg'),
(16, '61', 'pc_slider3.jpg'),
(16, '62', 'm_slider3.jpg'),
(16, '71', 'pc_slider4.jpg'),
(16, '72', 'm_slider4.jpg'),
(16, '81', 'pc_slider5.jpg'),
(16, '82', 'm_slider5.jpg'),
(17, '11', 'pc_logo.jpeg'),
(17, '21', 'pc_premio.jpg'),
(17, '22', 'm_premio.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `equipos`
--

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
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`team_id`, `name`, `country`, `team_url`, `group_order`, `campeon`, `wwhen`) VALUES
(1, 'Rusia', 'RUS', 'http://es.fifa.com/worldcup/teams/team=43965/index.html', 1, '0', '2018-03-15 14:42:11'),
(2, 'A.Saudita', 'KSA', 'http://es.fifa.com/worldcup/teams/team=43835/index.html', 1, '0', '2018-03-23 22:05:05'),
(3, 'Egipto', 'EGY', 'http://es.fifa.com/worldcup/teams/team=43855/index.html', 1, '0', '2018-03-12 01:41:54'),
(4, 'Uruguay', 'URU', 'http://es.fifa.com/worldcup/teams/team=43930/index.html', 1, '0', '2018-03-12 01:41:54'),
(5, 'Portugal', 'POR', 'http://es.fifa.com/worldcup/teams/team=43963/index.html', 2, '0', '2018-03-12 01:41:54'),
(6, 'España', 'ESP', 'http://es.fifa.com/worldcup/teams/team=43969/index.html', 2, '0', '2018-03-19 07:24:11'),
(7, 'Marruecos', 'MAR', 'http://es.fifa.com/worldcup/teams/team=43872/index.html', 2, '0', '2018-06-03 16:23:21'),
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
(19, 'C. Rica', 'CRC', 'http://es.fifa.com/worldcup/teams/team=43901/index.html', 5, '0', '2018-03-26 16:43:24'),
(20, 'Serbia', 'SRB', 'http://es.fifa.com/worldcup/teams/team=1902465/index.html', 5, '0', '2018-03-12 01:41:54'),
(21, 'Alemania', 'ALE', 'http://es.fifa.com/worldcup/teams/team=43948/index.html', 6, '0', '2018-03-12 01:41:54'),
(22, 'Mexico', 'MEX', 'http://es.fifa.com/worldcup/teams/team=43911/index.html', 6, '0', '2018-03-12 01:41:54'),
(23, 'Suecia', 'SWE', 'http://es.fifa.com/worldcup/teams/team=43970/index.html', 6, '0', '2018-03-12 01:41:54'),
(24, 'Corea', 'KOR', 'http://es.fifa.com/worldcup/teams/team=43822/index.html', 6, '0', '2018-03-23 22:31:01'),
(25, 'Bélgica', 'BEL', 'http://es.fifa.com/worldcup/teams/team=43935/index.html', 7, '0', '2018-03-12 01:58:22'),
(26, 'Panamá', 'PAN', 'http://es.fifa.com/worldcup/teams/team=43914/index.html', 7, '0', '2018-03-12 01:58:30'),
(27, 'Túnez', 'TUN', 'http://es.fifa.com/worldcup/teams/team=43888/index.html', 7, '0', '2018-03-12 01:58:36'),
(28, 'Inglaterra', 'ENG', 'http://es.fifa.com/worldcup/teams/team=43942/index.html', 7, '0', '2018-03-12 01:41:54'),
(29, 'Polonia', 'POL', 'http://es.fifa.com/worldcup/teams/team=43962/index.html', 8, '0', '2018-03-12 01:41:54'),
(30, 'Senegal', 'SEN', 'http://es.fifa.com/worldcup/teams/team=43879/index.html', 8, '0', '2018-03-12 01:41:54'),
(31, 'Colombia', 'COL', 'http://es.fifa.com/worldcup/teams/team=43926/index.html', 8, '0', '2018-03-12 01:41:54'),
(32, 'Japón', 'JAP', 'http://es.fifa.com/worldcup/teams/team=43819/index.html', 8, '0', '2018-03-12 01:58:42'),
(33, '1ero A', 'xxx', '', 0, '0', '2018-03-23 22:38:38'),
(34, '1ero B', 'xxx', '', 0, '0', '2018-03-23 22:38:42'),
(35, '1ero C', 'xxx', '', 0, '0', '2018-03-23 22:38:45'),
(36, '1ero D', 'xxx', '', 0, '0', '2018-03-23 22:38:49'),
(37, '1ero E', 'xxx', '', 0, '0', '2018-03-23 22:38:52'),
(38, '1ero F', 'xxx', '', 0, '0', '2018-03-23 22:38:55'),
(39, '1ero G', 'xxx', '', 0, '0', '2018-03-23 22:38:58'),
(40, '1ero H', 'xxx', '', 0, '0', '2018-03-23 22:39:02'),
(41, '2do A', 'xxx', '', 0, '0', '2018-03-23 22:39:08'),
(42, '2do B', 'xxx', '', 0, '0', '2018-03-23 22:39:12'),
(43, '2do C', 'xxx', '', 0, '0', '2018-03-23 22:39:15'),
(44, '2do D', 'xxx', '', 0, '0', '2018-03-23 22:39:18'),
(45, '2do E', 'xxx', '', 0, '0', '2018-03-23 22:39:21'),
(46, '2do F', 'xxx', '', 0, '0', '2018-03-23 22:39:24'),
(47, '2do G', 'xxx', '', 0, '0', '2018-03-23 22:39:26'),
(48, '2do H', 'xxx', '', 0, '0', '2018-03-23 22:39:29'),
(49, 'Ganador 49', 'xxx', '', 0, '0', '2018-03-23 22:39:45'),
(50, 'Ganador 50', 'xxx', '', 0, '0', '2018-03-23 22:39:54'),
(51, 'Ganador 51', 'xxx', '', 0, '0', '2018-03-26 16:54:54'),
(52, 'Ganador 52', 'xxx', '', 0, '0', '2018-03-26 16:55:03'),
(53, 'Ganador 53', 'xxx', '', 0, '0', '2018-03-26 16:55:14'),
(54, 'Ganador 54', 'xxx', '', 0, '0', '2018-03-26 16:55:25'),
(55, 'Ganador 55', 'xxx', '', 0, '0', '2018-03-26 16:55:41'),
(56, 'Ganador 56', 'xxx', '', 0, '0', '2018-03-26 16:55:54'),
(57, 'Ganador 57', 'xxx', '', 0, '0', '2018-03-26 16:56:03'),
(58, 'Ganador 58', 'xxx', '', 0, '0', '2018-03-26 16:56:12'),
(59, 'Ganador 59', 'xxx', '', 0, '0', '2018-03-26 16:56:23'),
(60, 'Ganador 60', 'xxx', '', 0, '0', '2018-03-26 16:56:29'),
(61, 'Perdedor 61', 'xxx', '', 0, '0', '2018-03-26 16:56:40'),
(62, 'Perdedor 62', 'xxx', '', 0, '0', '2018-03-26 16:56:55'),
(63, 'Ganador 61', 'xxx', '', 0, '0', '2018-03-26 16:57:11'),
(64, 'Ganador 62', 'xxx', '', 0, '0', '2018-03-26 16:57:21');

--
-- Triggers `equipos`
--
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
-- Table structure for table `equipo_grupo`
--

CREATE TABLE `equipo_grupo` (
  `equipo_grupo_id` int(20) NOT NULL,
  `team_id` int(20) NOT NULL,
  `grupo_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fases`
--

CREATE TABLE `fases` (
  `stage_id` int(11) NOT NULL,
  `stage_name` varchar(32) COLLATE latin1_spanish_ci NOT NULL,
  `is_group` tinyint(1) NOT NULL,
  `sort_order` tinyint(4) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `fases`
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
-- Table structure for table `gropus`
--

CREATE TABLE `gropus` (
  `grupo_id` int(20) NOT NULL,
  `grupo` varchar(200) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jugadores`
--

CREATE TABLE `jugadores` (
  `id_jugador` int(11) NOT NULL,
  `nombre_jugador` varchar(60) COLLATE latin1_spanish_ci NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `goles` smallint(2) NOT NULL DEFAULT '0',
  `es_goleador` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `jugadores`
--

INSERT INTO `jugadores` (`id_jugador`, `nombre_jugador`, `id_equipo`, `goles`, `es_goleador`) VALUES
(1, 'Lionel Messi', 13, 8, '1'),
(2, 'Gonzalo Higuaín', 13, 8, '1'),
(3, 'Cristiano Ronaldo', 5, 7, '0');

--
-- Triggers `jugadores`
--
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
-- Table structure for table `mecanica_juego`
--

CREATE TABLE `mecanica_juego` (
  `id` int(20) NOT NULL,
  `rules` text COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `mecanica_juego`
--

INSERT INTO `mecanica_juego` (`id`, `rules`) VALUES
(3, 'Mecánica de juego común a todas las empresas.\r\nComo se asignarán puntos:\r\n-20 puntos por acertar el resultado del partido\r\n-50 puntos por acertar exacto el marcador de ambos equipos\r\n-Por trivia contestada correctamente se duplica el puntaje de la fase\r\n-Si acertás goleador son 200 puntos extra!\r\n-Si acertás equipo campeón del mundial, son 200 puntos extra!');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

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
-- Dumping data for table `module`
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
-- Table structure for table `partidos`
--

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
-- Dumping data for table `partidos`
--

INSERT INTO `partidos` (`match_id`, `match_no`, `kickoff`, `home_team_id`, `away_team_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `venue_id`, `is_result`, `extra_time`, `stage_id`, `scored`, `wwhen`, `resultado`) VALUES
(1, 1, '2018-06-14 12:00:00', 1, 2, 1, 1, 0, 0, 8, 0, 0, 1, 0, '2018-05-08 15:48:11', 0),
(2, 2, '2018-06-15 09:00:00', 3, 4, 6, 6, 0, 0, 4, 0, 0, 1, 0, '2018-05-08 15:48:21', 0),
(3, 3, '2018-06-15 12:00:00', 7, 8, 4, 5, 0, 0, 1, 0, 0, 2, 1, '2018-06-10 16:43:29', 2),
(4, 4, '2018-06-15 15:00:00', 5, 6, 6, 6, 0, 0, 5, 0, 0, 2, 1, '2018-06-10 16:43:55', 0),
(5, 5, '2018-06-16 07:00:00', 9, 10, 8, 8, 0, 0, 6, 0, 0, 3, 1, '2018-06-10 16:43:41', 0),
(6, 6, '2018-06-16 10:00:00', 13, 14, 6, 6, 0, 0, 11, 0, 0, 4, 0, '2018-06-14 13:46:17', 0),
(7, 7, '2018-06-16 13:00:00', 11, 12, 9, 9, 0, 0, 13, 0, 0, 3, 0, '2018-06-09 07:30:15', 0),
(8, 8, '2018-06-16 21:00:00', 15, 16, 7, 6, 0, 0, 2, 0, 0, 4, 0, '2018-06-14 13:46:17', 0),
(9, 9, '2018-06-17 09:00:00', 19, 20, 6, 6, 0, 0, 9, 0, 0, 5, 0, '2018-06-14 13:46:17', 0),
(10, 10, '2018-06-17 12:00:00', 21, 22, 2, 1, 0, 0, 8, 0, 0, 6, 0, '2018-06-10 17:14:30', 0),
(11, 11, '2018-06-17 15:00:00', 17, 18, 1, 3, 0, 0, 10, 0, 0, 5, 0, '2018-06-10 17:14:45', 0),
(12, 12, '2018-06-18 09:00:00', 23, 24, 1, 3, 0, 0, 7, 0, 0, 6, 0, '2018-06-10 17:15:03', 0),
(13, 13, '2018-06-18 12:00:00', 25, 26, 0, 0, 0, 0, 5, 0, 0, 7, 0, '2018-01-19 18:55:11', 0),
(14, 14, '2018-06-18 15:00:00', 27, 28, 3, 2, 0, 0, 3, 0, 0, 7, 0, '2018-06-10 17:20:36', 0),
(15, 15, '2018-06-19 09:00:00', 31, 32, 1, 3, 0, 0, 13, 0, 0, 8, 0, '2018-06-10 17:20:48', 0),
(16, 16, '2018-06-19 12:00:00', 29, 30, 2, 0, 0, 0, 11, 0, 0, 8, 0, '2018-06-10 17:21:03', 0),
(17, 17, '2018-06-19 15:00:00', 1, 3, 1, 0, 0, 0, 1, 0, 0, 1, 0, '2018-06-10 17:21:12', 0),
(18, 18, '2018-06-20 09:00:00', 5, 7, 0, 1, 0, 0, 8, 0, 0, 2, 0, '2018-06-10 17:21:23', 0),
(19, 19, '2018-06-20 12:00:00', 4, 2, 0, 1, 0, 0, 10, 0, 0, 1, 0, '2018-06-10 17:21:43', 0),
(20, 20, '2018-06-20 15:00:00', 8, 6, 0, 0, 0, 0, 6, 0, 0, 2, 0, '2018-01-19 19:03:18', 0),
(21, 21, '2018-06-21 09:00:00', 12, 10, 0, 0, 0, 0, 9, 0, 0, 3, 0, '2018-01-19 19:04:11', 0),
(22, 22, '2018-06-21 12:00:00', 9, 11, 0, 0, 0, 0, 4, 0, 0, 3, 0, '2018-01-19 20:18:31', 0),
(23, 23, '2018-06-21 15:00:00', 13, 15, 0, 0, 0, 0, 7, 0, 0, 4, 0, '2018-01-19 20:19:17', 0),
(24, 24, '2018-06-22 09:00:00', 17, 19, 0, 0, 0, 0, 12, 0, 0, 5, 0, '2018-01-19 20:20:27', 0),
(25, 25, '2018-06-22 12:00:00', 16, 14, 0, 0, 0, 0, 3, 0, 0, 4, 0, '2018-01-19 20:21:10', 0),
(26, 26, '2018-06-22 15:00:00', 20, 18, 0, 0, 0, 0, 2, 0, 0, 5, 0, '2018-01-19 20:21:52', 0),
(27, 27, '2018-06-23 09:00:00', 25, 27, 0, 0, 0, 0, 11, 0, 0, 7, 0, '2018-01-19 20:22:53', 0),
(28, 28, '2018-06-23 12:00:00', 24, 22, 2, 5, 0, 0, 10, 0, 0, 6, 0, '2018-06-10 17:23:47', 0),
(29, 29, '2018-06-23 15:00:00', 21, 23, 0, 0, 0, 0, 5, 0, 0, 6, 0, '2018-01-19 20:25:18', 0),
(30, 30, '2018-06-24 09:00:00', 28, 26, 0, 0, 0, 0, 7, 0, 0, 7, 0, '2018-01-19 20:26:04', 0),
(31, 31, '2018-06-24 12:00:00', 32, 30, 3, 0, 0, 0, 4, 0, 0, 8, 0, '2018-06-10 17:23:58', 0),
(32, 32, '2018-06-24 15:00:00', 29, 31, 0, 0, 0, 0, 6, 0, 0, 8, 0, '2018-01-19 20:27:30', 0),
(33, 33, '2018-06-25 11:00:00', 4, 1, 0, 0, 0, 0, 9, 0, 0, 1, 0, '2018-01-19 20:28:45', 0),
(34, 34, '2018-06-25 11:00:00', 2, 3, 0, 0, 0, 0, 3, 0, 0, 1, 0, '2018-01-19 20:29:27', 0),
(35, 35, '2018-06-25 15:00:00', 8, 5, 0, 0, 0, 0, 13, 0, 0, 2, 0, '2018-01-19 20:30:15', 0),
(36, 36, '2018-06-25 15:00:00', 6, 7, 0, 0, 0, 0, 2, 0, 0, 2, 0, '2018-01-19 20:30:58', 0),
(37, 37, '2018-06-26 11:00:00', 12, 9, 0, 0, 0, 0, 8, 0, 0, 3, 0, '2018-01-19 20:31:34', 0),
(38, 38, '2018-06-26 11:00:00', 10, 11, 1, 0, 0, 0, 5, 0, 0, 3, 0, '2018-06-10 17:24:11', 0),
(39, 39, '2018-06-26 15:00:00', 16, 13, 0, 0, 0, 0, 1, 0, 0, 4, 0, '2018-01-19 20:32:53', 0),
(40, 40, '2018-06-26 15:00:00', 14, 15, 0, 0, 0, 0, 10, 0, 0, 4, 0, '2018-01-19 20:33:20', 0),
(41, 41, '2018-06-27 11:00:00', 22, 23, 3, 0, 0, 0, 4, 0, 0, 6, 0, '2018-06-10 17:25:55', 0),
(42, 42, '2018-06-27 11:00:00', 24, 21, 0, 0, 0, 0, 6, 0, 0, 6, 0, '2018-01-19 20:35:05', 0),
(43, 43, '2018-06-27 15:00:00', 20, 17, 0, 0, 0, 0, 11, 0, 0, 5, 0, '2018-01-19 20:35:37', 0),
(44, 44, '2018-06-27 15:00:00', 18, 19, 0, 1, 0, 0, 7, 0, 0, 5, 0, '2018-06-10 17:25:26', 0),
(45, 45, '2018-06-28 11:00:00', 32, 29, 0, 0, 0, 0, 3, 0, 0, 8, 0, '2018-01-19 20:36:51', 0),
(46, 46, '2018-06-28 11:00:00', 30, 31, 0, 0, 0, 0, 9, 0, 0, 8, 0, '2018-01-19 20:37:25', 0),
(47, 47, '2018-06-28 15:00:00', 26, 27, 0, 0, 0, 0, 13, 0, 0, 7, 0, '2018-01-19 20:37:59', 0),
(48, 48, '2018-06-28 15:00:00', 28, 25, 1, 2, 0, 0, 2, 0, 0, 7, 0, '2018-06-10 17:22:10', 0),
(49, 49, '2018-06-30 11:00:00', 32, 15, 0, 0, 0, 0, 2, 0, 0, 9, 1, '2018-06-30 12:07:08', 0),
(50, 50, '2018-06-30 15:00:00', 33, 42, 0, 0, 0, 0, 5, 0, 0, 9, 1, '2018-06-30 12:07:24', 0),
(51, 51, '2018-07-01 11:00:00', 34, 41, 0, 0, 0, 0, 8, 0, 0, 9, 1, '2018-06-30 12:07:38', 0),
(52, 52, '2018-07-01 15:00:00', 36, 43, 8, 6, 0, 0, 7, 0, 0, 9, 1, '2018-06-30 12:07:49', 1),
(53, 53, '2018-07-02 11:00:00', 37, 46, 1, 0, 0, 0, 9, 0, 0, 9, 1, '2018-06-30 12:08:03', 1),
(54, 54, '2018-07-02 15:00:00', 39, 48, 1, 0, 0, 0, 10, 0, 0, 9, 0, '2018-06-30 12:05:44', 0),
(55, 55, '2018-07-03 11:00:00', 38, 45, 0, 0, 0, 0, 1, 0, 0, 9, 1, '2018-06-30 12:06:28', 0),
(56, 56, '2018-07-03 15:00:00', 40, 47, 0, 0, 0, 0, 11, 0, 0, 9, 1, '2018-06-30 12:06:47', 0),
(57, 57, '2018-07-06 11:00:00', 49, 50, 0, 0, 0, 0, 7, 0, 0, 10, 0, '2018-01-19 20:47:41', 0),
(58, 58, '2018-07-06 15:00:00', 53, 54, 0, 0, 0, 0, 6, 0, 0, 10, 0, '2018-01-19 20:48:37', 0),
(59, 59, '2018-07-07 11:00:00', 55, 56, 0, 0, 0, 0, 9, 0, 0, 10, 0, '2018-01-19 20:49:19', 0),
(60, 60, '2018-07-07 15:00:00', 51, 52, 0, 0, 0, 0, 5, 0, 0, 10, 0, '2018-01-19 20:49:52', 0),
(61, 61, '2018-07-10 15:00:00', 57, 58, 10, 11, 0, 0, 5, 0, 0, 11, 0, '2018-06-30 10:49:29', 0),
(62, 62, '2018-07-11 15:00:00', 59, 60, 0, 0, 0, 0, 8, 0, 0, 11, 0, '2018-06-30 10:49:29', 0),
(63, 63, '2018-07-14 11:00:00', 61, 62, 0, 0, 0, 0, 1, 0, 0, 12, 0, '2018-01-19 20:53:22', 0),
(64, 64, '2018-07-15 12:00:00', 63, 64, 0, 0, 0, 0, 8, 0, 0, 13, 0, '2018-04-09 12:23:55', 0);

--
-- Triggers `partidos`
--
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
-- Table structure for table `pronosticos`
--

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
-- Dumping data for table `pronosticos`
--

INSERT INTO `pronosticos` (`prediction_id`, `user_id`, `match_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `points`, `wwhen`, `resultado`) VALUES
(1, 32982, 1, 2, 2, NULL, NULL, NULL, '2018-06-10 16:04:48', 0),
(2, 32982, 2, 6, 6, NULL, NULL, NULL, '2018-06-10 16:04:48', 0),
(3, 32982, 3, 4, 5, NULL, NULL, NULL, '2018-06-10 16:04:48', 2),
(4, 32982, 4, 5, 5, NULL, NULL, NULL, '2018-06-10 16:04:48', 0),
(5, 32982, 5, 8, 20, NULL, NULL, NULL, '2018-06-10 16:04:48', 2),
(6, 32982, 6, 4, 3, NULL, NULL, NULL, '2018-06-10 16:04:48', 1),
(7, 32982, 7, 9, 8, NULL, NULL, NULL, '2018-06-10 16:04:48', 1),
(8, 32982, 8, 6, 7, NULL, NULL, NULL, '2018-06-10 16:04:48', 2),
(9, 32982, 9, 1, 8, NULL, NULL, NULL, '2018-06-10 16:08:41', 2),
(10, 32982, 10, 0, 0, NULL, NULL, NULL, '2018-06-10 16:11:38', 0),
(11, 32982, 11, 3, 1, NULL, NULL, NULL, '2018-06-10 16:11:38', 1),
(12, 32982, 12, 1, 3, NULL, NULL, NULL, '2018-06-10 16:11:38', 2),
(13, 32982, 13, 10, 15, NULL, NULL, NULL, '2018-06-10 16:11:38', 2),
(14, 32982, 14, 5, 11, NULL, NULL, NULL, '2018-06-10 16:11:38', 2),
(15, 32982, 15, 16, 16, NULL, NULL, NULL, '2018-06-10 16:12:47', 0),
(16, 32982, 16, 0, 0, NULL, NULL, NULL, '2018-06-10 16:13:51', 0),
(17, 32982, 17, 14, 10, NULL, NULL, NULL, '2018-06-10 16:13:51', 1),
(18, 32982, 18, 8, 3, NULL, NULL, NULL, '2018-06-10 16:13:51', 1),
(19, 32982, 19, 12, 13, NULL, NULL, NULL, '2018-06-10 16:13:51', 2),
(20, 32982, 20, 6, 13, NULL, NULL, NULL, '2018-06-10 16:13:51', 2),
(21, 32982, 21, 10, 10, NULL, NULL, NULL, '2018-06-10 16:13:51', 0),
(22, 32982, 22, 0, 17, NULL, NULL, NULL, '2018-06-10 16:13:51', 2),
(23, 32982, 23, 17, 10, NULL, NULL, NULL, '2018-06-10 16:13:51', 1),
(24, 32982, 24, 15, 14, NULL, NULL, NULL, '2018-06-10 16:13:51', 1),
(25, 32982, 25, 2, 12, NULL, NULL, NULL, '2018-06-10 16:13:51', 2),
(26, 32982, 26, 12, 14, NULL, NULL, NULL, '2018-06-10 16:13:51', 2),
(27, 32982, 27, 16, 16, NULL, NULL, NULL, '2018-06-10 16:13:51', 0),
(28, 32982, 28, 0, 0, NULL, NULL, NULL, '2018-06-10 16:13:51', 0),
(29, 32982, 29, 3, 15, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(30, 32982, 30, 9, 12, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(31, 32982, 31, 6, 7, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(32, 32982, 32, 2, 6, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(33, 32982, 33, 0, 0, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(34, 32982, 34, 13, 6, NULL, NULL, NULL, '2018-06-10 16:15:42', 1),
(35, 32982, 35, 5, 3, NULL, NULL, NULL, '2018-06-10 16:15:42', 1),
(36, 32982, 36, 2, 5, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(37, 32982, 37, 2, 4, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(38, 32982, 38, 1, 5, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(39, 32982, 39, 3, 3, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(40, 32982, 40, 6, 0, NULL, NULL, NULL, '2018-06-10 16:15:42', 1),
(41, 32982, 41, 0, 0, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(42, 32982, 42, 0, 0, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(43, 32982, 43, 14, 4, NULL, NULL, NULL, '2018-06-10 16:15:42', 1),
(44, 32982, 44, 13, 13, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(45, 32982, 45, 17, 17, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(46, 32982, 46, 0, 0, NULL, NULL, NULL, '2018-06-10 16:15:42', 0),
(47, 32982, 47, 17, 16, NULL, NULL, NULL, '2018-06-10 16:15:42', 1),
(48, 32982, 48, 5, 10, NULL, NULL, NULL, '2018-06-10 16:15:42', 2),
(49, 32982, 52, 8, 6, NULL, NULL, NULL, '2018-06-30 12:03:02', 1),
(50, 32982, 53, 10, 7, NULL, NULL, NULL, '2018-06-30 12:03:02', 1),
(51, 32982, 54, 11, 2, NULL, NULL, NULL, '2018-06-30 12:03:02', 1),
(52, 32982, 55, 10, 14, NULL, NULL, NULL, '2018-06-30 12:03:02', 2),
(53, 32982, 56, 0, 0, NULL, NULL, NULL, '2018-06-30 12:03:02', 0),
(54, 32787, 63, 0, 0, NULL, NULL, NULL, '2018-07-11 15:49:07', 0),
(55, 32787, 61, 0, 0, NULL, NULL, NULL, '2018-07-08 15:48:01', 0);

--
-- Triggers `pronosticos`
--
DELIMITER $$
CREATE TRIGGER `actualizar_resultado_pronostico` BEFORE UPDATE ON `pronosticos` FOR EACH ROW BEGIN
    SET NEW.resultado = obtener_resultado_partido(NEW.home_goals, NEW.away_goals);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `registrar_resultado_pronostico` BEFORE INSERT ON `pronosticos` FOR EACH ROW BEGIN
    SET NEW.resultado = obtener_resultado_partido(NEW.home_goals, NEW.away_goals);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pronosticos20180411`
--

CREATE TABLE `pronosticos20180411` (
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
-- Dumping data for table `pronosticos20180411`
--

INSERT INTO `pronosticos20180411` (`prediction_id`, `user_id`, `match_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `points`, `wwhen`, `resultado`) VALUES
(1, 9, 1, 1, 1, NULL, NULL, NULL, '2018-03-31 17:36:58', 0),
(2, 9, 2, 3, 1, NULL, NULL, NULL, '2018-03-28 20:25:29', 1),
(3, 9, 4, 4, 2, NULL, NULL, NULL, '2018-03-28 20:25:29', 1),
(4, 32787, 1, 4, 3, NULL, NULL, NULL, '2018-06-10 11:08:41', 1),
(5, 32787, 2, 5, 4, NULL, NULL, NULL, '2018-06-13 11:49:21', 1),
(6, 32787, 3, 5, 6, NULL, NULL, NULL, '2018-03-28 20:27:51', 2),
(7, 32787, 4, 1, 1, NULL, NULL, NULL, '2018-03-28 20:28:10', 0),
(8, 32787, 63, 9, 0, NULL, NULL, NULL, '2018-03-28 20:29:15', 1),
(9, 32787, 64, 5, 6, NULL, NULL, NULL, '2018-03-28 20:28:03', 2),
(10, 32787, 49, 4, 4, NULL, NULL, NULL, '2018-03-28 20:28:57', 0),
(11, 32787, 50, 5, 6, NULL, NULL, NULL, '2018-03-28 20:28:57', 2),
(12, 32787, 51, 7, 8, NULL, NULL, NULL, '2018-03-28 20:28:57', 2),
(13, 32787, 52, 9, 9, NULL, NULL, NULL, '2018-03-28 20:28:57', 0),
(14, 2, 1, 2, 2, NULL, NULL, NULL, '2018-03-29 01:11:35', 0),
(15, 32787, 5, 5, 6, NULL, NULL, NULL, '2018-03-29 21:16:59', 2),
(16, 32787, 6, 0, 6, NULL, NULL, NULL, '2018-03-29 21:16:59', 2),
(17, 32787, 7, 0, 0, NULL, NULL, NULL, '2018-03-29 21:16:59', 0),
(18, 32787, 8, 3, 6, NULL, NULL, NULL, '2018-03-31 13:48:36', 2),
(19, 32787, 9, 6, 6, NULL, NULL, NULL, '2018-03-29 21:16:59', 0),
(20, 32787, 10, 6, 6, NULL, NULL, NULL, '2018-03-29 21:16:59', 0),
(21, 32787, 11, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(22, 32787, 12, 6, 0, NULL, NULL, NULL, '2018-03-29 21:17:00', 1),
(23, 32787, 13, 0, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 2),
(24, 32787, 14, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(25, 32787, 15, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(26, 32787, 16, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(27, 32787, 17, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(28, 32787, 18, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(29, 32787, 19, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(30, 32787, 20, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(31, 32787, 21, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(32, 32787, 22, 0, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 2),
(33, 32787, 23, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(34, 32787, 24, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(35, 32787, 25, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(36, 32787, 26, 6, 0, NULL, NULL, NULL, '2018-03-29 21:17:00', 1),
(37, 32787, 27, 0, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 2),
(38, 32787, 28, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(39, 32787, 29, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(40, 32787, 30, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(41, 32787, 31, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(42, 32787, 32, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(43, 32787, 33, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(44, 32787, 34, 6, 0, NULL, NULL, NULL, '2018-03-29 21:17:00', 1),
(45, 32787, 35, 0, 0, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(46, 32787, 36, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(47, 32787, 37, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(48, 32787, 38, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(49, 32787, 39, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(50, 32787, 40, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(51, 32787, 41, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(52, 32787, 42, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(53, 32787, 43, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(54, 32787, 44, 8, 6, NULL, NULL, NULL, '2018-04-01 15:05:00', 1),
(55, 32787, 45, 6, 6, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(56, 32787, 46, 0, 0, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(57, 32787, 47, 0, 0, NULL, NULL, NULL, '2018-03-29 21:17:00', 0),
(58, 32787, 48, 9, 6, NULL, NULL, NULL, '2018-04-01 15:05:16', 1),
(59, 32787, 53, 1, 1, NULL, NULL, NULL, '2018-03-29 21:17:13', 0),
(60, 9, 29, 2, 6, NULL, NULL, NULL, '2018-03-30 20:42:00', 2),
(61, 3, 1, 7, 9, NULL, NULL, NULL, '2018-03-31 13:36:54', 2),
(62, 9, 5, 5, 4, NULL, NULL, NULL, '2018-03-31 16:43:34', 1),
(63, 9, 64, 4, 2, NULL, NULL, NULL, '2018-03-31 16:43:55', 1),
(64, 12, 1, 9, 9, NULL, NULL, NULL, '2018-04-01 20:10:46', 0),
(65, 32786, 2, 1, 1, NULL, NULL, NULL, '2018-04-03 22:44:46', 0),
(66, 32786, 48, 4, 4, NULL, NULL, NULL, '2018-04-03 22:47:10', 0),
(67, 32786, 3, 4, 2, NULL, NULL, NULL, '2018-04-05 08:33:13', 1),
(68, 32786, 9, 2, 2, NULL, NULL, NULL, '2018-04-05 09:40:56', 0),
(69, 32788, 2, 8, 9, NULL, NULL, NULL, '2018-06-14 14:01:14', 2),
(70, 32788, 4, 3, 3, NULL, NULL, NULL, '2018-04-06 21:55:26', 0),
(71, 32788, 49, 1, 1, NULL, NULL, NULL, '2018-04-06 21:55:34', 0),
(72, 32788, 3, 2, 2, NULL, NULL, NULL, '2018-04-06 21:55:41', 0),
(73, 32788, 50, 3, 2, NULL, NULL, NULL, '2018-04-06 21:55:58', 1),
(74, 32788, 51, 3, 3, NULL, NULL, NULL, '2018-04-06 21:55:58', 0),
(75, 32788, 7, 8, 9, NULL, NULL, NULL, '2018-04-09 01:23:21', 2),
(76, 2, 2, 10, 10, NULL, NULL, NULL, '2018-06-14 14:00:33', 0),
(77, 32787, 57, 1, 2, NULL, NULL, NULL, '2018-06-14 15:10:21', 2),
(78, 32787, 58, 3, 4, NULL, NULL, NULL, '2018-06-14 15:10:21', 2),
(79, 1, 5, 3, 3, NULL, NULL, NULL, '2018-06-14 23:50:59', 0),
(80, 1, 6, 1, 2, NULL, NULL, NULL, '2018-06-14 23:51:12', 2),
(81, 32787, 59, 2, 4, NULL, NULL, NULL, '2018-06-15 03:07:16', 2),
(82, 32787, 60, 4, 7, NULL, NULL, NULL, '2018-06-15 03:08:08', 2),
(83, 32934, 5, 7, 7, NULL, NULL, NULL, '2018-06-14 11:19:05', 0),
(84, 32934, 6, 1, 2, NULL, NULL, NULL, '2018-06-14 11:19:25', 2),
(85, 32934, 49, 5, 6, NULL, NULL, NULL, '2018-06-14 11:19:41', 2),
(86, 32934, 7, 5, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 2),
(87, 32934, 8, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(88, 32934, 9, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(89, 32934, 10, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(90, 32934, 11, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(91, 32934, 12, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(92, 32934, 13, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(93, 32934, 14, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(94, 32934, 15, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(95, 32934, 16, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(96, 32934, 17, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(97, 32934, 18, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(98, 32934, 19, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(99, 32934, 20, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(100, 32934, 21, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(101, 32934, 22, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(102, 32934, 23, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(103, 32934, 24, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(104, 32934, 25, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(105, 32934, 26, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(106, 32934, 27, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(107, 32934, 28, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(108, 32934, 29, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(109, 32934, 30, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(110, 32934, 31, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(111, 32934, 32, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(112, 32934, 33, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(113, 32934, 34, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(114, 32934, 35, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(115, 32934, 36, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(116, 32934, 37, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(117, 32934, 38, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(118, 32934, 39, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(119, 32934, 40, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(120, 32934, 41, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(121, 32934, 42, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(122, 32934, 43, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(123, 32934, 44, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(124, 32934, 45, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(125, 32934, 46, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(126, 32934, 47, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(127, 32934, 48, 6, 6, NULL, NULL, NULL, '2018-06-14 11:23:35', 0),
(128, 11, 5, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(129, 11, 6, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(130, 11, 7, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(131, 11, 8, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(132, 11, 9, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(133, 11, 10, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(134, 11, 11, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(135, 11, 12, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(136, 11, 13, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(137, 11, 14, 4, 4, NULL, NULL, NULL, '2018-06-14 11:24:46', 0),
(138, 11, 15, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(139, 11, 16, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(140, 11, 17, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(141, 11, 18, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(142, 11, 19, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(143, 11, 20, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(144, 11, 21, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(145, 11, 22, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(146, 11, 23, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(147, 11, 24, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(148, 11, 25, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(149, 11, 26, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(150, 11, 27, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(151, 11, 28, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(152, 11, 29, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(153, 11, 30, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(154, 11, 31, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(155, 11, 32, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(156, 11, 33, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(157, 11, 34, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(158, 11, 35, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(159, 11, 36, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(160, 11, 37, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(161, 11, 38, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(162, 11, 39, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(163, 11, 40, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(164, 11, 41, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(165, 11, 42, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(166, 11, 43, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(167, 11, 44, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(168, 11, 45, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(169, 11, 46, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(170, 11, 47, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(171, 11, 48, 4, 5, NULL, NULL, NULL, '2018-06-14 11:24:46', 2),
(172, 12, 5, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(173, 12, 6, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(174, 12, 7, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(175, 12, 8, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(176, 12, 9, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(177, 12, 10, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(178, 12, 11, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(179, 12, 12, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(180, 12, 13, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(181, 12, 14, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(182, 12, 15, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(183, 12, 16, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(184, 12, 17, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(185, 12, 18, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(186, 12, 19, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(187, 12, 20, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(188, 12, 21, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(189, 12, 22, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(190, 12, 23, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(191, 12, 24, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(192, 12, 25, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(193, 12, 26, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(194, 12, 27, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(195, 12, 28, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(196, 12, 29, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(197, 12, 30, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(198, 12, 31, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(199, 12, 32, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(200, 12, 33, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(201, 12, 34, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(202, 12, 35, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(203, 12, 36, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(204, 12, 37, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(205, 12, 38, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(206, 12, 39, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(207, 12, 40, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(208, 12, 41, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(209, 12, 42, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(210, 12, 43, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(211, 12, 44, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(212, 12, 45, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(213, 12, 46, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(214, 12, 47, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(215, 12, 48, 7, 6, NULL, NULL, NULL, '2018-06-14 11:25:45', 1),
(216, 32792, 9, 6, 6, NULL, NULL, NULL, '2018-06-14 11:32:36', 0),
(217, 32788, 1, 8, 9, NULL, NULL, NULL, '2018-06-14 13:57:49', 2),
(218, 2, 5, 9, 4, NULL, NULL, NULL, '2018-06-14 14:00:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pronosticos_campeon`
--

CREATE TABLE `pronosticos_campeon` (
  `empleado_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `pronosticos_campeon`
--

INSERT INTO `pronosticos_campeon` (`empleado_id`, `equipo_id`, `wwhen`) VALUES
(2, 13, '2018-06-10 00:00:00'),
(32788, 0, '2018-06-10 00:00:00'),
(32982, 13, '2018-06-10 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pronosticos_campeon_log`
--

CREATE TABLE `pronosticos_campeon_log` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `wwhen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `pronosticos_campeon_log`
--

INSERT INTO `pronosticos_campeon_log` (`id`, `empleado_id`, `equipo_id`, `wwhen`) VALUES
(1, 32788, 0, '2018-06-10 00:00:00'),
(2, 2, 13, '2018-06-10 00:00:00'),
(3, 32982, 13, '2018-06-10 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pronosticos_goleador`
--

CREATE TABLE `pronosticos_goleador` (
  `empleado_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `pronosticos_goleador`
--

INSERT INTO `pronosticos_goleador` (`empleado_id`, `jugador_id`, `wwhen`) VALUES
(2, 3, '2018-06-10 00:00:00'),
(32788, 3, '2018-06-10 00:00:00'),
(32982, 3, '2018-06-10 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pronosticos_goleador_log`
--

CREATE TABLE `pronosticos_goleador_log` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `wwhen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `pronosticos_goleador_log`
--

INSERT INTO `pronosticos_goleador_log` (`id`, `empleado_id`, `jugador_id`, `wwhen`) VALUES
(1, 32788, 3, '2018-06-10 00:00:00'),
(2, 2, 3, '2018-06-10 00:00:00'),
(3, 32982, 3, '2018-06-10 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `puntos_condiciones`
--

CREATE TABLE `puntos_condiciones` (
  `puntos_condicion_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `puntos_condicion_resultado` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar resultado de partido',
  `puntos_condicion_marcador` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar marcador exacto de partido',
  `puntos_condicion_trivia` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'factor de multiplicación para puntos adicionales por fase',
  `puntos_condicion_campeon` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar campeón',
  `puntos_condicion_goleador` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos por acertar goleador'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `puntos_condiciones`
--

INSERT INTO `puntos_condiciones` (`puntos_condicion_id`, `fase_id`, `puntos_condicion_resultado`, `puntos_condicion_marcador`, `puntos_condicion_trivia`, `puntos_condicion_campeon`, `puntos_condicion_goleador`) VALUES
(1, 1, 20, 30, 1, 0, 0),
(2, 9, 20, 30, 1, 0, 0),
(3, 10, 20, 30, 1, 0, 0),
(4, 11, 20, 30, 1, 0, 0),
(5, 13, 20, 30, 1, 200, 200);

-- --------------------------------------------------------

--
-- Table structure for table `puntos_empleados`
--

CREATE TABLE `puntos_empleados` (
  `puntos_empleado_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `puntos_empleado_valor` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos',
  `partido_id` int(11) DEFAULT NULL COMMENT 'partido otorga puntos',
  `trivia_id` int(11) DEFAULT NULL COMMENT 'trivia otorga puntos',
  `equipo_id` int(11) DEFAULT NULL COMMENT 'campeón otorga puntos',
  `jugador_id` int(11) DEFAULT NULL COMMENT 'goleador otorga puntos'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `puntos_empleados`
--

INSERT INTO `puntos_empleados` (`puntos_empleado_id`, `empleado_id`, `puntos_empleado_valor`, `partido_id`, `trivia_id`, `equipo_id`, `jugador_id`) VALUES
(1, 32982, 20, 1, NULL, NULL, NULL),
(2, 32982, 50, 2, NULL, NULL, NULL),
(3, 32982, 50, 3, NULL, NULL, NULL),
(4, 32982, 20, 4, NULL, NULL, NULL),
(121, 2, 200, NULL, NULL, 7, NULL),
(131, 32788, 20, 5, NULL, NULL, NULL),
(171, 2, 20, 1, NULL, NULL, NULL),
(181, 32788, 20, 3, NULL, NULL, NULL),
(191, 32788, 20, 4, NULL, NULL, NULL),
(192, 2, 20, 4, NULL, NULL, NULL),
(193, 2, 50, 7, NULL, NULL, NULL),
(194, 32788, 50, 7, NULL, NULL, NULL),
(195, 2, 200, NULL, NULL, NULL, 2),
(200, 32788, 30, 8, NULL, NULL, NULL),
(201, 32788, 140, NULL, 1, NULL, NULL),
(202, 32982, 50, 56, NULL, NULL, NULL),
(203, 32982, 50, 52, NULL, NULL, NULL),
(204, 32982, 20, 53, NULL, NULL, NULL),
(205, 32982, 0, NULL, 3, NULL, NULL),
(206, 32982, 120, NULL, 2, NULL, NULL),
(208, 32982, 20, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `puntos_empleados20180411`
--

CREATE TABLE `puntos_empleados20180411` (
  `puntos_empleado_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `puntos_empleado_valor` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'puntos',
  `partido_id` int(11) DEFAULT NULL COMMENT 'partido otorga puntos',
  `trivia_id` int(11) DEFAULT NULL COMMENT 'trivia otorga puntos',
  `equipo_id` int(11) DEFAULT NULL COMMENT 'campeón otorga puntos',
  `jugador_id` int(11) DEFAULT NULL COMMENT 'goleador otorga puntos'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `puntos_empleados20180411`
--

INSERT INTO `puntos_empleados20180411` (`puntos_empleado_id`, `empleado_id`, `puntos_empleado_valor`, `partido_id`, `trivia_id`, `equipo_id`, `jugador_id`) VALUES
(1, 9, 50, 1, NULL, NULL, NULL),
(2, 2, 20, 1, NULL, NULL, NULL),
(3, 12, 20, 1, NULL, NULL, NULL),
(4, 32786, 20, 2, NULL, NULL, NULL),
(5, 32787, 20, 3, NULL, NULL, NULL),
(6, 32787, 20, 4, NULL, NULL, NULL),
(7, 32788, 20, 4, NULL, NULL, NULL),
(9, 1, 20, 5, NULL, NULL, NULL),
(10, 32934, 20, 5, NULL, NULL, NULL),
(12, 32787, 20, 7, NULL, NULL, NULL),
(13, 12, 50, 8, NULL, NULL, NULL),
(14, 32787, 50, 9, NULL, NULL, NULL),
(15, 32786, 20, 9, NULL, NULL, NULL),
(16, 32934, 50, 9, NULL, NULL, NULL),
(17, 32792, 50, 9, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_rolecode` varchar(50) CHARACTER SET utf8 NOT NULL,
  `role_rolename` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_rolecode`, `role_rolename`) VALUES
('ADMIN', 'Administrator'),
('SUPERADMIN', 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `role_rights`
--

CREATE TABLE `role_rights` (
  `rr_rolecode` varchar(50) CHARACTER SET utf8 NOT NULL,
  `rr_modulecode` varchar(25) CHARACTER SET utf8 NOT NULL,
  `rr_create` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `rr_edit` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `rr_delete` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `rr_view` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `role_rights`
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
-- Table structure for table `sedes`
--

CREATE TABLE `sedes` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stadium` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tz_offset` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `sedes`
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
-- Table structure for table `system_users`
--

CREATE TABLE `system_users` (
  `u_userid` int(11) NOT NULL,
  `u_username` varchar(100) CHARACTER SET utf8 NOT NULL,
  `u_password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `u_rolecode` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `system_users`
--

INSERT INTO `system_users` (`u_userid`, `u_username`, `u_password`, `u_rolecode`) VALUES
(1, 'admin', '123456', 'SUPERADMIN'),
(2, 'ronaldo', 'ronaldo', 'ADMIN');

-- --------------------------------------------------------

--
-- Table structure for table `trivias`
--

CREATE TABLE `trivias` (
  `id_trivia` int(11) NOT NULL,
  `inicio` datetime NOT NULL,
  `vencimiento` datetime NOT NULL,
  `id_fase` smallint(6) NOT NULL,
  `finalizada` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `trivias`
--

INSERT INTO `trivias` (`id_trivia`, `inicio`, `vencimiento`, `id_fase`, `finalizada`) VALUES
(1, '2018-06-10 12:00:00', '2018-06-27 15:00:00', 1, '0'),
(2, '2018-06-28 12:00:00', '2018-07-03 12:00:00', 9, '0'),
(3, '2018-07-04 12:00:00', '2018-07-07 12:00:00', 10, '0'),
(4, '2018-07-08 15:00:00', '2018-07-11 15:00:00', 11, '0'),
(5, '2018-07-12 11:00:00', '2018-07-15 12:00:00', 13, '0');

--
-- Triggers `trivias`
--
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
-- Table structure for table `trivias_preguntas`
--

CREATE TABLE `trivias_preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `id_trivia` int(11) NOT NULL,
  `orden` tinyint(4) NOT NULL,
  `pregunta` varchar(150) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `trivias_preguntas`
--

INSERT INTO `trivias_preguntas` (`id_pregunta`, `id_trivia`, `orden`, `pregunta`) VALUES
(1, 1, 1, '¿Cuál equipo es el actual campeón de la Copa Mundial de la FIFA?'),
(2, 1, 2, '¿Cuáles equipos organizaron en conjunto la Copa Mundial de la FIFA en 2002?'),
(3, 1, 3, '¿Quién anotó el gol de la victoria para España en la final de la Copa Mundial de la FIFA Sudáfrica 2010?'),
(4, 2, 1, '¿Cuál fue el equipo campeón de la Copa Mundial de la FIFA Suecia 1958?'),
(5, 2, 2, '¿Quién fue el máximo anotador de la Copa Mundial de la FIFA Italia 1990?'),
(6, 2, 3, '¿Cuál fue el primer equipo campeón de la Copa Mundial de la FIFA?'),
(7, 3, 1, '¿A cuál equipo venció Argentina en la tanda de penaltis para avanzar a la final de la Copa Mundial de la FIFA Italia 1990?'),
(8, 3, 2, '¿Cuál fue el último equipo de la Confederación de Oceanía en clasificarse a una Copa Mundial de la FIFA?'),
(9, 3, 3, '¿Cuáles equipos han organizado la Copa Mundial de la FIFA en más de una ocasión?'),
(10, 4, 1, 'Preguntaaaa'),
(11, 4, 2, 'Pregunta 2222'),
(12, 4, 3, 'Pregunta 3'),
(13, 5, 1, 'test last trivia'),
(14, 5, 2, 'dfdfsfsd as 654654654 65a4 564'),
(15, 5, 3, 'sdfasdf asd fas6d5f4 as6d54 f6a');

-- --------------------------------------------------------

--
-- Table structure for table `trivias_respuestas`
--

CREATE TABLE `trivias_respuestas` (
  `id_respuesta` int(11) NOT NULL,
  `id_trivia` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `orden` tinyint(4) NOT NULL,
  `respuesta` varchar(60) COLLATE latin1_spanish_ci NOT NULL,
  `respuesta_correcta` enum('0','1') COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `trivias_respuestas`
--

INSERT INTO `trivias_respuestas` (`id_respuesta`, `id_trivia`, `id_pregunta`, `orden`, `respuesta`, `respuesta_correcta`) VALUES
(1, 1, 1, 2, 'Alemania', '1'),
(2, 1, 1, 3, 'Brasil', '0'),
(3, 1, 1, 1, 'España', '0'),
(4, 1, 2, 2, 'Japón - Corea del Sur', '1'),
(5, 1, 2, 3, 'Corea del Sur - Corea del Norte', '0'),
(6, 1, 2, 1, 'China - Japón', '0'),
(7, 1, 3, 2, 'Andrés Iniesta', '1'),
(8, 1, 3, 3, 'Diego Costa', '0'),
(9, 1, 3, 1, 'Ronaldo', '0'),
(10, 2, 4, 1, 'Brasil', '1'),
(11, 2, 4, 2, 'Suecia', '0'),
(12, 2, 4, 3, 'Dinamarca', '0'),
(13, 2, 5, 1, 'Schilacci', '1'),
(14, 2, 5, 2, 'Maradona', '0'),
(15, 2, 5, 3, 'Bebeto', '0'),
(16, 2, 6, 2, 'Uruguay', '1'),
(17, 2, 6, 3, 'Italia', '0'),
(18, 2, 6, 1, 'Brasil', '0'),
(19, 3, 7, 3, 'Italia', '1'),
(20, 3, 7, 1, 'Holanda', '0'),
(21, 3, 7, 2, 'Camerún', '0'),
(22, 3, 8, 2, 'Tahití', '1'),
(23, 3, 8, 3, 'Islas Feroe', '0'),
(24, 3, 8, 1, 'Nueva Zelanda', '0'),
(25, 3, 9, 3, 'Alemania, Brasil, Italia y México', '1'),
(26, 3, 9, 1, 'Alemania, Italia, Francia y México', '0'),
(27, 3, 9, 2, 'Brasil, Italia, México y Uruguay', '0'),
(28, 4, 10, 2, '1', '1'),
(29, 4, 10, 3, '2', '0'),
(30, 4, 10, 1, '3', '0'),
(31, 4, 11, 2, '2', '1'),
(32, 4, 11, 3, '3', '0'),
(33, 4, 11, 1, '4', '0'),
(34, 4, 12, 3, '1', '1'),
(35, 4, 12, 1, '2', '0'),
(36, 4, 12, 2, '2', '0'),
(37, 5, 13, 2, '9', '1'),
(38, 5, 13, 3, '8', '0'),
(39, 5, 13, 1, '7', '0'),
(40, 5, 14, 1, '9', '1'),
(41, 5, 14, 2, '8', '0'),
(42, 5, 14, 3, '7', '0'),
(43, 5, 15, 2, '9', '1'),
(44, 5, 15, 3, '5', '0'),
(45, 5, 15, 1, '7', '0');

-- --------------------------------------------------------

--
-- Table structure for table `trivias_respuestas_empleados`
--

CREATE TABLE `trivias_respuestas_empleados` (
  `id_respuesta` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `trivias_respuestas_empleados`
--

INSERT INTO `trivias_respuestas_empleados` (`id_respuesta`, `id_empleado`, `wwhen`) VALUES
(1, 32788, '2018-06-17 04:38:14'),
(3, 32787, '2018-06-16 03:57:12'),
(4, 32787, '2018-06-16 03:57:12'),
(4, 32788, '2018-06-17 04:38:14'),
(7, 32787, '2018-06-16 03:57:12'),
(7, 32788, '2018-06-17 04:38:14'),
(10, 32982, '2018-06-30 11:50:40'),
(12, 32787, '2018-06-29 03:47:53'),
(12, 32788, '2018-06-28 03:57:49'),
(13, 32982, '2018-06-30 11:50:40'),
(14, 32788, '2018-06-28 03:57:49'),
(15, 32787, '2018-06-29 03:47:53'),
(16, 32787, '2018-06-29 03:47:53'),
(16, 32982, '2018-06-30 11:50:40'),
(18, 32788, '2018-06-28 03:57:49'),
(19, 32982, '2018-07-06 12:49:07'),
(20, 32787, '2018-07-05 03:47:52'),
(22, 32982, '2018-07-06 12:49:07'),
(24, 32787, '2018-07-05 03:47:52'),
(25, 32982, '2018-07-06 12:49:07'),
(26, 32787, '2018-07-05 03:47:52'),
(30, 32787, '2018-07-09 03:47:54'),
(32, 32787, '2018-07-09 03:47:54'),
(36, 32787, '2018-07-09 03:47:54'),
(39, 32787, '2018-07-12 03:47:52'),
(40, 32787, '2018-07-12 03:47:52'),
(44, 32787, '2018-07-12 03:47:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_log_history`
--

CREATE TABLE `user_log_history` (
  `log_history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_log_history`
--

INSERT INTO `user_log_history` (`log_history_id`, `user_id`, `login_time`) VALUES
(1, 32982, '2018-06-10 03:56:19'),
(2, 32982, '2018-06-10 03:56:49'),
(3, 2, '2018-06-10 04:20:10'),
(4, 2, '2018-06-10 04:57:26'),
(5, 2, '2018-06-10 04:57:47'),
(6, 2, '2018-06-10 04:58:27'),
(7, 2, '2018-06-15 10:54:22'),
(8, 2, '2018-06-10 04:17:38'),
(9, 2, '2018-06-10 04:21:34'),
(10, 32788, '2018-06-17 04:37:17'),
(11, 2, '2018-06-27 04:00:10'),
(12, 32982, '2018-06-27 04:00:42'),
(13, 32788, '2018-06-28 03:48:11'),
(14, 32788, '2018-06-28 03:57:19'),
(15, 32788, '2018-06-28 03:57:19'),
(16, 32788, '2018-06-28 03:57:20'),
(17, 2, '2018-06-28 04:30:45'),
(18, 2, '2018-06-28 04:35:30'),
(19, 2, '2018-06-28 04:53:33'),
(20, 32788, '2018-06-28 05:56:36'),
(21, 2, '2018-06-28 06:03:30'),
(22, 2, '2018-06-10 11:48:59'),
(23, 32982, '2018-06-10 11:52:43'),
(24, 32982, '2018-07-08 06:04:46'),
(25, 32787, '2018-07-08 06:12:01'),
(26, 2, '2018-06-10 11:51:50'),
(27, 11, '2018-06-30 11:55:35'),
(28, 32787, '2018-06-30 11:56:11'),
(29, 32788, '2018-06-16 04:06:11'),
(30, 32788, '2018-06-16 04:30:50'),
(31, 32788, '2018-05-08 03:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_prediction_log`
--

CREATE TABLE `user_prediction_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `home_prediction` int(11) NOT NULL,
  `away_prediction` int(11) NOT NULL,
  `log_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_prediction_log`
--

INSERT INTO `user_prediction_log` (`log_id`, `user_id`, `match_id`, `home_prediction`, `away_prediction`, `log_date`) VALUES
(1, 32982, 1, 2, 2, '2018-06-10 04:04:48'),
(2, 32982, 2, 6, 6, '2018-06-10 04:04:48'),
(3, 32982, 3, 4, 5, '2018-06-10 04:04:48'),
(4, 32982, 4, 5, 5, '2018-06-10 04:04:48'),
(5, 32982, 5, 8, 20, '2018-06-10 04:04:48'),
(6, 32982, 6, 4, 3, '2018-06-10 04:04:48'),
(7, 32982, 7, 9, 8, '2018-06-10 04:04:48'),
(8, 32982, 8, 6, 7, '2018-06-10 04:04:48'),
(9, 32982, 1, 2, 2, '2018-06-10 04:08:41'),
(10, 32982, 2, 6, 6, '2018-06-10 04:08:41'),
(11, 32982, 3, 4, 5, '2018-06-10 04:08:41'),
(12, 32982, 4, 5, 5, '2018-06-10 04:08:41'),
(13, 32982, 5, 8, 20, '2018-06-10 04:08:41'),
(14, 32982, 6, 4, 3, '2018-06-10 04:08:41'),
(15, 32982, 7, 9, 8, '2018-06-10 04:08:41'),
(16, 32982, 8, 6, 7, '2018-06-10 04:08:41'),
(17, 32982, 9, 1, 8, '2018-06-10 04:08:41'),
(18, 32982, 1, 2, 2, '2018-06-10 04:11:38'),
(19, 32982, 2, 6, 6, '2018-06-10 04:11:38'),
(20, 32982, 3, 4, 5, '2018-06-10 04:11:38'),
(21, 32982, 4, 5, 5, '2018-06-10 04:11:38'),
(22, 32982, 5, 8, 20, '2018-06-10 04:11:38'),
(23, 32982, 6, 4, 3, '2018-06-10 04:11:38'),
(24, 32982, 7, 9, 8, '2018-06-10 04:11:38'),
(25, 32982, 8, 6, 7, '2018-06-10 04:11:38'),
(26, 32982, 9, 1, 8, '2018-06-10 04:11:38'),
(27, 32982, 10, 0, 0, '2018-06-10 04:11:38'),
(28, 32982, 11, 3, 1, '2018-06-10 04:11:38'),
(29, 32982, 12, 1, 3, '2018-06-10 04:11:38'),
(30, 32982, 13, 10, 15, '2018-06-10 04:11:38'),
(31, 32982, 14, 5, 11, '2018-06-10 04:11:38'),
(32, 32982, 1, 2, 2, '2018-06-10 04:12:47'),
(33, 32982, 2, 6, 6, '2018-06-10 04:12:47'),
(34, 32982, 3, 4, 5, '2018-06-10 04:12:47'),
(35, 32982, 4, 5, 5, '2018-06-10 04:12:47'),
(36, 32982, 5, 8, 20, '2018-06-10 04:12:47'),
(37, 32982, 6, 4, 3, '2018-06-10 04:12:47'),
(38, 32982, 7, 9, 8, '2018-06-10 04:12:47'),
(39, 32982, 8, 6, 7, '2018-06-10 04:12:47'),
(40, 32982, 9, 1, 8, '2018-06-10 04:12:47'),
(41, 32982, 10, 0, 0, '2018-06-10 04:12:47'),
(42, 32982, 11, 3, 1, '2018-06-10 04:12:47'),
(43, 32982, 12, 1, 3, '2018-06-10 04:12:47'),
(44, 32982, 13, 10, 15, '2018-06-10 04:12:47'),
(45, 32982, 14, 5, 11, '2018-06-10 04:12:47'),
(46, 32982, 15, 16, 16, '2018-06-10 04:12:47'),
(47, 32982, 1, 2, 2, '2018-06-10 04:13:51'),
(48, 32982, 2, 6, 6, '2018-06-10 04:13:51'),
(49, 32982, 3, 4, 5, '2018-06-10 04:13:51'),
(50, 32982, 4, 5, 5, '2018-06-10 04:13:51'),
(51, 32982, 5, 8, 20, '2018-06-10 04:13:51'),
(52, 32982, 6, 4, 3, '2018-06-10 04:13:51'),
(53, 32982, 7, 9, 8, '2018-06-10 04:13:51'),
(54, 32982, 8, 6, 7, '2018-06-10 04:13:51'),
(55, 32982, 9, 1, 8, '2018-06-10 04:13:51'),
(56, 32982, 10, 0, 0, '2018-06-10 04:13:51'),
(57, 32982, 11, 3, 1, '2018-06-10 04:13:51'),
(58, 32982, 12, 1, 3, '2018-06-10 04:13:51'),
(59, 32982, 13, 10, 15, '2018-06-10 04:13:51'),
(60, 32982, 14, 5, 11, '2018-06-10 04:13:51'),
(61, 32982, 15, 16, 16, '2018-06-10 04:13:51'),
(62, 32982, 16, 0, 0, '2018-06-10 04:13:51'),
(63, 32982, 17, 14, 10, '2018-06-10 04:13:51'),
(64, 32982, 18, 8, 3, '2018-06-10 04:13:51'),
(65, 32982, 19, 12, 13, '2018-06-10 04:13:51'),
(66, 32982, 20, 6, 13, '2018-06-10 04:13:51'),
(67, 32982, 21, 10, 10, '2018-06-10 04:13:51'),
(68, 32982, 22, 0, 17, '2018-06-10 04:13:51'),
(69, 32982, 23, 17, 10, '2018-06-10 04:13:51'),
(70, 32982, 24, 15, 14, '2018-06-10 04:13:51'),
(71, 32982, 25, 2, 12, '2018-06-10 04:13:51'),
(72, 32982, 26, 12, 14, '2018-06-10 04:13:51'),
(73, 32982, 27, 16, 16, '2018-06-10 04:13:51'),
(74, 32982, 28, 0, 0, '2018-06-10 04:13:51'),
(75, 32982, 1, 2, 2, '2018-06-10 04:15:42'),
(76, 32982, 2, 6, 6, '2018-06-10 04:15:42'),
(77, 32982, 3, 4, 5, '2018-06-10 04:15:42'),
(78, 32982, 4, 5, 5, '2018-06-10 04:15:42'),
(79, 32982, 5, 8, 20, '2018-06-10 04:15:42'),
(80, 32982, 6, 4, 3, '2018-06-10 04:15:42'),
(81, 32982, 7, 9, 8, '2018-06-10 04:15:42'),
(82, 32982, 8, 6, 7, '2018-06-10 04:15:42'),
(83, 32982, 9, 1, 8, '2018-06-10 04:15:42'),
(84, 32982, 10, 0, 0, '2018-06-10 04:15:42'),
(85, 32982, 11, 3, 1, '2018-06-10 04:15:42'),
(86, 32982, 12, 1, 3, '2018-06-10 04:15:42'),
(87, 32982, 13, 10, 15, '2018-06-10 04:15:42'),
(88, 32982, 14, 5, 11, '2018-06-10 04:15:42'),
(89, 32982, 15, 16, 16, '2018-06-10 04:15:42'),
(90, 32982, 16, 0, 0, '2018-06-10 04:15:42'),
(91, 32982, 17, 14, 10, '2018-06-10 04:15:42'),
(92, 32982, 18, 8, 3, '2018-06-10 04:15:42'),
(93, 32982, 19, 12, 13, '2018-06-10 04:15:42'),
(94, 32982, 20, 6, 13, '2018-06-10 04:15:42'),
(95, 32982, 21, 10, 10, '2018-06-10 04:15:42'),
(96, 32982, 22, 0, 17, '2018-06-10 04:15:42'),
(97, 32982, 23, 17, 10, '2018-06-10 04:15:42'),
(98, 32982, 24, 15, 14, '2018-06-10 04:15:42'),
(99, 32982, 25, 2, 12, '2018-06-10 04:15:42'),
(100, 32982, 26, 12, 14, '2018-06-10 04:15:42'),
(101, 32982, 27, 16, 16, '2018-06-10 04:15:42'),
(102, 32982, 28, 0, 0, '2018-06-10 04:15:42'),
(103, 32982, 29, 3, 15, '2018-06-10 04:15:42'),
(104, 32982, 30, 9, 12, '2018-06-10 04:15:42'),
(105, 32982, 31, 6, 7, '2018-06-10 04:15:42'),
(106, 32982, 32, 2, 6, '2018-06-10 04:15:42'),
(107, 32982, 33, 0, 0, '2018-06-10 04:15:42'),
(108, 32982, 34, 13, 6, '2018-06-10 04:15:42'),
(109, 32982, 35, 5, 3, '2018-06-10 04:15:42'),
(110, 32982, 36, 2, 5, '2018-06-10 04:15:42'),
(111, 32982, 37, 2, 4, '2018-06-10 04:15:42'),
(112, 32982, 38, 1, 5, '2018-06-10 04:15:42'),
(113, 32982, 39, 3, 3, '2018-06-10 04:15:42'),
(114, 32982, 40, 6, 0, '2018-06-10 04:15:42'),
(115, 32982, 41, 0, 0, '2018-06-10 04:15:42'),
(116, 32982, 42, 0, 0, '2018-06-10 04:15:42'),
(117, 32982, 43, 14, 4, '2018-06-10 04:15:42'),
(118, 32982, 44, 13, 13, '2018-06-10 04:15:42'),
(119, 32982, 45, 17, 17, '2018-06-10 04:15:42'),
(120, 32982, 46, 0, 0, '2018-06-10 04:15:42'),
(121, 32982, 47, 17, 16, '2018-06-10 04:15:42'),
(122, 32982, 48, 5, 10, '2018-06-10 04:15:42'),
(123, 32982, 1, 2, 2, '2018-06-10 04:16:29'),
(124, 32982, 2, 6, 6, '2018-06-10 04:16:29'),
(125, 32982, 3, 4, 5, '2018-06-10 04:16:29'),
(126, 32982, 4, 5, 5, '2018-06-10 04:16:29'),
(127, 32982, 5, 8, 20, '2018-06-10 04:16:29'),
(128, 32982, 6, 4, 3, '2018-06-10 04:16:29'),
(129, 32982, 7, 9, 8, '2018-06-10 04:16:29'),
(130, 32982, 8, 6, 7, '2018-06-10 04:16:29'),
(131, 32982, 9, 1, 8, '2018-06-10 04:16:29'),
(132, 32982, 10, 0, 0, '2018-06-10 04:16:29'),
(133, 32982, 11, 3, 1, '2018-06-10 04:16:29'),
(134, 32982, 12, 1, 3, '2018-06-10 04:16:29'),
(135, 32982, 13, 10, 15, '2018-06-10 04:16:29'),
(136, 32982, 14, 5, 11, '2018-06-10 04:16:29'),
(137, 32982, 15, 16, 16, '2018-06-10 04:16:29'),
(138, 32982, 16, 0, 0, '2018-06-10 04:16:29'),
(139, 32982, 17, 14, 10, '2018-06-10 04:16:29'),
(140, 32982, 18, 8, 3, '2018-06-10 04:16:29'),
(141, 32982, 19, 12, 13, '2018-06-10 04:16:29'),
(142, 32982, 20, 6, 13, '2018-06-10 04:16:29'),
(143, 32982, 21, 10, 10, '2018-06-10 04:16:29'),
(144, 32982, 22, 0, 17, '2018-06-10 04:16:29'),
(145, 32982, 23, 17, 10, '2018-06-10 04:16:29'),
(146, 32982, 24, 15, 14, '2018-06-10 04:16:29'),
(147, 32982, 25, 2, 12, '2018-06-10 04:16:29'),
(148, 32982, 26, 12, 14, '2018-06-10 04:16:29'),
(149, 32982, 27, 16, 16, '2018-06-10 04:16:29'),
(150, 32982, 28, 0, 0, '2018-06-10 04:16:29'),
(151, 32982, 29, 3, 15, '2018-06-10 04:16:29'),
(152, 32982, 30, 9, 12, '2018-06-10 04:16:29'),
(153, 32982, 31, 6, 7, '2018-06-10 04:16:29'),
(154, 32982, 32, 2, 6, '2018-06-10 04:16:29'),
(155, 32982, 33, 0, 0, '2018-06-10 04:16:29'),
(156, 32982, 34, 13, 6, '2018-06-10 04:16:29'),
(157, 32982, 35, 5, 3, '2018-06-10 04:16:29'),
(158, 32982, 36, 2, 5, '2018-06-10 04:16:29'),
(159, 32982, 37, 2, 4, '2018-06-10 04:16:29'),
(160, 32982, 38, 1, 5, '2018-06-10 04:16:29'),
(161, 32982, 39, 3, 3, '2018-06-10 04:16:29'),
(162, 32982, 40, 6, 0, '2018-06-10 04:16:30'),
(163, 32982, 41, 0, 0, '2018-06-10 04:16:30'),
(164, 32982, 42, 0, 0, '2018-06-10 04:16:30'),
(165, 32982, 43, 14, 4, '2018-06-10 04:16:30'),
(166, 32982, 44, 13, 13, '2018-06-10 04:16:30'),
(167, 32982, 45, 17, 17, '2018-06-10 04:16:30'),
(168, 32982, 46, 0, 0, '2018-06-10 04:16:30'),
(169, 32982, 47, 17, 16, '2018-06-10 04:16:30'),
(170, 32982, 48, 5, 10, '2018-06-10 04:16:30'),
(171, 32982, 1, 2, 2, '2018-06-10 04:16:43'),
(172, 32982, 2, 6, 6, '2018-06-10 04:16:43'),
(173, 32982, 3, 4, 5, '2018-06-10 04:16:43'),
(174, 32982, 4, 5, 5, '2018-06-10 04:16:43'),
(175, 32982, 5, 8, 20, '2018-06-10 04:16:43'),
(176, 32982, 6, 4, 3, '2018-06-10 04:16:43'),
(177, 32982, 7, 9, 8, '2018-06-10 04:16:43'),
(178, 32982, 8, 6, 7, '2018-06-10 04:16:43'),
(179, 32982, 9, 1, 8, '2018-06-10 04:16:43'),
(180, 32982, 10, 0, 0, '2018-06-10 04:16:43'),
(181, 32982, 11, 3, 1, '2018-06-10 04:16:43'),
(182, 32982, 12, 1, 3, '2018-06-10 04:16:43'),
(183, 32982, 13, 10, 15, '2018-06-10 04:16:43'),
(184, 32982, 14, 5, 11, '2018-06-10 04:16:43'),
(185, 32982, 15, 16, 16, '2018-06-10 04:16:43'),
(186, 32982, 16, 0, 0, '2018-06-10 04:16:43'),
(187, 32982, 17, 14, 10, '2018-06-10 04:16:43'),
(188, 32982, 18, 8, 3, '2018-06-10 04:16:43'),
(189, 32982, 19, 12, 13, '2018-06-10 04:16:43'),
(190, 32982, 20, 6, 13, '2018-06-10 04:16:43'),
(191, 32982, 21, 10, 10, '2018-06-10 04:16:43'),
(192, 32982, 22, 0, 17, '2018-06-10 04:16:43'),
(193, 32982, 23, 17, 10, '2018-06-10 04:16:43'),
(194, 32982, 24, 15, 14, '2018-06-10 04:16:43'),
(195, 32982, 25, 2, 12, '2018-06-10 04:16:43'),
(196, 32982, 26, 12, 14, '2018-06-10 04:16:43'),
(197, 32982, 27, 16, 16, '2018-06-10 04:16:43'),
(198, 32982, 28, 0, 0, '2018-06-10 04:16:43'),
(199, 32982, 29, 3, 15, '2018-06-10 04:16:43'),
(200, 32982, 30, 9, 12, '2018-06-10 04:16:43'),
(201, 32982, 31, 6, 7, '2018-06-10 04:16:43'),
(202, 32982, 32, 2, 6, '2018-06-10 04:16:43'),
(203, 32982, 33, 0, 0, '2018-06-10 04:16:43'),
(204, 32982, 34, 13, 6, '2018-06-10 04:16:43'),
(205, 32982, 35, 5, 3, '2018-06-10 04:16:43'),
(206, 32982, 36, 2, 5, '2018-06-10 04:16:43'),
(207, 32982, 37, 2, 4, '2018-06-10 04:16:43'),
(208, 32982, 38, 1, 5, '2018-06-10 04:16:43'),
(209, 32982, 39, 3, 3, '2018-06-10 04:16:43'),
(210, 32982, 40, 6, 0, '2018-06-10 04:16:43'),
(211, 32982, 41, 0, 0, '2018-06-10 04:16:43'),
(212, 32982, 42, 0, 0, '2018-06-10 04:16:43'),
(213, 32982, 43, 14, 4, '2018-06-10 04:16:43'),
(214, 32982, 44, 13, 13, '2018-06-10 04:16:43'),
(215, 32982, 45, 17, 17, '2018-06-10 04:16:43'),
(216, 32982, 46, 0, 0, '2018-06-10 04:16:43'),
(217, 32982, 47, 17, 16, '2018-06-10 04:16:43'),
(218, 32982, 48, 5, 10, '2018-06-10 04:16:43'),
(219, 32982, 52, 8, 6, '2018-06-30 12:03:02'),
(220, 32982, 53, 10, 7, '2018-06-30 12:03:02'),
(221, 32982, 54, 11, 2, '2018-06-30 12:03:02'),
(222, 32982, 55, 10, 14, '2018-06-30 12:03:02'),
(223, 32982, 56, 0, 0, '2018-06-30 12:03:02'),
(224, 32787, 63, 0, 0, '2018-07-11 03:49:07'),
(225, 32787, 61, 0, 0, '2018-07-08 03:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_trivia_log`
--

CREATE TABLE `user_trivia_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_trivia` int(11) NOT NULL,
  `wwhen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_trivia_log`
--

INSERT INTO `user_trivia_log` (`log_id`, `user_id`, `id_trivia`, `wwhen`) VALUES
(1, 32788, 1, '2018-06-17 04:38:14'),
(2, 32788, 2, '2018-06-28 03:57:49'),
(3, 32982, 2, '2018-06-30 11:50:40'),
(4, 32982, 3, '2018-07-06 12:49:07'),
(5, 32787, 1, '2018-06-16 03:57:12'),
(6, 32787, 2, '2018-06-29 03:47:53'),
(7, 32787, 3, '2018-07-05 03:47:52'),
(8, 32787, 4, '2018-07-09 03:47:54'),
(9, 32787, 5, '2018-07-12 03:47:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `empresa` (`empresa`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Indexes for table `empresas_imagenes`
--
ALTER TABLE `empresas_imagenes`
  ADD PRIMARY KEY (`id_empresa`,`tipo_imagen`);

--
-- Indexes for table `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `equipo_grupo`
--
ALTER TABLE `equipo_grupo`
  ADD PRIMARY KEY (`equipo_grupo_id`);

--
-- Indexes for table `fases`
--
ALTER TABLE `fases`
  ADD PRIMARY KEY (`stage_id`);

--
-- Indexes for table `gropus`
--
ALTER TABLE `gropus`
  ADD PRIMARY KEY (`grupo_id`);

--
-- Indexes for table `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id_jugador`);

--
-- Indexes for table `mecanica_juego`
--
ALTER TABLE `mecanica_juego`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`mod_modulegroupcode`,`mod_modulecode`),
  ADD UNIQUE KEY `mod_modulecode` (`mod_modulecode`);

--
-- Indexes for table `partidos`
--
ALTER TABLE `partidos`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `pronosticos`
--
ALTER TABLE `pronosticos`
  ADD PRIMARY KEY (`prediction_id`),
  ADD UNIQUE KEY `idx_pred_um` (`user_id`,`match_id`);

--
-- Indexes for table `pronosticos20180411`
--
ALTER TABLE `pronosticos20180411`
  ADD PRIMARY KEY (`prediction_id`),
  ADD UNIQUE KEY `idx_pred_um` (`user_id`,`match_id`);

--
-- Indexes for table `pronosticos_campeon`
--
ALTER TABLE `pronosticos_campeon`
  ADD PRIMARY KEY (`empleado_id`,`equipo_id`);

--
-- Indexes for table `pronosticos_campeon_log`
--
ALTER TABLE `pronosticos_campeon_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pronosticos_goleador`
--
ALTER TABLE `pronosticos_goleador`
  ADD PRIMARY KEY (`empleado_id`,`jugador_id`);

--
-- Indexes for table `pronosticos_goleador_log`
--
ALTER TABLE `pronosticos_goleador_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `puntos_condiciones`
--
ALTER TABLE `puntos_condiciones`
  ADD PRIMARY KEY (`puntos_condicion_id`);

--
-- Indexes for table `puntos_empleados`
--
ALTER TABLE `puntos_empleados`
  ADD PRIMARY KEY (`puntos_empleado_id`);

--
-- Indexes for table `puntos_empleados20180411`
--
ALTER TABLE `puntos_empleados20180411`
  ADD PRIMARY KEY (`puntos_empleado_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_rolecode`);

--
-- Indexes for table `role_rights`
--
ALTER TABLE `role_rights`
  ADD PRIMARY KEY (`rr_rolecode`,`rr_modulecode`),
  ADD KEY `rr_modulecode` (`rr_modulecode`);

--
-- Indexes for table `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`venue_id`);

--
-- Indexes for table `system_users`
--
ALTER TABLE `system_users`
  ADD PRIMARY KEY (`u_userid`),
  ADD KEY `u_rolecode` (`u_rolecode`);

--
-- Indexes for table `trivias`
--
ALTER TABLE `trivias`
  ADD PRIMARY KEY (`id_trivia`);

--
-- Indexes for table `trivias_preguntas`
--
ALTER TABLE `trivias_preguntas`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indexes for table `trivias_respuestas`
--
ALTER TABLE `trivias_respuestas`
  ADD PRIMARY KEY (`id_respuesta`);

--
-- Indexes for table `trivias_respuestas_empleados`
--
ALTER TABLE `trivias_respuestas_empleados`
  ADD PRIMARY KEY (`id_respuesta`,`id_empleado`);

--
-- Indexes for table `user_log_history`
--
ALTER TABLE `user_log_history`
  ADD PRIMARY KEY (`log_history_id`);

--
-- Indexes for table `user_prediction_log`
--
ALTER TABLE `user_prediction_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_trivia_log`
--
ALTER TABLE `user_trivia_log`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32983;

--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `team_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `equipo_grupo`
--
ALTER TABLE `equipo_grupo`
  MODIFY `equipo_grupo_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fases`
--
ALTER TABLE `fases`
  MODIFY `stage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gropus`
--
ALTER TABLE `gropus`
  MODIFY `grupo_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mecanica_juego`
--
ALTER TABLE `mecanica_juego`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `partidos`
--
ALTER TABLE `partidos`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `pronosticos`
--
ALTER TABLE `pronosticos`
  MODIFY `prediction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `pronosticos20180411`
--
ALTER TABLE `pronosticos20180411`
  MODIFY `prediction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `pronosticos_campeon_log`
--
ALTER TABLE `pronosticos_campeon_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pronosticos_goleador_log`
--
ALTER TABLE `pronosticos_goleador_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `puntos_condiciones`
--
ALTER TABLE `puntos_condiciones`
  MODIFY `puntos_condicion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `puntos_empleados`
--
ALTER TABLE `puntos_empleados`
  MODIFY `puntos_empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `puntos_empleados20180411`
--
ALTER TABLE `puntos_empleados20180411`
  MODIFY `puntos_empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sedes`
--
ALTER TABLE `sedes`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `system_users`
--
ALTER TABLE `system_users`
  MODIFY `u_userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trivias`
--
ALTER TABLE `trivias`
  MODIFY `id_trivia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trivias_preguntas`
--
ALTER TABLE `trivias_preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `trivias_respuestas`
--
ALTER TABLE `trivias_respuestas`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `user_log_history`
--
ALTER TABLE `user_log_history`
  MODIFY `log_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user_prediction_log`
--
ALTER TABLE `user_prediction_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `user_trivia_log`
--
ALTER TABLE `user_trivia_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_rights`
--
ALTER TABLE `role_rights`
  ADD CONSTRAINT `role_rights_ibfk_1` FOREIGN KEY (`rr_rolecode`) REFERENCES `role` (`role_rolecode`) ON UPDATE CASCADE,
  ADD CONSTRAINT `role_rights_ibfk_2` FOREIGN KEY (`rr_modulecode`) REFERENCES `module` (`mod_modulecode`) ON UPDATE CASCADE;

--
-- Constraints for table `system_users`
--
ALTER TABLE `system_users`
  ADD CONSTRAINT `system_users_ibfk_1` FOREIGN KEY (`u_rolecode`) REFERENCES `role` (`role_rolecode`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
