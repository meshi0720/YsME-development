-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql3104.db.sakura.ne.jp
-- 生成日時: 2025 年 1 月 12 日 09:52
-- サーバのバージョン： 8.0.40
-- PHP のバージョン: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `meshi0720_schoolchoice_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `answer1`
--

CREATE TABLE `answer1` (
  `id` int NOT NULL,
  `q1` varchar(64) DEFAULT NULL,
  `q2` varchar(64) DEFAULT NULL,
  `q3` varchar(64) DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `answer1`
--

INSERT INTO `answer1` (`id`, `q1`, `q2`, `q3`, `date`) VALUES
(1, '共学', 'ある', '2時間以内', '2025-01-11 17:51:08'),
(2, '男子校', 'ない', NULL, '2025-01-03 16:16:59'),
(3, '共学', 'ある', '1時間半以内', '2025-01-06 01:18:31'),
(5, '男子校', 'こだわらない', NULL, '2025-01-06 01:18:40'),
(6, '女子校', 'こだわらない', NULL, '2025-01-11 12:02:19'),
(8, '共学', 'ある', '1時間以内', '2025-01-11 12:01:09'),
(9, NULL, 'こだわらない', '1時間半以内', '2025-01-11 15:48:08'),
(11, '女子校', 'こだわらない', NULL, '2025-01-11 16:29:53'),
(12, NULL, NULL, NULL, '2025-01-11 16:29:56'),
(13, NULL, NULL, NULL, '2025-01-11 16:31:29'),
(14, 'こだわらない', 'ない', '1時間以内', '2025-01-11 16:31:34'),
(15, '男子校', 'ない', '1時間以内', '2025-01-11 16:33:55'),
(16, 'こだわらない', 'こだわらない', '1時間半以内', '2025-01-11 16:36:31'),
(17, '共学', 'ない', NULL, '2025-01-11 16:46:15'),
(18, NULL, 'こだわらない', '1時間半以内', '2025-01-12 09:52:32');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `answer1`
--
ALTER TABLE `answer1`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `answer1`
--
ALTER TABLE `answer1`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
