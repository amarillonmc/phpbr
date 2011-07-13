<?php

define('CURSCRIPT', 'login');

require './include/common.inc.php';

include './include/user.func.php';
include './gamedata/banlist.php';

if(isset($cuser) && isset($cpass)){
	gexit($_ERROR['logged_in'],__file__,__line__);
}elseif(!$allowreg){
	gexit($_ERROR['reg_off'],__file__,__line__);
}
if(!isset($cmd)){
	$ustate = 'register';
	$icon = 0;
	$gender = 'm';
	$iconarray = get_iconlist();
	$select_icon = 0;
	$motto = $criticalmsg = $killmsg = $lastword = '';
	include template('register');
}elseif($cmd = 'post_register'){
	$ustate = 'register';
	$gamedata = Array();
	$name_check = name_check($username);
	$pass_check = pass_check($npass,$rnpass);
	$onlineip = real_ip();
	
	if($name_check!='name_ok'){
		$gamedata['info'] = $_ERROR[$name_check];
	}elseif($pass_check!='pass_ok'){
		$gamedata['info'] = $_ERROR[$pass_check];
	}elseif(preg_match($iplimit,$onlineip)){
		$gamedata['info'] = $_ERROR['ip_banned'];
	}else{
		$result = $db->query("SELECT * FROM {$tablepre}users WHERE username = '$username'");
		if($db->num_rows($result) > 0) {
			$gamedata['info'] = $_ERROR['name_exists'];
		}else{//现在开始注册
			$groupid = 1;
			$credits = 0;
			$password = md5($npass);
			$result = $db->query("INSERT INTO {$tablepre}users (username,`password`,groupid,ip,credits,gender,`icon`,motto,criticalmsg,killmsg,lastword) VALUES ('$username', '$password', '$groupid', '$onlineip', '$credits', '$gender', '$icon', '$motto','$criticalmsg', '$killmsg', '$lastword')");
			if($result){
				$gamedata['info'] = $_INFO['reg_success'];
				$ustate = 'check';
				gsetcookie('user',$username);
				gsetcookie('pass',$password);
			}else{
				$gamedata['info'] = $_ERROR['db_failure'];
			}
		}
	}
	if($ustate == 'check'){
		$gamedata['postreg'] = '<input type="button" name="back" value="返回游戏首页" onclick="window.location.href=\'index.php\'">';
		ob_clean();
		$jgamedata = compatible_json_encode($gamedata);
		echo $jgamedata;
		ob_end_flush();
	}else{
		ob_clean();
		$jgamedata = compatible_json_encode($gamedata);
		echo $jgamedata;
		ob_end_flush();
	}
}

?>

