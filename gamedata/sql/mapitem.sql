--
-- 表的结构 `bra_mapitem`
-- 储存地图道具（和玩家身上道具）的信息
--

DROP TABLE IF EXISTS bra_mapitem;
CREATE TABLE bra_mapitem (
  iid smallint unsigned NOT NULL auto_increment,
  itm char(30) NOT NULL default '',
  itmk char(5) not null default '',
  itme smallint unsigned NOT NULL default '0',
  itms char(5) not null default '0',
  itmsk char(5) not null default '',
  map tinyint unsigned NOT NULL default '0',
  
  PRIMARY KEY  (iid),
  INDEX MAP (map, itm)
) TYPE=MyISAM;