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
-- テーブルの構造 `gs_user`
--

CREATE TABLE `gs_user` (
  `id` int(10) NOT NULL COMMENT '管理ID',
  `email` varchar(255) NOT NULL COMMENT 'ユーザーID',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `pass` varchar(100) NOT NULL COMMENT 'パスワード',
  `account_type` int(1) NOT NULL COMMENT '0:スーパー管理者、1:一般ユーザー',
  `create_time` datetime NOT NULL COMMENT '作成日時',
  `update_time` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `gs_user`
--

INSERT INTO `gs_user` (`id`, `email`, `name`, `pass`, `account_type`, `create_time`, `update_time`) VALUES
(1, 'test@test.com', 'MASTER_アカウント', '91b4d142823f7d20c5f08df69122de43f35f057a988d9619f6d3138485c9a203', 0, '2021-01-14 01:37:49', '2021-01-14 01:37:49'),
(4, 'test1@test.com', 'ジーズ三郎', '62080f96a2bfc48794326c5b9750942d15886e6a9746fc215cd0d04127196db2', 1, '2021-01-20 00:57:08', '2021-01-20 00:57:08'),
(5, 'test2@test.com', 'ジーズ二郎', '62080f96a2bfc48794326c5b9750942d15886e6a9746fc215cd0d04127196db2', 1, '2021-01-20 00:59:56', '2021-01-20 00:59:56');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_user`
--
ALTER TABLE `gs_user`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `gs_user`
--
ALTER TABLE `gs_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '管理ID', AUTO_INCREMENT=7;
