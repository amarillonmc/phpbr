<?php

error_reporting(E_ERROR);
set_magic_quotes_runtime(0);

define('IN_GAME', TRUE);
define('GAME_ROOT', substr(dirname(__FILE__), 0, -7));
define('GAMENAME', 'bra');

if(PHP_VERSION < '4.3.0') {
	exit('PHP version must >= 4.3.0!');
}
require_once GAME_ROOT.'./include/global.func.php';
require_once GAME_ROOT.'./config.inc.php';

$now = time() + $moveut*3600 + $moveutmin*60;   
list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));

$magic_quotes_gpc = get_magic_quotes_gpc();
extract(gkillquotes($_COOKIE));
extract(gkillquotes($_POST));
unset($_GET);
if(!$magic_quotes_gpc) {
	$_FILES = gaddslashes($_FILES);
}


//if($attackevasive) {
//	include_once GAME_ROOT.'./include/security.inc.php';
//}

require_once GAME_ROOT.'./include/db_'.$database.'.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
$db->select_db($dbname);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

require_once GAME_ROOT.'./gamedata/system.php';
require_once GAME_ROOT.'./gamedata/resources.php';
require_once config('gamecfg',$gamecfg);
include_once GAME_ROOT.'./gamedata/gameinfo.php';
include_once GAME_ROOT.'./gamedata/combatinfo.php';

if($gzipcompress && CURSCRIPT != 'wap') {
	ob_start('ob_gzhandler');
} else {
	$gzipcompress = 0;
	ob_start();
}

//$gamestate状态：0-上局游戏结束；10-新游戏准备阶段；20-游戏开放激活；30-游戏停止激活；40-游戏连斗；50-游戏死斗。

if(!$gamestate) { 
	if(($starttime)&&($now > $starttime - $startmin*60)) {
		$gamenum++;
		$gamestate = 10;
		$hdamage = 0;
		$hplayer = '';
		$noisemode = '';
		//save_gameinfo();
		include_once GAME_ROOT.'./include/system.func.php';
		rs_game(1+2+4+8+16+32);
		save_gameinfo();
	}
}
if($gamestate == 10) {
	if($now >= $starttime) {
		$gamestate = 20;
		save_gameinfo();
		//addnews($starttime,'newgame',$gamenum);
		naddnews($starttime,'newgame',$gamenum);
		
	}
}
//if (($gamestate > 10)&&($now > $areatime)) {
//	include_once GAME_ROOT.'./include/system.func.php';
//	addarea($areatime);
//	save_gameinfo();
//}
//$combatinfo = file_get_contents(GAME_ROOT.'./gamedata/combatinfo.php');
//list($hdamage,$hplayer,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode) = explode(',',$combatinfo);

if (($gamestate > 10)&&($now > $areatime)) {
	include_once GAME_ROOT.'./include/system.func.php';
	while($now>$areatime){
		$o_areatime = $areatime;
		$areatime += $areahour*60;
		save_gameinfo();
		add_once_area($o_areatime);
		save_gameinfo();
	}
	//addarea($areatime);
}



if($gamestate == 20) {
	$arealimit = $arealimit > 0 ? $arealimit : 1; 
	if(($validnum <= 0)&&($areanum >= $arealimit*$areaadd)) {
		gameover($areatime-3599,'end4');
	} elseif(($areanum >= $arealimit*$areaadd) || ($validnum >= $validlimit)) {
		$gamestate = 30;
		save_gameinfo();
	}
}

if((($gamestate == 30)&&($alivenum <= $combolimit))||($deathlimit&&($gamestate < 40)&&($gamestate >= 20)&&($deathnum >= $deathlimit))) {
	$gamestate = 40;
	save_gameinfo();
	$db->query("UPDATE {$tablepre}players SET teamID='',teamPass='' WHERE type=0 ");
	naddnews($now,'combo');
	
}

if($gamestate == 40 || $gamestate == 50) {
	if($alivenum <= 1) {
		include_once GAME_ROOT.'./include/system.func.php';
		gameover();
	}
}

$cuser = & ${$tablepre.'user'};
$cpass = & ${$tablepre.'pass'};

?>