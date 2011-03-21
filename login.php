<?php

error_reporting(E_ERROR);
set_magic_quotes_runtime(0);
 
define('IN_GAME', TRUE);
define('GAME_ROOT', substr(dirname(__FILE__), 0, 0));
define('GAMENAME', 'bra');

if(PHP_VERSION < '4.3.0') {
	exit('PHP version must >= 4.3.0!');
}
require_once GAME_ROOT.'./include/global.func.php';
require_once GAME_ROOT.'./config.inc.php';

$now = time() + $moveut*3600 + $moveutmin*60;   

extract(gkillquotes($_COOKIE));
extract(gkillquotes($_POST));
unset($_GET);

//if($attackevasive) {
//	include_once GAME_ROOT.'./include/security.inc.php';
//}



if($mode == 'quit') {

	gsetcookie('user','');
	gsetcookie('pass','');
	header("Location: index.php");
	exit();

}

require_once GAME_ROOT.'./include/db_'.$database.'.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
$db->select_db($dbname);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
require_once GAME_ROOT.'./gamedata/system.php';
require_once GAME_ROOT.'./gamedata/resources.php';
require_once './gamedata/validlimit.php';
//if($gzipcompress && function_exists('ob_gzhandler') && CURSCRIPT != 'wap') {
//	ob_start('ob_gzhandler');
//} else {
//	$gzipcompress = 0;
//	ob_start();
//}
foreach($nmlimit as $value){
	if(!empty($value) && strpos($username,$value)!==false){
		gexit($_ERROR['banned_name'],__file__,__line__);
	}
}
if(!$username||!$password) {
	gexit($_ERROR['login_info'],__file__,__line__);
} elseif(preg_match("[,|>|<|;|'|\"]",$username)){
	gexit($_ERROR['invalid_name'],__file__,__line__);
} elseif(mb_strlen($username,'utf-8')>15) {
	gexit($_ERROR['long_name'],__file__,__line__);
} else{
	include_once GAME_ROOT.'./gamedata/system.php';

	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	foreach($iplimit as $value){
		$ippart=explode('.',$value);
		if(count($ippart)>1 && count($ippart)<4){//保证IP段有2-4个
			$value=str_replace('*','',implode('.',$ippart));
			if(strpos($onlineip,$value)===0){
				gexit($_ERROR['banned_ip'],__file__,__line__);
			}
		}
//		if(!empty($value) &&  strpos($value,'*')!==false){
//			$value = str_replace('*','',$value);
//		}
//		if(strpos($onlineip,$value)!==false){
//			gexit($_ERROR['banned_ip'],__file__,__line__);
//		}
	}
	$password = md5($password);
	$groupid = 1;
	$credits = 0;
	$gender = 0;

	$result = $db->query("SELECT * FROM {$tablepre}users WHERE username = '$username'");
	if(!$db->num_rows($result)) {
		$groupid = 1;
		$db->query("INSERT INTO {$tablepre}users (username,`password`,groupid,ip,credits,gender) VALUES ('$username', '$password', '$groupid', '$onlineip', '$credits', '$gender')");
	} else {
		$userdata = $db->fetch_array($result);
		if($userdata['groupid'] <= 0){
			gexit($_ERROR['user_ban'],__file__,__line__);
		} elseif($userdata['password'] != $password) {
			gexit($_ERROR['login_check'],__file__,__line__);
		}
	}
	gsetcookie('user',$username);
	gsetcookie('pass',$password);
}

Header("Location: index.php");
exit();

?>

