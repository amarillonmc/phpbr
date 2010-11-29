<?php

set_magic_quotes_runtime(0);
define('IN_GAME', TRUE);
define('GAME_ROOT', substr(dirname(__FILE__), 0, 0));
define('GAMENAME', 'bra');

extract(gaddslashes($_COOKIE));
extract(gaddslashes($_POST));
extract(gaddslashes($_GET));

require_once GAME_ROOT.'./config.inc.php';
require_once GAME_ROOT.'./include/db_'.$database.'.class.php';

$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
$db->select_db($dbname);

if($command == 'uninit') {
	$sql = "DROP TABLE IF EXISTS bra_users;DROP TABLE IF EXISTS bra_winners;DROP TABLE IF EXISTS bra_players;DROP TABLE IF EXISTS bra_chat;";
	$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
	$db->query($sql);
	echo "DATABASE is uninstalled.";

	dir_clear('./gamedata/');
	dir_clear('./gamedata/bak');
	dir_clear('./gamedata/cache');
	dir_clear('./gamedata/log');
	dir_clear('./gamedata/mapitem');
	dir_clear('./gamedata/shopitem');
	dir_clear('./gamedata/templates');
	dir_clear('./templates/default');
	
}

function dir_clear($dir) {

	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			@unlink($filename);
		}
	}
	$directory->close();

	echo '/$dir is deleted.';
}

?>

你确定要删除 生存游戏 吗?

<form>
<input type="radio" name="command" value="cancel" checked>取消<br>
<input type="radio" name="command" value="uninit"><font color="red"><b>确认删除</b></font><br>
<input type="submit" name="submit" value="提交"><br>
</form>

