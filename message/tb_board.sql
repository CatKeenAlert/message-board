-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 02 月 13 日 08:15
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `board`
--

-- --------------------------------------------------------

--
-- 表的结构 `tb_board`
--

CREATE TABLE IF NOT EXISTS `tb_board` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言id',
  `username` char(16) NOT NULL COMMENT '留言用户名',
  `content` varchar(100) NOT NULL COMMENT '留言内容',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '留言时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='留言表' AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `tb_board`
--

INSERT INTO `tb_board` (`id`, `username`, `content`, `time`) VALUES
(1, '二大爷', '二大爷', '2014-02-10 10:02:14'),
(2, '一大爷', '二大爷', '2014-02-10 10:08:47'),
(3, '三大爷', '二大爷', '2014-02-10 10:09:02'),
(4, '1111', '1111', '2014-02-11 04:06:01'),
(5, '', '222', '2014-02-13 03:13:21'),
(6, '', '333', '2014-02-13 03:13:40'),
(7, '444', '444', '2014-02-13 03:14:02'),
(8, '555', '555', '2014-02-13 06:12:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
