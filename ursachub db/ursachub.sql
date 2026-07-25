-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 11:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ursachub`
--
CREATE DATABASE IF NOT EXISTS `ursachub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ursachub`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `org` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gcash_name` varchar(255) DEFAULT NULL,
  `gcash_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fb_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `org`, `password`, `gcash_name`, `gcash_number`, `created_at`, `updated_at`, `logo`, `fb_link`) VALUES
(1, 'COENGSC', 'College of Engineering - Student Council', '$2y$10$XoAcG6gDLl6eodXY5cc2D.Il4syEKI/eaMJsyRruTvP1hSzWjQj4W', 'Mark Angelo A. Isulat', '0948 679 3481\r\n', '2024-11-15 11:06:00', '2024-11-30 08:33:28', 'logos/1732224110_COENG LOGO (1).png', NULL),
(2, 'ACES', 'Association of Civil Engineering Students', '$2y$10$YFVfP0ZufsJx49rgupO6L.Nf61HsJbIU09eg1MbIuAixpn4fmtz62', 'Prince Bengkong Vivaz', '0912 345 6789', '2024-11-16 04:49:14', '2024-11-21 13:52:31', 'logos/1732225951_Association of Civil Engineering Students.jpg', NULL),
(3, 'BRS', 'Bartender\'s Society', '$2y$10$ue5tcvB5FXaRgnEGEb0raeIk06A29/.17x.4lU8ZXQwwU34mAdaei', NULL, NULL, '2024-11-21 12:37:44', '2024-11-21 13:53:43', 'logos/1732226023_Bartender_s Society.jpg', NULL),
(4, 'ACCESS', 'Association of Concerned Computer Engineering Students', '$2y$10$1MhzABPp/PkZTIRjr76ocepM7d1fePmeKSsaKq4J4ONRX1Gl9okHm', NULL, NULL, '2024-11-21 12:48:05', '2024-11-30 14:02:39', 'logos/1732226230_Association of Concerned Computer Engineering Students.png', 'https://www.facebook.com/AccessOfficial.URSAntipolo'),
(5, 'AJA', 'Association of Junior Administrator', '$2y$10$3KmzAJhSWMvDP2eEgVTjxecYDM0EECMwdn.deXNdEkAGurE2/uKxq', NULL, NULL, '2024-11-30 08:34:41', '2024-11-30 08:45:42', 'logos/1732985142_Association of Junior Administrator.png', NULL),
(6, 'STENO', 'Association of Stenographers Aiming for Progress', '$2y$10$XpNti8K4zHiSVyZw18Zxu.QpI6GukAHmBdvoJZIV/u5JuL0l9HWO.', NULL, NULL, '2024-11-30 08:35:27', '2024-11-30 08:46:15', 'logos/1732985175_Association of Stenographers Aiming for Progress.jpg', NULL),
(7, 'beeds', 'Bachelor of Elementary Education Society', '$2y$10$qQfCx0kp5v3jrSPm12R2xuX4AUCYpru3ETCkg7tmOx4ooNlvlzYay', NULL, NULL, '2024-11-30 08:36:15', '2024-11-30 08:46:54', 'logos/1732985214_Bachelor of Elementary Education Society.jpg', NULL),
(8, 'CBi', 'Christian Brotherhood International', '$2y$10$MlSnRtJyg8reSaj6whbPVuP7iAoBUHrNUZpsaLqXUxTqmmVpfTA5G', NULL, NULL, '2024-11-30 08:37:09', '2024-11-30 08:50:11', 'logos/1732985411_Christian Brotherhood International.png', NULL),
(9, 'cobasc', 'College of Business Administration - Student Council', '$2y$10$FQINlzPseWWg.x3O1o3eOu2.RC0bmGEBk1MwNDWQUaWCzzTUh8w9i', NULL, NULL, '2024-11-30 08:37:36', '2024-11-30 08:50:33', 'logos/1732985433_College of Business Administration - Student Council.jpg', NULL),
(10, 'coesc', 'College of Education - Student Council', '$2y$10$/vYRUgUmTaxm3hDX01s/gOp68D20d..5dOpSVkCcoAYYRqMjzjhCq', NULL, NULL, '2024-11-30 08:37:47', '2024-11-30 09:43:39', 'logos/1732988619_College of Education - Student Council.jpg', NULL),
(11, 'chisC', 'College of Hospitality Industry - Student Council', '$2y$10$vaQSLAiR.9aV.gr.E0UzYOas2TMo.cv/F80J0tCKwUozKR7//fJj.', NULL, NULL, '2024-11-30 08:39:37', '2024-11-30 08:51:04', 'logos/1732985464_College of Hospitality Industry - Student Council.jpg', NULL),
(12, 'coro', 'CORO URSAC', '$2y$10$rDZKqdclRoafAuUllmfyVuszZ9vOcFeLot5hWmVCI38uSs81TeR5y', NULL, NULL, '2024-11-30 08:39:49', '2024-11-30 08:51:22', 'logos/1732985482_CORO URSAC.jpg', NULL),
(13, 'elevate', 'Elevate University of Rizal System Antipolo Chapter', '$2y$10$A33o4BOGcS87fUD7LpVRU.kTOEpq5hQ2E.AdyaOBIkQA/VD/Py3m.', NULL, NULL, '2024-11-30 08:40:37', '2024-11-30 08:43:16', 'logos/1732984996_Elevate University of Rizal System Antipolo Chapter.jpg', NULL),
(14, 'ARMY', 'Environmental Army Society', '$2y$10$cpEBeNYyAqoj385x8Xfwz.X99umdOHmobUbmDhuLzoR4sbgYurRUm', NULL, NULL, '2024-11-30 08:52:03', '2024-11-30 08:52:30', 'logos/1732985550_Environmental Army Society.jpg', NULL),
(15, 'HIYAS', 'Hiyas ng Rizal Dance Troup', '$2y$10$L1ISFdotJa75umgASVL9L..4mZVnczh5YhMJHCOu9B/fGGqaVFX9q', NULL, NULL, '2024-11-30 08:53:06', '2024-11-30 08:53:25', 'logos/1732985605_Hiyas ng Rizal Dance Troup.png', NULL),
(16, 'HMS', 'Hospitality Management Society', '$2y$10$CVANFyGn.A0MnoMnBRFckuzAoaE2OagJbNHhoexGxFUjuHsSFFpJK', NULL, NULL, '2024-11-30 08:54:29', '2024-11-30 08:54:44', 'logos/1732985684_Hospitality Management Society.jpg', NULL),
(17, 'KPFIL', 'Kapulungang Filipino', '$2y$10$TYDrpW6RpbteKs6fvs0vxeW/QspmifWg3fDpXNS1nZ6hxTQPaJGW2', NULL, NULL, '2024-11-30 08:55:28', '2024-11-30 08:55:50', 'logos/1732985750_Kapulungang Filipino.jpg', NULL),
(18, 'LITERA', 'Litera Organization', '$2y$10$Zrgep2rhKGo8WvsNGZrJGeG0aR9KkI7nPo7nV88/V0ZMAcYXtbScC', NULL, NULL, '2024-11-30 08:56:10', '2024-11-30 08:56:25', 'logos/1732985785_Litera Organization.jpg', NULL),
(19, 'RADS', 'Radicals Organization', '$2y$10$OHyz6Mpm41qZMnW0rxDiwO.lG87Ysj1yLh4FJMeM56wM9q0MfNyjW', NULL, NULL, '2024-11-30 08:56:37', '2024-11-30 08:56:54', 'logos/1732985814_Radicals Organization.png', NULL),
(20, 'RCYC', 'Red Cross Youth Council', '$2y$10$1UIKEELgwWUU.VAuI8k3DuD.P4fMCkeS3XuduepXZHGXWoYIo4EQ.', NULL, NULL, '2024-11-30 08:57:30', '2024-11-30 08:57:51', 'logos/1732985871_Red Cross Youth Council.jpg', NULL),
(21, 'TIPOLO', 'Tipolo Student Publication', '$2y$10$VTRv30rUCMlPstKjSeDuQ.18TGjjxSnmTSoIIbR0JbesZuxfBeu1S', NULL, NULL, '2024-11-30 08:58:33', '2024-11-30 08:58:48', 'logos/1732985928_Tipolo Student Publication.jpg', NULL),
(22, 'TOURS', 'Tourism Society Organization', '$2y$10$MaPxUomla6XENHfa4.HXzeof5m1NTG.LHvmm/OvGd2uiRFWNfbSt2', NULL, NULL, '2024-11-30 08:59:35', '2024-11-30 08:59:49', 'logos/1732985989_Tourism Society Organization.jpg', NULL),
(23, 'USSG', 'University Supreme Student Government', '$2y$10$awaeMU61Rn0c0sOnNFsK7uxXMIJjjw5v.eOBAHDnhwUYr8dayaC7S', NULL, NULL, '2024-11-30 09:00:20', '2024-11-30 09:00:33', 'logos/1732986033_University Supreme Student Government.jpg', NULL),
(24, 'URSFGF', 'URSAC - Fierce Group Facilitator', '$2y$10$mR7U61Uk4gg.ydSo8z.vTuDf447GMsLK/yJIg8uqo5U6knXCcu8PS', NULL, NULL, '2024-11-30 09:01:01', '2024-11-30 09:01:19', 'logos/1732986079_URSAC - Fierce Group Facilitator.jpg', NULL),
(25, 'UNESCO', 'URSAC - Social Studies Organization for UNESCO', '$2y$10$77u.LJd6hPoxeMavdhdpTOFnhfIuDWBSL3qVKbXdRlhMcqNx4/UXC', NULL, NULL, '2024-11-30 09:01:37', '2024-11-30 09:01:54', 'logos/1732986114_URSAC - Social Studies Organization for UNESCO.jpg', NULL),
(26, 'URSACE', 'URSAC Extensionist', '$2y$10$ZmKIQUkGAzrVSne0tW/OruZB5qZE1cKYMJsFJcIzjmRmu7fCvUQTu', NULL, NULL, '2024-11-30 09:02:18', '2024-11-30 09:02:28', 'logos/1732986148_URSAC Extensionist.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `org` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `student_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `name`, `org`, `size`, `quantity`, `price`, `photos`, `student_id`, `created_at`, `updated_at`) VALUES
(59, 'TSHIRT FOR CPE', 'Association of Civil Engineering Students', 'small', 1, 150.00, '[\"product_photos\\/m7wGcJJcQX9T1m9K20me2U99gTsPjKoMaNBJ137A.png\"]', 'AC2023-00123', '2024-11-22 11:04:52', '2024-11-22 11:04:52'),
(60, 'Tshirt', 'College of Engineering - Student Council', 'medium', 1, 300.00, '[\"product_photos\\/RoAsfyxr2p3UtwukGruAnoo6mzFCpzUks4uMrXXC.png\",\"product_photos\\/u6ighgu3ATKWSDNIRUmyfjYy0fFYZxnCdeC8Gu1Z.png\"]', 'AC2023-00123', '2024-11-22 11:09:53', '2024-11-22 11:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Science in Computer Engineering', NULL, NULL),
(2, 'Bachelor of Science in Civil Engineering', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_product`
--

CREATE TABLE `course_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_product`
--

INSERT INTO `course_product` (`id`, `course_id`, `product_id`, `created_at`, `updated_at`) VALUES
(39, 1, 21, NULL, NULL),
(40, 2, 21, NULL, NULL),
(41, 1, 22, NULL, NULL),
(42, 2, 22, NULL, NULL),
(43, 1, 23, NULL, NULL),
(44, 2, 23, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_10_14_135224_create_products_table', 1),
(6, '2024_10_28_101925_create_news_table', 1),
(7, '2024_11_01_193512_create_admins_table', 1),
(8, '2024_11_10_114412_create_courses_table', 1),
(9, '2024_11_10_115201_create_course_product_table', 1),
(10, '2024_11_10_116620_create_students_table', 1),
(11, '2024_11_10_182652_create_cart_table', 1),
(16, '2024_11_17_122728_create_orders_table', 2),
(18, '2024_11_18_092450_create_orders_table', 3),
(19, '2024_11_18_092501_create_order_product_table', 3),
(20, '2024_11_18_095256_create_orders_table', 4),
(21, '2024_11_18_095356_create_order_items_table', 4),
(22, '2024_11_18_113213_create_orders_table', 5),
(23, '2024_11_18_113227_create_order_items_table', 5),
(26, '2024_11_18_124921_create_orders_table', 6),
(27, '2024_11_21_211731_add_logo_to_admins_table', 7),
(28, '2024_11_30_213805_add_fb_link_to_admins_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org` varchar(255) NOT NULL,
  `headline` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `org`, `headline`, `content`, `photos`, `created_at`, `updated_at`) VALUES
