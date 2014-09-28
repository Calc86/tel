-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 15 2013 г., 14:40
-- Версия сервера: 5.0.88
-- Версия PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `asterisk`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cdr`
--

DROP TABLE IF EXISTS `cdr`;
CREATE TABLE IF NOT EXISTS `cdr` (
  `id` int(11) NOT NULL auto_increment,
  `calldate` datetime NOT NULL default '0000-00-00 00:00:00',
  `clid` varchar(80) character set latin1 NOT NULL default '',
  `src` varchar(80) character set latin1 NOT NULL default '',
  `dst` varchar(80) character set latin1 NOT NULL default '',
  `dcontext` varchar(80) character set latin1 NOT NULL default '',
  `channel` varchar(80) character set latin1 NOT NULL default '',
  `dstchannel` varchar(80) character set latin1 NOT NULL default '',
  `lastapp` varchar(80) character set latin1 NOT NULL default '',
  `lastdata` varchar(80) character set latin1 NOT NULL default '',
  `duration` int(11) NOT NULL default '0',
  `billsec` int(11) NOT NULL default '0',
  `disposition` varchar(45) character set latin1 NOT NULL default '',
  `amaflags` int(11) NOT NULL default '0',
  `accountcode` varchar(20) character set latin1 NOT NULL default '',
  `userfield` varchar(255) character set latin1 NOT NULL default '',
  `uniqueid` varchar(32) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`),
  KEY `lastapp` (`lastapp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL auto_increment,
  `dtype` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `mac` varchar(50) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `soft_ver` varchar(50) NOT NULL,
  `hard_ver` varchar(50) NOT NULL,
  `user` varchar(50) NOT NULL,
  `admin` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dial_opts`
--

DROP TABLE IF EXISTS `dial_opts`;
CREATE TABLE IF NOT EXISTS `dial_opts` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `pid1` int(11) NOT NULL,
  `pid2` int(11) NOT NULL,
  `rule` varchar(255) NOT NULL,
  `prior` int(11) NOT NULL,
  `internal` int(11) NOT NULL,
  `context` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dtypes`
--

DROP TABLE IF EXISTS `dtypes`;
CREATE TABLE IF NOT EXISTS `dtypes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `cost` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `filter`
--

DROP TABLE IF EXISTS `filter`;
CREATE TABLE IF NOT EXISTS `filter` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `str` varchar(255) NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `inbound_opts`
--

DROP TABLE IF EXISTS `inbound_opts`;
CREATE TABLE IF NOT EXISTS `inbound_opts` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pid` (`pid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `money`
--

DROP TABLE IF EXISTS `money`;
CREATE TABLE IF NOT EXISTS `money` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `day` int(11) NOT NULL,
  `plus` int(11) NOT NULL,
  `sum` float NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `operation` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `operation` (`operation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `num_pool`
--

DROP TABLE IF EXISTS `num_pool`;
CREATE TABLE IF NOT EXISTS `num_pool` (
  `id` int(11) NOT NULL auto_increment,
  `prid` int(11) NOT NULL,
  `no` varchar(255) NOT NULL,
  `lines` int(11) NOT NULL,
  `dt` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `descr` text NOT NULL,
  `cost` double NOT NULL,
  `cost2` double NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `org`
--

DROP TABLE IF EXISTS `org`;
CREATE TABLE IF NOT EXISTS `org` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `money` float NOT NULL default '0',
  `fullname` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `peers`
--

DROP TABLE IF EXISTS `peers`;
CREATE TABLE IF NOT EXISTS `peers` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `nid` int(11) NOT NULL,
  `ban` int(11) NOT NULL,
  `call_limit` int(11) NOT NULL default '10',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `price`
--

DROP TABLE IF EXISTS `price`;
CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL auto_increment,
  `ptid` int(11) NOT NULL,
  `prid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `kod` varchar(255) NOT NULL,
  `cost` float NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ptid` (`ptid`,`kod`),
  KEY `kod` (`kod`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pt`
--

DROP TABLE IF EXISTS `pt`;
CREATE TABLE IF NOT EXISTS `pt` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stat`
--

DROP TABLE IF EXISTS `stat`;
CREATE TABLE IF NOT EXISTS `stat` (
  `id` int(11) NOT NULL auto_increment,
  `cdrid` int(11) NOT NULL,
  `direction` varchar(20) NOT NULL,
  `cd` datetime NOT NULL,
  `ut` datetime NOT NULL,
  `src` varchar(255) NOT NULL,
  `dst` varchar(255) NOT NULL,
  `kod` varchar(255) NOT NULL,
  `ch` varchar(255) NOT NULL,
  `dstch` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `billsec` int(11) NOT NULL,
  `cause` varchar(20) NOT NULL,
  `uniqueid` varchar(32) NOT NULL,
  `minute` float NOT NULL,
  `cost` float NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cdrid` (`cdrid`),
  KEY `cd` (`cd`),
  KEY `ch` (`ch`),
  KEY `dstch` (`dstch`),
  KEY `test12` (`direction`,`cd`,`cause`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stat_03`
--

DROP TABLE IF EXISTS `stat_03`;
CREATE TABLE IF NOT EXISTS `stat_03` (
  `kod` varchar(255) NOT NULL,
  `billsec` int(11) NOT NULL,
  `ch` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `intno` varchar(20) NOT NULL,
  `secret` varchar(20) NOT NULL,
  `pid` int(11) NOT NULL,
  `dtmfmode` varchar(50) NOT NULL default 'auto',
  `flags` int(11) NOT NULL,
  `call_limit` int(11) NOT NULL default '10',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `intno` (`intno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
