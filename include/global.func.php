<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function gaddslashes($string, $force = 0) {
	if(!$GLOBALS['magic_quotes_gpc'] || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = gaddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function language($file, $templateid = 0, $tpldir = '') {
	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$languagepack = GAME_ROOT.'./'.$tpldir.'/'.$file.'.lang.php';
	if(file_exists($languagepack)) {
		return $languagepack;
	} elseif($templateid != 1 && $tpldir != './templates/default') {
		return language($file, 1, './templates/default');
	} else {
		return FALSE;
	}
}

function template($file, $templateid = 0, $tpldir = '') {
	global $tplrefresh;

	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$tplfile = GAME_ROOT.'./'.$tpldir.'/'.$file.'.htm';
	$objfile = GAME_ROOT.'./gamedata/templates/'.$templateid.'_'.$file.'.tpl.php';
	if(TEMPLATEID != 1 && $templateid != 1 && !file_exists($tplfile)) {
		return template($file, 1, './templates/default/');
	}
	if($tplrefresh == 1) {
		if(filemtime($tplfile) > filemtime($objfile)) {
			require_once GAME_ROOT.'./include/template.func.php';
			parse_template($file, $templateid, $tpldir);
		}
	}
	return $objfile;
}

function gexit($message = '',$file = '', $line = 0) {
	global $charset,$title,$extrahead,$allowcsscache,$errorinfo;
	include template('error');
	exit();
}

function output($content = '') {
	//if(!$content){$content = ob_get_contents();}
	//ob_end_clean();
	//$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
	//echo $content;
	ob_end_flush();
}

function content($file = '') {
	ob_clean();
	include template($file);
	$content = ob_get_contents();
	ob_end_clean();
	$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
	return $content;
}

function gsetcookie($var, $value, $life = 0, $prefix = 1) {
	global $tablepre, $cookiedomain, $cookiepath, $now, $_SERVER;
	setcookie(($prefix ? $tablepre : '').$var, $value,
		$life ? $now + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function clearcookies() {
	global $cookiepath, $cookiedomain, $game_uid, $game_user, $game_pw, $game_secques, $adminid, $groupid, $credits;
	dsetcookie('auth', '', -86400 * 365);

	$game_uid = $adminid = $credits = 0;
	$game_user = $game_pw = $game_secques = '';
}

function config($file = '', $cfg = 1) {
	$cfgfile = file_exists(GAME_ROOT."./gamedata/cache/{$file}_{$cfg}.php") ? GAME_ROOT."./gamedata/cache/{$file}_{$cfg}.php" : GAME_ROOT."./gamedata/cache/{$file}_1.php";
	return $cfgfile;
}

function dir_clear($dir) {
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			unlink($filename);
		}
	}
	$directory->close();
}

//读取文件
function readover($filename,$method="rb"){
	strpos($filename,'..')!==false && exit('Forbidden');
	//$filedata=file_get_contents($filename);
	$handle=fopen($filename,$method);
	if(flock($handle,LOCK_SH)){
		$filedata='';
		while (!feof($handle)) {
   		$filedata .= fread($handle, 8192);
		}
		//$filedata.=fread($handle,filesize($filename));
		fclose($handle);
	} else {exit ('Read file error.');}
	return $filedata;
}

//写入文件
function writeover($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$check && strpos($filename,'..')!==false && exit('Forbidden');
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		if(flock($handle,LOCK_EX)){
			fwrite($handle,$data);
			if($method=="rb+") ftruncate($handle,strlen($data));
			fclose($handle); 
		} else {exit ('Write file error.');}
	} else {
		fwrite($handle,$data);
		if($method=="rb+") ftruncate($handle,strlen($data));
		fclose($handle); 
	}
	$chmod && chmod($filename,0777);
}

//打开文件，以数组形式返回
function openfile($filename){
	$filedata=readover($filename);
	$filedata=str_replace("\n","\n<:game:>",$filedata);
	$filedb=explode("<:game:>",$filedata);
	$count=count($filedb);
	if($filedb[$count-1]==''||$filedb[$count-1]=="\r"){unset($filedb[$count-1]);}
	if(empty($filedb)){$filedb[0]='';}
	return $filedb;
}


function addnews($t = '', $n = '', $a = '',$b = '', $c = '', $d = '') {
	global $now,$db,$tablepre;
	$t = $t ? $t : $now;
	$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
	$newsdata = readover($newsfile); //file_get_contents($newsfile);
	if(is_array($a)) {
		$news = "$t,$n,".implode('-',$a).",$b,$c,$d,\n";
	} elseif(isset($n)) {
		$news = "$t,$n,$a,$b,$c,$d,\n";
	}
	$newsdata = substr_replace($newsdata,$news,53,0);
	writeover($newsfile,$newsdata,'wb');
	
	if(strpos($n,'death11') === 0  || strpos($n,'death32') === 0) {
		$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
		$lastword = $db->result($result, 0);
		//$result = $db->query("SELECT pls FROM {$tablepre}players WHERE name = '$a' AND type = '$b'");
		//$pls = $db->result($result, 0);
		$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','$a','$c','$lastword')");
	}	elseif(strpos($n,'death15') === 0 || strpos($n,'death16') === 0) {
		$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
		$lastword = $db->result($result, 0);
		$result = $db->query("SELECT pls FROM {$tablepre}players WHERE name = '$a' AND type = '$b'");
		$pls = $db->result($result, 0);
		$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','$a','$pls','$lastword')");
	}
}

function logsave($pid,$time,$log = ''){
	$logfile = GAME_ROOT."./gamedata/log/$pid.log";
	$date = date("H:i:s",$time);
	$logdata = "{$date}，{$log}<br>\n";
	writeover($logfile,$logdata,'ab+');
	return;	
}

function save_gameinfo() {
	global $gamenum,$gamestate,$starttime,$winmode,$winner,$arealist,$areanum,$areatime,$weather,$hack,$validnum,$alivenum,$deathnum;
	if(!isset($gamenum)||!isset($gamestate)){return;}
	if($alivenum < 0){$alivenum = 0;}
	if($deathnum < 0){$deathnum = 0;}
	$gameinfo = "<?php\n\n\$gamenum = {$gamenum};\n\$gamestate = {$gamestate};\n\$starttime = {$starttime};\n\$winmode = {$winmode};\n\$winner = '{$winner}';\n\$arealist = array(".implode(',',$arealist).");\n\$areanum = {$areanum};\n\$areatime = {$areatime};\n\$weather = {$weather};\n\$hack = {$hack};\n\$validnum = {$validnum};\n\$alivenum = {$alivenum};\n\$deathnum = {$deathnum};\n\n?>";
	$dir = GAME_ROOT.'./gamedata/';
	if($fp = fopen("{$dir}gameinfo.php", 'w')) {
		if(flock($fp,LOCK_EX)) {
			fwrite($fp, $gameinfo);
		} else {
			exit("Couldn't save the game's info !");
		}
		fclose($fp);
	} else {
		gexit('Can not write to cache files, please check directory ./gamedata/ .', __file__, __line__);
	}
	return;
}

function save_combatinfo(){
	global $hdamage,$hplayer,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
	$combatinfo = "{$hdamage},{$hplayer},{$noisetime},{$noisepls},{$noiseid},{$noiseid2},{$noisemode},\n";
	$dir = GAME_ROOT.'./gamedata/';
	if($fp = fopen("{$dir}combatinfo.php", 'w')) {
		if(flock($fp,LOCK_EX)) {
			fwrite($fp, $combatinfo);
		} else {
			exit("Couldn't save combat info !");
		}
		fclose($fp);
	} else {
		gexit('Can not write to cache files, please check directory ./gamedata/ .', __file__, __line__);
	}
	return;
}

function getchat($last,$team='',$limit=0) {
	global $db,$tablepre,$chatlimit,$chatinfo,$plsinfo;
	$limit = $limit ? $limit : $chatlimit;
	$result = $db->query("SELECT * FROM {$tablepre}chat WHERE cid>$last AND (type!='1' OR (type='1' AND recv='$team')) ORDER BY cid desc LIMIT $limit");

	if(!$db->num_rows($result)){$chatdata = array('lastcid' => $last, 'msg' => '');return $chatdata;}
	
	while($chat = $db->fetch_array($result)) {
		if(!$chatdata['lastcid']){$chatdata['lastcid'] = $chat['cid'];}
		$chat['msg'] = htmlspecialchars($chat['msg']);
		if($chat['type'] == '0') {
			$msg = "【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'<br>';
		} elseif($chat['type'] == '1') {
			$msg = "<span class=\"clan\">【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		} elseif($chat['type'] == '3') {
			if ($chat['msg']){
				$msg = "<span class=\"red\">【{$plsinfo[$chat['recv']]}】{$chat['send']}：{$chat['msg']} ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			} else {
				$msg = "<span class=\"red\">【{$plsinfo[$chat['recv']]}】{$chat['send']} 什么都没说就死去了 ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			}
		} elseif($chat['type'] == '4') {
			$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		}
		$chatdata['msg'][$chat['cid']] = $msg;
	}
	return $chatdata;
}



?>