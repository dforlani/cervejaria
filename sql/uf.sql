-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 19-Fev-2020 às 00:55
-- Versão do servidor: 10.1.38-MariaDB
-- versão do PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fabrica-13-02-2020`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `uf`
--

CREATE TABLE `uf` (
  `cod_uf` char(2) NOT NULL,
  `nome_uf` varchar(45) NOT NULL,
  `sigla_uf` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `uf`
--

INSERT INTO `uf` (`cod_uf`, `nome_uf`, `sigla_uf`) VALUES
('11', 'RONDÔNIA', 'RO'),
('12', 'ACRE', 'AC'),
('13', 'AMAZONAS', 'AM'),
('14', 'RORAIMA', 'RR'),
('15', 'PARÁ', 'PA'),
('16', 'AMAPÁ', 'AP'),
('17', 'TOCANTINS', 'TO'),
('21', 'MARANHÃO', 'MA'),
('22', 'PIAUÍ', 'PI'),
('23', 'CEARÁ', 'CE'),
('24', 'RIO GRANDE DO NORTE', 'RN'),
('25', 'PARAÍBA', 'PB'),
('26', 'PERNAMBUCO', 'PE'),
('27', 'ALAGOAS', 'AL'),
('28', 'SERGIPE', 'SE'),
('29', 'BAHIA', 'BA'),
('31', 'MINAS GERAIS', 'MG'),
('32', 'ESPIRITO SANTO', 'ES'),
('33', 'RIO DE JANEIRO', 'RJ'),
('35', 'SÃO PAULO', 'SP'),
('41', 'PARANÁ', 'PR'),
('42', 'SANTA CATARINA', 'SC'),
('43', 'RIO GRANDE DO SUL', 'RS'),
('50', 'MATO GROSSO DO SUL', 'MS'),
('51', 'MATO GROSSO', 'MT'),
('52', 'GOIÁS', 'GO'),
('53', 'DISTRITO FEDERAL', 'DF');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uf`
--
ALTER TABLE `uf`
  ADD PRIMARY KEY (`cod_uf`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
