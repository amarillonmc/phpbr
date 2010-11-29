<?php

define('CURSCRIPT', 'end');

require_once './include/common.inc.php';
require_once './include/game.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { header("Location: index.php");exit(); }

$pdata = $db->fetch_array($result);
if($pdata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$tablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	if($password == $cpass) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}

extract($pdata);
init_playerdata();

if($hp<=0 || $state>=10) {
	$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username='$name'");
	$motto = $db->result($result,0);
	$dtime = date("Y 年 m 月 d 日 H 时 i 分 s 秒",$endtime);
	$dinfo = Array(10 => '未知原因',11 => '禁区停留',12 => '中毒', 13 => '意外事故',14 => '项圈爆炸', 15 => '政府处决', 16 => '政府处决', 20 => '空手格杀', 21 => '钝器殴打致死', 22 => '利器斩杀', 23 => '枪械射杀', 24 => '重物投掷致死', 25 => '爆炸致死', 26 => '食用毒物', 27 => '误中陷阱', 28 => '死亡笔记',29 => '符咒打飞', 30 => 'RP过低', 31 => 'L5病发');
	$result = $db->query("SELECT name FROM {$tablepre}players WHERE pid='$bid'");
	if($db->num_rows($result)) { $kname = $db->result($result,0); }
}

include template('end');


?>