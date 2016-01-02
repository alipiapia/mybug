-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- 主机: hvaornp0.zzcdb.dnstoo.com:4001
-- 生成日期: 2016 年 01 月 02 日 12:02
-- 服务器版本: 5.5.35
-- PHP 版本: 5.2.17
-- 
-- 数据库: `wodeshujuku`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `bug_user`
-- 

CREATE TABLE `bug_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `bug_user`
-- 

INSERT INTO `bug_user` VALUES (1, 'pp', 'c483f6ce851c9ecd9fb835ff7551737c');
