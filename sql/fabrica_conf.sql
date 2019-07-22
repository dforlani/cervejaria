-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16-Jul-2019 Ã s 14:37
-- VersÃ£o do servidor: 10.1.38-MariaDB
-- versÃ£o do PHP: 7.3.4

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
-- Estrutura da tabela `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `pk_cliente` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(200) DEFAULT NULL,
  `telefone` char(10) DEFAULT NULL,
  `cpf` char(14) DEFAULT NULL,
  `dt_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comanda`
--

CREATE TABLE IF NOT EXISTS `comanda` (
  `pk_comanda` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `numero` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `entrada`
--

CREATE TABLE IF NOT EXISTS `entrada` (
  `pk_entrada` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fk_usuario` varchar(20) NOT NULL,
  `fk_produto` int(11) DEFAULT NULL,
  `quantidade` float DEFAULT NULL,
  `dt_entrada` date DEFAULT NULL,
  `custo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_venda`
--

CREATE TABLE IF NOT EXISTS `item_venda` (
pk_item_venda  int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fk_venda` int(11) NOT NULL,
  `fk_preco` int(11) NOT NULL,
  `quantidade` float NOT NULL,
  `preco_unitario` decimal(10,2) DEFAULT NULL,
  `preco_final` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `papel`
--

CREATE TABLE IF NOT EXISTS `papel` (
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


-- --------------------------------------------------------

--
-- Estrutura da tabela `papel_hierarquia`
--

CREATE TABLE IF NOT EXISTS `papel_hierarquia` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `preco`
--

CREATE TABLE IF NOT EXISTS `preco` (
  `pk_preco` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fk_produto` int(11) NOT NULL,
  `denominacao` varchar(100) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `quantidade` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE IF NOT EXISTS `produto` (
  `pk_produto` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `estoque` float DEFAULT NULL,
  `unidade_medida` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produto`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `regra`
--
CREATE TABLE IF NOT EXISTS `regra` (
  `name` varchar(64) NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `login` varchar(20) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `sobrenome` varchar(60) NOT NULL,
  `senha` text NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_papel`
--

CREATE TABLE IF NOT EXISTS `usuario_papel` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario_papel`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `venda`
--

CREATE TABLE IF NOT EXISTS `venda` (
  `pk_venda` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fk_cliente` int(11) DEFAULT NULL,
  `fk_comanda` int(11) DEFAULT NULL,
  `fk_usuario_iniciou_venda` varchar(20) DEFAULT NULL,
  `fk_usuario_recebeu_pagamento` varchar(20) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT NULL,
  `valor_final` decimal(10,2) DEFAULT NULL,
  `estado` enum('aberta','fiado','paga') DEFAULT NULL,
  `dt_venda` date DEFAULT NULL,
  `dt_pagamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--


--
-- Indexes for table `entrada`
--
ALTER TABLE `entrada`
  ADD KEY `fk_produto` (`fk_produto`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Indexes for table `item_venda`
--
ALTER TABLE `item_venda`
  ADD KEY `fk_preco` (`fk_preco`),
  ADD KEY `fk_venda` (`fk_venda`) USING BTREE;

--
-- Indexes for table `papel`
--
ALTER TABLE `papel`
  ADD PRIMARY KEY (`name`) ,
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `papel_hierarquia`
--
ALTER TABLE `papel_hierarquia`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `preco`
--
ALTER TABLE `preco`
  ADD KEY `fk_produto` (`fk_produto`);

--


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
-- Indexes for table `venda`
--
ALTER TABLE `venda`
  ADD KEY `fk_cliente` (`fk_cliente`),
  ADD KEY `fk_comanda` (`fk_comanda`),
  ADD KEY `fk_usuario_iniciou_venda` (`fk_usuario_iniciou_venda`),
  ADD KEY `fk_usuario_recebeu_pagamento` (`fk_usuario_recebeu_pagamento`);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`fk_produto`) REFERENCES `produto` (`pk_produto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `item_venda`
--
ALTER TABLE `item_venda`
  ADD CONSTRAINT `item_venda_ibfk_1` FOREIGN KEY (`fk_venda`) REFERENCES `venda` (`pk_venda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_venda_ibfk_2` FOREIGN KEY (`fk_preco`) REFERENCES `preco` (`pk_preco`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Limitadores para a tabela `preco`
--
ALTER TABLE `preco`
  ADD CONSTRAINT `preco_ibfk_1` FOREIGN KEY (`fk_produto`) REFERENCES `produto` (`pk_produto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `usuario_papel`
--
ALTER TABLE `usuario_papel`
  ADD CONSTRAINT `usuario_papel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_papel_ibfk_2` FOREIGN KEY (`item_name`) REFERENCES `papel` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`fk_cliente`) REFERENCES `cliente` (`pk_cliente`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`fk_comanda`) REFERENCES `comanda` (`pk_comanda`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `venda_ibfk_3` FOREIGN KEY (`fk_usuario_iniciou_venda`) REFERENCES `usuario` (`login`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `venda_ibfk_4` FOREIGN KEY (`fk_usuario_recebeu_pagamento`) REFERENCES `usuario` (`login`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
