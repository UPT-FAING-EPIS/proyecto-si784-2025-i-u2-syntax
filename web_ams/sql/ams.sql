-- --------------------------------------------------------
-- Host:                         161.132.45.228
-- Versión del servidor:         10.11.11-MariaDB-0+deb12u1 - Debian 12
-- SO del servidor:              debian-linux-gnu
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para sistema_mentoria
CREATE DATABASE IF NOT EXISTS `sistema_mentoria` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sistema_mentoria`;

-- Volcando estructura para tabla sistema_mentoria.administrador
CREATE TABLE IF NOT EXISTS `administrador` (
  `ID_ADMIN` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `NIVEL_ACCESO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_ADMIN`),
  KEY `ID_USUARIO` (`ID_USUARIO`),
  CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.administrador: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_estudiante` varchar(12) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email_institucional` varchar(150) DEFAULT NULL,
  `carrera` varchar(100) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo','graduado','retirado') DEFAULT 'activo',
  `fecha_ingreso` date DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_estudiante` (`codigo_estudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.alumnos: ~7 rows (aproximadamente)
INSERT INTO `alumnos` (`id`, `codigo_estudiante`, `nombres`, `apellidos`, `email_institucional`, `carrera`, `semestre`, `estado`, `fecha_ingreso`, `creado_en`, `actualizado_en`) VALUES
	(1, '2022073898', 'Gregory Brandon', 'Huanca Merma', 'gh2022073898@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-16 20:02:05', '2025-06-20 23:17:46'),
	(2, '2020001235', 'María Elena', 'Rodríguez Silva', 'gregfhm789@gmail.com', 'Ingeniería Civil', 8, 'activo', NULL, '2025-06-16 20:02:05', '2025-06-21 00:02:58'),
	(3, '2022001236', 'Luis Miguel', 'Fernández Torres', 'lfernandez@virtual.upt.pe', 'Ingeniería Industrial', 4, 'activo', NULL, '2025-06-16 20:02:05', '2025-06-16 20:02:05'),
	(4, '2023077472', 'Bruno Enrique', 'Ancco Suaña', 'ba2023077472@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-20 23:16:18', '2025-06-21 00:04:53'),
	(5, '2022074255', 'Joan Cristian ', 'Medina Quispe', 'jm2022074255@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-20 23:17:29', '2025-06-20 23:18:45'),
	(6, '2022073505', 'Augusto Joaquin', 'Rivera Muñoz', 'ar2022073505@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-20 23:18:32', '2025-06-20 23:18:43'),
	(7, '2019063331', 'Rodrigo Samuel', 'Lira Alvarez', 'rl2019063331@virtual.upt.pe', 'Ingeniería de Sistemas', 10, 'activo', NULL, '2025-06-21 14:40:15', '2025-06-21 14:41:15'),
	(41, '2022074268', 'DAVID JORDAN', 'ANAMPA PANCCA', 'da2022074268@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(42, '2022073504', 'RENZO ANTONIO', 'ANTAYHUA MAMANI', 'ra2022073504@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(43, '2022074266', 'CARLOS DANIEL', 'AYALA RAMOS', 'ca2022074266@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(44, '2022073895', 'DIEGO FERNANDO', 'CASTILLO MAMANI', 'dc2022073895@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(45, '2020067574', 'JUAN DIEGO', 'CASTRO PACOHUANACO', 'jc2020067574@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(46, '2022073903', 'VICTOR WILLIAMS', 'CRUZ MAMANI', 'vc2022073903@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(47, '2022073902', 'SEBASTIAN NICOLAS', 'FUENTES AVALOS', 'sf2022073902@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(48, '2022074263', 'GABRIELA LUZKALID', 'GUTIERREZ MAMANI', 'gg2022074263@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(49, '2022073899', 'FRANKLIN LUIS', 'LLANO CUTIPA', 'fl2022073899@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(50, '2021072615', 'RENZO FERNANDO', 'LOYOLA VILCA CHOQUE', 'rl2021072615@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(51, '2022073501', 'RENATO DANIEL', 'MOLINA HUAMAN', 'rm2022073501@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(52, '2021071079', 'WALTER AUGUSTO', 'PORTILLA QUISPE', 'wp2021071079@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(53, '2022073896', 'WALTER EMMANUEL', 'SALAS JIMENEZ', 'ws2022073896@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(54, '2023077628', 'SEBASTIAN', 'SCURA WISMANN', 'ss2023077628@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(55, '2020066922', 'ANGEL JOSE', 'VARGAS GUTIERREZ', 'av2020066922@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:25:34', '2025-06-23 22:25:34'),
	(73, '2022074261', 'AHMED HASAN', 'AKHTAR OVIEDO', 'aa2022074261@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(74, '2022073897', 'SEBASTIAN ALBERTO', 'BUTILIER MASIAS', 'sb2022073897@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(75, '2022074258', 'ANDY MICHAEL', 'CALIZAYA LADERA', 'ac2022074258@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(76, '2022074262', 'CESAR NIKOLAS', 'CAMAC MELENDEZ', 'cc2022074262@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(77, '2018060898', 'JORGE JESÚS', 'COHAILA MAMANI', 'jc2018060898@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(78, '2022073503', 'SERGIO ALBERTO', 'COLQUE PONCE', 'sc2022073503@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(79, '2022073904', 'ARIANA BYANCA SOFIA', 'ESPINOZA CASTAÑEDA', 'ae2022073904@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(80, '2018060901', 'MIGUEL ANGEL', 'GÓMEZ MAMANI', 'mg2018060901@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(81, '2020068973', 'ADRIAN FABRICIO', 'GUILLEN RUIZ', 'ag2020068973@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(82, '2021072628', 'FRANCO TEDDY', 'MANGO PUMA', 'fm2021072628@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(83, '2019063079', 'KATHERINE XIOMARA ANGELA', 'MAQUERA MAMANI', 'km2019063079@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(84, '2021071076', 'JOSE HUMBERTO', 'PAYPAY CHOQUE', 'jp2021071076@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(85, '2017059279', 'RICHARD SMITH', 'ROLDÁN LEA', 'rr2017059279@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(86, '2021069829', 'ADRIAN FABRICIO', 'SALAS ACUÑA', 'as2021069829@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(87, '2022073905', 'LUIS ANDRES', 'SUAREZ QUISPE', 'ls2022073905@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07'),
	(88, '2020066927', 'FERNANDO WILSON', 'VILLANUEVA BARRIGA', 'fv2020066927@virtual.upt.pe', 'Ingeniería de Sistemas', 7, 'activo', NULL, '2025-06-23 22:27:07', '2025-06-23 22:27:07');

-- Volcando estructura para tabla sistema_mentoria.asistencia
CREATE TABLE IF NOT EXISTS `asistencia` (
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `ID_CLASE` int(11) DEFAULT NULL,
  `FECHA` datetime DEFAULT current_timestamp(),
  `ESTADO` tinyint(4) DEFAULT 1,
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  KEY `ID_CLASE` (`ID_CLASE`),
  CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`),
  CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`ID_CLASE`) REFERENCES `clase` (`ID_CLASE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.asistencia: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.aula
CREATE TABLE IF NOT EXISTS `aula` (
  `ID_AULA` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(100) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `FECHA_REG` date DEFAULT NULL,
  PRIMARY KEY (`ID_AULA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.aula: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.ciclo
CREATE TABLE IF NOT EXISTS `ciclo` (
  `ID_CICLO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(150) DEFAULT NULL,
  `ID_SEMESTRE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_CICLO`),
  KEY `ID_SEMESTRE` (`ID_SEMESTRE`),
  CONSTRAINT `ciclo_ibfk_1` FOREIGN KEY (`ID_SEMESTRE`) REFERENCES `semestre_academico` (`ID_SEMESTRE`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.ciclo: ~20 rows (aproximadamente)
INSERT INTO `ciclo` (`ID_CICLO`, `NOMBRE`, `ID_SEMESTRE`) VALUES
	(1, 'CICLO - I', 1),
	(2, 'CICLO - II', 1),
	(3, 'CICLO - III', 1),
	(4, 'CICLO - IV', 1),
	(5, 'CICLO - V', 1),
	(6, 'CICLO - VI', 1),
	(7, 'CICLO - VII', 1),
	(8, 'CICLO - VIII', 1),
	(9, 'CICLO - IX', 1),
	(10, 'CICLO - X', 1),
	(16, 'CICLO - I', 2),
	(17, 'CICLO - II', 2),
	(18, 'CICLO - III', 2),
	(19, 'CICLO - IV', 2),
	(20, 'CICLO - V', 2),
	(21, 'CICLO - VI', 2),
	(22, 'CICLO - VII', 2),
	(23, 'CICLO - VIII', 2),
	(24, 'CICLO - IX', 2),
	(25, 'CICLO - X', 2);

-- Volcando estructura para tabla sistema_mentoria.clase
CREATE TABLE IF NOT EXISTS `clase` (
  `ID_CLASE` int(11) NOT NULL AUTO_INCREMENT,
  `HORARIO` varchar(150) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `FECHA_INICIO` datetime DEFAULT NULL,
  `FECHA_FIN` datetime DEFAULT NULL,
  `RAZON` varchar(100) DEFAULT NULL,
  `CAPACIDAD` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `ID_CURSO` int(11) DEFAULT NULL,
  `ENLACE` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID_CLASE`),
  KEY `ID_CURSO` (`ID_CURSO`),
  CONSTRAINT `clase_ibfk_2` FOREIGN KEY (`ID_CURSO`) REFERENCES `curso` (`ID_CURSO`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.clase: ~4 rows (aproximadamente)
INSERT INTO `clase` (`ID_CLASE`, `HORARIO`, `ESTADO`, `FECHA_INICIO`, `FECHA_FIN`, `RAZON`, `CAPACIDAD`, `FECHA_REG`, `ID_CURSO`, `ENLACE`) VALUES
	(20, '08:00 - 10:00', 1, '2025-06-21 08:39:48', '2025-06-21 10:39:48', 'asdadsadadasddasdad', 30, '2025-06-14 01:08:10', 19, ''),
	(21, '08:00 - 10:00', 3, '2025-06-21 09:34:00', '2025-06-21 10:46:59', 'Testeando mano', 30, '2025-06-14 01:50:00', 33, NULL),
	(23, '09:00 - 11:00 (mañana)', 3, '2025-06-21 09:00:00', '2025-06-21 09:39:37', 'Necesito entender Conceptos basicos de calidad\r\n', 30, '2025-06-18 13:46:12', 40, NULL),
	(24, 'Lunes 18:00-20:00', 1, '2025-06-28 09:46:13', '2025-07-28 09:46:13', 'ytyiuun', 30, '2025-06-21 09:46:13', 51, NULL);

-- Volcando estructura para tabla sistema_mentoria.codigos_verificacion
CREATE TABLE IF NOT EXISTS `codigos_verificacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_estudiante` varchar(12) NOT NULL,
  `codigo_verificacion` varchar(6) NOT NULL,
  `fecha_expiracion` datetime NOT NULL,
  `intentos` int(11) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.codigos_verificacion: ~1 rows (aproximadamente)
INSERT INTO `codigos_verificacion` (`id`, `codigo_estudiante`, `codigo_verificacion`, `fecha_expiracion`, `intentos`, `creado_en`) VALUES
	(18, '2020001235', '283714', '2025-06-21 00:18:12', 0, '2025-06-21 00:03:12');

-- Volcando estructura para tabla sistema_mentoria.comentario
CREATE TABLE IF NOT EXISTS `comentario` (
  `ID_COMENTARIO` int(11) NOT NULL AUTO_INCREMENT,
  `PUNTUACION` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `ID_DOCENTE` int(11) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_COMENTARIO`),
  KEY `ID_DOCENTE` (`ID_DOCENTE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`ID_DOCENTE`) REFERENCES `docente` (`ID_DOCENTE`),
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.comentario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.curso
CREATE TABLE IF NOT EXISTS `curso` (
  `ID_CURSO` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(10) DEFAULT NULL,
  `NOMBRE` varchar(150) DEFAULT NULL,
  `ID_CICLO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_CURSO`),
  KEY `ID_CICLO` (`ID_CICLO`),
  CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`ID_CICLO`) REFERENCES `ciclo` (`ID_CICLO`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.curso: ~60 rows (aproximadamente)
INSERT INTO `curso` (`ID_CURSO`, `CODIGO`, `NOMBRE`, `ID_CICLO`) VALUES
	(1, 'EG-181', 'COMUNICACIÓN I', 1),
	(2, 'EG-182', 'MATEMÁTICA BÁSICA', 1),
	(3, 'EG-183', 'ESTRATEGIAS PARA EL APRENDIZAJE AUTÓNOMO', 1),
	(4, 'EG-184', 'DESARROLLO PERSONAL Y LIDERAZGO', 1),
	(5, 'EG-185', 'DESARROLLO DE COMPETENCIAS DIGITALES', 1),
	(6, 'INE-186', 'MATEMÁTICA I', 1),
	(7, 'EG-281', 'COMUNICACION II', 2),
	(8, 'EG-282', 'TERRITORIO PERUANO, DEFENSA Y SEGURIDAD NACIONAL', 2),
	(9, 'EG-283', 'FILOSOFIA', 2),
	(10, 'INE-284', 'TECNICAS DE PROGRAMACION', 2),
	(11, 'INE-285', 'FISICA I', 2),
	(12, 'INE-286', 'MATEMATICA II', 2),
	(13, 'INE-381', 'ECONOMIA', 3),
	(14, 'EG-382', 'ETICA', 3),
	(15, 'INE-383', 'ESTADISTICA Y PROBABILIDADES', 3),
	(16, 'SI-384', 'ESTRUCTURA DE DATOS', 3),
	(17, 'SI-385', 'SISTEMAS DE INFORMACION', 3),
	(18, 'SI-386', 'MATEMATICA DISCRETA', 3),
	(19, 'SI-481', 'MODELAMIENTO DE PROCESOS', 4),
	(20, 'SI-482', 'INGENIERIA ECONOMICA Y FINANCIERA', 4),
	(21, 'SI-483', 'INTERACCION Y DISEÑO DE INTERFACES', 4),
	(22, 'INE-484', 'DISEÑO DE INGENIERIA', 4),
	(23, 'SI-485', 'SISTEMAS ELECTRONICOS DIGITALES', 4),
	(24, 'SI-486', 'PROGRAMACION I', 4),
	(25, 'SI-581', 'ARQUITECTURA DE COMPUTADORAS', 5),
	(26, 'SI-582', 'DISEÑO DE DATOS', 5),
	(27, 'SI-583', 'DISEÑO Y MODELAMIENTO VIRTUAL', 5),
	(28, 'SI-584', 'INGENIERIA DE REQUERIMIENTOS', 5),
	(29, 'SI-585', 'INGENIERIA DE SOFTWARE', 5),
	(30, 'SI-586', 'PROGRAMACION II', 5),
	(31, 'SI-681', 'ECOLOGIA Y DESARROLLO SOSTENIBLE', 6),
	(32, 'SI-682', 'SISTEMAS OPERATIVOS I', 6),
	(33, 'SI-683', 'BASE DE DATOS', 6),
	(34, 'SI-684', 'INVESTIGACION DE OPERACIONES', 6),
	(35, 'SI-685', 'DISEÑO Y ARQUITECTURA DE SOFTWARE', 6),
	(36, 'SI-686', 'PROGRAMACION III', 6),
	(37, 'EG-781', 'PROBLEMAS Y DESAFIOS DEL PERU EN UN MUNDO GLOBAL', 7),
	(38, 'SI-782', 'SISTEMAS OPERATIVOS II', 7),
	(39, 'SI-783', 'BASE DE DATOS II', 7),
	(40, 'SI-784', 'CALIDAD Y PRUEBAS DE SOFTWARE', 7),
	(41, 'SI-785', 'GESTION DE PROYECTOS DE TI', 7),
	(42, 'SI-786', 'PROGRAMACION WEB I', 7),
	(43, 'SI-881', 'INTELIGENCIA ARTIFICIAL', 8),
	(44, 'SI-882', 'REDES DE COMPUTADORAS', 8),
	(45, 'SI-883', 'SOLUCIONES MOVILES I', 8),
	(46, 'SI-884', 'ESTADISTICA INFERENCIAL Y ANALISIS DE DATOS', 8),
	(47, 'SI-885', 'INTELIGENCIA DE NEGOCIOS', 8),
	(48, 'SI-886', 'PLANEAMIENTO ESTRATEGICO DE TI', 8),
	(49, 'SI-981', 'TALLER DE TESIS I', 9),
	(50, 'SI-982', 'PROGRAMACION WEB II', 9),
	(51, 'SI-983', 'CONSTRUCCION DE SOFTWARE I', 9),
	(52, 'SI-984', 'REDES Y COMUNICACION DE DATOS II', 9),
	(53, 'SI-985', 'GESTION DE LA CONFIGURACION DE SOFTWARE', 9),
	(54, 'SI-986', 'INGLES TECNICO', 9),
	(55, 'SI-080', 'TALLER DE TESIS II / TRABAJO DE INVESTIGACION', 10),
	(56, 'SI-082', 'SEGURIDAD DE TECNOLOGIA DE INFORMACION', 10),
	(57, 'SI-083', 'CONSTRUCCION DE SOFTWARE II', 10),
	(58, 'SI-084', 'AUDITORIA DE SISTEMAS', 10),
	(59, 'SI-085', 'TALLER DE EMPRENDIMIENTO Y LIDERAZGO', 10),
	(60, 'SI-086', 'GERENCIA DE TECNOLOGIAS DE INFORMACION', 10);

-- Volcando estructura para tabla sistema_mentoria.discord
CREATE TABLE IF NOT EXISTS `discord` (
  `ID_CODIGO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) NOT NULL,
  `ID_ESTUDIANTE` int(11) NOT NULL,
  `KEY` varchar(100) NOT NULL,
  `ESTADO` tinyint(1) DEFAULT 0,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_CODIGO`),
  UNIQUE KEY `KEY` (`KEY`),
  KEY `fk_codigos_usuario` (`ID_USUARIO`),
  KEY `fk_codigos_estudiante` (`ID_ESTUDIANTE`),
  CONSTRAINT `fk_codigos_estudiante` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`),
  CONSTRAINT `fk_codigos_usuario` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.discord: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.docente
CREATE TABLE IF NOT EXISTS `docente` (
  `ID_DOCENTE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_DOCENTE`),
  KEY `ID_USUARIO` (`ID_USUARIO`),
  CONSTRAINT `docente_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.docente: ~1 rows (aproximadamente)
INSERT INTO `docente` (`ID_DOCENTE`, `ID_USUARIO`, `ESTADO`, `FECHA_REG`) VALUES
	(1, 79, 1, '2025-06-17 16:32:18');

-- Volcando estructura para tabla sistema_mentoria.estudiante
CREATE TABLE IF NOT EXISTS `estudiante` (
  `ID_ESTUDIANTE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `CODIGO` varchar(10) DEFAULT NULL,
  `EMAIL_CORPORATIVO` varchar(100) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `CONDICION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_ESTUDIANTE`),
  UNIQUE KEY `CODIGO` (`CODIGO`),
  UNIQUE KEY `EMAIL_CORPORATIVO` (`EMAIL_CORPORATIVO`),
  KEY `ID_USUARIO` (`ID_USUARIO`),
  CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.estudiante: ~3 rows (aproximadamente)
INSERT INTO `estudiante` (`ID_ESTUDIANTE`, `ID_USUARIO`, `CODIGO`, `EMAIL_CORPORATIVO`, `FECHA_REG`, `CONDICION`) VALUES
	(3, 1, '2022073898', 'gh2022073898@virtual.upt.pe', '2025-06-16 16:44:51', 'activo'),
	(4, 83, '2023077472', 'ba2023077472@virtual.upt.pe', '2025-06-20 19:06:47', 'activo'),
	(5, 85, '2019063331', 'rl2019063331@virtual.upt.pe', '2025-06-21 09:45:49', 'activo');

-- Volcando estructura para evento sistema_mentoria.ev_clase_cambio_a_estado_3
DELIMITER //
CREATE EVENT `ev_clase_cambio_a_estado_3` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-06-18 12:53:55' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  UPDATE clase
  SET ESTADO = 3,
      ENLACE = NULL
  WHERE ESTADO = 2
    AND FECHA_INICIO IS NOT NULL
    AND TIMESTAMPDIFF(HOUR, FECHA_INICIO, NOW()) >= 2
    AND ESTADO <> 5;
END//
DELIMITER ;

-- Volcando estructura para tabla sistema_mentoria.inscripcion
CREATE TABLE IF NOT EXISTS `inscripcion` (
  `ID_CLASE` int(11) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  KEY `ID_CLASE` (`ID_CLASE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`ID_CLASE`) REFERENCES `clase` (`ID_CLASE`),
  CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.inscripcion: ~4 rows (aproximadamente)
INSERT INTO `inscripcion` (`ID_CLASE`, `ID_ESTUDIANTE`, `FECHA_REG`) VALUES
	(20, 3, '2025-06-16 16:52:43'),
	(23, 3, '2025-06-18 13:46:12'),
	(23, 4, '2025-06-20 19:07:03'),
	(24, 5, '2025-06-21 09:46:13');

-- Volcando estructura para tabla sistema_mentoria.notas
CREATE TABLE IF NOT EXISTS `notas` (
  `ID_NOTAS` int(11) NOT NULL AUTO_INCREMENT,
  `ID_REGISTRO` int(11) NOT NULL,
  `ID_UNIDAD` int(11) NOT NULL,
  `TIPO_NOTA` varchar(100) NOT NULL,
  `CALIFICACION` decimal(5,2) NOT NULL CHECK (`CALIFICACION` between 0 and 20),
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `USUARIO_REGISTRADOR` int(11) DEFAULT NULL,
  `IP_REGISTRADOR` varchar(45) DEFAULT NULL,
  `OBSERVACION` text DEFAULT NULL,
  PRIMARY KEY (`ID_NOTAS`),
  KEY `ID_REGISTRO` (`ID_REGISTRO`),
  KEY `ID_UNIDAD` (`ID_UNIDAD`),
  KEY `USUARIO_REGISTRADOR` (`USUARIO_REGISTRADOR`),
  CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`ID_REGISTRO`) REFERENCES `registro_academico` (`ID_REGISTRO`),
  CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`ID_UNIDAD`) REFERENCES `unidad` (`ID_UNIDAD`),
  CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`USUARIO_REGISTRADOR`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.notas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.registro_academico
CREATE TABLE IF NOT EXISTS `registro_academico` (
  `ID_REGISTRO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DOCENTE` int(11) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `ID_CLASE` int(11) DEFAULT NULL,
  `ID_UNIDAD` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_REGISTRO`),
  KEY `ID_DOCENTE` (`ID_DOCENTE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  KEY `ID_CLASE` (`ID_CLASE`),
  KEY `ID_UNIDAD` (`ID_UNIDAD`),
  CONSTRAINT `registro_academico_ibfk_1` FOREIGN KEY (`ID_DOCENTE`) REFERENCES `docente` (`ID_DOCENTE`),
  CONSTRAINT `registro_academico_ibfk_2` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`),
  CONSTRAINT `registro_academico_ibfk_3` FOREIGN KEY (`ID_CLASE`) REFERENCES `clase` (`ID_CLASE`),
  CONSTRAINT `registro_academico_ibfk_4` FOREIGN KEY (`ID_UNIDAD`) REFERENCES `unidad` (`ID_UNIDAD`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.registro_academico: ~5 rows (aproximadamente)
INSERT INTO `registro_academico` (`ID_REGISTRO`, `ID_DOCENTE`, `ID_ESTUDIANTE`, `ID_CLASE`, `ID_UNIDAD`, `FECHA_REG`) VALUES
	(3, 1, 3, 20, NULL, '2025-06-17 16:32:29'),
	(4, 1, NULL, 20, NULL, '2025-06-18 08:31:42'),
	(5, 1, NULL, 21, NULL, '2025-06-18 08:31:42'),
	(6, 1, 3, 23, NULL, '2025-06-18 13:46:12'),
	(7, NULL, 5, 24, NULL, '2025-06-21 09:46:13');

-- Volcando estructura para tabla sistema_mentoria.rol
CREATE TABLE IF NOT EXISTS `rol` (
  `ID_ROL` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.rol: ~4 rows (aproximadamente)
INSERT INTO `rol` (`ID_ROL`, `NOMBRE`) VALUES
	(1, 'VISITANTE'),
	(2, 'ESTUDIANTE'),
	(3, 'DOCENTE'),
	(4, 'ADMINISTRADOR');

-- Volcando estructura para tabla sistema_mentoria.roles_asignados
CREATE TABLE IF NOT EXISTS `roles_asignados` (
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ID_ROL` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `ESTADO` tinyint(1) DEFAULT 1,
  KEY `ID_USUARIO` (`ID_USUARIO`),
  KEY `ID_ROL` (`ID_ROL`),
  CONSTRAINT `roles_asignados_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`),
  CONSTRAINT `roles_asignados_ibfk_2` FOREIGN KEY (`ID_ROL`) REFERENCES `rol` (`ID_ROL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.roles_asignados: ~8 rows (aproximadamente)
INSERT INTO `roles_asignados` (`ID_USUARIO`, `ID_ROL`, `FECHA_REG`, `ESTADO`) VALUES
	(1, 4, '2025-05-20 13:46:43', 1),
	(79, 3, '2025-06-17 15:11:06', 1),
	(81, 1, '2025-06-20 15:15:34', 1),
	(82, 1, '2025-06-20 16:33:20', 1),
	(83, 2, '2025-06-20 18:52:26', 1),
	(84, 1, '2025-06-20 19:01:53', 1),
	(85, 2, '2025-06-21 09:44:04', 1),
	(86, 1, '2025-06-23 12:13:38', 1);

-- Volcando estructura para tabla sistema_mentoria.semestre_academico
CREATE TABLE IF NOT EXISTS `semestre_academico` (
  `ID_SEMESTRE` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(100) DEFAULT NULL,
  `FECHA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_SEMESTRE`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.semestre_academico: ~2 rows (aproximadamente)
INSERT INTO `semestre_academico` (`ID_SEMESTRE`, `CODIGO`, `FECHA`) VALUES
	(1, '2025-I', '2025-06-13 15:56:01'),
	(2, '2025-II', '2025-06-13 15:56:01');

-- Volcando estructura para procedimiento sistema_mentoria.sp_crear_clase_con_inscripcion
DELIMITER //
CREATE PROCEDURE `sp_crear_clase_con_inscripcion`(
	IN `p_id_estudiante` INT,
	IN `p_id_curso` INT,
	IN `p_horario` VARCHAR(50),
	IN `p_razon` TEXT
)
BEGIN
    DECLARE v_id_clase INT;

    START TRANSACTION;

    -- Insertar nueva clase
    INSERT INTO clase (
        HORARIO, ESTADO, FECHA_INICIO, FECHA_FIN, RAZON, CAPACIDAD, FECHA_REG, ID_CURSO
    ) VALUES (
        p_horario, 1,
        DATE_ADD(NOW(), INTERVAL 7 DAY),
        DATE_ADD(NOW(), INTERVAL 37 DAY),
        p_razon, 30, NOW(), p_id_curso
    );

    SET v_id_clase = LAST_INSERT_ID();

    -- Insertar inscripción del estudiante
    INSERT INTO inscripcion (
        ID_CLASE, ID_ESTUDIANTE, FECHA_REG
    ) VALUES (
        v_id_clase, p_id_estudiante, NOW()
    );
    INSERT INTO registro_academico (
        ID_CLASE, ID_ESTUDIANTE, FECHA_REG
    ) VALUES (
        v_id_clase, p_id_estudiante, NOW()
    );

    COMMIT;
END//
DELIMITER ;

-- Volcando estructura para procedimiento sistema_mentoria.sp_registrar_usuario
DELIMITER //
CREATE PROCEDURE `sp_registrar_usuario`(
	IN `p_dni` VARCHAR(8),
	IN `p_nombre` VARCHAR(100),
	IN `p_apellido` VARCHAR(100),
	IN `p_email` VARCHAR(100),
	IN `p_password_hashed` VARCHAR(255)
)
BEGIN
    DECLARE v_user_id INT;

    START TRANSACTION;

    -- Insertar en tabla USUARIO
    INSERT INTO usuario (DNI, NOMBRE, APELLIDO, EMAIL, PASSWORD)
    VALUES (p_dni, p_nombre, p_apellido, p_email, p_password_hashed);

    SET v_user_id = LAST_INSERT_ID();

    -- Verificar si se obtuvo un ID válido
    IF v_user_id IS NULL OR v_user_id = 0 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se pudo crear el usuario.';
    END IF;

    -- Insertar en tabla ROLES_ASIGNADOS
    INSERT INTO ROLES_ASIGNADOS (ID_USUARIO, ID_ROL, FECHA_REG, ESTADO)
    VALUES (v_user_id, 1, NOW(), 1);

    COMMIT;

    -- Retornar el ID generado
    SELECT v_user_id AS id_usuario;
END//
DELIMITER ;

-- Volcando estructura para procedimiento sistema_mentoria.sp_registrar_usuario_oauth
DELIMITER //
CREATE PROCEDURE `sp_registrar_usuario_oauth`(
	IN `p_nombre` VARCHAR(100),
	IN `p_apellido` VARCHAR(100),
	IN `p_email` VARCHAR(100),
	OUT `p_id_usuario` INT
)
BEGIN
    DECLARE v_id_usuario INT;

    START TRANSACTION;

    INSERT INTO usuario (NOMBRE, APELLIDO, EMAIL)
    VALUES (p_nombre, p_apellido, p_email);

    SET v_id_usuario = LAST_INSERT_ID();

    IF v_id_usuario IS NULL OR v_id_usuario = 0 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al insertar usuario, ID inválido';
    END IF;

    INSERT INTO roles_asignados (ID_USUARIO, ID_ROL, FECHA_REG, ESTADO)
    VALUES (v_id_usuario, 1, NOW(), 1);

    COMMIT;

    SET p_id_usuario = v_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para procedimiento sistema_mentoria.sp_tomar_clase
DELIMITER //
CREATE PROCEDURE `sp_tomar_clase`(
    IN p_id_clase INT,
    IN p_id_docente INT,
    OUT p_resultado VARCHAR(255),
    OUT p_success BOOLEAN
)
BEGIN
    DECLARE v_registros_actualizados INT DEFAULT 0;
    DECLARE v_nombre_curso VARCHAR(150);
    DECLARE v_codigo_curso VARCHAR(10);
    
    -- Handler simplificado para MariaDB
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_success = FALSE;
        SET p_resultado = 'Error interno del servidor';
    END;

    START TRANSACTION;

    -- Obtener información del curso
    SELECT cu.NOMBRE, cu.CODIGO
    INTO v_nombre_curso, v_codigo_curso
    FROM clase c
    INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
    WHERE c.ID_CLASE = p_id_clase;

    -- Verificar que la clase existe
    IF v_nombre_curso IS NULL THEN
        SET p_success = FALSE;
        SET p_resultado = 'La clase no existe';
        ROLLBACK;
    ELSE
        -- Actualizar todos los registros académicos de la clase
        UPDATE registro_academico 
        SET ID_DOCENTE = p_id_docente 
        WHERE ID_CLASE = p_id_clase;

        -- Obtener número de registros actualizados
        SET v_registros_actualizados = ROW_COUNT();

        IF v_registros_actualizados > 0 THEN
            SET p_success = TRUE;
            SET p_resultado = CONCAT('Te has convertido en mentor del curso: ', v_codigo_curso, ' - ', v_nombre_curso, ' (', v_registros_actualizados, ' registros actualizados)');
            COMMIT;
        ELSE
            SET p_success = FALSE;
            SET p_resultado = 'No hay registros académicos para actualizar en esta clase';
            ROLLBACK;
        END IF;
    END IF;

END//
DELIMITER ;

-- Volcando estructura para tabla sistema_mentoria.unidad
CREATE TABLE IF NOT EXISTS `unidad` (
  `ID_UNIDAD` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_UNIDAD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.unidad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `DNI` varchar(10) DEFAULT NULL,
  `NOMBRE` varchar(100) DEFAULT NULL,
  `APELLIDO` varchar(100) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `CELULAR` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(100) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_USUARIO`),
  UNIQUE KEY `DNI` (`DNI`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.usuario: ~8 rows (aproximadamente)
INSERT INTO `usuario` (`ID_USUARIO`, `DNI`, `NOMBRE`, `APELLIDO`, `EMAIL`, `CELULAR`, `PASSWORD`, `FECHA_REG`) VALUES
	(1, NULL, 'GREGORY BRANDON', 'HUANCA MERMA', 'gh2022073898@virtual.upt.pe', NULL, NULL, '2025-05-20 12:32:35'),
	(79, '71547818', 'GREGORY BRANDON', 'HUANCA MERMA', 'gbhm2003@gmail.com', NULL, '$2y$10$4Qr172TB.2LB9dZQRyI.u.UlnjnMiLwTO5HoU1erYueRLszcO2CRO', '2025-06-17 15:11:06'),
	(81, NULL, 'Leslie', 'Huaracha Ticona', 'leslieht28@gmail.com', NULL, NULL, '2025-06-20 15:15:34'),
	(82, NULL, 'AUGUSTO JOAQUIN', 'RIVERA MUÑOZ', 'ar2022073505@virtual.upt.pe', NULL, NULL, '2025-06-20 16:33:20'),
	(83, NULL, 'Bruno Enrique', 'ANCCO SUAÑA', 'ba2023077472@virtual.upt.pe', NULL, NULL, '2025-06-20 18:52:26'),
	(84, NULL, 'GREGORY BRANDON', 'HUANCA MERMA', 'gregfhm789@gmail.com', NULL, NULL, '2025-06-20 19:01:53'),
	(85, NULL, 'Rodrigo Samael Adonai', 'LIRA ALVAREZ', 'rl2019063331@virtual.upt.pe', NULL, NULL, '2025-06-21 09:44:04'),
	(86, NULL, 'SERGIO ALBERTO', 'COLQUE PONCE', 'sc2022073503@virtual.upt.pe', NULL, NULL, '2025-06-23 12:13:38');

-- Volcando estructura para disparador sistema_mentoria.tr_clase_enlace_insertado
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER tr_clase_enlace_insertado
BEFORE UPDATE ON clase
FOR EACH ROW
BEGIN
  -- Solo si el estado actual no es 5
  IF OLD.ESTADO <> 5 THEN
    -- Si estaba en estado 1 y se inserta un enlace
    IF OLD.ESTADO = 1 AND NEW.ENLACE IS NOT NULL AND TRIM(NEW.ENLACE) <> '' THEN
      SET NEW.ESTADO = 2;
    END IF;
  END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
