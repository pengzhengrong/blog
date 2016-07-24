create database blog;
use blog;

create  table `think_blog` (
  `id` int(11) unsigned not null  auto_increment,
  `cat_id` int(11) unsigned not null default 0,
  `title` varchar(50) not null default '',
  `click` smallint(6) unsigned not null  default 0,
  `created` int(11) not null default 0,
  `update_time` int(11) not null default 0,
  `time` int(11) not null default 0,
  `status` tinyint(1) not null default 0,
  `isdisplay` tinyint(1) not null default 0,
  primary key(`id`),
  key(`title`) ,
  key(`cat_id`),
  key(`created`)
)engine=myisam default charset=utf8 auto_increment=1;

create table `think_blog_data`(
 `id` int(11) unsigned not null,
 `content` text,
 `extra` text,
 unique(`id`)
)engine=myisam default charset=utf8 auto_increment=1;

create table `think_category`(
  `id` int(11) unsigned not null auto_increment,
  `title` varchar(50) not null default '',
  `sort` smallint(6) not null default 0,
  `pid`  int(11) not null default 0,
  `level` smallint(2) not null default 0,
  `status` tinyint(1) not null default 0,
  `isdisplay` tinyint(1) not null default 0,
  primary key(`id`),
  key(`title`)
)engine=myisam default charset=utf8 auto_increment=1;

-- alter table think_user add column `last_login` int(11) not null default 0;
-- alter table think_user add column `last_ip` varchar(16) not null default '';
-- alter table think_user add column `name` varchar(20) not null default '';
-- alter table think_user add column `email` varchar(20) not null default '';

create table `think_navigation`(
 `id` int(11) unsigned primary key auto_increment,
 `pid` int(11) unsigned not null default 0,
 `title` varchar(50) not null default '',
 `m` varchar(50) not null default '',
 `c` varchar(50) not null default '',
 `a` varchar(50) not null default '',
 `status` tinyint(1) not null default 0,
 `level` tinyint(1) not null default 1,
 `sort` tinyint(1) not null default 0,
 key(`m`,`c`,`a`)
)engine=myisam default charset=utf8 auto_increment=1;

create table `think_attr`(
 `id` int(11) unsigned primary key auto_increment,
 `blog_id` int(11) unsigned not null default 0,
 `status` tinyint(1) not null default 0,
 `extra` text
)engine=myisam default charset=utf8 auto_increment=1;

CREATE TABLE think_session (
       session_id varchar(255) NOT NULL,
       session_expire int(11) NOT NULL,
       session_data blob,
       UNIQUE KEY `session_id` (`session_id`)
     )engine=myisam default charset=utf8 ;

create table `think_user`(
  `id` int(11) unsigned auto_increment primary key,
  `username` varchar(20) not null default '',
  `password` varchar(100) not null default '',
  `last_login` int(11) not null default 0,
  `last_ip` varchar(16) not null default '',
  `name` varchar(20) not null default '',
  `extra` text ,
  `status` tinyint(1) not null default 0,
  `role` varchar(50) not null default '',
  `role_id` int(11) not null default 0,
  `created` int(11) not null default 0,
  key(`username`,`password`)
)engine=myisam default charset=utf8;

CREATE TABLE IF NOT EXISTS `think_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `think_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;









--------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `think_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `think_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




