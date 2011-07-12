<?php

define('CURSCRIPT', 'login');

require './include/common.inc.php';

if($mode == 'quit') {
	gsetcookie('user','');
	gsetcookie('pass','');
	gsetcookie('ctrl','');
	gsetcookie('promap','');
	header("Location: index.php");
	exit();

} elseif (isset($cuser) && isset($cpass)){
	gexit($_ERROR['logged_in'],__file__,__line__);
}
include './include/user.func.php';
include './gamedata/banlist.php';

$name_check = name_check($username);
$pass_check = pass_check($password,$password);
if($name_check!='name_ok'){
	gexit($_ERROR[$name_check],__file__,__line__);
}elseif($pass_check!='pass_ok'){
	gexit($_ERROR[$pass_check],__file__,__line__);
}

$onlineip = real_ip();

if(preg_match($iplimit,$onlineip)){
	gexit($_ERROR['ip_banned'],__file__,__line__);
}

$password = md5($password);
$groupid = 1;
$credits = 0;
$gender = 0;

$result = $db->query("SELECT * FROM {$tablepre}users WHERE username = '$username'");
if(!$db->num_rows($result)) {
	gexit($_ERROR['user_not_exists'],__file__,__line__);
	//$groupid = 1;
	//$db->query("INSERT INTO {$tablepre}users (username,`password`,groupid,ip,credits,gender) VALUES ('$username', '$password', '$groupid', '$onlineip', '$credits', '$gender')");
} else {
	$userdata = $db->fetch_array($result);
	if($userdata['groupid'] <= 0){
		gexit($_ERROR['user_ban'],__file__,__line__);
	} elseif($userdata['password'] != $password) {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}
$db->query("UPDATE {$tablepre}users SET ip='$onlineip' WHERE username = '$username'");
gsetcookie('user',$username);
gsetcookie('pass',$password);
gsetcookie('ctrl','pl');
gsetcookie('promap','pro');

Header("Location: index.php");
exit();

?>

