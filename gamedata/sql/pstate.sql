--
-- 表的结构 `bra_pstate`
-- 储存玩家临时信息
--

DROP TABLE IF EXISTS bra_pstate;
CREATE TABLE bra_pstate (
  pid mediumint unsigned NOT NULL default '0',
  cdsec int(10) unsigned NOT NULL default '0',
  cdmsec smallint(3) unsigned NOT NULL default '0',
  cdtime mediumint unsigned NOT NULL default '0',
  cmd char(12) NOT NULL default '',

  PRIMARY KEY (pid)
) ENGINE=HEAP;



