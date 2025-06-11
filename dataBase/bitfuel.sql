-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Tempo de geração: 11/06/2025 às 13:33
-- Versão do servidor: 9.2.0
-- Versão do PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bitfuel`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `cost` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `cost`, `created_at`, `updated_at`) VALUES
(1, 'Filtro de óleo', 'VEI-MOT-001', 25.9, '2025-06-11 00:45:31', '2025-06-11 00:45:31'),
(2, 'Vela de ignição', 'VEI-MOT-002', 18.5, '2025-06-11 00:45:42', '2025-06-11 00:45:42'),
(3, 'Correia dentada', 'VEI-MOT-003', 85, '2025-06-11 00:46:58', '2025-06-11 00:46:58'),
(4, 'Pastilha de freio dianteira', 'VEI-FRE-001', 52, '2025-06-11 00:47:09', '2025-06-11 00:47:09'),
(5, 'Disco de freio traseiro', 'VEI-FRE-002', 96.4, '2025-06-11 00:47:20', '2025-06-11 00:47:20'),
(6, 'Amortecedor dianteiro direito', 'VEI-SUS-001', 145.9, '2025-06-11 00:51:13', '2025-06-11 00:51:13'),
(7, 'Molas helicoidais traseiras', 'VEI-SUS-002', 112.7, '2025-06-11 00:51:35', '2025-06-11 00:51:35'),
(8, 'Caixa de direção', 'VEI-TRM-001', 275, '2025-06-11 00:51:43', '2025-06-11 00:51:43'),
(9, 'Braço da suspensão inferior', 'VEI-TRM-002', 132.8, '2025-06-11 00:51:52', '2025-06-11 00:51:52'),
(10, 'Tampa do tanque de combustível', 'VEI-CMB-001', 23.4, '2025-06-11 00:52:03', '2025-06-11 00:52:03'),
(11, 'Bomba de combustível elétrica', 'VEI-CMB-002', 198.5, '2025-06-11 00:52:07', '2025-06-11 00:52:07'),
(12, 'Farol dianteiro esquerdo', 'VEI-LUZ-001', 235, '2025-06-11 00:52:16', '2025-06-11 00:52:16'),
(13, 'Lanterna traseira direita', 'VEI-LUZ-002', 118, '2025-06-11 00:52:23', '2025-06-11 00:52:23'),
(14, 'Volante com comando de som', 'VEI-INT-001', 289.9, '2025-06-11 00:52:28', '2025-06-11 00:52:28'),
(15, 'Painel de instrumentos digital', 'VEI-INT-002', 890, '2025-06-11 00:52:35', '2025-06-11 00:52:35'),
(16, 'Condensador do ar condicionado', 'VEI-ARF-001', 320, '2025-06-11 00:52:43', '2025-06-11 00:52:43'),
(17, 'Ventoinha do radiador', 'VEI-ARF-002', 210.75, '2025-06-11 00:52:48', '2025-06-11 00:52:48'),
(18, 'Silencioso traseiro', 'VEI-ESC-001', 167.9, '2025-06-11 00:52:57', '2025-06-11 00:52:57'),
(19, 'Catalisador', 'VEI-ESC-002', 345, '2025-06-11 00:53:06', '2025-06-11 00:53:06'),
(20, 'Alternador', 'VEI-ELT-001', 389, '2025-06-11 00:53:12', '2025-06-11 00:53:12'),
(21, 'Bateria 60Ah', 'VEI-ELT-002', 370, '2025-06-11 00:53:24', '2025-06-11 00:53:24'),
(22, 'Sensor de rotação', 'VEI-SEN-001', 78.5, '2025-06-11 00:53:38', '2025-06-11 00:53:38'),
(23, 'Bobina de ignição', 'VEI-MOT-004', 135, '2025-06-11 00:53:45', '2025-06-11 00:53:45'),
(24, 'Radiador do motor', 'VEI-ARF-003', 320, '2025-06-11 00:53:53', '2025-06-11 00:53:53'),
(25, 'Reservatório de expansão', 'VEI-ARF-004', 65, '2025-06-11 00:54:02', '2025-06-11 00:54:02'),
(26, 'Sensor de temperatura', 'VEI-SEN-002', 45, '2025-06-11 00:54:09', '2025-06-11 00:54:09'),
(27, 'Embreagem completa', 'VEI-TRN-001', 480, '2025-06-11 00:54:16', '2025-06-11 00:54:16'),
(28, 'Filtro de ar', 'VEI-MOT-005', 34.9, '2025-06-11 00:54:23', '2025-06-11 00:54:23'),
(29, 'Filtro de combustível', 'VEI-MOT-006', 29.9, '2025-06-11 00:54:29', '2025-06-11 00:54:29'),
(30, 'Eixo homocinético', 'VEI-TRN-002', 240, '2025-06-11 00:54:37', '2025-06-11 00:54:37'),
(31, 'Parabrisa dianteiro', 'VEI-VID-001', 420, '2025-06-11 00:54:45', '2025-06-11 00:54:45'),
(32, 'Retrovisor elétrico direito', 'VEI-EXT-001', 210, '2025-06-11 00:54:52', '2025-06-11 00:54:52'),
(33, 'Para-choque dianteiro', 'VEI-EXT-002', 315, '2025-06-11 00:54:58', '2025-06-11 00:54:58'),
(34, 'Sensor de ré', 'VEI-SEN-003', 95, '2025-06-11 00:55:04', '2025-06-11 00:55:04'),
(35, 'Coxim do motor', 'VEI-MOT-007', 110, '2025-06-11 00:55:11', '2025-06-11 00:55:11'),
(36, 'Motor do limpador de para-brisa', 'VEI-ELT-003', 189.9, '2025-06-11 00:55:21', '2025-06-11 00:55:21'),
(37, 'Interruptor da luz de freio', 'VEI-ELT-004', 38.5, '2025-06-11 00:55:28', '2025-06-11 00:55:28'),
(38, 'Sensor de oxigênio (sonda lambda)', 'VEI-SEN-004', 160, '2025-06-11 00:55:34', '2025-06-11 00:55:34'),
(39, 'Chave de seta', 'VEI-ELT-005', 145, '2025-06-11 00:55:42', '2025-06-11 00:55:42'),
(40, 'Painel de porta dianteiro esquerdo', 'VEI-INT-003', 390, '2025-06-11 00:55:48', '2025-06-11 00:55:48'),
(41, 'Unidade de comando eletrônico (ECU)', 'VEI-ELT-006', 1150, '2025-06-11 01:17:31', '2025-06-11 01:17:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `stock`
--

CREATE TABLE `stock` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` double NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

