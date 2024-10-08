-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 19-Set-2024 às 13:19
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ca`
--
CREATE DATABASE IF NOT EXISTS `ca` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ca`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `calendario`
--

DROP TABLE IF EXISTS `calendario`;
CREATE TABLE IF NOT EXISTS `calendario` (
  `id_evento` int(100) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `horario` time(6) NOT NULL,
  `cor` varchar(7) NOT NULL,
  `inicio` datetime NOT NULL,
  `fim_horario` time NOT NULL,
  PRIMARY KEY (`id_evento`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `calendario`
--

INSERT INTO `calendario` (`id_evento`, `titulo`, `horario`, `cor`, `inicio`, `fim_horario`) VALUES
(2, 'ww', '00:00:02.000000', 'aa', '2011-11-11 00:00:00', '00:00:00'),
(3, 'ee', '11:11:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(4, 'we', '23:33:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(5, 'qe', '12:22:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(6, 'we', '03:33:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(7, 'we', '08:30:00.000000', '#007bff', '2024-09-04 00:00:00', '00:00:00'),
(8, 'we', '10:15:00.000000', '#007bff', '2024-09-06 00:00:00', '00:00:00'),
(9, 'we', '08:30:00.000000', '#007bff', '2024-09-06 00:00:00', '00:00:00'),
(10, 'ee', '13:10:00.000000', '#007bff', '2024-09-06 00:00:00', '00:00:00'),
(11, 'we', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(12, 'wewe', '08:30:00.000000', '#007bff', '2024-09-12 00:00:00', '00:00:00'),
(13, 'ee', '13:10:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(14, 'we', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(15, 'we', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(16, 'qe', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(17, 'we', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(18, 'we', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '00:00:00'),
(19, 'we', '08:30:00.000000', '#007bff', '2024-09-05 00:00:00', '11:11:00'),
(20, 'aeea', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '09:40:00'),
(21, 'aeea', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '09:40:00'),
(22, 'aeea', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '09:40:00'),
(23, 'marcelao', '17:50:00.000000', '#007bff', '2024-09-27 00:00:00', '18:25:00'),
(24, 'aeea', '08:30:00.000000', '#007bff', '2024-09-07 00:00:00', '09:05:00'),
(25, 'aeea', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '09:40:00'),
(26, 'marcelao', '09:40:00.000000', '#007bff', '2024-09-06 00:00:00', '00:00:02'),
(27, 'marcelao', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '00:00:02'),
(28, 'aeea', '10:15:00.000000', '#007bff', '2024-09-06 00:00:00', '00:00:02'),
(29, 'marcelao', '09:05:00.000000', '#007bff', '2024-09-07 00:00:00', '00:00:02'),
(30, 'marcelao', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '09:40:00'),
(31, 'aeea', '09:05:00.000000', '#007bff', '2024-09-06 00:00:00', '09:40:00'),
(32, 'marcelao', '09:05:00.000000', '#007bff', '2024-09-12 00:00:00', '09:40:00'),
(33, 'marcelao', '09:05:00.000000', '#007bff', '2024-09-13 00:00:00', '09:40:00'),
(34, 'sa', '10:15:00.000000', '#007bff', '2024-09-09 00:00:00', '10:50:00'),
(35, 'oi', '10:15:00.000000', '#007bff', '2024-09-15 00:00:00', '10:50:00'),
(36, 'tr', '09:40:00.000000', '#007bff', '2024-09-02 00:00:00', '10:15:00'),
(37, 'oi', '10:15:00.000000', '#007bff', '2024-09-09 00:00:00', '10:50:00'),
(38, 'oi', '10:15:00.000000', '#007bff', '2024-09-02 00:00:00', '10:50:00'),
(39, 'tr', '11:25:00.000000', '#007bff', '2024-09-03 00:00:00', '12:00:00'),
(40, 't', '10:50:00.000000', '#007bff', '2024-09-09 00:00:00', '11:25:00'),
(41, '54', '10:15:00.000000', '#007bff', '2024-09-25 00:00:00', '10:50:00'),
(42, '5', '09:40:00.000000', '#007bff', '2024-09-10 00:00:00', '10:15:00'),
(43, 'oi', '09:40:00.000000', '#007bff', '2024-09-11 00:00:00', '10:15:00'),
(44, 'u', '16:40:00.000000', '#007bff', '2024-09-13 00:00:00', '17:15:00'),
(45, 'f', '11:25:00.000000', '#007bff', '2024-09-07 00:00:00', '12:00:00'),
(46, 'u', '08:30:00.000000', '#007bff', '2024-09-22 00:00:00', '09:05:00'),
(47, 'oi', '10:50:00.000000', '#007bff', '2024-09-10 00:00:00', '11:25:00'),
(48, 'u', '13:10:00.000000', '#007bff', '2024-09-12 00:00:00', '13:45:00'),
(49, 'u57', '16:40:00.000000', '#007bff', '2024-09-09 00:00:00', '17:15:00'),
(50, 'oi', '08:30:00.000000', '#007bff', '2024-09-10 00:00:00', '09:05:00'),
(51, 'u57', '10:50:00.000000', '#007bff', '2024-09-12 00:00:00', '11:25:00'),
(52, 'oi', '09:40:00.000000', '#007bff', '2024-10-03 00:00:00', '10:15:00'),
(53, '23', '11:25:00.000000', '#007bff', '2024-10-03 00:00:00', '12:00:00'),
(54, 'w23', '15:30:00.000000', '#007bff', '2024-10-03 00:00:00', '16:05:00'),
(55, 'yh6sr', '10:15:00.000000', '#007bff', '2024-09-08 00:00:00', '10:50:00'),
(56, 'oi', '16:05:00.000000', '#007bff', '2024-09-15 00:00:00', '16:40:00'),
(57, 'oi', '10:50:00.000000', '#007bff', '2024-09-17 00:00:00', '11:25:00'),
(58, 'u', '16:05:00.000000', '#007bff', '2024-09-10 00:00:00', '16:40:00'),
(59, 'yh6sr', '17:15:00.000000', '#007bff', '2024-09-10 00:00:00', '17:50:00'),
(60, 'oi', '13:10:00.000000', '#007bff', '2024-09-10 00:00:00', '13:45:00'),
(61, 'rr', '11:25:00.000000', '#007bff', '2024-09-18 00:00:00', '12:00:00'),
(62, 'tt', '10:15:00.000000', '#007bff', '2024-10-04 00:00:00', '10:50:00'),
(63, 'oi', '10:15:00.000000', '#007bff', '2024-10-23 00:00:00', '10:50:00'),
(64, 'yh6sr', '10:15:00.000000', '#007bff', '2024-11-03 00:00:00', '10:50:00'),
(65, 'oi', '10:50:00.000000', '#007bff', '2024-10-27 00:00:00', '11:25:00'),
(66, 'yh6sr', '09:05:00.000000', '#007bff', '2024-10-25 00:00:00', '09:40:00'),
(67, 'oi', '09:40:00.000000', '#007bff', '2024-10-29 00:00:00', '10:15:00'),
(68, 'h', '17:15:00.000000', '#007bff', '2024-10-20 00:00:00', '17:50:00'),
(69, 'oi', '15:30:00.000000', '#007bff', '2024-12-03 00:00:00', '16:05:00'),
(70, 'oi', '10:50:00.000000', '#007bff', '2024-12-03 00:00:00', '11:25:00'),
(71, 'yh6sr', '10:15:00.000000', '#007bff', '2024-11-19 00:00:00', '10:50:00'),
(72, 'oi', '13:10:00.000000', '#007bff', '2024-10-16 00:00:00', '13:45:00'),
(73, 'yh6sr', '11:25:00.000000', '#007bff', '2024-10-25 00:00:00', '12:00:00'),
(74, 'oi', '11:25:00.000000', '#007bff', '2024-10-22 00:00:00', '12:00:00'),
(75, 'yh6sr', '14:20:00.000000', '#007bff', '2024-10-30 00:00:00', '14:55:00'),
(76, 'e', '14:55:00.000000', '#007bff', '2024-10-16 00:00:00', '15:30:00'),
(77, 'yh6sr', '13:10:00.000000', '#007bff', '2024-11-13 00:00:00', '13:45:00'),
(78, 'yh6sr', '10:50:00.000000', '#007bff', '2024-11-07 00:00:00', '11:25:00'),
(79, 'u57', '13:10:00.000000', '#007bff', '2024-11-27 00:00:00', '13:45:00'),
(80, 'u57', '14:20:00.000000', '#007bff', '2024-09-28 00:00:00', '14:55:00'),
(81, 'yh6sr', '11:25:00.000000', '#007bff', '2024-10-24 00:00:00', '12:00:00'),
(82, 'u57', '11:25:00.000000', '#ff00ea', '2024-10-31 00:00:00', '12:00:00'),
(83, 'tyuu', '11:25:00.000000', '#ff0033', '2024-11-02 00:00:00', '12:00:00'),
(84, 'sa', '10:50:00.000000', '#3700ff', '2024-11-01 00:00:00', '11:25:00'),
(85, 'yh6sr', '10:50:00.000000', '#6600ff', '2024-12-29 00:00:00', '11:25:00'),
(86, '3', '10:50:00.000000', '#0400ff', '2024-12-30 00:00:00', '11:25:00'),
(87, 'yh6sr', '15:30:00.000000', '#ff0000', '2025-01-04 00:00:00', '16:05:00'),
(88, 'yh6sr', '10:15:00.000000', '#ff9500', '2025-01-03 00:00:00', '10:50:00'),
(89, 'yh6sr', '10:50:00.000000', '#fff700', '2025-01-02 00:00:00', '11:25:00'),
(90, 'u57', '10:15:00.000000', '#007bff', '2024-12-31 00:00:00', '10:50:00'),
(91, 'u57', '11:25:00.000000', '#48c71a', '2025-01-01 00:00:00', '12:00:00'),
(92, 'yr65', '14:55:00.000000', '#ffddad', '2024-12-29 00:00:00', '15:30:00'),
(93, 'u57', '10:50:00.000000', '#007bff', '2024-11-23 00:00:00', '11:25:00'),
(94, 'u57', '08:30:00.000000', '#007bff', '2024-11-14 00:00:00', '09:05:00'),
(95, 'yh6sr', '13:10:00.000000', '#007bff', '2024-11-29 00:00:00', '13:45:00'),
(96, 'yr65', '10:50:00.000000', '#007bff', '2024-12-18 00:00:00', '11:25:00'),
(97, 'csfs', '09:40:00.000000', '#007bff', '2025-02-12 00:00:00', '10:15:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `teste`
--

DROP TABLE IF EXISTS `teste`;
CREATE TABLE IF NOT EXISTS `teste` (
  `id_evento` int(100) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `horario` int(100) NOT NULL,
  `cor` varchar(7) NOT NULL,
  `inicio` varchar(100) NOT NULL,
  `fim` varchar(100) NOT NULL,
  PRIMARY KEY (`id_evento`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `teste`
--

INSERT INTO `teste` (`id_evento`, `titulo`, `horario`, `cor`, `inicio`, `fim`) VALUES
(2, 'd', 2, '', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
