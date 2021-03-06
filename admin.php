<?php

define('CURSCRIPT', 'admin');

require './include/common.inc.php';
define('IN_ADMIN', TRUE);
require GAME_ROOT.'./gamedata/admincfg.php';
require GAME_ROOT.'./include/admin/admin.lang.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
elseif(($udata['groupid'] <= 1)&&($cuser!==$gamefounder)) { gexit($_ERROR['no_admin'], __file__, __line__); }

if($cuser===$gamefounder){$mygroup=10;}
else{$mygroup = $udata['groupid'];}
include template('header');
echo '<br>';
if($mode == 'admin') {
	include_once GAME_ROOT."./include/admin/{$command}.php";
	echo '<br><br>操作完成。<a href="admin.php">返回游戏管理</a><br>';
} elseif($mode) {
	include_once GAME_ROOT."./include/admin/{$mode}.php";
	echo '<br><br>操作完成。<a href="admin.php">返回游戏管理</a><br>';
} else {
	echo <<<EOT
<form method="post" id="admin" name="admin" style="margin: 0px" onsubmit="admin.php">
<input type="hidden" name="mode" value="admin">
<input type="radio" name="command" id="configmng" value="configmng"><a onclick=sl('configmng'); href="javascript:void(0);" >系统环境设置</a><br>
<input type="radio" name="command" id="systemmng" value="systemmng"><a onclick=sl('systemmng'); href="javascript:void(0);" >游戏环境配置</a><br>
<input type="radio" name="command" id="gamecfgmng" value="gamecfgmng"><a onclick=sl('gamecfgmng'); href="javascript:void(0);" >游戏数据配置</a><br>
<input type="radio" name="command" id="gamemng" value="gamemng"><a onclick=sl('gamemng'); href="javascript:void(0);" >当前游戏管理</a><br>
<input type="radio" name="command" id="gmlist" value="gmlist"><a onclick=sl('gmlist'); href="javascript:void(0);" >GM管理</a><br>
<input type="radio" name="command" id="urlist" value="urlist"><a onclick=sl('urlist'); href="javascript:void(0);" >用户管理</a><br>
<input type="radio" name="command" id="banlistmng" value="banlistmng"><a onclick=sl('banlistmng'); href="javascript:void(0);" >屏蔽列表管理</a><br>
<!--<input type="radio" name="command" id="checklog" value="checklog"><a onclick=sl('checklog'); href="javascript:void(0);" >查看操作纪录</a><br>-->
<input type="submit" name="submit" value="提交">
</form>
EOT;
}
include template('footer');

function adminlog($op,$an1='',$an2='',$an3=''){
	global $now,$cuser;
	$alfile = GAME_ROOT.'./gamedata/adminlog.php';
	if($op){
		$aldata = "$now,$cuser,$op,$an1,$an2,$an3,\n";
		writeover($alfile,$aldata,'ab+');
	}
	return;
}

function setadminlog($op,$an1='',$an2='',$an3=''){
	global $now,$cuser;
	if(!$op){
		return;
	}
	return "$now,$cuser,$op,$an1,$an2,$an3,\n";
}

function putadminlog($al){
	$alfile = GAME_ROOT.'./gamedata/adminlog.php';
	if($al){
		writeover($alfile,$al,'ab+');
	}
	return;
}

function getstart($start = 0,$mode = ''){
	global $showlimit;
	if($mode == 'up') {
		$start -= $showlimit;
		$start = $start <= 0 ? 0 : $start;
	} elseif($mode == 'down') {
		$start += $showlimit;
	} elseif($mode == 'ref') {
		$start = 0;
	} else {
		$start = $start ? $start : 0;
	}
	return $start;
}
function setconfig($string) {
	if(!get_magic_quotes_gpc()) {
		$string = str_replace('\'', '\\\'', $string);
	} else {
		$string = str_replace('\"', '"', $string);
	}
	return $string;
}

?>