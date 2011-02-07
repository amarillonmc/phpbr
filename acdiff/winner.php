<?php

define('CURSCRIPT', 'winner');

require_once './include/common.inc.php';
if($command == 'info') {
	$result = $db->query("SELECT * FROM {$tablepre}winners WHERE gid='$gnum' LIMIT 1");
	$pdata = $db->fetch_array($result);
	$pdata['gdate'] = floor($pdata['gtime']/3600).':'.floor($pdata['gtime']%3600/60).':'.($pdata['gtime']%60);
	$pdata['gsdate'] = date("m/d/Y H:i:s",$pdata['gstime']);
	$pdata['gedate'] = date("m/d/Y H:i:s",$pdata['getime']);
	extract($pdata);
	include_once GAME_ROOT.'./include/game.func.php';
	init_playerdata();
	init_profile();
} elseif($command == 'news') {
	include_once  GAME_ROOT.'./include/news.func.php';
	$hnewsfile = GAME_ROOT."./gamedata/bak/{$gnum}_newsinfo.php";
	if(file_exists($hnewsfile)){
		$hnewsinfo = readover($hnewsfile);
	}
} else {
	if(!$start){
		$result = $db->query("SELECT gid,name,wep,wmode,getime,motto FROM {$tablepre}winners ORDER BY gid desc LIMIT $winlimit");
	} else {
		$result = $db->query("SELECT gid,name,wep,wmode,getime,motto FROM {$tablepre}winners WHERE gid<=$start ORDER BY gid desc LIMIT $winlimit");
	}
	while($wdata = $db->fetch_array($result)) {
		$wdata['date'] = date("m/d/Y \<\b\\r\> H:i:s",$wdata['getime']);
		$winfo[] = $wdata;
	}
	$listnum = floor($gamenum/$winlimit);

	for($i=0;$i<$listnum;$i++) {
		$snum = ($listnum-$i)*$winlimit;
		$enum = $snum-$winlimit+1;
		$listinfo .= "<input style='width: 120px;' type='button' value='{$snum} ~ {$enum} 回' onClick=\"document['list']['start'].value = '$snum'; document['list'].submit();\">";
		if(is_int(($i+1)/3)&&$i){$listinfo .= '<br>';}
	}
}

include_once template('winner');

?>