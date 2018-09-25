-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-08-30 06:16:38
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wxmessage`
--

-- --------------------------------------------------------

--
-- 表的结构 `wx_developer`
--

CREATE TABLE `wx_developer` (
  `id` int(10) UNSIGNED NOT NULL,
  `createAt` varchar(16) DEFAULT NULL,
  `accessToken` varchar(256) DEFAULT NULL,
  `expires_in` smallint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wx_developer`
--

INSERT INTO `wx_developer` (`id`, `createAt`, `accessToken`, `expires_in`) VALUES
(4, '1535608425', '13_oqeX7hdMt6ObalqhtUb4Ce__SPt_r_dObpAP_wx6RxM_eHdDirQBx968RM7bC5KmEg-WFBtvBCHjAAEypioilczu2L6vf_0Tt3FjAZZ5Ail-ECfb5OVSZkEB12kwbUBAl4QFh7D3gqvs9fJQSZIfADANUD', 7200),
(3, '1535608340', '13_bmr9RGPlK3L6khSl71KcgitFr9fU3ZQO0iscKSEb5_OG0zDVeqGwCPVCvAjae5oKQYpyTinkV8VBjOEAg_0UiclWxirGs9EzZmkacjYaUUXwcg4_TMvbgR8BrE4yrL8V2Rmy9z_EVl427HfRAXGcABAOLU', 7);

-- --------------------------------------------------------

--
-- 表的结构 `wx_message`
--

CREATE TABLE `wx_message` (
  `id` int(10) UNSIGNED NOT NULL,
  `ToUserName` varchar(64) NOT NULL,
  `FromUserName` varchar(64) NOT NULL,
  `CreateTime` int(20) NOT NULL,
  `MsgType` varchar(10) NOT NULL,
  `MediaID` int(64) DEFAULT NULL,
  `Format` varchar(10) DEFAULT NULL,
  `Recognition` text,
  `Content` text,
  `Reply` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wx_user`
--

CREATE TABLE `wx_user` (
  `FromUserName` varchar(64) NOT NULL,
  `ToUserName` varchar(64) NOT NULL,
  `MsgId` text,
  `UpdateTime` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wx_developer`
--
ALTER TABLE `wx_developer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wx_message`
--
ALTER TABLE `wx_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wx_user`
--
ALTER TABLE `wx_user`
  ADD PRIMARY KEY (`FromUserName`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `wx_developer`
--
ALTER TABLE `wx_developer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `wx_message`
--
ALTER TABLE `wx_message`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
