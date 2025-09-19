-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Tempo de geração: 11/07/2024 às 15:13
-- Versão do servidor: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- Versão do PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cassinoadmin`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `activity_log`
--

CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` varchar(1024) DEFAULT NULL,
  `image` varchar(1024) NOT NULL,
  `type` varchar(1024) NOT NULL DEFAULT 'carousel',
  `order_value` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `banners`
--

INSERT INTO `banners` (`id`, `action`, `image`, `type`, `order_value`, `created_at`, `updated_at`) VALUES
(20, 'https://alcateia.bet/casino/pgsoft/1543462', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/23c82f22-42a3-4ca6-0876-9d80d03a7300/banner', 'carousel', 1, NULL, NULL),
(18, 'https://alcateia.bet/casino/pgsoft/126', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/8ff264c7-07a9-430d-977c-da05f8ed1c00/banner', 'carousel', 3, NULL, NULL),
(17, 'https://alcateia.bet/casino/evolution/25559', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/96580d52-8be7-43aa-9b17-8e0e967c9a00/banner', 'carousel', 2, NULL, NULL),
(21, 'https://alcateia.bet/casino/evolution/25278', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/84d9f628-e2ac-4a12-d9f7-36651ef69100/banner', 'carousel', 1, NULL, NULL),
(28, 'https://alcateia.bet/casino/evolution/25634', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/01ae2810-0c13-472f-cb8a-dc11de613100/banner', 'desktop_action_btns', 1, NULL, NULL),
(30, 'https://alcateia.bet/casino/evolution/25278', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/d4a9892b-80b0-4ef9-c69b-8fbf23956b00/banner', 'desktop_action_btns', 2, NULL, NULL),
(31, 'https://alcateia.bet/casino/evolution/25634', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/da8b0af8-eee6-45f9-69e8-93763eb61000/banner', 'mobile_action_btns', 1, NULL, NULL),
(33, 'https://alcateia.bet/casino/evolution/25278', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/c51b8e94-7fba-4c24-e11a-4452b1ca5400/banner', 'mobile_action_btns', 2, NULL, NULL),
(41, 'https://alcateia.bet/casino/evolution/25252', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/8c919058-b4fc-4998-0785-9d2cffc26e00/banner', 'mobile_action_btns', 1, NULL, NULL),
(40, 'https://alcateia.bet/casino/evolution/25252', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/debb30c0-87ec-4def-5e49-939b17dfe700/banner', 'desktop_action_btns', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `betnet_view`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE IF NOT EXISTS `betnet_view` (
`id` bigint(21)
,`provider` varchar(255)
,`total_bet_amount` decimal(32,2)
,`total_win_amount` decimal(32,2)
,`net_loss` decimal(33,2)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `category_provider` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_id`, `category_provider`, `position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PGSoft', 1, 'PG Soft', 3, 1, '2024-06-04 21:33:37', '2024-06-23 03:44:56'),
(2, 'Evolution', 2, 'Evolution', 1, 1, '2024-06-04 21:33:37', '2024-06-23 03:44:56'),
(3, 'Spribe', 3, 'Spribe', 4, 1, '2024-06-04 21:33:37', '2024-06-23 03:45:07'),
(4, 'Pragmatic Play', 4, 'pragmatic slot', 5, 1, '2024-06-04 21:33:37', '2024-06-23 03:44:56'),
(5, 'Pragmatic Ao Vivo', 5, 'Pragmatic Play', 2, 1, '2024-06-04 21:33:37', '2024-06-23 03:44:56'),
(6, 'Ezugi', 6, 'Ezugi', 6, 1, '2024-06-04 21:33:37', '2024-06-23 03:45:20'),
(7, 'Hypetech', 7, 'Hypetech', 7, 1, '2024-06-04 21:33:37', '2024-06-23 03:44:56'),
(8, 'Jogos Iconix', NULL, 'Iconix', 8, 0, '2024-06-19 00:14:34', '2024-06-23 03:44:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `codes`
--

CREATE TABLE IF NOT EXISTS `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `amountactive` int(11) NOT NULL,
  `activate` int(11) NOT NULL,
  `tipobonus` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_name` text DEFAULT NULL,
  `websiteurl` text DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `activate_bonus` int(11) NOT NULL,
  `value_bonus` int(11) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `logomarca` varchar(1024) NOT NULL,
  `minimodeposit` int(11) NOT NULL,
  `minimosaque` int(11) NOT NULL,
  `maxsaque` int(11) DEFAULT NULL,
  `maxautomatico` int(11) NOT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `cpa_value` double(20,2) DEFAULT NULL,
  `cpa_min` double(20,2) DEFAULT NULL,
  `ngr_percent` int(11) NOT NULL,
  `bonus_percentage` int(11) NOT NULL DEFAULT 100,
  `wallet_ggr` double(10,2) NOT NULL DEFAULT 0.00,
  `ggr_percent` int(11) NOT NULL DEFAULT 5,
  `rollover` int(11) NOT NULL DEFAULT 1,
  `base_rollover` int(11) DEFAULT NULL,
  `fb_pixel_id` varchar(1024) DEFAULT NULL,
  `gtm_id` varchar(1024) DEFAULT NULL,
  `daily_limit_whitdrawal` int(11) DEFAULT 1,
  `auto_withdraw` tinyint(4) DEFAULT 0,
  `maxauto_withdraw` int(11) DEFAULT NULL,
  `tax_active` tinyint(4) DEFAULT 0,
  `tax_withdraw` int(11) DEFAULT NULL,
  `primary_color` char(10) DEFAULT NULL,
  `cf_key` varchar(255) DEFAULT NULL COMMENT 'CloudFlare site key',
  `cf_secret` varchar(255) DEFAULT NULL COMMENT 'CloudFlare site secret',
  `test_column` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `config`
--

INSERT INTO `config` (`id`, `website_name`, `websiteurl`, `description`, `keywords`, `activate_bonus`, `value_bonus`, `favicon`, `logomarca`, `minimodeposit`, `minimosaque`, `maxsaque`, `maxautomatico`, `maintenance`, `cpa_value`, `cpa_min`, `ngr_percent`, `bonus_percentage`, `wallet_ggr`, `ggr_percent`, `rollover`, `base_rollover`, `fb_pixel_id`, `gtm_id`, `daily_limit_whitdrawal`, `auto_withdraw`, `maxauto_withdraw`, `tax_active`, `tax_withdraw`, `primary_color`, `cf_key`, `cf_secret`, `test_column`) VALUES
(1, 'Alcateia', 'https://alcateia.bet', '', '', 1, 1, 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/e76f3a96-cbd6-4071-fe68-f1ac696b5200/banner', 'https://imagedelivery.net/OUTngZrupQp0X-PFiPzvuA/5f541975-291b-40a5-14e7-9ce67d490e00/logo', 10, 2, 15, 0, 0, 5.00, 30.00, 20, 100, 70000.00, 23, 5, 1, '', '', 5, 1, 15, 0, 2, '#00ACFF', '', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `games_api`
--

CREATE TABLE IF NOT EXISTS `games_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(1024) DEFAULT NULL,
  `order_value` int(11) DEFAULT NULL,
  `distribution` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `provider_name` (`provider_name`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=1927 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `games_history`
--

CREATE TABLE IF NOT EXISTS `games_history` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_tx_id` varchar(255) NOT NULL,
  `game` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `round_id` varchar(255) NOT NULL,
  `demo` tinyint(4) NOT NULL DEFAULT 0,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `games_history`
--

INSERT INTO `games_history` (`id`, `user_id`, `amount`, `provider`, `provider_tx_id`, `game`, `action`, `round_id`, `demo`, `session_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1.00, 'PRAGMATIC', '9ea66eb5846a4d784c64858217f60801', 'vs20fruitsw', 'bet', '9ea66eb5846a4d784c64858217f60801', 0, '9ea66eb5846a4d784c64858217f60801', '2024-07-08 12:03:22', '2024-07-08 12:03:22'),
(2, 1, 1.00, 'PRAGMATIC', '9ea66eb5846a4d784c64858217f60801', 'vs20fruitsw', 'loss', '9ea66eb5846a4d784c64858217f60801', 0, '9ea66eb5846a4d784c64858217f60801', '2024-07-08 12:03:22', '2024-07-08 12:03:22'),
(3, 21, 1.00, 'PRAGMATIC', '29aeaf9d9e1829c5fa1d83219c48def6', 'vs20olympgate', 'bet', '29aeaf9d9e1829c5fa1d83219c48def6', 0, '29aeaf9d9e1829c5fa1d83219c48def6', '2024-07-08 18:30:39', '2024-07-08 18:30:39'),
(4, 21, 1.00, 'PRAGMATIC', '29aeaf9d9e1829c5fa1d83219c48def6', 'vs20olympgate', 'loss', '29aeaf9d9e1829c5fa1d83219c48def6', 0, '29aeaf9d9e1829c5fa1d83219c48def6', '2024-07-08 18:30:39', '2024-07-08 18:30:39'),
(5, 21, 1.00, 'PRAGMATIC', '99bd05948c217a98b1322ff2d5e4ff5d', 'vs20olympgate', 'bet', '99bd05948c217a98b1322ff2d5e4ff5d', 0, '99bd05948c217a98b1322ff2d5e4ff5d', '2024-07-08 18:30:48', '2024-07-08 18:30:48'),
(6, 21, 1.00, 'PRAGMATIC', '99bd05948c217a98b1322ff2d5e4ff5d', 'vs20olympgate', 'loss', '99bd05948c217a98b1322ff2d5e4ff5d', 0, '99bd05948c217a98b1322ff2d5e4ff5d', '2024-07-08 18:30:48', '2024-07-08 18:30:48'),
(7, 21, 1.00, 'PRAGMATIC', '818be8b100efa7998ca6e059674a060b', 'vs20olympgate', 'bet', '818be8b100efa7998ca6e059674a060b', 0, '818be8b100efa7998ca6e059674a060b', '2024-07-08 18:30:57', '2024-07-08 18:30:57'),
(8, 21, 1.00, 'PRAGMATIC', '818be8b100efa7998ca6e059674a060b', 'vs20olympgate', 'loss', '818be8b100efa7998ca6e059674a060b', 0, '818be8b100efa7998ca6e059674a060b', '2024-07-08 18:30:57', '2024-07-08 18:30:57'),
(9, 21, 1.00, 'PRAGMATIC', '6a3d52c733036cbc7a9689a6c87f0d8d', 'vs20olympgate', 'bet', '6a3d52c733036cbc7a9689a6c87f0d8d', 0, '6a3d52c733036cbc7a9689a6c87f0d8d', '2024-07-08 18:31:02', '2024-07-08 18:31:02'),
(10, 21, 1.00, 'PRAGMATIC', '6a3d52c733036cbc7a9689a6c87f0d8d', 'vs20olympgate', 'loss', '6a3d52c733036cbc7a9689a6c87f0d8d', 0, '6a3d52c733036cbc7a9689a6c87f0d8d', '2024-07-08 18:31:02', '2024-07-08 18:31:02'),
(11, 21, 1.00, 'PRAGMATIC', '85cb8f4c9c276890ea8d76d2bd0c4011', 'vs20olympgate', 'bet', '85cb8f4c9c276890ea8d76d2bd0c4011', 0, '85cb8f4c9c276890ea8d76d2bd0c4011', '2024-07-08 18:31:06', '2024-07-08 18:31:06'),
(12, 21, 1.00, 'PRAGMATIC', '85cb8f4c9c276890ea8d76d2bd0c4011', 'vs20olympgate', 'loss', '85cb8f4c9c276890ea8d76d2bd0c4011', 0, '85cb8f4c9c276890ea8d76d2bd0c4011', '2024-07-08 18:31:06', '2024-07-08 18:31:06'),
(13, 21, 1.00, 'PRAGMATIC', '75d78a31209a54a370c3503ed8536840', 'vs20olympgate', 'bet', '75d78a31209a54a370c3503ed8536840', 0, '75d78a31209a54a370c3503ed8536840', '2024-07-08 18:31:26', '2024-07-08 18:31:26'),
(14, 21, 1.00, 'PRAGMATIC', '75d78a31209a54a370c3503ed8536840', 'vs20olympgate', 'loss', '75d78a31209a54a370c3503ed8536840', 0, '75d78a31209a54a370c3503ed8536840', '2024-07-08 18:31:26', '2024-07-08 18:31:26'),
(15, 21, 1.00, 'PRAGMATIC', '3a849086a36067d2e000c08a89bbd8a1', 'vs20olympgate', 'bet', '3a849086a36067d2e000c08a89bbd8a1', 0, '3a849086a36067d2e000c08a89bbd8a1', '2024-07-08 18:31:30', '2024-07-08 18:31:30'),
(16, 21, 1.00, 'PRAGMATIC', '3a849086a36067d2e000c08a89bbd8a1', 'vs20olympgate', 'loss', '3a849086a36067d2e000c08a89bbd8a1', 0, '3a849086a36067d2e000c08a89bbd8a1', '2024-07-08 18:31:30', '2024-07-08 18:31:30'),
(17, 21, 1.00, 'PRAGMATIC', '38d8358221a4ec168748742365b57273', 'vs20olympgate', 'bet', '38d8358221a4ec168748742365b57273', 0, '38d8358221a4ec168748742365b57273', '2024-07-08 18:31:36', '2024-07-08 18:31:36'),
(18, 21, 1.00, 'PRAGMATIC', '38d8358221a4ec168748742365b57273', 'vs20olympgate', 'loss', '38d8358221a4ec168748742365b57273', 0, '38d8358221a4ec168748742365b57273', '2024-07-08 18:31:36', '2024-07-08 18:31:36'),
(19, 21, 1.00, 'PRAGMATIC', '00a45f55231e070abfde24d289c3d00c', 'vs20olympgate', 'bet', '00a45f55231e070abfde24d289c3d00c', 0, '00a45f55231e070abfde24d289c3d00c', '2024-07-08 18:31:40', '2024-07-08 18:31:40'),
(20, 21, 1.00, 'PRAGMATIC', '00a45f55231e070abfde24d289c3d00c', 'vs20olympgate', 'loss', '00a45f55231e070abfde24d289c3d00c', 0, '00a45f55231e070abfde24d289c3d00c', '2024-07-08 18:31:40', '2024-07-08 18:31:40'),
(21, 21, 1.00, 'PRAGMATIC', '1303a6a475db7346864a0840cd194b8a', 'vs20olympgate', 'bet', '1303a6a475db7346864a0840cd194b8a', 0, '1303a6a475db7346864a0840cd194b8a', '2024-07-08 18:31:45', '2024-07-08 18:31:45'),
(22, 21, 1.00, 'PRAGMATIC', '1303a6a475db7346864a0840cd194b8a', 'vs20olympgate', 'loss', '1303a6a475db7346864a0840cd194b8a', 0, '1303a6a475db7346864a0840cd194b8a', '2024-07-08 18:31:45', '2024-07-08 18:31:45'),
(23, 21, 0.20, 'PRAGMATIC', 'c76cc451e511c370eccece618c33a71c', 'vs20olympgate', 'bet', 'c76cc451e511c370eccece618c33a71c', 0, 'c76cc451e511c370eccece618c33a71c', '2024-07-08 18:36:04', '2024-07-08 18:36:04'),
(24, 21, 0.20, 'PRAGMATIC', 'c76cc451e511c370eccece618c33a71c', 'vs20olympgate', 'loss', 'c76cc451e511c370eccece618c33a71c', 0, 'c76cc451e511c370eccece618c33a71c', '2024-07-08 18:36:04', '2024-07-08 18:36:04'),
(25, 21, 0.20, 'PRAGMATIC', '3a4d6bd6e66cd1425a2fb9475c8b832b', 'vs20olympgate', 'bet', '3a4d6bd6e66cd1425a2fb9475c8b832b', 0, '3a4d6bd6e66cd1425a2fb9475c8b832b', '2024-07-08 18:36:11', '2024-07-08 18:36:11'),
(26, 21, 0.20, 'PRAGMATIC', '3a4d6bd6e66cd1425a2fb9475c8b832b', 'vs20olympgate', 'loss', '3a4d6bd6e66cd1425a2fb9475c8b832b', 0, '3a4d6bd6e66cd1425a2fb9475c8b832b', '2024-07-08 18:36:11', '2024-07-08 18:36:11'),
(27, 21, 0.20, 'PRAGMATIC', 'c205881e36597d630de80770c288c1a7', 'vs20olympgate', 'bet', 'c205881e36597d630de80770c288c1a7', 0, 'c205881e36597d630de80770c288c1a7', '2024-07-08 18:36:20', '2024-07-08 18:36:20'),
(28, 21, 0.20, 'PRAGMATIC', 'c205881e36597d630de80770c288c1a7', 'vs20olympgate', 'loss', 'c205881e36597d630de80770c288c1a7', 0, 'c205881e36597d630de80770c288c1a7', '2024-07-08 18:36:20', '2024-07-08 18:36:20'),
(29, 21, 100.00, 'PRAGMATIC', 'c78169763244d9fe4e849fb5ee44c16b', 'vs20olympgate', 'bet', 'c78169763244d9fe4e849fb5ee44c16b', 0, 'c78169763244d9fe4e849fb5ee44c16b', '2024-07-08 18:38:36', '2024-07-08 18:38:36'),
(30, 21, 100.00, 'PRAGMATIC', 'c78169763244d9fe4e849fb5ee44c16b', 'vs20olympgate', 'loss', 'c78169763244d9fe4e849fb5ee44c16b', 0, 'c78169763244d9fe4e849fb5ee44c16b', '2024-07-08 18:38:36', '2024-07-08 18:38:36'),
(31, 21, 0.00, 'PRAGMATIC', 'c78169763244d9fe4e849fb5ee44c16b', 'vs20olympgate', 'bet', 'c78169763244d9fe4e849fb5ee44c16b', 0, 'c78169763244d9fe4e849fb5ee44c16b', '2024-07-08 18:40:18', '2024-07-08 18:40:18'),
(32, 21, 4.25, 'PRAGMATIC', 'c78169763244d9fe4e849fb5ee44c16b', 'vs20olympgate', 'win', 'c78169763244d9fe4e849fb5ee44c16b', 0, 'c78169763244d9fe4e849fb5ee44c16b', '2024-07-08 18:40:18', '2024-07-08 18:40:18'),
(33, 21, 10.00, 'PRAGMATIC', 'f100961dc67ca3d411d95c7a910c0cbd', 'vs20olympgate', 'bet', 'f100961dc67ca3d411d95c7a910c0cbd', 0, 'f100961dc67ca3d411d95c7a910c0cbd', '2024-07-08 18:41:13', '2024-07-08 18:41:13'),
(34, 21, 10.00, 'PRAGMATIC', 'f100961dc67ca3d411d95c7a910c0cbd', 'vs20olympgate', 'loss', 'f100961dc67ca3d411d95c7a910c0cbd', 0, 'f100961dc67ca3d411d95c7a910c0cbd', '2024-07-08 18:41:13', '2024-07-08 18:41:13'),
(35, 21, 5.00, 'PRAGMATIC', 'fe86231e3a8406ad9c3b712da6348210', 'vs20olympgate', 'bet', 'fe86231e3a8406ad9c3b712da6348210', 0, 'fe86231e3a8406ad9c3b712da6348210', '2024-07-08 18:46:11', '2024-07-08 18:46:11'),
(36, 21, 5.00, 'PRAGMATIC', 'fe86231e3a8406ad9c3b712da6348210', 'vs20olympgate', 'loss', 'fe86231e3a8406ad9c3b712da6348210', 0, 'fe86231e3a8406ad9c3b712da6348210', '2024-07-08 18:46:11', '2024-07-08 18:46:11'),
(37, 21, 5.00, 'PRAGMATIC', '4702b40c1f73f23ddffb58114d0fe5f2', 'vs20olympgate', 'bet', '4702b40c1f73f23ddffb58114d0fe5f2', 0, '4702b40c1f73f23ddffb58114d0fe5f2', '2024-07-08 18:46:40', '2024-07-08 18:46:40'),
(38, 21, 5.00, 'PRAGMATIC', '4702b40c1f73f23ddffb58114d0fe5f2', 'vs20olympgate', 'loss', '4702b40c1f73f23ddffb58114d0fe5f2', 0, '4702b40c1f73f23ddffb58114d0fe5f2', '2024-07-08 18:46:40', '2024-07-08 18:46:40'),
(39, 21, 5.00, 'PRAGMATIC', '081989c6444ab1803fefc2e104d0aec4', 'vs20olympgate', 'bet', '081989c6444ab1803fefc2e104d0aec4', 0, '081989c6444ab1803fefc2e104d0aec4', '2024-07-08 18:46:45', '2024-07-08 18:46:45'),
(40, 21, 5.00, 'PRAGMATIC', '081989c6444ab1803fefc2e104d0aec4', 'vs20olympgate', 'loss', '081989c6444ab1803fefc2e104d0aec4', 0, '081989c6444ab1803fefc2e104d0aec4', '2024-07-08 18:46:45', '2024-07-08 18:46:45'),
(41, 21, 5.00, 'PRAGMATIC', 'a108a9824e3752e1ffb849f993aa2dc6', 'vs20olympgate', 'bet', 'a108a9824e3752e1ffb849f993aa2dc6', 0, 'a108a9824e3752e1ffb849f993aa2dc6', '2024-07-08 18:46:50', '2024-07-08 18:46:50'),
(42, 21, 5.00, 'PRAGMATIC', 'a108a9824e3752e1ffb849f993aa2dc6', 'vs20olympgate', 'loss', 'a108a9824e3752e1ffb849f993aa2dc6', 0, 'a108a9824e3752e1ffb849f993aa2dc6', '2024-07-08 18:46:50', '2024-07-08 18:46:50'),
(43, 21, 5.00, 'PRAGMATIC', 'b490f1799eb1cb1d85e565c720c7b577', 'vs20olympgate', 'bet', 'b490f1799eb1cb1d85e565c720c7b577', 0, 'b490f1799eb1cb1d85e565c720c7b577', '2024-07-08 18:46:55', '2024-07-08 18:46:55'),
(44, 21, 5.00, 'PRAGMATIC', 'b490f1799eb1cb1d85e565c720c7b577', 'vs20olympgate', 'loss', 'b490f1799eb1cb1d85e565c720c7b577', 0, 'b490f1799eb1cb1d85e565c720c7b577', '2024-07-08 18:46:55', '2024-07-08 18:46:55'),
(45, 21, 5.00, 'PRAGMATIC', 'fb4b172cd20dcd59009de94cff531788', 'vs20olympgate', 'bet', 'fb4b172cd20dcd59009de94cff531788', 0, 'fb4b172cd20dcd59009de94cff531788', '2024-07-08 18:47:12', '2024-07-08 18:47:12'),
(46, 21, 5.00, 'PRAGMATIC', 'fb4b172cd20dcd59009de94cff531788', 'vs20olympgate', 'loss', 'fb4b172cd20dcd59009de94cff531788', 0, 'fb4b172cd20dcd59009de94cff531788', '2024-07-08 18:47:12', '2024-07-08 18:47:12'),
(47, 21, 5.00, 'PRAGMATIC', '09505b246292b2e25239ccf4060bb98d', 'vs20olympgate', 'bet', '09505b246292b2e25239ccf4060bb98d', 0, '09505b246292b2e25239ccf4060bb98d', '2024-07-08 18:47:19', '2024-07-08 18:47:19'),
(48, 21, 5.00, 'PRAGMATIC', '09505b246292b2e25239ccf4060bb98d', 'vs20olympgate', 'loss', '09505b246292b2e25239ccf4060bb98d', 0, '09505b246292b2e25239ccf4060bb98d', '2024-07-08 18:47:19', '2024-07-08 18:47:19'),
(49, 21, 5.00, 'PRAGMATIC', 'dc728161c934d05867ce027be5475ff6', 'vs20olympgate', 'bet', 'dc728161c934d05867ce027be5475ff6', 0, 'dc728161c934d05867ce027be5475ff6', '2024-07-08 18:47:24', '2024-07-08 18:47:24'),
(50, 21, 5.00, 'PRAGMATIC', 'dc728161c934d05867ce027be5475ff6', 'vs20olympgate', 'loss', 'dc728161c934d05867ce027be5475ff6', 0, 'dc728161c934d05867ce027be5475ff6', '2024-07-08 18:47:24', '2024-07-08 18:47:24'),
(51, 21, 5.00, 'PRAGMATIC', '0b07653a86681374ccfb9bf8d5a2cf7d', 'vs20olympgate', 'bet', '0b07653a86681374ccfb9bf8d5a2cf7d', 0, '0b07653a86681374ccfb9bf8d5a2cf7d', '2024-07-08 18:47:30', '2024-07-08 18:47:30'),
(52, 21, 5.00, 'PRAGMATIC', '0b07653a86681374ccfb9bf8d5a2cf7d', 'vs20olympgate', 'loss', '0b07653a86681374ccfb9bf8d5a2cf7d', 0, '0b07653a86681374ccfb9bf8d5a2cf7d', '2024-07-08 18:47:30', '2024-07-08 18:47:30'),
(53, 21, 5.00, 'PRAGMATIC', '9da3ed7cfd714e0079337459440fc545', 'vs20olympgate', 'bet', '9da3ed7cfd714e0079337459440fc545', 0, '9da3ed7cfd714e0079337459440fc545', '2024-07-08 18:47:35', '2024-07-08 18:47:35'),
(54, 21, 5.00, 'PRAGMATIC', '9da3ed7cfd714e0079337459440fc545', 'vs20olympgate', 'loss', '9da3ed7cfd714e0079337459440fc545', 0, '9da3ed7cfd714e0079337459440fc545', '2024-07-08 18:47:35', '2024-07-08 18:47:35'),
(55, 21, 5.00, 'PRAGMATIC', '983cf4730c072837777140be04a2db52', 'vs20olympgate', 'bet', '983cf4730c072837777140be04a2db52', 0, '983cf4730c072837777140be04a2db52', '2024-07-08 18:47:41', '2024-07-08 18:47:41'),
(56, 21, 5.00, 'PRAGMATIC', '983cf4730c072837777140be04a2db52', 'vs20olympgate', 'loss', '983cf4730c072837777140be04a2db52', 0, '983cf4730c072837777140be04a2db52', '2024-07-08 18:47:41', '2024-07-08 18:47:41'),
(57, 21, 0.00, 'PRAGMATIC', '983cf4730c072837777140be04a2db52', 'vs20olympgate', 'bet', '983cf4730c072837777140be04a2db52', 0, '983cf4730c072837777140be04a2db52', '2024-07-08 18:47:58', '2024-07-08 18:47:58'),
(58, 21, 7.25, 'PRAGMATIC', '983cf4730c072837777140be04a2db52', 'vs20olympgate', 'win', '983cf4730c072837777140be04a2db52', 0, '983cf4730c072837777140be04a2db52', '2024-07-08 18:47:58', '2024-07-08 18:47:58'),
(59, 21, 5.00, 'PRAGMATIC', '42eb3a20fa926ea36d7502c9d876d125', 'vs20olympgate', 'bet', '42eb3a20fa926ea36d7502c9d876d125', 0, '42eb3a20fa926ea36d7502c9d876d125', '2024-07-08 18:48:47', '2024-07-08 18:48:47'),
(60, 21, 5.00, 'PRAGMATIC', '42eb3a20fa926ea36d7502c9d876d125', 'vs20olympgate', 'loss', '42eb3a20fa926ea36d7502c9d876d125', 0, '42eb3a20fa926ea36d7502c9d876d125', '2024-07-08 18:48:47', '2024-07-08 18:48:47'),
(61, 23, 5.00, 'PG Soft', '', '1682240', 'bet', '', 0, '', '2024-07-08 19:57:54', '2024-07-08 19:57:54'),
(62, 23, 25.00, 'PG Soft', '', '1682240', 'win', '', 0, '', '2024-07-08 19:57:54', '2024-07-08 19:57:54'),
(63, 23, 10.00, 'EVOLUTION', '', '25669', 'bet', '', 0, '', '2024-07-08 20:00:43', '2024-07-08 20:00:43'),
(64, 23, 10.00, 'EVOLUTION', '', '25669', 'loss', '', 0, '', '2024-07-08 20:00:43', '2024-07-08 20:00:43'),
(65, 23, 0.00, 'EVOLUTION', '', '25669', 'bet', '', 0, '', '2024-07-08 20:00:52', '2024-07-08 20:00:52'),
(66, 23, 0.49, 'EVOLUTION', '', '25669', 'win', '', 0, '', '2024-07-08 20:00:52', '2024-07-08 20:00:52'),
(67, 9, 0.01, 'SportsBook', 'demo_1720548129', '1', 'bet', 'demo_1720548129', 0, 'demo_1720548129', '2024-07-09 15:02:14', '2024-07-09 15:02:14'),
(68, 9, 0.01, 'SportsBook', 'demo_1720548129', '1', 'loss', 'demo_1720548129', 0, 'demo_1720548129', '2024-07-09 15:02:14', '2024-07-09 15:02:14'),
(69, 21, 4.00, 'PG Soft', '', '126', 'bet', '', 0, '', '2024-07-09 15:20:37', '2024-07-09 15:20:37'),
(70, 21, 4.00, 'PG Soft', '', '126', 'loss', '', 0, '', '2024-07-09 15:20:37', '2024-07-09 15:20:37'),
(71, 21, 2.00, 'PG Soft', '', '126', 'bet', '', 0, '', '2024-07-09 15:23:51', '2024-07-09 15:23:51'),
(72, 21, 2.00, 'PG Soft', '', '126', 'loss', '', 0, '', '2024-07-09 15:23:51', '2024-07-09 15:23:51'),
(73, 21, 2.00, 'PG Soft', '', '126', 'bet', '', 0, '', '2024-07-09 15:24:08', '2024-07-09 15:24:08'),
(74, 21, 2.00, 'PG Soft', '', '126', 'loss', '', 0, '', '2024-07-09 15:24:08', '2024-07-09 15:24:08'),
(75, 21, 2.00, 'PG Soft', '', '126', 'bet', '', 0, '', '2024-07-09 15:24:24', '2024-07-09 15:24:24'),
(76, 21, 3.60, 'PG Soft', '', '126', 'win', '', 0, '', '2024-07-09 15:24:24', '2024-07-09 15:24:24'),
(77, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:00', '2024-07-09 15:27:00'),
(78, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:00', '2024-07-09 15:27:00'),
(79, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:19', '2024-07-09 15:27:19'),
(80, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:19', '2024-07-09 15:27:19'),
(81, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:23', '2024-07-09 15:27:23'),
(82, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:23', '2024-07-09 15:27:23'),
(83, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:26', '2024-07-09 15:27:26'),
(84, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:26', '2024-07-09 15:27:26'),
(85, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:31', '2024-07-09 15:27:31'),
(86, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:31', '2024-07-09 15:27:31'),
(87, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:35', '2024-07-09 15:27:35'),
(88, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:35', '2024-07-09 15:27:35'),
(89, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:42', '2024-07-09 15:27:42'),
(90, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:42', '2024-07-09 15:27:42'),
(91, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:27:55', '2024-07-09 15:27:55'),
(92, 21, 2.00, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:27:55', '2024-07-09 15:27:55'),
(93, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:28:01', '2024-07-09 15:28:01'),
(94, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:28:01', '2024-07-09 15:28:01'),
(95, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:28:06', '2024-07-09 15:28:06'),
(96, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:28:06', '2024-07-09 15:28:06'),
(97, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:28:11', '2024-07-09 15:28:11'),
(98, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:28:11', '2024-07-09 15:28:11'),
(99, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:28:15', '2024-07-09 15:28:15'),
(100, 21, 1.20, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:28:15', '2024-07-09 15:28:15'),
(101, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:28:20', '2024-07-09 15:28:20'),
(102, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:28:20', '2024-07-09 15:28:20'),
(103, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:28:44', '2024-07-09 15:28:44'),
(104, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:28:44', '2024-07-09 15:28:44'),
(105, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:29:00', '2024-07-09 15:29:00'),
(106, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:29:00', '2024-07-09 15:29:00'),
(107, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'bet', '', 0, '', '2024-07-09 15:29:04', '2024-07-09 15:29:04'),
(108, 21, 0.40, 'PG Soft', '', 'fortune-dragon', 'loss', '', 0, '', '2024-07-09 15:29:04', '2024-07-09 15:29:04'),
(109, 21, 2.00, 'PG Soft', '', '1508783', 'bet', '', 0, '', '2024-07-09 15:29:52', '2024-07-09 15:29:52'),
(110, 21, 2.00, 'PG Soft', '', '1508783', 'loss', '', 0, '', '2024-07-09 15:29:52', '2024-07-09 15:29:52'),
(111, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:30:35', '2024-07-09 15:30:35'),
(112, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:30:35', '2024-07-09 15:30:35'),
(113, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:31:01', '2024-07-09 15:31:01'),
(114, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:31:01', '2024-07-09 15:31:01'),
(115, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:31:07', '2024-07-09 15:31:07'),
(116, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:31:07', '2024-07-09 15:31:07'),
(117, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:31:15', '2024-07-09 15:31:15'),
(118, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:31:15', '2024-07-09 15:31:15'),
(119, 21, 0.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:31:20', '2024-07-09 15:31:20'),
(120, 21, 2.40, 'PG Soft', '', '115', 'win', '', 0, '', '2024-07-09 15:31:20', '2024-07-09 15:31:20'),
(121, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:32:21', '2024-07-09 15:32:21'),
(122, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:32:21', '2024-07-09 15:32:21'),
(123, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:32:29', '2024-07-09 15:32:29'),
(124, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:32:29', '2024-07-09 15:32:29'),
(125, 21, 2.00, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:32:41', '2024-07-09 15:32:41'),
(126, 21, 2.00, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:32:41', '2024-07-09 15:32:41'),
(127, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:32:52', '2024-07-09 15:32:52'),
(128, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:32:52', '2024-07-09 15:32:52'),
(129, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:33:02', '2024-07-09 15:33:02'),
(130, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:33:02', '2024-07-09 15:33:02'),
(131, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:33:18', '2024-07-09 15:33:18'),
(132, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:33:18', '2024-07-09 15:33:18'),
(133, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:33:26', '2024-07-09 15:33:26'),
(134, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:33:26', '2024-07-09 15:33:26'),
(135, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:33:32', '2024-07-09 15:33:32'),
(136, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:33:32', '2024-07-09 15:33:32'),
(137, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:33:41', '2024-07-09 15:33:41'),
(138, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:33:41', '2024-07-09 15:33:41'),
(139, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:33:49', '2024-07-09 15:33:49'),
(140, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:33:49', '2024-07-09 15:33:49'),
(141, 21, 0.40, 'PG Soft', '', '115', 'bet', '', 0, '', '2024-07-09 15:34:15', '2024-07-09 15:34:15'),
(142, 21, 0.40, 'PG Soft', '', '115', 'loss', '', 0, '', '2024-07-09 15:34:15', '2024-07-09 15:34:15'),
(143, 21, 0.50, 'PG Soft', '', '24', 'bet', '', 0, '', '2024-07-09 15:35:18', '2024-07-09 15:35:18'),
(144, 21, 0.50, 'PG Soft', '', '24', 'loss', '', 0, '', '2024-07-09 15:35:18', '2024-07-09 15:35:18'),
(145, 21, 0.50, 'PG Soft', '', '24', 'bet', '', 0, '', '2024-07-09 15:35:24', '2024-07-09 15:35:24'),
(146, 21, 0.50, 'PG Soft', '', '24', 'loss', '', 0, '', '2024-07-09 15:35:24', '2024-07-09 15:35:24'),
(147, 21, 0.50, 'PG Soft', '', '24', 'bet', '', 0, '', '2024-07-09 15:35:35', '2024-07-09 15:35:35'),
(148, 21, 0.50, 'PG Soft', '', '24', 'loss', '', 0, '', '2024-07-09 15:35:35', '2024-07-09 15:35:35'),
(149, 21, 0.50, 'PG Soft', '', '1682240', 'bet', '', 0, '', '2024-07-09 15:36:01', '2024-07-09 15:36:01'),
(150, 21, 0.50, 'PG Soft', '', '1682240', 'loss', '', 0, '', '2024-07-09 15:36:01', '2024-07-09 15:36:01'),
(151, 21, 0.50, 'PG Soft', '', '1682240', 'bet', '', 0, '', '2024-07-09 15:36:07', '2024-07-09 15:36:07'),
(152, 21, 0.50, 'PG Soft', '', '1682240', 'win', '', 0, '', '2024-07-09 15:36:07', '2024-07-09 15:36:07'),
(153, 21, 5.00, 'EVOLUTION', '', '25669', 'bet', '', 0, '', '2024-07-09 18:09:30', '2024-07-09 18:09:30'),
(154, 21, 5.00, 'EVOLUTION', '', '25669', 'loss', '', 0, '', '2024-07-09 18:09:30', '2024-07-09 18:09:30'),
(155, 21, 0.00, 'EVOLUTION', '', '25669', 'bet', '', 0, '', '2024-07-09 18:09:39', '2024-07-09 18:09:39'),
(156, 21, 8.02, 'EVOLUTION', '', '25669', 'win', '', 0, '', '2024-07-09 18:09:39', '2024-07-09 18:09:39'),
(157, 21, 5.00, 'EVOLUTION', '', '25669', 'bet', '', 0, '', '2024-07-09 18:10:13', '2024-07-09 18:10:13'),
(158, 21, 5.00, 'EVOLUTION', '', '25669', 'loss', '', 0, '', '2024-07-09 18:10:13', '2024-07-09 18:10:13'),
(159, 21, 0.00, 'EVOLUTION', '', '25669', 'bet', '', 0, '', '2024-07-09 18:10:23', '2024-07-09 18:10:23'),
(160, 21, 2.53, 'EVOLUTION', '', '25669', 'win', '', 0, '', '2024-07-09 18:10:23', '2024-07-09 18:10:23'),
(161, 21, 1.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:11:44', '2024-07-09 18:11:44'),
(162, 21, 1.00, 'Spribe', '', '15000', 'loss', '', 0, '', '2024-07-09 18:11:44', '2024-07-09 18:11:44'),
(163, 21, 0.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:11:57', '2024-07-09 18:11:57'),
(164, 21, 0.00, 'Spribe', '', '15000', 'loss', '', 0, '', '2024-07-09 18:11:57', '2024-07-09 18:11:57'),
(165, 21, 1.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:11:58', '2024-07-09 18:11:58'),
(166, 21, 1.00, 'Spribe', '', '15000', 'loss', '', 0, '', '2024-07-09 18:11:58', '2024-07-09 18:11:58'),
(167, 21, 0.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:12:13', '2024-07-09 18:12:13'),
(168, 21, 2.12, 'Spribe', '', '15000', 'win', '', 0, '', '2024-07-09 18:12:13', '2024-07-09 18:12:13'),
(169, 21, 10.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:12:41', '2024-07-09 18:12:41'),
(170, 21, 10.00, 'Spribe', '', '15000', 'loss', '', 0, '', '2024-07-09 18:12:41', '2024-07-09 18:12:41'),
(171, 21, 0.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:12:52', '2024-07-09 18:12:52'),
(172, 21, 0.00, 'Spribe', '', '15000', 'loss', '', 0, '', '2024-07-09 18:12:52', '2024-07-09 18:12:52'),
(173, 21, 10.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:14:07', '2024-07-09 18:14:07'),
(174, 21, 10.00, 'Spribe', '', '15000', 'loss', '', 0, '', '2024-07-09 18:14:07', '2024-07-09 18:14:07'),
(175, 21, 0.00, 'Spribe', '', '15000', 'bet', '', 0, '', '2024-07-09 18:14:12', '2024-07-09 18:14:12'),
(176, 21, 10.80, 'Spribe', '', '15000', 'win', '', 0, '', '2024-07-09 18:14:12', '2024-07-09 18:14:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `game_providers`
--

CREATE TABLE IF NOT EXISTS `game_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(255) NOT NULL,
  `agent_code` varchar(255) NOT NULL,
  `agent_token` varchar(255) NOT NULL,
  `agent_secret` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `game_providers`
--

INSERT INTO `game_providers` (`id`, `provider_name`, `agent_code`, `agent_token`, `agent_secret`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'XGAMING', 'nalabetv2', '87KR1tyqdJA46ORnWDTk9mnbXYGFBU', 'VAZIO', '2024-06-02 20:20:15', '2024-07-08 23:45:39', 1),
(2, 'TBS2API', '3204587', '010101', 'VAZIO', '2024-06-02 20:20:15', '2024-07-09 17:48:09', 0),
(3, 'FIVERS', 'nalabet', '02c208e71ee39737cee123096c813f9e', 'f7b4d311c636251101415bd0634e8018', '2024-06-02 20:20:15', '2024-07-06 21:08:29', 0),
(4, 'HYPERTECH', 'VAZIO', 'fbbfabc93a8fe99d5937055240193db6108107af3d368c0269c1d8b354ffa715', 'VAZIO', '2024-06-02 20:20:15', '2024-07-08 23:45:54', 0),
(5, 'TTL', '', '', '', '2024-06-02 20:20:15', '2024-07-04 12:21:22', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `game_token`
--

CREATE TABLE IF NOT EXISTS `game_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `token` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gateways`
--

CREATE TABLE IF NOT EXISTS `gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway_name` varchar(255) NOT NULL,
  `gateway_id` varchar(255) NOT NULL,
  `gateway_secret` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `show_admin` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `gateways`
--

INSERT INTO `gateways` (`id`, `gateway_name`, `gateway_id`, `gateway_secret`, `is_active`, `show_admin`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Ezzebank', '', '', 0, 1, 100, '2024-06-02 16:45:31', '2024-07-08 23:40:13'),
(2, 'Suitpay', '', '', 0, 0, 100, '2024-06-02 16:54:07', '2024-07-04 11:59:33'),
(3, 'Bspay', 'khoutz_7054346893', 'b688e44d651a11a1b053f513ac830fa7967cf47f1a31c897b2cdba5926c83ad2', 1, 1, 1, '2024-06-02 16:54:07', '2024-07-09 20:51:33'),
(4, 'Primepag', '934f4b43-e84e-42d8-aa0f-1f495423bbee', '27d3a2fa-ac5b-423d-b2bb-c6344ae83946', 0, 1, 1, '2024-06-02 16:54:07', '2024-07-09 20:51:22'),
(5, 'GatewayPass', '0', '$2y$10$6x6Bc2tJon9u1mg44lUW7.GgCenDi36mlHHK81WRdxwAublrqI2qO', 0, 0, 100, '2024-06-17 01:59:15', '2024-06-23 13:50:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ggr_payment_history`
--

CREATE TABLE IF NOT EXISTS `ggr_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` varchar(255) DEFAULT NULL,
  `offer_state` varchar(50) DEFAULT NULL,
  `saldo_ggr` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `final_balance` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txtlog` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_ips`
--

CREATE TABLE IF NOT EXISTS `log_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `model_has_permissions`
--

CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `model_has_roles`
--

CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 516),
(3, 'App\\Models\\User', 1453),
(2, 'App\\Models\\User', 1455);

-- --------------------------------------------------------

--
-- Estrutura para tabela `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_reset_tokens`
--

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura para tabela `payment_history`
--

CREATE TABLE IF NOT EXISTS `payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `offer_state` varchar(255) NOT NULL,
  `worth` bigint(20) UNSIGNED NOT NULL,
  `bonus` text DEFAULT NULL,
  `ggr` tinyint(4) NOT NULL DEFAULT 0,
  `offer_id` text DEFAULT NULL,
  `credited` tinyint(1) NOT NULL DEFAULT 0,
  `provider` varchar(100) DEFAULT 'EzzeBank',
  `rollover` tinyint(4) NOT NULL DEFAULT 0,
  `coupon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) NOT NULL,
  `guard_name` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view user', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(2, 'view withdraw', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(3, 'view paymenthistory', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(4, 'view gameshistory', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(5, 'view code', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(6, 'view gamesapi', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(7, 'view ggr', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(8, 'view webhook', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(9, 'view gateway', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(10, 'view socialurl', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(11, 'view setting', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(12, 'view gameprovider', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(13, 'view themes', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(14, 'view banners', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(15, 'view category', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45'),
(16, 'view logger', 'web', '2024-06-19 01:21:45', '2024-06-19 01:21:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) NOT NULL,
  `guard_name` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2024-06-19 00:24:45', '2024-06-19 00:24:45'),
(2, 'SuperAdmin', 'web', '2024-06-19 00:24:45', '2024-06-19 00:24:45'),
(3, 'Dev', 'web', '2024-06-19 00:24:45', '2024-06-19 00:24:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `role_has_permissions`
--

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(5, 1),
(6, 1),
(7, 1),
(10, 1),
(11, 1),
(13, 1),
(14, 1),
(15, 1),
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `smtp_credentials`
--

CREATE TABLE IF NOT EXISTS `smtp_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `host` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `encryption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from_address` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `smtp_credentials`
--

INSERT INTO `smtp_credentials` (`id`, `host`, `port`, `username`, `password`, `encryption`, `created_at`, `updated_at`, `from_address`, `from_name`) VALUES
(1, 'smtp.titan.email', 465, 'no-reply@xgamingcassino.com', '@V>0I1rbq0h@', 'ssl', '2024-06-24 23:38:14', '2024-06-24 23:38:14', 'no-reply@xgamingcassino.com', 'alcateia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `social_url`
--

CREATE TABLE IF NOT EXISTS `social_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instagram_url` varchar(255) DEFAULT NULL,
  `whatsapp_url` varchar(255) DEFAULT NULL,
  `telegram_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `jivo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `social_url`
--

INSERT INTO `social_url` (`id`, `instagram_url`, `whatsapp_url`, `telegram_url`, `youtube_url`, `jivo_url`, `created_at`, `updated_at`) VALUES
(1, 'instagram.com', '', 'https://t.me/alcateiaofficial', 'youtu.be', '//code.jivosite.com/widget/Q6MP9KHS24', '2024-06-04 22:22:06', '2024-06-19 21:27:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tlt_transactions`
--

CREATE TABLE IF NOT EXISTS `tlt_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reason` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transaction_timestamp` timestamp NULL DEFAULT NULL,
  `round_id` int(11) DEFAULT NULL,
  `round_finished` tinyint(1) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `transaction_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `cpf` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `wallet` double(20,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `wallet_bonus` double(10,2) NOT NULL DEFAULT 0.00,
  `username` varchar(255) NOT NULL,
  `inviter` varchar(255) DEFAULT NULL,
  `rank` varchar(32) NOT NULL DEFAULT 'user',
  `banned` int(11) NOT NULL DEFAULT 0,
  `banned_from_playing` int(11) DEFAULT 0,
  `code` varchar(255) DEFAULT NULL,
  `code_forget` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `anti_bot` double(8,2) NOT NULL DEFAULT 0.00,
  `last_won` double(20,2) NOT NULL DEFAULT 0.00,
  `last_lose` double(20,2) NOT NULL DEFAULT 0.00,
  `deposit_sum` double(20,4) NOT NULL DEFAULT 0.0000,
  `deposit_sum_code` double(20,2) NOT NULL DEFAULT 0.00,
  `referRewards` double(8,2) NOT NULL DEFAULT 0.00,
  `referPercent` int(11) NOT NULL DEFAULT 20,
  `bonus` double(8,4) NOT NULL DEFAULT 0.0000,
  `code_bonus_1_activated` int(11) NOT NULL DEFAULT 0,
  `last_deposit` double(8,2) NOT NULL DEFAULT 0.00,
  `xp` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 1,
  `collected` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `last_withdraw` bigint(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_ip_address` varchar(45) DEFAULT NULL,
  `value_cpa` int(11) NOT NULL DEFAULT 5,
  `game_token` varchar(255) DEFAULT NULL,
  `ddi` varchar(10) NULL,
  `birth_date` varchar(255) NULL,
  `user_demo` tinyint(4) DEFAULT 0,
  `rollover` double(10,2) NOT NULL DEFAULT 0.00,
  `baseline` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `wallet_change`
--

CREATE TABLE IF NOT EXISTS `wallet_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `change` double(20,2) NOT NULL DEFAULT 0.00,
  `reason` text NOT NULL,
  `game` varchar(255) DEFAULT NULL,
  `value_entry` decimal(12,2) NOT NULL DEFAULT 0.00,
  `value_roi` decimal(12,2) NOT NULL DEFAULT 0.00,
  `value_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `value_bonus` decimal(12,2) NOT NULL DEFAULT 0.00,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `webhooks`
--

CREATE TABLE IF NOT EXISTS `webhooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webhook_signup` varchar(255) DEFAULT NULL,
  `webhook_deposit` varchar(255) DEFAULT NULL,
  `webhook_deposit_paid` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `webhooks`
--

INSERT INTO `webhooks` (`id`, `webhook_signup`, `webhook_deposit`, `webhook_deposit_paid`, `created_at`, `updated_at`) VALUES
(1, 'https://', 'https://', 'https://', '2024-06-04 17:21:20', '2024-06-04 17:21:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `withdraw`
--

CREATE TABLE IF NOT EXISTS `withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `transactionId` text DEFAULT NULL,
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `amount_paid` double(10,2) DEFAULT NULL,
  `pix` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(1255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `tax_withdraw` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `auto_withdraw` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `withdraw_affiliate`
--

CREATE TABLE IF NOT EXISTS `withdraw_affiliate` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `transactionId` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `pix` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para view `betnet_view`
--
DROP TABLE IF EXISTS `betnet_view`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `betnet_view`  AS SELECT row_number() over ( order by `games_history`.`provider`) AS `id`, `games_history`.`provider` AS `provider`, sum(case when `games_history`.`action` = 'bet' then `games_history`.`amount` else 0 end) AS `total_bet_amount`, sum(case when `games_history`.`action` = 'win' then `games_history`.`amount` else 0 end) AS `total_win_amount`, sum(case when `games_history`.`action` = 'win' then `games_history`.`amount` else 0 end) - sum(case when `games_history`.`action` = 'bet' then `games_history`.`amount` else 0 end) AS `net_loss` FROM `games_history` WHERE `games_history`.`demo` = 0 GROUP BY `games_history`.`provider` ;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `game_token`
--
ALTER TABLE `game_token`
  ADD CONSTRAINT `game_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
