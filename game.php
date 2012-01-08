<?php

define('CURSCRIPT', 'game');

require './include/common.inc.php';
require GAME_ROOT.'./include/game.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
if($mode == 'quit') {

	gsetcookie('user','');
	gsetcookie('pass','');
	header("Location: index.php");
	exit();

}
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { header("Location: valid.php");exit(); }

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



if(($pdata['hp'] <= 0)||($gamestate === 0)) {
	header("Location: end.php");exit();
}

extract($pdata);

init_playerdata();
init_profile();

$log = '';

//显示枪声信息
if(($now <= $noisetime+$noiselimit)&&$noisemode&&($noiseid!=$pid)&&($noiseid2!=$pid)) {
	if(($now-$noisetime) < 60) {
		$noisesec = $now - $noisetime;
		$log .= "<span class=\"yellow b\">{$noisesec}秒前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
	} else {
		$noisemin = floor(($now-$noisetime)/60);
		$log .= "<span class=\"yellow b\">{$noisemin}分钟前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
	}
}

//读取玩家互动信息
$result = $db->query("SELECT time,log FROM {$tablepre}log WHERE toid = '$pid' ORDER BY time,lid");

while($logtemp = $db->fetch_array($result)){
	$log .= date("H:i:s",$logtemp['time']).'，'.$logtemp['log'].'<br />';
}
$db->query("DELETE FROM {$tablepre}log WHERE toid = '$pid'");

$chatdata = getchat(0,$teamID);

//判断冷却时间是否过去
if($coldtimeon){
	$cdover = $cdsec*1000 + $cdmsec + $cdtime;
	$nowmtime = floor(getmicrotime()*1000);
	$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
}
if($hp > 0 && $coldtimeon && $showcoldtimer && $rmcdtime){$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow\"></span>秒<script type=\"text/javascript\">demiSecTimerStarter($rmcdtime);</script><br>";}
include template('game');

?>