(10, 'University Supreme Student Government', 'Acquaintance Party 2024', '𝗟𝗘𝗧\'𝗦 𝗚𝗢 𝗖𝗥𝗔𝗭𝗬 𝗨𝗡𝗧𝗜𝗟 𝗪𝗘 𝗦𝗘𝗘 𝗧𝗛𝗘 𝗦𝗨𝗡! ☀️<br />\r\n<br />\r\nGiants, are you READY!!!!!!🎆<br />\r\n<br />\r\nIt\'s time to put your best Y2K outfit and practice your dance moves because it\'s time to PARTY! 🎆<br />\r\n<br />\r\nPlease be guided by the given schedule and don\'t forget the necessary things upon entry such as:<br />\r\n• ID<br />\r\n• TICKET<br />\r\n• PARENT\'S CONSENT<br />\r\n<br />\r\nEnsure that you pack your things properly to avoid any lost items. BE RESPONSIBLE FOR YOUR OWN BELONGINGS.<br />\r\n𝗠𝗮𝗸𝗲 𝗡𝗲𝘄 𝗠𝗲𝗺𝗼𝗿𝗶𝗲𝘀 𝘁𝗵𝗶𝘀 𝗙𝗿𝗶𝗱𝗮𝘆 𝗡𝗶𝗴𝗵𝘁 🎆💜<br />\r\n<br />\r\n#USSG2425<br />\r\n#AcquaintanceParty2024', '[\"news_photos\\/nYxIqb57L0NwpCd7yA2dgH65mr8Glr5wfoP3YQ07.jpg\"]', '2024-11-30 10:14:58', '2024-11-30 10:15:31'),
(11, 'College of Engineering - Student Council', 'CoEng Week 2024', '𝑯𝒂𝒏𝒅𝒂 𝒏𝒂 𝒃𝒂 𝒌𝒂𝒚𝒐𝒏𝒈 𝒅𝒖𝒎𝒂𝒚𝒐\'𝒕 𝒎𝒂𝒌𝒊-𝒑𝒊𝒚𝒆𝒔𝒕𝒂, 𝑬𝒏𝒈𝒊𝒏𝒆𝒆𝒓𝒔? 🛠️✨<br />\r\n<br />\r\nGet ready to level up your fiesta game this coming 𝐍𝐨𝐯𝐞𝐦𝐛𝐞𝐫 𝟏𝟏-𝟏𝟓, 𝟐𝟎𝟐𝟒, as we celebrate 𝐏𝐢𝐬𝐭𝐚𝐧𝐠 𝐈𝐧𝐡𝐢𝐧𝐲𝐞𝐫𝐨—hindi lang TATLO, kundi LIMANG araw ng kasiyahan! <br />\r\n<br />\r\nTime to wield your pambansang sandata—our brilliant Engineering minds! ✨<br />\r\n<br />\r\nHumanda nang magpasikat at makikulit with our interactive games that promise fun and a few surprises along the way! Let’s unleash our creativity and engineer some unforgettable moments together! 🎉<br />\r\n<br />\r\n#COENGWeek<br />\r\n#PistangInhinyero2024', '[\"news_photos\\/8KWA0MfyWpjskI7GUDKC7HRplz7YKOZTjSQMHOAT.png\"]', '2024-11-30 10:18:09', '2024-11-30 10:18:09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `org` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `course` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `gcash_proof` varchar(255) DEFAULT NULL,
  `claimed_by` varchar(255) DEFAULT NULL,
  `claimed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `size`, `price`, `org`, `quantity`, `student_id`, `firstname`, `lastname`, `middlename`, `course`, `payment_method`, `reference_number`, `order_number`, `status`, `gcash_proof`, `claimed_by`, `claimed_at`, `created_at`, `updated_at`) VALUES
(12, 'SHIRT FOR ANYONE', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'XOCR9W4LBS', 'claimed', NULL, 'Marky Isulat', '2024-11-23 06:30:07', '2024-11-23 06:06:18', '2024-11-23 06:30:07'),
(13, 'SHIRT FOR ANYONE', 'medium', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'XOCR9W4LBS', 'claimed', NULL, 'Marky Isulat', '2024-11-23 06:30:07', '2024-11-23 06:06:18', '2024-11-23 06:30:07'),
(14, 'SHIRT FOR ANYONE', 'large', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'XOCR9W4LBS', 'claimed', NULL, 'Marky Isulat', '2024-11-23 06:30:07', '2024-11-23 06:06:18', '2024-11-23 06:30:07'),
(15, 'SHIRT FOR ANYONE', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'JJZQCOK0OR', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-23 06:30:03', '2024-11-23 06:06:34', '2024-11-23 06:30:03'),
(16, 'Lanyard', 'small', 600.00, 'College of Engineering - Student Council', 4, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'SPLAAK4GJO', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-23 06:30:00', '2024-11-23 06:27:25', '2024-11-23 06:30:00'),
(17, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(18, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(19, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(20, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(21, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(22, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(23, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(24, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(25, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(26, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(27, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(28, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(29, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(30, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(31, 'Lanyard', 'small', 150.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'I8CY8XVJ3N', 'claimed', NULL, 'Mark Angelo A. Isulat', '2024-11-24 03:14:58', '2024-11-23 06:31:59', '2024-11-24 03:14:58'),
(32, 'TSHIRT FOR CPE', 'small', 300.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'gcash', '4940417150210', 'KVJAD0FSUW', 'pending', 'gcash_proofs/rF0YSh3XXJ7C0p9ZeVD3cYjan0ERRz7MNR7RJv23.png', NULL, NULL, '2024-11-24 12:21:44', '2024-11-24 12:22:18'),
(33, 'TSHIRT FOR CPE', 'double_extralarge', 300.00, 'College of Engineering - Student Council', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'gcash', '4940417150210', 'KVJAD0FSUW', 'pending', 'gcash_proofs/rF0YSh3XXJ7C0p9ZeVD3cYjan0ERRz7MNR7RJv23.png', NULL, NULL, '2024-11-24 12:21:44', '2024-11-24 12:22:18'),
(34, 'SHIRT FOR ANYONE', 'small', 450.00, 'College of Engineering - Student Council', 3, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'gcash', '4940417150210', 'KVJAD0FSUW', 'pending', 'gcash_proofs/rF0YSh3XXJ7C0p9ZeVD3cYjan0ERRz7MNR7RJv23.png', NULL, NULL, '2024-11-24 12:21:44', '2024-11-24 12:22:18'),
(35, 'CPE Shirt 2k23', 'small', 1050.00, 'Association of Concerned Computer Engineering Students', 3, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'TUVOZPS9WW', 'pending', NULL, NULL, NULL, '2024-11-30 09:47:51', '2024-11-30 09:47:51'),
(36, 'CPE Shirt 2k23', 'small', 350.00, 'Association of Concerned Computer Engineering Students', 1, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'QOZFQZVRFC', 'pending', NULL, NULL, NULL, '2024-11-30 12:52:12', '2024-11-30 12:52:12'),
(37, 'CPE Shirt 2k23', 'small', 700.00, 'Association of Concerned Computer Engineering Students', 2, 'AC2023-00521', 'MARK ANGELO', 'ISULAT', 'AGA', 'Bachelor of Science in Computer Engineering', 'cash', 'null', 'JRBRJ8RBQS', 'pending', NULL, NULL, NULL, '2024-11-30 12:56:16', '2024-11-30 12:56:53');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `small` int(11) NOT NULL,
  `medium` int(11) NOT NULL,
  `large` int(11) NOT NULL,
  `extralarge` int(11) NOT NULL,
  `double_extralarge` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `org`, `name`, `small`, `medium`, `large`, `extralarge`, `double_extralarge`, `price`, `photos`, `created_at`, `updated_at`) VALUES
(21, 'Association of Concerned Computer Engineering Students', 'CPE Shirt 2k23', 93, 99, 99, 99, 99, 350.00, '[\"product_photos\\/W9VjUnGWmnygjcYTCcNncjH77IV5wy3BGZeMGsML.png\"]', '2024-11-30 09:37:59', '2024-11-30 09:37:59'),
(22, 'College of Education - Student Council', 'COE Shirt 2k24', 99, 99, 99, 99, 99, 350.00, '[\"product_photos\\/9F8xlKqlTEHmTjVoIEeSQkpgahQY8bpeP55UWFjL.jpg\"]', '2024-11-30 09:44:06', '2024-11-30 09:44:06'),
(23, 'College of Hospitality Industry - Student Council', 'CHI Shirt 2k24', 99, 99, 99, 99, 99, 350.00, '[\"product_photos\\/pPvmiKou0zB91b25IgDl1dAj7aZAkvNMCrJzjJnE.jpg\"]', '2024-11-30 09:44:52', '2024-11-30 09:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `middle_name`, `student_id`, `course_id`, `password`, `created_at`, `updated_at`) VALUES
(1, 'MARK ANGELO', 'ISULAT', 'AGA', 'AC2023-00521', 1, '$2y$10$o/CNshTdHmGkOubl9/eooeYrDzuBWbw9h10.cAy4i8h9ws5KA8VKi', '2024-11-15 11:09:50', '2024-11-15 11:09:50'),
(2, 'Elijah Mei', 'IRINGAN', 'BATUCAN', 'AC2023-00123', 2, '$2y$10$94u4tM84C2IajQihVvHby.WGSB7/RdNjhZ9zEIO1qV8Lscf5lQE06', '2024-11-17 11:09:32', '2024-11-17 11:09:32'),
(3, 'BREXCEL', 'ORIAS', 'bENGKONG', 'AC2023-00890', 1, '$2y$10$vGRtpIcY6rk0hG1GQdgY1.ZQGaKvqQb0IfBI2qKvAUD3fN3O6ZkAC', '2024-11-24 13:59:45', '2024-11-24 13:59:45'),
(4, 'althea', 'PALustre', 'ewan', 'AC2023-00789', 2, '$2y$10$QPYfHBVpEA412vm6z9LxLO8Y3gbB1dAiV.z/JH8dU8JRo0vX198rS', '2024-11-24 14:01:13', '2024-11-24 14:01:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_org_unique` (`org`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_product`
--
ALTER TABLE `course_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_product_course_id_foreign` (`course_id`),
  ADD KEY `course_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_student_id_unique` (`student_id`),
  ADD KEY `students_course_id_foreign` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_product`
--
ALTER TABLE `course_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_product`
--
ALTER TABLE `course_product`
  ADD CONSTRAINT `course_product_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
