-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2018 at 12:31 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `todosalacancha`
--

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE IF NOT EXISTS `empleados` (
  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `imagen_perfil` varchar(255) DEFAULT NULL,
  `state` enum('0','1') DEFAULT '0',
  `acepto_bases` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id_empleado`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `id_empresa`, `user`, `pass`, `nombre`, `apellido`, `imagen_perfil`, `state`, `acepto_bases`) VALUES
(3, 23, 'csegovia', '827ccb0eea8a706c4c34a16891f84e7b', 'Carlos', 'Segovia', 'R_p.png', '1', '0'),
(8, 23, 'carlos ', 'aaceafb234f14b6b65a98fecbf426780', 'carlos2', 'carlos3', '20160918-saben-ustedes-que-durante-una-tormenta-el-leon-da-la-cara-al-viento-para-que-su-pelambre-no-se-leopoldo-marechal-192470.jpg', '0', '0'),
(10, 23, 'pepe', '926e27eecdbc7a18858b3798ba99bddd', 'pepito', 'pepiton', NULL, '0', '0'),
(15, 23, 'robert', '9ce98b66ba3847c5a67a55b76cdd4294', 'roberto', 'murer', NULL, '0', '0'),
(27, 27, 'popo', '1a1465daaa67981c180e96f80b79a2ef', 'tgh', 'dsgdsf', '', '1', '0'),
(46, 33, 'rasputin2', '7815696ecbf1c96e6894b779456d330e', 'Super', 'Pedo', '20161113_Bebe_No_Estare.jpg', '1', '0'),
(47, 26, 'asdf', '912ec803b2ce49e4a541068d495ab570', 'asdf', 'asdf', '', '1', '0'),
(48, 38, 'asf', '0aa1ea9a5a04b78d4581dd6d17742627', 'asdf', 'asdas', '', '1', '0'),
(49, 24, 'csegovi', 'fe6d1fed11fa60277fb6a2f73efb8be2', 'ljlk', 'asdfsd', '', '1', '0'),
(50, 33, 'pepes', '3691308f2a4c2f6983f2880d32e29c84', 'sdf', 'safd', '', '1', '0'),
(51, 24, 'gqadfe', 'c519671596cbd25461fa9ae7c229f034', 'asdfsad', 'asdfasdf', '', '1', '0'),
(52, 32, 'poronguito', '3a097bd1be7c2585e231e3d91a26035d', 'asdfsad', 'asdfsadf', '', '1', '0'),
(53, 33, 'azul', 'c519671596cbd25461fa9ae7c229f034', 'Azul', 'Marino', '', '1', '0'),
(54, 23, 'asdfasdfasdfasdf', 'c519671596cbd25461fa9ae7c229f034', 'sadfas', 'asdfsadf', '', '1', '0'),
(55, 31, 'asgggggg', '387d5b288a7c68f1f022e6563a0310a2', 'safasdf', 'asdfasdf', '', '1', '0'),
(56, 23, 'pepesssss', 'f31a81e91afdcf0b84dfee82ec2fb196', 'lkj', 'kjl', '', '1', '0'),
(60, 0, 'test1', 'ad0234829205b9033196ba818f7a872b', 'test3', 'test4', NULL, '1', '0'),
(81, 0, 'jessi', 'dc3f44fcc1f2b475bf18ee6d6c2c4d5c', 'jessi', 'jessi', NULL, '1', '0'),
(82, 0, 'Copyright (c) 2007-2013 Diego Plentz (plentz.org)', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(83, 0, '', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(84, 0, 'Permission is hereby granted, free of charge, to any person', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(85, 0, 'obtaining a copy of this software and associated documentation', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(86, 0, '-- phpMyAdmin SQL Dump', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(87, 0, '-- version 4.1.14', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(88, 0, '-- http://www.phpmyadmin.net', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(89, 0, '--', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(90, 0, '-- Host: 127.0.0.1', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(91, 0, '-- Generation Time: Feb 13, 2018 at 03:23 PM', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(92, 0, '-- Server version: 5.6.17', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(93, 0, '-- PHP Version: 5.5.12', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(94, 0, '<style>', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(95, 0, 'html {', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(96, 0, '    background-color: #f6f6f6', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(97, 0, '    margin: 16px', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(98, 0, '}', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(99, 0, 'body {', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0'),
(100, 0, '    color: #333', 'd41d8cd98f00b204e9800998ecf8427e', '', '', NULL, '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `empresas`
--

CREATE TABLE IF NOT EXISTS `empresas` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `bases_condiciones` text,
  `url` varchar(100) NOT NULL,
  `is_trivia` int(40) DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  UNIQUE KEY `empresa` (`empresa`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `empresa`, `logo`, `descripcion`, `bases_condiciones`, `url`, `is_trivia`) VALUES
(23, 'empresa editada', 'google.png', '1234', '', '123456', 1),
(24, 'siemens 2', 'siemens.png', 'empresa siemens 2', NULL, 'siemensurl', NULL),
(26, 'google', 'google.png', 'google 2 desc', NULL, 'google2url', NULL),
(27, 'testconbases', 'google.png', 'Descripción de la empresa.... Premios que va a entregar etc.', 'Hola\r\nEstas son las bases y condiciones\r\nDe la empresa\r\nGoogle\r\nPfffff', 'testbases', NULL),
(28, 'NombreEmpresa', 'siemens.png', 'Desc de la empresa', 'bla bla bla!', 'URLPoronga', NULL),
(32, 'NombreEmpresa3', '', 'desc3', 'BASES3', 'URL3', NULL),
(33, 'asdf', '20170327_E.M.Forster.jpg', 'asf', 'sadf', 'asdf', NULL),
(31, 'sdfgsdf', '20161013-Jung-teorias.jpg', 'sdfgsdfg', 'fsdfas', 'asdfas', NULL),
(34, 'Toyota', '', '', '', '', NULL),
(35, 'Nestle', '', '', '', '', NULL),
(36, 'Nextel', '', '', '', '', NULL),
(37, 'sdfg', '20170327_E.M.Forster.jpg', 'sadf', '', 'asdf', NULL),
(38, 'Sancor', '20160815-frase-todos_somos_mortales_hasta_el_primer_beso_y_el_segundo_vaso_-eduardo_galeano.jpg', 'asdfas', 'sadfs', 'asdf', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipos`
--

CREATE TABLE IF NOT EXISTS `equipos` (
  `team_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_order` int(11) NOT NULL,
  `campeon` enum('0','1') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`team_id`),
  UNIQUE KEY `campeon` (`campeon`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=65 ;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`team_id`, `name`, `country`, `team_url`, `group_order`, `campeon`, `wwhen`) VALUES
(1, 'Rusia', 'RUS', 'http://es.fifa.com/worldcup/teams/team=43965/index.html', 1, NULL, '2018-02-08 16:14:59'),
(2, 'Arabia Saudita', 'KSA', 'http://es.fifa.com/worldcup/teams/team=43835/_index_default.html', 1, NULL, '2018-02-08 16:13:19'),
(3, 'Egipto', 'EGY', 'http://es.fifa.com/worldcup/teams/team=43855/index.html', 1, NULL, '2018-01-18 06:00:00'),
(4, 'Uruguay', 'URU', 'http://es.fifa.com/worldcup/teams/team=43930/index.html', 1, NULL, '2018-01-18 06:00:00'),
(5, 'Portugal', 'POR', 'http://es.fifa.com/worldcup/teams/team=43963/index.html', 2, NULL, '2018-01-18 06:00:00'),
(6, 'España', 'ESP', 'http://es.fifa.com/worldcup/teams/team=43969/index.html', 2, NULL, '2018-02-08 16:15:06'),
(7, 'Marruecos', 'MAR', 'http://es.fifa.com/worldcup/teams/team=43872/index.html', 2, NULL, '2018-01-31 17:19:55'),
(8, 'Iran', 'IRN', 'http://es.fifa.com/worldcup/teams/team=43817/index.html', 2, NULL, '2018-02-08 16:13:21'),
(9, 'Francia', 'FRA', 'http://es.fifa.com/worldcup/teams/team=43946/index.html', 3, NULL, '2018-01-18 06:00:00'),
(10, 'Australia', 'AUS', 'http://es.fifa.com/worldcup/teams/team=43976/index.html', 3, NULL, '2018-01-18 06:00:00'),
(11, 'Perú', 'PER', 'http://http://es.fifa.com/worldcup/teams/team=43929/index.html', 3, NULL, '2018-01-18 06:00:00'),
(12, 'Dinamarca', 'DEN', 'http://es.fifa.com/worldcup/teams/team=43941/index.html', 3, NULL, '2018-01-18 06:00:00'),
(13, 'Argentina', 'ARG', 'http://es.fifa.com/worldcup/teams/team=43922/index.html', 4, NULL, '2018-02-08 13:01:42'),
(14, 'Islandia', 'ISL', 'http://es.fifa.com/worldcup/teams/team=43951/index.html', 4, NULL, '2018-01-18 06:00:00'),
(15, 'Croacia', 'CRO', 'http://es.fifa.com/worldcup/teams/team=43938/index.html', 4, NULL, '2018-01-18 06:00:00'),
(16, 'Nigeria', 'NEG', 'http://es.fifa.com/worldcup/teams/team=43876/index.html', 4, NULL, '2018-01-18 06:00:00'),
(17, 'Brasil', 'BRA', 'http://es.fifa.com/worldcup/teams/team=43924/index.html', 5, NULL, '2018-01-18 06:00:00'),
(18, 'Suiza', 'SUI', 'http://es.fifa.com/worldcup/teams/team=43971/index.html', 5, NULL, '2018-01-18 06:00:00'),
(19, 'Costa Rica', 'CRC', 'http://es.fifa.com/worldcup/teams/team=43901/index.html', 5, NULL, '2018-01-18 06:00:00'),
(20, 'Serbia', 'SRB', 'http://es.fifa.com/worldcup/teams/team=1902465/index.html', 5, NULL, '2018-01-18 06:00:00'),
(21, 'Alemania', 'ALE', 'http://es.fifa.com/worldcup/teams/team=43948/index.html', 6, NULL, '2018-02-08 13:11:28'),
(22, 'Mexico', 'MEX', 'http://es.fifa.com/worldcup/teams/team=43911/index.html', 6, NULL, '2018-01-18 06:00:00'),
(23, 'Suecia', 'SWE', 'http://es.fifa.com/worldcup/teams/team=43970/index.html', 6, NULL, '2018-01-18 06:00:00'),
(24, 'República de Corea', 'KOR', 'http://es.fifa.com/worldcup/teams/team=43822/index.html', 6, NULL, '2018-01-18 06:00:00'),
(25, 'Bélgica', 'BEL', 'http://es.fifa.com/worldcup/teams/team=43935/index.html', 7, NULL, '2018-01-18 06:00:00'),
(26, 'Panama', 'PAN', 'http://es.fifa.com/worldcup/teams/team=43914/index.html', 7, NULL, '2018-01-18 06:00:00'),
(27, 'Túnez', 'TUN', 'http://es.fifa.com/worldcup/teams/team=43888/index.html', 7, NULL, '2018-01-18 06:00:00'),
(28, 'Inglaterra', 'ENG', 'http://es.fifa.com/worldcup/teams/team=43942/index.html', 7, NULL, '2018-01-18 06:00:00'),
(29, 'Polonia', 'POL', 'http://es.fifa.com/worldcup/teams/team=43962/index.html', 8, NULL, '2018-02-08 13:11:24'),
(30, 'Senegal', 'SEN', 'http://es.fifa.com/worldcup/teams/team=43879/index.html', 8, NULL, '2018-01-18 06:00:00'),
(31, 'Colombia', 'COL', 'http://es.fifa.com/worldcup/teams/team=43926/index.html', 8, NULL, '2018-01-18 06:00:00'),
(32, 'Japón', 'JAP', 'http://es.fifa.com/worldcup/teams/team=43819/index.html', 8, NULL, '2018-02-13 03:09:23'),
(33, 'Ganador Grupo A', 'xxx', '', 0, NULL, '2014-01-17 08:20:59'),
(34, 'Ganador Grupo B', 'xxx', '', 0, NULL, '2014-01-17 08:31:37'),
(35, 'Ganador Grupo C', 'xxx', '', 0, NULL, '2014-01-17 08:31:53'),
(36, 'Ganador Grupo D', 'xxx', '', 0, NULL, '2014-01-17 08:32:11'),
(37, 'Ganador Grupo E', 'xxx', '', 0, NULL, '2014-01-17 08:32:25'),
(38, 'Ganador Grupo F', 'xxx', '', 0, NULL, '2014-01-17 08:32:49'),
(39, 'Ganador Grupo G', 'xxx', '', 0, NULL, '2014-01-17 08:33:06'),
(40, 'Ganador Grupo H', 'xxx', '', 0, NULL, '2014-01-17 08:33:23'),
(41, 'Segundo de Grupo A', 'xxx', '', 0, NULL, '2014-01-17 08:33:47'),
(42, 'Segundo de Grupo B', 'xxx', '', 0, NULL, '2014-01-17 08:34:03'),
(43, 'Segundo de Grupo C', 'xxx', '', 0, NULL, '2014-01-17 08:23:36'),
(44, 'Segundo de Grupo D', 'xxx', '', 0, NULL, '2014-01-17 08:23:49'),
(45, 'Segundo de Grupo E', 'xxx', '', 0, NULL, '2014-01-17 08:24:00'),
(46, 'Segundo de Grupo F', 'xxx', '', 0, NULL, '2014-01-17 08:24:14'),
(47, 'Segundo de Grupo G', 'xxx', '', 0, NULL, '2014-01-17 08:24:29'),
(48, 'Segundo de Grupo H', 'xxx', '', 0, NULL, '2014-01-17 08:24:42'),
(49, 'Ganador Partido 49', 'xxx', '', 0, NULL, '2014-01-17 08:24:58'),
(50, 'Ganador Partido 50', 'xxx', '', 0, NULL, '2014-01-17 08:25:09'),
(51, 'Ganador Partido 51', 'xxx', '', 0, NULL, '2014-01-17 08:25:21'),
(52, 'Ganador Partido 52', 'xxx', '', 0, NULL, '2014-01-17 08:25:38'),
(53, 'Ganador Partido 53', 'xxx', '', 0, NULL, '2014-01-17 08:25:49'),
(54, 'Ganador Partido 54', 'xxx', '', 0, NULL, '2014-01-17 08:26:07'),
(55, 'Ganador Partido 55', 'xxx', '', 0, NULL, '2014-01-17 08:26:27'),
(56, 'Ganador Partido 56', 'xxx', '', 0, NULL, '2014-01-17 08:26:36'),
(57, 'Ganador Partido 57', 'xxx', '', 0, NULL, '2014-01-17 08:26:48'),
(58, 'Ganador Partido 58', 'xxx', '', 0, NULL, '2014-01-17 08:26:58'),
(59, 'Ganador Partido 59', 'xxx', '', 0, NULL, '2014-01-17 08:27:09'),
(60, 'Ganador Partido 60', 'xxx', '', 0, NULL, '2014-01-17 08:27:19'),
(61, 'Perdedor Partido 61', 'xxx', '', 0, NULL, '2014-01-17 10:09:37'),
(62, 'Perdedor Partido 62', 'xxx', '', 0, NULL, '2014-01-17 10:09:49'),
(63, 'Ganador Partido 61', 'xxx', '', 0, NULL, '2014-01-17 10:10:15'),
(64, 'Ganador Partido 62', 'xxx', '', 0, NULL, '2014-01-17 10:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `equipo_grupo`
--

CREATE TABLE IF NOT EXISTS `equipo_grupo` (
  `equipo_grupo_id` int(20) NOT NULL AUTO_INCREMENT,
  `team_id` int(20) NOT NULL,
  `grupo_id` int(20) NOT NULL,
  PRIMARY KEY (`equipo_grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fases`
--

CREATE TABLE IF NOT EXISTS `fases` (
  `stage_id` int(11) NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_group` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`stage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=14 ;

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

CREATE TABLE IF NOT EXISTS `gropus` (
  `grupo_id` int(20) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(200) NOT NULL,
  PRIMARY KEY (`grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jugadores`
--

CREATE TABLE IF NOT EXISTS `jugadores` (
  `id_jugador` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_jugador` varchar(255) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `goles` smallint(2) DEFAULT NULL,
  `es_goleador` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id_jugador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `jugadores`
--

INSERT INTO `jugadores` (`id_jugador`, `nombre_jugador`, `id_equipo`, `goles`, `es_goleador`) VALUES
(1, 'Lionel Messi', 13, 5, '1'),
(2, 'Gonzalo Higuain', 13, 3, '1'),
(3, 'Cristiano Ronaldo', 5, 2, '1');

-- --------------------------------------------------------

--
-- Table structure for table `mecanica_juego`
--

CREATE TABLE IF NOT EXISTS `mecanica_juego` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `rules` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mecanica_juego`
--

INSERT INTO `mecanica_juego` (`id`, `rules`) VALUES
(3, 'sdsdsd');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `mod_modulegroupcode` varchar(25) NOT NULL,
  `mod_modulegroupname` varchar(50) NOT NULL,
  `mod_modulecode` varchar(25) NOT NULL,
  `mod_modulename` varchar(50) NOT NULL,
  `mod_modulegrouporder` int(3) NOT NULL,
  `mod_moduleorder` int(3) NOT NULL,
  `mod_modulepagename` varchar(255) NOT NULL,
  PRIMARY KEY (`mod_modulegroupcode`,`mod_modulecode`),
  UNIQUE KEY `mod_modulecode` (`mod_modulecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `partidos` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=65 ;

--
-- Dumping data for table `partidos`
--

INSERT INTO `partidos` (`match_id`, `match_no`, `kickoff`, `home_team_id`, `away_team_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `venue_id`, `is_result`, `extra_time`, `stage_id`, `scored`, `wwhen`) VALUES
(1, 1, '2018-06-14 12:00:00', 1, 2, 2, 5, 0, 0, 8, 0, 0, 1, 0, '2018-02-06 21:01:14'),
(2, 2, '2018-06-15 09:00:00', 3, 4, 0, 0, 0, 0, 4, 0, 0, 1, 0, '2018-02-06 21:00:48'),
(3, 3, '2018-06-15 12:00:00', 7, 8, 0, 0, 0, 0, 1, 0, 0, 2, 0, '2018-01-19 00:01:18'),
(4, 4, '2018-06-15 15:00:00', 5, 6, 0, 0, 0, 0, 5, 0, 0, 2, 0, '2018-01-19 00:02:05'),
(5, 5, '2018-06-16 07:00:00', 9, 10, 0, 0, 0, 0, 6, 0, 0, 3, 0, '2018-01-31 19:22:36'),
(6, 6, '2018-06-16 10:00:00', 13, 14, 0, 0, 0, 0, 11, 0, 0, 4, 0, '2018-01-19 00:03:49'),
(7, 7, '2018-06-16 13:00:00', 11, 12, 0, 0, 0, 0, 13, 0, 0, 3, 0, '2018-01-19 00:04:50'),
(8, 8, '2018-06-16 21:00:00', 15, 16, 0, 0, 0, 0, 2, 0, 0, 4, 0, '2018-01-19 00:06:16'),
(9, 9, '2018-06-17 09:00:00', 19, 20, 0, 0, 0, 0, 9, 0, 0, 5, 0, '2018-01-19 18:34:44'),
(10, 10, '2018-06-17 12:00:00', 21, 22, 0, 0, 0, 0, 8, 0, 0, 6, 0, '2018-01-31 19:22:41'),
(11, 11, '2018-06-17 15:00:00', 17, 18, 0, 0, 0, 0, 10, 0, 0, 5, 0, '2018-01-19 18:36:49'),
(12, 12, '2018-06-18 09:00:00', 23, 24, 0, 0, 0, 0, 7, 0, 0, 6, 0, '2018-02-06 20:57:28'),
(13, 13, '2018-06-18 12:00:00', 25, 26, 0, 0, 0, 0, 5, 0, 0, 7, 0, '2018-01-19 18:55:11'),
(14, 14, '2018-06-18 15:00:00', 27, 28, 0, 0, 0, 0, 3, 0, 0, 7, 0, '2018-02-06 20:57:36'),
(15, 15, '2018-06-19 09:00:00', 31, 32, 0, 0, 0, 0, 13, 0, 0, 8, 0, '2018-01-19 18:57:38'),
(16, 16, '2018-06-19 12:00:00', 29, 30, 0, 0, 0, 0, 11, 0, 0, 8, 0, '2018-01-19 18:58:36'),
(17, 17, '2018-06-19 15:00:00', 1, 3, 0, 0, 0, 0, 1, 0, 0, 1, 0, '2018-01-19 18:59:16'),
(18, 18, '2018-06-20 09:00:00', 5, 7, 0, 0, 0, 0, 8, 0, 0, 2, 0, '2018-01-19 19:01:16'),
(19, 19, '2018-06-20 12:00:00', 4, 2, 0, 0, 0, 0, 10, 0, 0, 1, 0, '2018-01-19 19:02:07'),
(20, 20, '2018-06-20 15:00:00', 8, 6, 0, 0, 0, 0, 6, 0, 0, 2, 0, '2018-01-19 19:03:18'),
(21, 21, '2018-06-21 09:00:00', 12, 10, 0, 0, 0, 0, 9, 0, 0, 3, 0, '2018-01-19 19:04:11'),
(22, 22, '2018-06-21 12:00:00', 9, 11, 0, 0, 0, 0, 4, 0, 0, 3, 0, '2018-01-19 20:18:31'),
(23, 23, '2018-06-21 15:00:00', 13, 15, 0, 0, 0, 0, 7, 0, 0, 4, 0, '2018-01-19 20:19:17'),
(24, 24, '2018-06-22 09:00:00', 17, 19, 0, 0, 0, 0, 12, 0, 0, 5, 0, '2018-01-19 20:20:27'),
(25, 25, '2018-06-22 12:00:00', 16, 14, 0, 0, 0, 0, 3, 0, 0, 4, 0, '2018-01-19 20:21:10'),
(26, 26, '2018-06-22 15:00:00', 20, 18, 0, 0, 0, 0, 2, 0, 0, 5, 0, '2018-01-19 20:21:52'),
(27, 27, '2018-06-23 09:00:00', 25, 27, 0, 0, 0, 0, 11, 0, 0, 7, 0, '2018-01-19 20:22:53'),
(28, 28, '2018-06-23 12:00:00', 24, 22, 0, 0, 0, 0, 10, 0, 0, 6, 0, '2018-01-19 20:24:39'),
(29, 29, '2018-06-23 15:00:00', 21, 23, 0, 0, 0, 0, 5, 0, 0, 6, 0, '2018-01-19 20:25:18'),
(30, 30, '2018-06-24 09:00:00', 28, 26, 0, 0, 0, 0, 7, 0, 0, 7, 0, '2018-01-19 20:26:04'),
(31, 31, '2018-06-24 12:00:00', 32, 30, 0, 0, 0, 0, 4, 0, 0, 8, 0, '2018-01-19 20:26:44'),
(32, 32, '2018-06-24 15:00:00', 29, 31, 0, 0, 0, 0, 6, 0, 0, 8, 0, '2018-01-19 20:27:30'),
(33, 33, '2018-06-25 11:00:00', 4, 1, 0, 0, 0, 0, 9, 0, 0, 1, 0, '2018-01-19 20:28:45'),
(34, 34, '2018-06-25 11:00:00', 2, 3, 0, 0, 0, 0, 3, 0, 0, 1, 0, '2018-01-19 20:29:27'),
(35, 35, '2018-06-25 15:00:00', 8, 5, 0, 0, 0, 0, 13, 0, 0, 2, 0, '2018-01-19 20:30:15'),
(36, 36, '2018-06-25 15:00:00', 6, 7, 0, 0, 0, 0, 2, 0, 0, 2, 0, '2018-01-19 20:30:58'),
(37, 37, '2018-06-26 11:00:00', 12, 9, 0, 0, 0, 0, 8, 0, 0, 3, 0, '2018-01-19 20:31:34'),
(38, 38, '2018-06-26 11:00:00', 10, 11, 0, 0, 0, 0, 5, 0, 0, 3, 0, '2018-01-19 20:32:13'),
(39, 39, '2018-06-26 15:00:00', 16, 13, 0, 0, 0, 0, 1, 0, 0, 4, 0, '2018-01-19 20:32:53'),
(40, 40, '2018-06-26 15:00:00', 14, 15, 0, 0, 0, 0, 10, 0, 0, 4, 0, '2018-01-19 20:33:20'),
(41, 41, '2018-06-27 11:00:00', 22, 23, 0, 0, 0, 0, 4, 0, 0, 6, 0, '2018-01-19 20:34:29'),
(42, 42, '2018-06-27 11:00:00', 24, 21, 0, 0, 0, 0, 6, 0, 0, 6, 0, '2018-01-19 20:35:05'),
(43, 43, '2018-06-27 15:00:00', 20, 17, 0, 0, 0, 0, 11, 0, 0, 5, 0, '2018-01-19 20:35:37'),
(44, 44, '2018-06-27 15:00:00', 18, 19, 0, 0, 0, 0, 7, 0, 0, 5, 0, '2018-01-19 20:36:16'),
(45, 45, '2018-06-28 11:00:00', 32, 29, 0, 0, 0, 0, 3, 0, 0, 8, 0, '2018-01-19 20:36:51'),
(46, 46, '2018-06-28 11:00:00', 30, 31, 0, 0, 0, 0, 9, 0, 0, 8, 0, '2018-01-19 20:37:25'),
(47, 47, '2018-06-28 15:00:00', 26, 27, 0, 0, 0, 0, 13, 0, 0, 7, 0, '2018-01-19 20:37:59'),
(48, 48, '2018-06-28 15:00:00', 28, 25, 0, 0, 0, 0, 2, 0, 0, 7, 0, '2018-01-19 20:38:41'),
(49, 49, '2018-06-30 11:00:00', 35, 44, 0, 0, 0, 0, 2, 0, 0, 9, 0, '2018-01-31 19:22:28'),
(50, 50, '2018-06-30 15:00:00', 33, 42, 0, 0, 0, 0, 5, 0, 0, 9, 0, '2018-01-19 20:42:51'),
(51, 51, '2018-07-01 11:00:00', 34, 41, 0, 0, 0, 0, 8, 0, 0, 9, 0, '2018-01-19 20:43:24'),
(52, 52, '2018-07-01 15:00:00', 36, 43, 0, 0, 0, 0, 7, 0, 0, 9, 0, '2018-01-19 20:44:06'),
(53, 53, '2018-07-02 11:00:00', 37, 46, 0, 0, 0, 0, 9, 0, 0, 9, 0, '2018-01-19 20:44:53'),
(54, 54, '2018-07-02 15:00:00', 39, 48, 0, 0, 0, 0, 10, 0, 0, 9, 0, '2018-01-19 20:45:22'),
(55, 55, '2018-07-03 11:00:00', 38, 45, 0, 0, 0, 0, 1, 0, 0, 9, 0, '2018-01-19 20:46:02'),
(56, 56, '2018-07-03 15:00:00', 40, 47, 0, 0, 0, 0, 11, 0, 0, 9, 0, '2018-01-19 20:46:46'),
(57, 57, '2018-07-06 11:00:00', 49, 50, 0, 0, 0, 0, 7, 0, 0, 10, 0, '2018-01-19 20:47:41'),
(58, 58, '2018-07-06 15:00:00', 53, 54, 0, 0, 0, 0, 6, 0, 0, 10, 0, '2018-01-19 20:48:37'),
(59, 59, '2018-07-07 11:00:00', 55, 56, 0, 0, 0, 0, 9, 0, 0, 10, 0, '2018-01-19 20:49:19'),
(60, 60, '2018-07-07 15:00:00', 51, 52, 0, 0, 0, 0, 5, 0, 0, 10, 0, '2018-01-19 20:49:52'),
(61, 61, '2018-07-10 15:00:00', 57, 58, 0, 0, 0, 0, 5, 0, 0, 11, 0, '2018-01-19 20:51:12'),
(62, 62, '2018-07-11 15:00:00', 59, 60, 0, 0, 0, 0, 8, 0, 0, 11, 0, '2018-01-19 20:52:04'),
(63, 63, '2018-07-14 11:00:00', 61, 62, 0, 0, 0, 0, 1, 0, 0, 12, 0, '2018-01-19 20:53:22'),
(64, 64, '2018-07-15 12:00:00', 63, 64, 0, 0, 0, 0, 8, 0, 0, 13, 0, '2018-01-19 20:54:14');

-- --------------------------------------------------------

--
-- Table structure for table `pronosticos`
--

CREATE TABLE IF NOT EXISTS `pronosticos` (
  `prediction_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `match_id` int(11) NOT NULL,
  `home_goals` int(11) NOT NULL,
  `away_goals` int(11) NOT NULL,
  `home_penalties` int(11) NOT NULL,
  `away_penalties` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prediction_id`),
  UNIQUE KEY `idx_pred_um` (`user_id`,`match_id`),
  KEY `idx_pred_wwhen` (`wwhen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `role_rolecode` varchar(50) NOT NULL,
  `role_rolename` varchar(50) NOT NULL,
  PRIMARY KEY (`role_rolecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `role_rights` (
  `rr_rolecode` varchar(50) NOT NULL,
  `rr_modulecode` varchar(25) NOT NULL,
  `rr_create` enum('yes','no') NOT NULL DEFAULT 'no',
  `rr_edit` enum('yes','no') NOT NULL DEFAULT 'no',
  `rr_delete` enum('yes','no') NOT NULL DEFAULT 'no',
  `rr_view` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`rr_rolecode`,`rr_modulecode`),
  KEY `rr_modulecode` (`rr_modulecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `sedes` (
  `venue_id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stadium` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tz_offset` int(11) NOT NULL,
  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=14 ;

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

CREATE TABLE IF NOT EXISTS `system_users` (
  `u_userid` int(11) NOT NULL AUTO_INCREMENT,
  `u_username` varchar(100) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_rolecode` varchar(50) NOT NULL,
  PRIMARY KEY (`u_userid`),
  KEY `u_rolecode` (`u_rolecode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

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

CREATE TABLE IF NOT EXISTS `trivias` (
  `id_trivia` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` varchar(255) NOT NULL,
  `puntos_trivia` smallint(6) DEFAULT NULL,
  `vencimiento` datetime NOT NULL,
  `id_fase` smallint(6) NOT NULL,
  PRIMARY KEY (`id_trivia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `trivias`
--

INSERT INTO `trivias` (`id_trivia`, `pregunta`, `puntos_trivia`, `vencimiento`, `id_fase`) VALUES
(1, '11111', 2000, '0000-00-00 00:00:00', 13);

-- --------------------------------------------------------

--
-- Table structure for table `trivias_respuestas`
--

CREATE TABLE IF NOT EXISTS `trivias_respuestas` (
  `id_trivia` smallint(6) NOT NULL,
  `id_respuesta` smallint(6) NOT NULL,
  `respuesta` varchar(255) NOT NULL,
  `respuesta_correcta` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trivias_respuestas`
--

INSERT INTO `trivias_respuestas` (`id_trivia`, `id_respuesta`, `respuesta`, `respuesta_correcta`) VALUES
(1, 1, '2222', '1'),
(1, 2, '3333', '0'),
(1, 3, '4444', '0');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
