<?php

define('CURSCRIPT', 'game');

require './include/common.inc.php';
require './include/game.func.php';
require './include/display.func.php';
require './include/system.func.php';
//update_gamemap();
//update_radar();
active_AI();
if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
if($mode == 'quit') {

	gsetcookie('user','');
	gsetcookie('pass','');
	gsetcookie('ctrl','');
	header("Location: index.php");
	exit();

}
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { header("Location: valid.php");exit(); }

$pldata = $db->fetch_array($result);

if($pldata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$tablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	if($password == $cpass) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}

if(($pldata['hp'] <= 0)||($gamestate === 0)) {
	header("Location: end.php");exit();
}

//extract($pdata,EXTR_REFS);

//初始化同伴
if($companysystem && $pldata['company'] > 0){ 
	$company = $pldata['company'];
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid = '$company' AND type = 100");
	if(!$db->num_rows($result)) {
		$pldata['company'] = 0;
	}
	else{
		$cpdata = $db->fetch_array($result);
		if($cpdata['company'] != $pldata['pid']){
			unset($cpdata);
			$pldata['company'] = 0;
		}
	}
	if($pldata['company'] != 0){
		if($ctrl == 'cp' && $cpdata['hp']>0){
			$pdata = $cpdata;$cdata = $pldata;
		}elseif($ctrl == 'cp' && $cpdata['hp'] == 0){
			gsetcookie('ctrl','pl');
			$pdata = $pldata;$cdata = $cpdata;
		}else{
			$pdata = $pldata;$cdata = $cpdata;
		}
	}else{
		$pdata = $pldata;
	}
}else{
	$pdata = $pldata;
}
//if($ctrl == 'cp' && $companysystem && $pldata['company'] && $cpdata['hp'] > 0){
//	$pdata = $cpdata;
//}elseif($cpdata['hp'] == 0){
//	$pdata = $pldata;
//}else{
//	$pdata = $pldata;
//}

$pid = $pdata['pid'];
init_battlefield();
$pdata['mapprop'] = player_property($pdata);
//init_playerdata($pdata);

$log = '';
$noise = get_noise($pid);

//读取玩家互动信息
$result = $db->query("SELECT time,log FROM {$tablepre}log WHERE toid = '$pid' ORDER BY time,lid");

while($logtemp = $db->fetch_array($result)){
	$log .= date("H:i:s",$logtemp['time']).'，'.$logtemp['log'].'<br />';
}
$db->query("DELETE FROM {$tablepre}log WHERE toid = '$pid'");

//判断冷却时间是否过去
if($coldtimeon){
	$cdover = $pdata['lastcmd']*1000 + $pdata['cdmsec'] + $pdata['cdtime'];
	$nowmtime = floor(getmicrotime()*1000);
	$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
}
if($pdata['hp'] > 0 && $coldtimeon && $showcoldtimer && $rmcdtime){$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow\"></span>秒<script type=\"text/javascript\">demiSecTimerStarter($rmcdtime);</script><br>";}
if($pdata['inf']){
	check_cannot_cmd($pdata,1,1);
}

init_displaydata($pdata);
init_profile($pdata);
init_itemwords($pdata);
init_techniquewords($pdata);
$nmap = get_neighbor_map($pdata['pls']);
$gst = $gstate[$gamestate];
if(show_new_tech($pdata)){
	$log .= '<span class="yellow">你能学习新的技能！</span>';
	$canlearntech = true;
}else{
	$canlearntech = false;
}
ob_start();
if($pdata['state'] >=1 && $pdata['state'] <= 3){
	include template('rest');
	$cmd = ob_get_contents();
	$mode = 'rest';
}elseif($pdata['itms0']){
	include template('itemfind');
	$cmd = ob_get_contents();
	$mode = 'itemfind';
}else{
	include template('command');
	$cmd = ob_get_contents();
	$mode = 'command';
}
ob_end_clean();
include template('game');

?>