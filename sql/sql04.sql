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
-- テーブルの構造 `gs_m_prefecture`
--

CREATE TABLE `gs_m_prefecture` (
  `prefecture_id` char(3) NOT NULL,
  `prefecture_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `gs_m_prefecture`
--

INSERT INTO `gs_m_prefecture` (`prefecture_id`, `prefecture_name`) VALUES
('P01', '北海道'),
('P02', '青森県'),
('P03', '岩手県'),
('P04', '宮城県'),
('P05', '秋田県'),
('P06', '山形県'),
('P07', '福島県'),
('P08', '茨城県'),
('P09', '栃木県'),
('P10', '群馬県'),
('P11', '埼玉県'),
('P12', '千葉県'),
('P13', '東京都'),
('P14', '神奈川県'),
('P15', '新潟県'),
('P16', '富山県'),
('P17', '石川県'),
('P18', '福井県'),
('P19', '山梨県'),
('P20', '長野県'),
('P21', '岐阜県'),
('P22', '静岡県'),
('P23', '愛知県'),
('P24', '三重県'),
('P25', '滋賀県'),
('P26', '京都府'),
('P27', '大阪府'),
('P28', '兵庫県'),
('P29', '奈良県'),
('P30', '和歌山県'),
('P31', '鳥取県'),
('P32', '島根県'),
('P33', '岡山県'),
('P34', '広島県'),
('P35', '山口県'),
('P36', '徳島県'),
('P37', '香川県'),
('P38', '愛媛県'),
('P39', '高知県'),
('P40', '福岡県'),
('P41', '佐賀県'),
('P42', '長崎県'),
('P43', '熊本県'),
('P44', '大分県'),
('P45', '宮崎県'),
('P46', '鹿児島県'),
('P47', '沖縄県'),
('P48', '海外');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_m_prefecture`
--
ALTER TABLE `gs_m_prefecture`
  ADD UNIQUE KEY `prefecture_id` (`prefecture_id`);
