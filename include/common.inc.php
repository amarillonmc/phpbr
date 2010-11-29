<?php

error_reporting(0);
set_magic_quotes_runtime(0);

define('IN_GAME', TRUE);
define('GAME_ROOT', substr(dirname(__FILE__), 0, -7));
define('GAMENAME', 'bra');

if(PHP_VERSION < '4.3.0') {
	exit('PHP version must >= 4.3.0!');
}
require_once GAME_ROOT.'./include/global.func.php';
require_once GAME_ROOT.'./config.inc.php';

$now = time() + $moveut*3600;   
list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));

$magic_quotes_gpc = get_magic_quotes_gpc();
extract(gaddslashes($_COOKIE));
extract(gaddslashes($_POST));
if(!$magic_quotes_gpc) {
	$_FILES = gaddslashes($_FILES);
}

if($attackevasive) {
	include_once GAME_ROOT.'./include/security.inc.php';
}

if($gzipcompress && function_exists('ob_gzhandler') && CURSCRIPT != 'wap') {
	ob_start('ob_gzhandler');
} else {
	$gzipcompress = 0;
	ob_start();
}

require_once GAME_ROOT.'./include/db_'.$database.'.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
$db->select_db($dbname);

require_once GAME_ROOT.'./gamedata/system.php';
require_once GAME_ROOT.'./gamedata/gameinfo.php';
require_once config('gamecfg',$gamecfg);

if(!$gamestate) { 
	if(($starttime)&&($now > $starttime - $startmin*60)) {
		include_once GAME_ROOT.'./include/system.func.php';
		rs_game(1+2+4+8+16+32);
		$gamenum++;
		$gamestate = 10;
		$hdamage = 0;
		$hplayer = '';
		$noisemode = '';
		save_gameinfo();
	}
}
if($gamestate == 10) {
	if($now >= $starttime) {
		$gamestate = 20;
		addnews($starttime,'newgame',$gamenum);
		save_gameinfo();
	}
}
if (($gamestate > 10)&&($now > $areatime)) {
	include_once GAME_ROOT.'./include/system.func.php';
	addarea($areatime);
	save_gameinfo();
}

if($gamestate == 20) {
	$arealimit = $arealimit > 0 ? $arealimit : 1; 
	if(($validnum <= 0)&&($areanum >= $arealimit*$areaadd)) {
		gameover($areanum,'end4');
	} elseif(($areanum >= $arealimit*$areaadd) || ($validnum >= $validlimit)) {
		$gamestate = 30;
		save_gameinfo();
	}
}

if((($gamestate == 30)&&($alivenum <= $combolimit))||($deathlimit&&($gamestate < 40)&&($gamestate >= 20)&&($deathnum >= $deathlimit))) {
	$gamestate = 40;
	$db->query("UPDATE {$tablepre}players SET teamID='',teamPass='' WHERE type=0 ");
	addnews($now,'combo');
	save_gameinfo();
}

if($gamestate == 40) {
	if($alivenum <= 1) {
		include_once GAME_ROOT.'./include/system.func.php';
		gameover();
	}
}

$cuser = & ${$tablepre.'user'};
$cpass = & ${$tablepre.'pass'};

$combatinfo = file_get_contents(GAME_ROOT.'./gamedata/combatinfo.php');
list($hdamage,$hplayer,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode) = explode(',',$combatinfo);

?>