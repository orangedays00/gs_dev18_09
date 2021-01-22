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
-- テーブルの構造 `gs_applicant_user`
--

CREATE TABLE `gs_applicant_user` (
  `id` int(10) NOT NULL,
  `last_name` varchar(10) NOT NULL,
  `first_name` varchar(10) NOT NULL,
  `last_kana` varchar(10) NOT NULL,
  `first_kana` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `birth_day` date NOT NULL,
  `sex` varchar(5) NOT NULL,
  `now_work` varchar(10) NOT NULL,
  `now_income` varchar(20) NOT NULL,
  `now_prefecture` varchar(10) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `gs_applicant_user`
--

INSERT INTO `gs_applicant_user` (`id`, `last_name`, `first_name`, `last_kana`, `first_kana`, `email`, `tel`, `birth_day`, `sex`, `now_work`, `now_income`, `now_prefecture`, `create_time`, `update_time`) VALUES
(1, '地図', '太郎', 'ジズ', 'タロウ', 'test@test.com', '000-1111-2222', '1990-01-01', '男性', '正社員', '200万円以下', '北海道', '2021-01-23 00:12:00', '2021-01-23 00:12:00'),
(2, '地図', '二郎', 'ジズ', 'ジロウ', 'test1@test.com', '111-0000-2222', '1991-07-20', '男性', '契約社員', '300万円以上400万円未満', '東京都', '2021-01-23 00:20:19', '2021-01-23 00:20:19');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_applicant_user`
--
ALTER TABLE `gs_applicant_user`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `gs_applicant_user`
--
ALTER TABLE `gs_applicant_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
