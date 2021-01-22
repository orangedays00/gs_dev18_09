-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2021 年 1 月 22 日 16:10
-- サーバのバージョン： 5.7.30
-- PHP のバージョン: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `gs_db_ats`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_m_employment`
--

CREATE TABLE `gs_m_employment` (
  `id` int(11) NOT NULL,
  `employment` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `gs_m_employment`
--

INSERT INTO `gs_m_employment` (`id`, `employment`) VALUES
(1, '正社員'),
(2, '契約社員'),
(3, '派遣社員'),
(4, 'パート・アルバイト'),
(5, '業務委託'),
(6, 'その他'),
(7, '離職中');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_m_employment`
--
ALTER TABLE `gs_m_employment`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `gs_m_employment`
--
ALTER TABLE `gs_m_employment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
