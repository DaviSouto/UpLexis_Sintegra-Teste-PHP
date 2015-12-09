-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 09-Dez-2015 às 18:59
-- Versão do servidor: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uplexis`
--
CREATE DATABASE IF NOT EXISTS `uplexis` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `uplexis`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sintegra`
--
-- Criação: 09-Dez-2015 às 05:34
--

CREATE TABLE IF NOT EXISTS `sintegra` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id do cadastro',
  `cnpj` varchar(18) NOT NULL COMMENT 'CNPJ',
  `inscEstadual` varchar(15) NOT NULL COMMENT 'Inscrição estadual',
  `razaoSocial` varchar(255) NOT NULL COMMENT 'Razão social',
  `logradouro` varchar(50) NOT NULL COMMENT 'Logradouro',
  `numero` varchar(20) NOT NULL COMMENT 'Número',
  `complemento` varchar(20) DEFAULT NULL COMMENT 'Complemento',
  `bairro` varchar(20) NOT NULL COMMENT 'Bairro',
  `municipio` varchar(20) NOT NULL COMMENT 'Município',
  `uf` varchar(2) NOT NULL COMMENT 'UF',
  `cep` varchar(9) NOT NULL COMMENT 'Cep',
  `telefone` varchar(20) DEFAULT NULL COMMENT 'Telefone',
  `ativEconomica` varchar(100) NOT NULL COMMENT 'Atividade econômica',
  `dtaInicioAtividade` date NOT NULL COMMENT 'Data de inicio de atividade',
  `sitCadastral` varchar(20) NOT NULL COMMENT 'Situação cadastral',
  `dtaSitCadastral` date NOT NULL COMMENT 'Data desta situação cadastral',
  `regApuracao` varchar(20) NOT NULL COMMENT 'Regime de apuração',
  `dtaEmitenteDesde` date NOT NULL COMMENT 'Emitente de NFe desde',
  `dtaUltimaAtu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data da última atualização',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
