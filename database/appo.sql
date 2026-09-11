-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Set-2026 às 13:18
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `appo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcacoes`
--

CREATE TABLE `marcacoes` (
  `id_marcacao` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `id_profissional` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('Pendente','Confirmada','Cancelada') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissionais`
--

CREATE TABLE `profissionais` (
  `id_profissional` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `profissionais`
--

INSERT INTO `profissionais` (`id_profissional`, `nome`, `email`, `telefone`, `ativo`) VALUES
(1, 'Fernanda', 'fernanda@appo.pt', '912345678', 1),
(2, 'Thamires', 'thamires@appo.pt', '913456789', 1),
(3, 'Mairane', 'mairane@appo.pt', '914567890', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissional_servico`
--

CREATE TABLE `profissional_servico` (
  `id_profissional` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `profissional_servico`
--

INSERT INTO `profissional_servico` (`id_profissional`, `id_servico`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id_servico` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `duracao` int(11) NOT NULL,
  `preco` decimal(6,2) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id_servico`, `nome`, `descricao`, `duracao`, `preco`, `ativo`) VALUES
(1, 'Corte', 'Corte feminino', 45, 20.00, 1),
(2, 'Coloração', 'Coloração completa', 120, 45.00, 1),
(3, 'Brushing', 'Escova e finalização', 30, 15.00, 1),
(4, 'Tratamento', 'Tratamento capilar', 60, 25.00, 1),
(5, 'Penteado', 'Penteado para eventos', 60, 35.00, 1),
(6, 'Maquilhagem', 'Maquilhagem profissional', 45, 40.00, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome`, `email`, `password`, `telefone`, `data_registo`) VALUES
(1, 'Ana Silva', 'ana@email.com', '123456', '911111111', '2026-09-07 19:41:16'),
(2, 'João Costa', 'joao@email.com', '123456', '922222222', '2026-09-07 19:41:16'),
(3, 'Maria Oliveira', 'maria@email.com', '123456', '933333333', '2026-09-07 19:41:16');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `marcacoes`
--
ALTER TABLE `marcacoes`
  ADD PRIMARY KEY (`id_marcacao`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_profissional` (`id_profissional`),
  ADD KEY `id_servico` (`id_servico`);

--
-- Índices para tabela `profissionais`
--
ALTER TABLE `profissionais`
  ADD PRIMARY KEY (`id_profissional`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `profissional_servico`
--
ALTER TABLE `profissional_servico`
  ADD PRIMARY KEY (`id_profissional`,`id_servico`),
  ADD KEY `id_servico` (`id_servico`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id_servico`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `marcacoes`
--
ALTER TABLE `marcacoes`
  MODIFY `id_marcacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `profissionais`
--
ALTER TABLE `profissionais`
  MODIFY `id_profissional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `marcacoes`
--
ALTER TABLE `marcacoes`
  ADD CONSTRAINT `marcacoes_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON UPDATE CASCADE,
  ADD CONSTRAINT `marcacoes_ibfk_2` FOREIGN KEY (`id_profissional`) REFERENCES `profissionais` (`id_profissional`) ON UPDATE CASCADE,
  ADD CONSTRAINT `marcacoes_ibfk_3` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `profissional_servico`
--
ALTER TABLE `profissional_servico`
  ADD CONSTRAINT `profissional_servico_ibfk_1` FOREIGN KEY (`id_profissional`) REFERENCES `profissionais` (`id_profissional`),
  ADD CONSTRAINT `profissional_servico_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
