-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql3104.db.sakura.ne.jp
-- 生成日時: 2025 年 1 月 06 日 01:22
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
(1, '共学', 'ある', '1時間以内', '2025-01-03 14:19:43'),
(2, '男子校', 'ない', NULL, '2025-01-03 16:16:59'),
(3, '共学', 'ある', '1時間半以内', '2025-01-06 01:18:31'),
(5, '男子校', 'こだわらない', NULL, '2025-01-06 01:18:40'),
(6, 'こだわらない', 'こだわらない', NULL, '2025-01-06 01:18:45');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
