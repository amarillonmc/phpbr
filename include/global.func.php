<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function gstrfilter($str) {
	global $magic_quotes_gpc;
	if(is_array($str)) {
		foreach($str as $key => $val) {
			$str[$key] = gstrfilter($val);
		}
	} else {		
		if($magic_quotes_gpc) {
			$str = stripslashes($str);
		}
		$str = str_replace("'","",$str);//屏蔽单引号'
		$str = str_replace("\\","",$str);//屏蔽反斜杠/
		$str = htmlspecialchars($str,ENT_COMPAT);//转义html特殊字符，即"<>&
	}
	return $str;
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
		if(!file_exists($objfile) || filemtime($tplfile) > filemtime($objfile)) {
			require_once GAME_ROOT.'./include/template.func.php';
			parse_template($file, $templateid, $tpldir);
		}
	}
	return $objfile;
}

function gexit($message = '',$file = '', $line = 0) {
	global $charset,$title,$extrahead,$allowcsscache,$errorinfo,$cuser,$cpass;
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

function player_save($data) {
	global $db,$tablepre;
	$pid = $data['pid'];
	unset($data['pid']);
	unset($data['name']);
	unset($data['sNo']);
	unset($data['type']);//防止影响索引
	if(isset($data['mapprop'])){unset($data['mapprop']);}
	if(isset($data['display'])){unset($data['display']);}
	$db->array_update("{$tablepre}players",$data,"pid='$pid'");
	return;
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

function naddnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '') {
	add_multi_news(array(array($t,$n,$a,$b,$c,$d,$e)));
//	global $now,$db,$tablepre;
//	$t = $t ? $t : $now;
//	$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
//	touch($newsfile);
//	if(is_array($a)){
//		$a=implode('_',$a);
//	}
//	if(strpos($n,'death11') === 0  || strpos($n,'death32') === 0 || strpos($n,'death34') === 0) {//这几个死法还需要加入称号功能
//		$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
//		if($db->num_rows($result)){
//			$e = $lastword = $db->result($result, 0);
//		}else{
//			$e = $lastword = 'addnews判断遗言出错，请检查';
//		}		
//		$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','$a','$c','$lastword')");
//	}	elseif(strpos($n,'death15') === 0 || strpos($n,'death16') === 0) {
//		$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
//		$e = $lastword = $db->result($result, 0);
//		$result = $db->query("SELECT pls FROM {$tablepre}players WHERE name = '$a' AND type = '0'");
//		$place = $db->result($result, 0);
//		$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','$a','$place','$lastword')");
//	}
//	$db->query("INSERT INTO {$tablepre}newsinfo (`time`,`news`,`a`,`b`,`c`,`d`,`e`) VALUES ('$t','$n','$a','$b','$c','$d','$e')");
//	
	return;
}

function add_multi_news($news){
	global $now,$db,$tablepre,$gamenum;
	if(!is_array($news) || empty($news)){return;}
	$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
	touch($newsfile);
	$usersread = $playersread = false;
	$chatqry = '';$newsqry = '';
	foreach($news as $val){
		$t = $val[0] ? $val[0] : $now;
		if(is_array($val[2])){
			$a=implode('_',$val[2]);
		}else{
			$a = $val[2];
		}
		$n = $val[1];
		$b = isset($val[3]) ? $val[3] : '';
		$c = isset($val[4]) ? $val[4] : '';
		$d = isset($val[5]) ? $val[5] : '';
		$e = isset($val[6]) ? $val[6] : '';
		if(in_array(substr($n,5),array(11,15,16,32,34))){
			if(!$usersread){
				$lwlist = array();
				$result = $db->query("SELECT username,lastword FROM {$tablepre}users WHERE lastgame = '$gamenum'");
				while($udata = $db->fetch_array($result)){
					$lwlist[$udata['username']] = $udata['lastword'];
				}
				$db->free_result($result);
				$usersread = true;
			}
			$lastword = $lwlist[$a];
			if(in_array(substr($n,5),array(15,16))){
				if(!$playersread){
					$plslist = array();
					$result = $db->query("SELECT name,pls FROM {$tablepre}players WHERE name = '$a' AND (type = '0' OR type = '100')");
					while($pldata = $db->fetch_array($result)){
						$plslist[$pldata['name']] = $pldata['pls'];
					}
					$db->free_result($result);
					$playersread = true;
				}
				$place = $plslist[$a];
				$chatqry .= "('3','$t','$a','$place','$lastword'),";
			}else{
				$chatqry .= "('3','$t','$a','$c','$lastword'),";
			}
			$newsqry .= "('$t','$n','$a','$b','$c','$d','$lastword'),";
		}else{
			$newsqry .= "('$t','$n','$a','$b','$c','$d','$e'),";
		}		
	}
	if(!empty($chatqry)){
		$chatqry = "INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ".substr($chatqry,0,-1);
		$db->query($chatqry);
	}
	if(!empty($newsqry)){
		$newsqry = "INSERT INTO {$tablepre}newsinfo (`time`,`news`,`a`,`b`,`c`,`d`,`e`) VALUES ".substr($newsqry,0,-1);
		$db->query($newsqry);
	}
	return;
}

function systemchat($chatmsg,$t = 0){
//	multi_systemchat(array(array($chatmsg,$t)));
	global $now,$db,$tablepre;
	$t = $t ? $t : $now;
	if($chatmsg){
		$db->query("INSERT INTO {$tablepre}chat (type,`time`,msg) VALUES ('5','$t','$chatmsg')");
	}
	return;
}

//function multi_systemchat($chat){
//	global $now,$db,$tablepre;
//	if(!is_array($chat) || empty($chat)){return;}
//	$chatqry = '';
//	foreach($chat as $val){
//		if(!empty($val[0])){
//			$t = $val[1] ? $val[1] : $now;
//			$send = '';
//			$msg = $val[0];
//			$chatqry .= "('5','$t','$msg'),";
//		}		
//	}
//	if(!empty($chatqry)){
//		$chatqry = "INSERT INTO {$tablepre}chat (type,`time`,msg) VALUES ".substr($chatqry,0,-1);
//		$db->query($chatqry);
//	}
//	return;
//}

function get_areawords($num = 0){//0表示显示全部地区，-1表示显示全部禁区，-2表示显示下回禁区，正数表示显示第几个到第几个地区。
	global $hack,$areatime,$areahour,$areaadd,$areanum,$arealist,$mapdata;
	$areawords = '';
	$plsnum = count($arealist);
	$showbase = 0;
	$shownum = $plsnum;
	if($num > 0 && $num <= $plsnum){$shownum = $num;}
	elseif($num == -1){$shownum = $areanum +1;}
	elseif($num == -2){
		$showbase = $hack ? 0 : $areanum +1;
		$shownum = $areanum + $areaadd + 1;
	}
	for($i = $showbase;$i < $shownum;$i++){
		$areawords .= $areawords ? ','.$mapdata[$arealist[$i]]['name'] : $mapdata[$arealist[$i]]['name'];
	}
	return $areawords;
}

function logsave($pid,$time,$log = '',$type = 's'){
	global $db,$tablepre;
	$ldata['toid']=$pid;
	$ldata['type']=$type;
	$ldata['time']=$time;
	$ldata['log']=$log;
	//$db->query("INSERT INTO {$tablepre}log (toid,type,`time`,log) VALUES ('$pid','$type','$time','$log')");
	$db->array_insert("{$tablepre}log", $ldata);
	return;	
}

function save_gameinfo() {
	global $gamenum,$gamestate,$starttime,$winmode,$winner,$arealist,$areanum,$areatime,$weather,$hack,$validnum,$alivenum,$deathnum,$bossdeath,$arealock;
	if(!isset($gamenum)||!isset($gamestate)){return;}
	if($alivenum < 0){$alivenum = 0;}
	if($deathnum < 0){$deathnum = 0;}
	$gameinfo = "<?php\n\nif(!defined('IN_GAME')){exit('Access Denied');}\n\n\$gamenum = {$gamenum};\n\$gamestate = {$gamestate};\n\$starttime = {$starttime};\n\$winmode = {$winmode};\n\$winner = '{$winner}';\n\$arealist = array(".implode(',',$arealist).");\n\$areanum = {$areanum};\n\$areatime = {$areatime};\n\$weather = {$weather};\n\$hack = {$hack};\n\$validnum = {$validnum};\n\$alivenum = {$alivenum};\n\$deathnum = {$deathnum};\n\$bossdeath = {$bossdeath};\n\$arealock = {$arealock};\n\n?>";
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
	global $hdamage,$hplayer;//,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
	if(!$hdamage){$hdamage = 0;}
	$combatinfo = "<?php\n\nif(!defined('IN_GAME')){exit('Access Denied');}\n\n\$hdamage = {$hdamage};\n\$hplayer = '{$hplayer}';\n\n?>";
	//$combatinfo = "{$hdamage},{$hplayer},{$noisetime},{$noisepls},{$noiseid},{$noiseid2},{$noisemode},\n";
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

function get_noise($pid = -1, $limit = 0){
	global $now,$db,$tablepre,$noiselimit,$mapdata,$noiseinfo,$noiselimit,$noisenum;
	$noiselog = '';
	if(!$limit){
		$limit = $noisenum;
	}
	$nline = $now - $noiselimit;
	$result = $db->query("SELECT * FROM {$tablepre}noise WHERE pid1 != '$pid' AND pid2 != '$pid' AND time >= '$nline' ORDER BY nid DESC LIMIT $limit");
	while($ndata = $db->fetch_array($result)) {
		$ntime = $ndata['time'];
		$npls = $ndata['pls'];
		$ntype = $ndata['type'];
		$npid1 = $ndata['pid1'];
		$npid2 = $ndata['pid2'];
		if(($now-$ntime) < 60) {
			$nsec = $now - $ntime;
			$noiselog .= "<span class=\"yellow b\">{$nsec}秒前，{$mapdata[$npls]['name']}传来了{$noiseinfo[$ntype]}。</span><br>";
		} else {
			$nmin = floor(($now-$ntime)/60);
			$nsec = ($now - $ntime) % 60;
			$noiselog .= "<span class=\"yellow b\">{$nmin}分{$nsec}秒前，{$mapdata[$npls]['name']}传来了{$noiseinfo[$ntype]}。</span><br>";
		}
		
	}
	if(!$noiselog){
		$noiselog = '没有听到任何异常响动。<br>';
	}
	return $noiselog;
}

function getchat($last,$cteam='',$cpls=-1,$limit=0) {
	global $db,$tablepre,$chatlimit,$chatinfo,$mapdata;
	$limit = $limit ? $limit : $chatlimit;
	$result = $db->query("SELECT * FROM {$tablepre}chat WHERE cid > '$last' ORDER BY cid DESC LIMIT $limit");
	
	$chatdata = Array('lastcid' => $last, 'msg' => Array());
	if(!$db->num_rows($result)){return $chatdata;}
	
	while($chat = $db->fetch_array($result)) {
		if(!$chatdata['lastcid']){$chatdata['lastcid'] = $chat['cid'];}
		$chat['msg'] = htmlspecialchars($chat['msg']);
		$msg = '';
		if($chat['type'] == '0') {
			$msg = "【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'<br>';
		} elseif($chat['type'] == '1') {
			if(!empty($cteam) && $cteam == $chat['recv']){
				$msg = "<span class=\"clan\">【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
			}
		} elseif($chat['type'] == '2') {
			if($cpls >= 0 && $cpls == $chat['recv']){
				$msg = "<span class=\"lime\">【{$chatinfo[$chat['type']]}】〖{$mapdata[$chat['recv']]['name']}〗{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
			}
		} elseif($chat['type'] == '3') {
			if ($chat['msg']){
				$msg = "<span class=\"red\">【{$chatinfo[$chat['type']]}】〖{$mapdata[$chat['recv']]['name']}〗{$chat['send']}：{$chat['msg']} ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			} else {
				$msg = "<span class=\"red\">【{$chatinfo[$chat['type']]}】〖{$mapdata[$chat['recv']]['name']}〗{$chat['send']} 什么都没说就死去了 ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			}
		} elseif($chat['type'] == '4') {
			$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		} elseif($chat['type'] == '5') {
			$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		}
		if(!empty($msg)){$chatdata['msg'][$chat['cid']] = $msg;}
	}
	return $chatdata;
}

function get_neighbor_map($pls) {
	global $hack,$mapdata,$arealist,$areanum,$arealock;
	$nmap = Array();
	$forbidden = array_slice($arealist,0,$areanum+1);
	foreach($mapdata[$pls]['moveto'] as $map){
		if(($map != 30 && (!in_array($map,$forbidden) || $hack)) || ($map == 30 && !$arealock)){
			$distance = pow(($mapdata[$pls]['mapx']-$mapdata[$map]['mapx']),2) + pow(($mapdata[$pls]['mapy']-$mapdata[$map]['mapy']),2);
			if($distance <= 2){$movesp = 15;}
			elseif($distance <=8){$movesp = 20;}
			elseif($distance <=18){$movesp = 25;}
			else{$movesp = 30;}
			$nmap[$map] = $movesp;
		}
	}
	return $nmap;
}

function get_mapweapon(){
	global $now,$db,$tablepre,$mapdata,$mapweaponinfo,$deathnum;
	$result = $db->query("SELECT * FROM {$tablepre}mapweapon WHERE time <= '$now' ORDER BY time DESC");
	$qry = $hpcase = $statecase = $bidcase = $lasteffcase = '';
	$adnws = array();
//	$mwdata = array();
//	while($md = $db->fetch_array($result)) {
//		$mwdata[] = $md;
//	}
//	$result = $db->query("SELECT * FROM {$tablepre}mapweapon WHERE time <= '$now' ORDER BY time DESC");
	while($mwdata = $db->fetch_array($result)) {
		$mwtime = $mwdata['time'];
		$mwpls = $mwdata['pls'];
		$mwtype = $mwdata['type'];
		$mwlpid = $mwdata['lpid'];
		$mwlog = "{$mapdata[$mwpls]['name']}遭到了{$mapweaponinfo[$mwtype]['name']}的打击！";
		systemchat($mwlog,$mwtime);
		$adnws[] = array($mwtime, 'MAPWexpl', $mwpls, $mapweaponinfo[$mwtype]['dmgnm']);
		//naddnews($mwtime, 'MAPWexpl', $mwpls, $mapweaponinfo[$mwtype]['dmgnm']);
		$parray = Array();
		$result2 = $db->query("SELECT pid, name, type, hp, state FROM {$tablepre}players WHERE pls = '$mwpls' AND hp > 0 AND type = 0");
		while($pldata = $db->fetch_array($result2)) {
			extract($pldata,EXTR_REFS);
			$hp -= $mapweaponinfo[$mwtype]['dmg'];
			
			$plog = "你遭到了{$mapweaponinfo[$mwtype]['dmgnm']}的攻击！受到<span class=\"red\">{$mapweaponinfo[$mwtype]['dmg']}</span>点伤害！";
			logsave($pid,$mwtime,$plog);
			
			if($hp <= 0){
				$hp = 0; $state = $mapweaponinfo[$mwtype]['state'];
				$adnws[] = array($mwtime, 'death34', $name, $type, $mwpls,$mapweaponinfo[$mwtype]['dmgnm']);
				//naddnews($mwtime, 'death34', $name, $type, $mwpls,$mapweaponinfo[$mwtype]['dmgnm']);
				$deathnum++;
			}
			unset($pldata['name'],$pldata['type']);
			$parray[] = $pldata;
//			$qry =
//			"UPDATE {} SET hp = CASE";
		}
		save_gameinfo();
		$db->multi_update("{$tablepre}players", $parray, 'pid', " pls='$mwpls'", "lasteff = '$now'");
	}
	add_multi_news($adnws);
	$db->query("DELETE FROM {$tablepre}mapweapon WHERE time <= '$now'");
	return;
}

function compatible_json_encode($data){	//自动选择使用内置函数或者自定义函数，结合JSON.php可做到兼容低版本PHP
	if(PHP_VERSION < '5.2.0'){
		require_once GAME_ROOT.'./include/JSON.php';
		$json = new Services_JSON();
		$jdata = $json->encode($data);
	} else{
		$jdata = json_encode($data);
	}
	return $jdata;	
}

function getmicrotime(){
	global $moveut,$moveutmin;
	list($usec, $sec) = explode(" ",microtime());
	$sec = $sec + $moveut * 3600 + $moveutmin;
	return ((float)$usec + (float)$sec);
}

function putmicrotime($t_s,$t_e,$file,$info)
{
	$mtime = ($t_e - $t_s)*1000;
	writeover( $file.'.txt',"$info ；执行时间：$mtime 毫秒 \n",'ab');
}

function rep_label($str){
	if(strpos($str,'[br]')!==false){
		$str = str_replace('[br]','<br />',$str);
	}
	foreach(Array('b','i','u','strike') as $val){
		if(preg_match("/\[$val\].+?\[\/$val\]/is",$str)){
			$str = preg_replace("/\[$val\](.+?)\[\/$val\]/is","<$val>\\1</$val>",$str);
		}
	}
	foreach(Array('red','yellow','green','lime','blue','aqua','purple') as $val){
		if(preg_match("/\[$val\].+?\[\/$val\]/is",$str)){
			$str = preg_replace("/\[$val\](.+?)\[\/$val\]/is","<font color=\"$val\">\\1</font>",$str);
		}
	}
	
	if(strpos($str,'[img]')!==false && strpos($str,'[/img]')!==false){
		$str = preg_replace('/\[img\](.+?)\[\/img\]/is', '<div style="text-align:center"><img id="\\1" src="\\1" onload="imageAutoSizer(\'\\1\', 400, 500)"></div>', $str);
	}
	return $str;
}

?>