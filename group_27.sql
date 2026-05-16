-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3307
-- 產生時間： 2026 年 05 月 16 日 12:02
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `group_27`
--
CREATE DATABASE IF NOT EXISTS `group_27` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `group_27`;

-- --------------------------------------------------------

--
-- 資料表結構 `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`, `author_id`) VALUES
(4, '網站更新', '更新註冊登入功能', '2026-04-19 12:48:21', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `forms`
--

INSERT INTO `forms` (`id`, `title`, `description`, `author_id`, `start_date`, `end_date`, `created_at`) VALUES
(3, '羽球活動', '進德羽球場!', 2, NULL, NULL, '2026-04-19 09:32:08'),
(4, '資料庫考試讀書會', '趕快來參加', 2, NULL, NULL, '2026-04-19 12:55:01');

-- --------------------------------------------------------

--
-- 資料表結構 `form_answers`
--

CREATE TABLE `form_answers` (
  `id` int(11) NOT NULL,
  `response_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `form_answers`
--

INSERT INTO `form_answers` (`id`, `response_id`, `question_id`, `answer_text`) VALUES
(4, 3, 4, '一小時'),
(5, 3, 5, '是'),
(6, 4, 6, '一小時'),
(7, 4, 7, '單人');

-- --------------------------------------------------------

--
-- 資料表結構 `form_options`
--

CREATE TABLE `form_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `form_options`
--

INSERT INTO `form_options` (`id`, `question_id`, `option_text`) VALUES
(3, 5, '是'),
(4, 5, '否'),
(5, 7, '單人'),
(6, 7, '多人');

-- --------------------------------------------------------

--
-- 資料表結構 `form_questions`
--

CREATE TABLE `form_questions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('text','textarea','radio','checkbox') NOT NULL,
  `is_required` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `form_questions`
--

INSERT INTO `form_questions` (`id`, `form_id`, `question_text`, `question_type`, `is_required`) VALUES
(4, 3, '會打多久呢', 'text', 0),
(5, 3, '需要公用球拍嗎', 'radio', 0),
(6, 4, '參加多久', 'text', 0),
(7, 4, '幾個人來', 'radio', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `form_responses`
--

CREATE TABLE `form_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `form_responses`
--

INSERT INTO `form_responses` (`id`, `form_id`, `user_id`, `submitted_at`) VALUES
(3, 3, 2, '2026-04-19 09:35:19'),
(4, 4, 2, '2026-04-19 12:55:14');

-- --------------------------------------------------------

--
-- 資料表結構 `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `groups`
--

INSERT INTO `groups` (`id`, `group_name`) VALUES
(1, '資工系'),
(2, '一般會員');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `role` enum('guest','member','admin') DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nickname`, `group_name`, `group_id`, `role`) VALUES
(1, 'admin', 'admin123456', '大管理員', NULL, 1, 'admin'),
(2, 'member', 'member123456', '小菜雞', '123456', 1, 'admin'),
(3, 'member1', 'member123456', 'abc', NULL, 2, 'member'),
(4, '123', '123', '1', NULL, 2, 'member'),
(5, 'admin1', '123456', 'abcde', NULL, 2, 'member'),
(6, 'member12', '123456', 'membera', NULL, 2, 'member'),
(7, 'abc', '123456', 'abcd', NULL, 2, 'member');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- 資料表索引 `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- 資料表索引 `form_answers`
--
ALTER TABLE `form_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`);

--
-- 資料表索引 `form_options`
--
ALTER TABLE `form_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- 資料表索引 `form_questions`
--
ALTER TABLE `form_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- 資料表索引 `form_responses`
--
ALTER TABLE `form_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `group_id` (`group_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `form_answers`
--
ALTER TABLE `form_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `form_options`
--
ALTER TABLE `form_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `form_questions`
--
ALTER TABLE `form_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- 資料表的限制式 `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `form_answers`
--
ALTER TABLE `form_answers`
  ADD CONSTRAINT `form_answers_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `form_responses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `form_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `form_questions` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `form_options`
--
ALTER TABLE `form_options`
  ADD CONSTRAINT `form_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `form_questions` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `form_questions`
--
ALTER TABLE `form_questions`
  ADD CONSTRAINT `form_questions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `form_responses`
--
ALTER TABLE `form_responses`
  ADD CONSTRAINT `form_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `form_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
