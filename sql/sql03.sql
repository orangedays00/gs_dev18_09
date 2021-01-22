-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2021 年 1 月 22 日 16:11
-- サーバのバージョン： 5.7.30
-- PHP のバージョン: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `gs_db_ats`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_m_nowincome`
--

CREATE TABLE `gs_m_nowincome` (
  `id` int(2) NOT NULL,
  `nowincome_text` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `gs_m_nowincome`
--

INSERT INTO `gs_m_nowincome` (`id`, `nowincome_text`) VALUES
(1, '200万円以下'),
(2, '200万円以上300万円未満'),
(3, '300万円以上400万円未満'),
(4, '400万円以上500万円未満'),
(5, '500万円以上600万円未満'),
(6, '600万円以上700万円未満'),
(7, '700万円以上800万円未満'),
(8, '800万円以上900万円未満'),
(9, '900万円以上1000万円未満'),
(10, '1000万円以上');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_m_nowincome`
--
ALTER TABLE `gs_m_nowincome`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `gs_m_nowincome`
--
ALTER TABLE `gs_m_nowincome`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