--
-- Despejando dados para a tabela `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `quantity`, `updated_at`) VALUES
(1, 1, 10, '2025-06-11 01:57:47'),
(2, 2, 0, '2025-06-11 00:45:42'),
(3, 3, 0, '2025-06-11 00:46:58'),
(4, 4, 0, '2025-06-11 00:47:09'),
(5, 5, 10, '2025-06-11 00:47:20'),
(6, 6, 0, '2025-06-11 00:51:13'),
(7, 7, 10, '2025-06-11 00:51:35'),
(8, 8, 0, '2025-06-11 00:51:43'),
(9, 9, 0, '2025-06-11 00:51:52'),
(10, 10, 0, '2025-06-11 00:52:03'),
(11, 11, 0, '2025-06-11 00:52:07'),
(12, 12, 10, '2025-06-11 00:52:16'),
(13, 13, 0, '2025-06-11 00:52:23'),
(14, 14, 0, '2025-06-11 00:52:28'),
(15, 15, 0, '2025-06-11 00:52:35'),
(16, 16, 0, '2025-06-11 00:52:43'),
(17, 17, 0, '2025-06-11 00:52:48'),
(18, 18, 10, '2025-06-11 00:52:57'),
(19, 19, 0, '2025-06-11 00:53:06'),
(20, 20, 0, '2025-06-11 00:53:12'),
(21, 21, 0, '2025-06-11 00:53:24'),
(22, 22, 0, '2025-06-11 00:53:38'),
(23, 23, 10, '2025-06-11 00:53:45'),
(24, 24, 10, '2025-06-11 00:53:53'),
(25, 25, 10, '2025-06-11 00:54:02'),
(26, 26, 0, '2025-06-11 00:54:09'),
(27, 27, 0, '2025-06-11 00:54:16'),
(28, 28, 0, '2025-06-11 00:54:23'),
(29, 29, 0, '2025-06-11 00:54:29'),
(30, 30, 0, '2025-06-11 00:54:37'),
(31, 31, 0, '2025-06-11 00:54:45'),
(32, 32, 0, '2025-06-11 00:54:52'),
(33, 33, 0, '2025-06-11 00:54:58'),
(34, 34, 0, '2025-06-11 00:55:04'),
(35, 35, 10, '2025-06-11 00:55:11'),
(36, 36, 5, '2025-06-11 00:55:21'),
(37, 37, 0, '2025-06-11 00:55:28'),
(38, 38, 0, '2025-06-11 00:55:34'),
(39, 39, 0, '2025-06-11 00:55:42'),
(40, 40, 0, '2025-06-11 00:55:48'),
(41, 41, 0, '2025-06-11 01:17:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `type` varchar(1) NOT NULL COMMENT 'E = Entrada | S = Saída',
  `quantity` double NOT NULL,
  `date` timestamp NOT NULL
) ENGINE=InnoDB;

--
-- Despejando dados para a tabela `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `quantity`, `date`) VALUES
(1, 1, 'E', 10, '2025-06-11 00:31:00'),
(2, 5, 'E', 10, '2025-06-11 00:20:00'),
(3, 7, 'E', 10, '2025-06-11 00:35:00'),
(4, 12, 'E', 10, '2025-06-11 00:16:00'),
(5, 18, 'E', 10, '2025-06-11 00:57:00'),
(6, 23, 'E', 10, '2025-06-11 00:45:00'),
(7, 24, 'E', 10, '2025-06-11 00:53:00'),
(8, 25, 'E', 10, '2025-06-11 00:02:00'),
(9, 35, 'E', 10, '2025-06-11 00:11:00'),
(10, 36, 'E', 5, '2025-06-11 00:21:00'),
(11, 1, 'E', 1, '2025-06-11 01:42:00'),
(12, 1, 'E', 1, '2025-06-11 01:02:00'),
(13, 1, 'S', 1, '2025-06-11 01:24:00'),
(14, 1, 'S', 1, '2025-06-11 01:47:00');


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices de tabela `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_products`
--

CREATE OR REPLACE VIEW `vw_products`  AS SELECT `products`.`id` AS `id`, `stock`.`id` AS `id_stock`, `products`.`name` AS `name`, `products`.`cost` AS `cost`, `stock`.`quantity` AS `quantity`, `products`.`sku` AS `sku`, `products`.`created_at` AS `created_at`, `products`.`updated_at` AS `updated_at`, `stock`.`updated_at` AS `stock_updated_at` FROM (`products` left join `stock` on((`stock`.`product_id` = `products`.`id`))) ;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
