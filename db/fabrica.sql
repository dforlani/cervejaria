-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 21-Abr-2019 às 15:32
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
-- Database: `fabrica`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `papel`
--

CREATE TABLE `papel` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `papel`
--

INSERT INTO `papel` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('estudante', 1, '', NULL, '', NULL, NULL),
('orientador', 1, '', NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `papel_hierarquia`
--

CREATE TABLE `papel_hierarquia` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `regra`
--

CREATE TABLE `regra` (
  `name` varchar(64) NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `login` varchar(20) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `sobrenome` varchar(60) NOT NULL,
  `senha` text NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`login`, `nome`, `sobrenome`, `senha`, `email`) VALUES
('asdas', 'asd', 'ads', '$2y$13$yS9HKCYQsDiOI.8T6h2HnOUiIkcusJRtT9wt7nxMzcDLu1zDeX8BS', 'aa@oi.com'),
('dforlani', 'dioo', 'go', '$2y$13$IrDA37UKDG.skqxvBf3wYOn50fkby2ELVmiNmmr42SqHf8ECktEce', 'dforlani@gmail.com'),
('novo', 'mm', 'mm', '$2y$13$oxV4Gb/B3Znu2vDsuu6ioONI6y6IsLihuje8xIWn5MlL09mICbhxG', ''),
('novo1', 'nn', 'nn', '$2y$13$HCif4ZerpL/1LU1QZx2Sku.Ms/o1f/zgB/2fo.VZ/p5OwP.q/gfT.', 'ddd@mm.com'),
('tttt', 'ttt', 'tt', '$2y$13$j0b2qE94qXksDmCl2dezVOHIzVhP0rg9AYxO7yFfjlwjLwiMXaDmG', 'tt@mmm.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_papel`
--

CREATE TABLE `usuario_papel` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario_papel`
--

INSERT INTO `usuario_papel` (`item_name`, `user_id`, `created_at`) VALUES
('estudante', 'asdas', NULL),
('estudante', 'tttt', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `papel`
--
ALTER TABLE `papel`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `papel_hierarquia`
--
ALTER TABLE `papel_hierarquia`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `regra`
--
ALTER TABLE `regra`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`login`);

--
-- Indexes for table `usuario_papel`
--
ALTER TABLE `usuario_papel`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assignment_user_id_idx` (`user_id`);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `papel`
--
ALTER TABLE `papel`
  ADD CONSTRAINT `papel_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `regra` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `papel_hierarquia`
--
ALTER TABLE `papel_hierarquia`
  ADD CONSTRAINT `papel_hierarquia_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `papel` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `papel_hierarquia_ibfk_2` FOREIGN KEY (`child`) REFERENCES `papel` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `usuario_papel`
--
ALTER TABLE `usuario_papel`
  ADD CONSTRAINT `usuario_papel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_papel_ibfk_2` FOREIGN KEY (`item_name`) REFERENCES `papel` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
