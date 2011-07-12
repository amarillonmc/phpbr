-- 
-- 表的结构 `bra_mapweapon` 
-- 

DROP TABLE IF EXISTS bra_mapweapon;
CREATE TABLE bra_mapweapon (
  wid mediumint unsigned NOT NULL auto_increment,
  pls tinyint unsigned NOT NULL default 0,
  type tinyint unsigned NOT NULL default 0,
 `time` int(10) unsigned NOT NULL default 0,
	lpid smallint unsigned NOT NULL default 0,
	

  PRIMARY KEY  (wid)
) ENGINE=MyISAM;

-- 
-- 表的结构 `bra_log` 
-- 类型：c对话、t队友、b作战、s系统 
-- 

DROP TABLE IF EXISTS bra_log;
CREATE TABLE bra_log (
  lid mediumint unsigned NOT NULL auto_increment,
  toid smallint unsigned NOT NULL default 0,
  type char(1) NOT NULL default '',
 `time` int(10) unsigned NOT NULL default 0,
 `log` text NOT NULL default '',

  PRIMARY KEY  (lid)
) ENGINE=MyISAM;

-- 
-- 表的结构 `bra_chat` 
-- 0  全员, 1  队伍, 2  地区, 3  遗言, 4  公告, 5  系统
-- 

DROP TABLE IF EXISTS bra_chat;
CREATE TABLE bra_chat (
  cid smallint unsigned NOT NULL auto_increment,
  type tinyint(1) unsigned NOT NULL default 0,
 `time` int(10) unsigned NOT NULL default 0,
  send char(24) NOT NULL default '',
  recv char(15) NOT NULL default '',
  msg char(60) NOT NULL default '',

  PRIMARY KEY  (cid)
) ENGINE=HEAP;

-- 
-- 表的结构 `bra_noise` 
-- 储存枪声、爆炸声等的信息
-- 

DROP TABLE IF EXISTS bra_noise;
CREATE TABLE bra_noise (
  nid mediumint unsigned NOT NULL auto_increment,
  type char(1) NOT NULL default '',
 `time` int(10) unsigned NOT NULL default 0,
  pls tinyint unsigned NOT NULL default 0,
  pid1 smallint unsigned NOT NULL default 0,
  pid2 smallint unsigned NOT NULL default 0,

  PRIMARY KEY  (nid)
) ENGINE=HEAP;

-- 
-- 表的结构 `bra_mapitem` 
-- 储存地图道具的信息 
-- 

DROP TABLE IF EXISTS bra_mapitem;
CREATE TABLE bra_mapitem (
  iid mediumint unsigned NOT NULL auto_increment,
  itm varchar(30) NOT NULL default '',
  itmk char(5) not null default '',
  itme smallint unsigned NOT NULL default 0,
  itms char(5) not null default 0,
  itmsk varchar(30) not null default '',
  itmnp smallint unsigned NOT NULL default 0,
  pls tinyint unsigned not null default 0,
  
  PRIMARY KEY  (iid)
) ENGINE=MyISAM;

-- 
-- 表的结构 `bra_maptrap` 
-- 储存地图陷阱的信息 
-- 

DROP TABLE IF EXISTS bra_maptrap;
CREATE TABLE bra_maptrap (
  tid mediumint unsigned NOT NULL auto_increment,
  itm varchar(30) NOT NULL default '',
  itmk char(5) not null default '',
  itme smallint unsigned NOT NULL default 0,
  itms char(5) not null default 0,
  itmsk varchar(30) not null default '',
  itmnp smallint unsigned NOT NULL default 0,
  pls tinyint unsigned not null default 0,
  
  PRIMARY KEY  (tid)
) ENGINE=MyISAM;

-- 
-- 表的结构 `bra_newsinfo` 
-- 储存游戏进行状况的信息 
-- 

DROP TABLE IF EXISTS bra_newsinfo;
CREATE TABLE bra_newsinfo (
 `nid` smallint unsigned NOT NULL auto_increment,
 `time` int(10) unsigned NOT NULL default 0,
 `news` char(15) NOT NULL default '',
 `a` varchar(255) NOT NULL default '',
 `b` varchar(255) NOT NULL default '',
 `c` varchar(255) NOT NULL default '',
 `d` varchar(255) NOT NULL default '',
 `e` varchar(255) NOT NULL default '',

  PRIMARY KEY  (nid)
) ENGINE=MyISAM;

#-- 
#-- 表的结构 `bra_script` 
#-- 储存游戏剧本的信息 
#-- 
#
#DROP TABLE IF EXISTS bra_script;
#CREATE TABLE bra_script (
# `sid` smallint unsigned NOT NULL auto_increment,
# `name` varchar(15) NOT NULL default '',
# `cmd` varchar(255) NOT NULL default '',
# `done` tinyint(1) NOT NULL default 0,
# `time` int(10) unsigned NOT NULL default 0,
#
#  PRIMARY KEY  (sid)
#) ENGINE=MyISAM;