<?php

define('CURSCRIPT', 'game');

require_once './include/common.inc.php';
require_once GAME_ROOT.'./include/game.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
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
if(($now <= $noisetime+$noiselimit)&&$noisemode&&($noiseid!=$pid)&&($noiseid2!=$pid)) {
	if(($now-$noisetime) < 60) {
		$noisesec = $now - $noisetime;
		$log .= "<span class=\"yellow b\">{$noisesec}秒前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
	} else {
		$noisemin = floor(($now-$noisetime)/60);
		$log .= "<span class=\"yellow b\">{$noisemin}分钟前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
	}
}

//if(file_exists(GAME_ROOT."./gamedata/log/$pid.log")){
//	$log2 = readover(GAME_ROOT."./gamedata/log/$pid.log");
//	if($log2 != '\n');{
//		$log .= $log2;
//		writeover(GAME_ROOT."./gamedata/log/$pid.log", "\n", 'wb');
//	}
//} else {
//	writeover(GAME_ROOT."./gamedata/log/$pid.log", "\n", 'wb');
//}

$result = $db->query("SELECT * FROM {$tablepre}log WHERE toid = '$pid' AND isnew = 1 ORDER BY time,lid");
$db->query("UPDATE {$tablepre}log SET isnew = 0 WHERE toid = '$pid' AND isnew = 1");
while($logtemp = $db->fetch_array($result)){
	$log .= date("H:i:s",$logtemp['time']).'，'.$logtemp['log'].'<br />';
}


$chatdata = getchat(0,$teamID);

include template('game');

?>