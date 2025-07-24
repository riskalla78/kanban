-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/07/2025 às 00:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `kanban_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `tarefa_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('A_FAZER','FAZENDO','REVISANDO','CONCLUIDO') DEFAULT 'A_FAZER',
  `prioridade` enum('BAIXA','MEDIA','ALTA') DEFAULT 'MEDIA',
  `data_criacao` datetime DEFAULT current_timestamp(),
  `data_conclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefas`
--

INSERT INTO `tarefas` (`tarefa_id`, `usuario_id`, `titulo`, `descricao`, `status`, `prioridade`, `data_criacao`, `data_conclusao`) VALUES
(8, 4, 'wewew', 'dewdfwdwf', 'A_FAZER', 'MEDIA', '2025-07-23 16:33:31', NULL),
(9, 4, 'wewew', 'dewdfwdwf', 'A_FAZER', 'MEDIA', '2025-07-23 16:33:41', NULL),
(10, 4, 'sdsds', 'sdsdsdsdsd', 'A_FAZER', 'MEDIA', '2025-07-23 16:34:55', NULL),
(11, 4, 'fdfdfdg', 'gddgdgdg', 'A_FAZER', 'MEDIA', '2025-07-23 16:38:26', NULL),
(14, 4, 'efgefef', 'hihihhihi', 'A_FAZER', 'ALTA', '2025-07-23 16:42:11', NULL),
(15, 4, 'sdsfsf', 'sfsfsfsfsf', 'CONCLUIDO', 'MEDIA', '2025-07-23 16:52:14', '2025-07-23 17:56:55'),
(16, 4, 'rgrgr', 'grrgrgrgrg', 'CONCLUIDO', 'MEDIA', '2025-07-23 16:55:03', '2025-07-23 17:43:12'),
(21, 6, 'só gerente', 'asfsadasfd', 'A_FAZER', 'MEDIA', '2025-07-23 17:11:06', NULL),
(22, 6, 'oklkl', 'ihojoojooj', 'A_FAZER', 'BAIXA', '2025-07-23 18:23:40', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_historico`
--

CREATE TABLE `tarefa_historico` (
  `historico_id` int(11) NOT NULL,
  `tarefa_id` int(11) NOT NULL,
  `status_anterior` enum('A_FAZER','FAZENDO','REVISANDO','CONCLUIDO') DEFAULT NULL,
  `novo_status` enum('A_FAZER','FAZENDO','REVISANDO','CONCLUIDO') NOT NULL,
  `data_mudanca` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('COMUM','GERENTE') DEFAULT 'COMUM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nome`, `email`, `senha`, `tipo`) VALUES
(4, 'Matheus Riskalla', 'matheus.riskalla78@gmail.com', '$2y$10$.0sOQfCp4xTzn3SltdIR1OVBRcpKinyQUeCqd7gGEWWAsQghwnuUW', 'COMUM'),
(6, 'Administrador', 'admin@kanban.com', '$2y$10$ANwLrEwCeSUBFlMHqUB/QOpzOp3QrTSJjm7iHpEjKWW.rwL4gWEOu', 'GERENTE');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`tarefa_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tarefa_historico`
--
ALTER TABLE `tarefa_historico`
  ADD PRIMARY KEY (`historico_id`),
  ADD KEY `tarefa_id` (`tarefa_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `tarefa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tarefa_historico`
--
ALTER TABLE `tarefa_historico`
  MODIFY `historico_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tarefas`
--
ALTER TABLE `tarefas`
  ADD CONSTRAINT `tarefas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tarefa_historico`
--
ALTER TABLE `tarefa_historico`
  ADD CONSTRAINT `tarefa_historico_ibfk_1` FOREIGN KEY (`tarefa_id`) REFERENCES `tarefas` (`tarefa_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarefa_historico_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
