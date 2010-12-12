<?php

define('CURSCRIPT', 'game');

require_once './include/common.inc.php';
require_once GAME_ROOT.'./include/JSON.php';
require_once GAME_ROOT.'./include/game.func.php';
require_once config('combatcfg',$gamecfg);

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { header("Location: valid.php");exit(); }

$pdata = $db->fetch_array($result);
if($pdata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$tablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	if($password == $cpass) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}


if(($pdata['hp'] <= 0)||($gamestate === 0)) {
	$gamedata['url'] = 'end.php';
	ob_clean();
	$json = new Services_JSON();
	$jgamedata = $json->encode($gamedata);
	echo $jgamedata;
	ob_end_flush();
	exit;
}

extract($pdata);

if(($now <= $noisetime+$noiselimit)&&$noisemode&&($noiseid!=$pid)&&($noiseid2!=$pid)) {
	if(($now-$noisetime) < 60) {
		$noisesec = $now - $noisetime;
		$log .= "<span class=\"yellow b\">{$noisesec}秒前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
	} else {
		$noisemin = floor(($now-$noisetime)/60);
		$log .= "<span class=\"yellow b\">{$noisemin}分钟前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
	}
}

$log2 = readover(GAME_ROOT."./gamedata/log/$pid.log");
if($log2 != '\n'){
	$log .= $log2;
	writeover(GAME_ROOT."./gamedata/log/$pid.log", "\n", 'wb');
}

init_playerdata();

$gamedata = array();

$bid = ($mode == 'combat') ? $bid : 0;

if($command == 'menu') {
	$mode = 'command';
} elseif($mode == 'command') {
	if($command == 'move') {
		include_once GAME_ROOT.'./include/game/search.func.php';
		move($moveto);
	} elseif($command == 'search') {
		include_once GAME_ROOT.'./include/game/search.func.php';
		search();
	} elseif(strpos($command,'itm') === 0) {
		include_once GAME_ROOT.'./include/game/item.func.php';
		$item = substr($command,3);
		itemuse($item);
	} elseif(strpos($command,'rest') === 0) {
		$state = substr($command,4,1);
		$mode = 'rest';
	} elseif($command == 'itemmain') {
		$mode = $itemcmd;
	} elseif($command == 'special') {
		if($sp_cmd == 'sp_word'){
			include_once GAME_ROOT.'./include/game/special.func.php';
			getword();
		} 
		$mode = $sp_cmd;
	} elseif($command == 'team') {
		if($teamcmd == 'teamquit') {
			include_once GAME_ROOT.'./include/game/team.func.php';
			teamquit();
		} else {
			$mode = 'team';
		}
	}
} elseif($mode == 'item') {
	include_once GAME_ROOT.'./include/game/item2.func.php';
	$item = substr($command,3);
	$usemode($item);
} elseif($mode == 'itemmain') {
	include_once GAME_ROOT.'./include/game/itemmain.func.php';
	if($command == 'itemget') {
		itemget();
	} elseif($command == 'itemadd') {
		itemadd();
	} elseif($command == 'itemmerge') {
		if($merge2 == 'n'){itemadd();}
		else{itemmerge($merge1,$merge2);}
	} elseif(strpos($command,'drop') === 0) {
		$drop_item = substr($command,4);
		itemdrop($drop_item);
	} elseif(strpos($command,'off') === 0) {
		$off_item = substr($command,3);
		itemoff($off_item);
		//itemadd();
	} elseif(strpos($command,'swap') === 0) {
		$swap_item = substr($command,4);
		itemdrop($swap_item);
		itemadd();
	} elseif($command == 'itemmix') {
		itemmix($mix1,$mix2,$mix3);
	}
} elseif($mode == 'special') {
	include_once GAME_ROOT.'./include/game/special.func.php';
	if(strpos($command,'pose') === 0) {
		$pose = substr($command,4,1);
		$log .= "基础姿态变为<span class=\"yellow\">$poseinfo[$pose]</span>。<br> ";
		$mode = 'command';
	} elseif(strpos($command,'tac') === 0) {
		$tactic = substr($command,3,1);
		$log .= "应战策略变为<span class=\"yellow\">$tacinfo[$tactic]</span>。<br> ";
		$mode = 'command';
	} elseif(strpos($command,'inf') === 0) {
		$infpos = substr($command,3,1);
		chginf($infpos);
	} elseif(strpos($command,'chkp') === 0) {
		$itmn = substr($command,4,1);
		chkpoison($itmn);
	} elseif(strpos($command,'shop') === 0) {
		$shop = substr($command,4,1);
		shoplist($shop);
	}
} elseif($mode == 'senditem') {
	include_once GAME_ROOT.'./include/game/battle.func.php';
	senditem();
} elseif($mode == 'combat') {
	include_once GAME_ROOT.'./include/game/combat.func.php';
	combat($wid,1,$command);
} elseif($mode == 'rest') {
	include_once GAME_ROOT.'./include/state.func.php';
	rest($command);
} elseif($mode == 'chgword') {
	include_once GAME_ROOT.'./include/game/special.func.php';
	chgword($newmotto,$newlastword,$newkillmsg);
} elseif($mode == 'corpse') {
	include_once GAME_ROOT.'./include/game/itemmain.func.php';
	getcorpse($wid,$command);
} elseif($mode == 'team') {
	include_once GAME_ROOT.'./include/game/team.func.php';
	$command($nteamID,$nteamPass);
} elseif($mode == 'shop') {
	if($command == 'shop') {
		$mode = 'sp_shop';
	} else {
		include_once GAME_ROOT.'./include/game/itemmain.func.php';
		itembuy($command,$shoptype,$buynum);
	}
} elseif($mode == 'deathnote') {
	if($dnname){
		include_once GAME_ROOT.'./include/game/item2.func.php';
		deathnote($item,$dnname,$dndeath,$dngender,$dnicon,$name);
	} else {
		$log .= "嗯，暂时还不想杀人。<br>你合上了■DeathNote■。<br>";
		$mode = 'command';
	}
} else {
	$mode = 'command';
}
//$bid = 0;

$gamedata['notice'] = ob_get_contents();

if($hp <= 0) {
	$cmd = '<span class="dmg">你死了。</span><br><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >确定</a><br><br><br><input type="button" id="submit" onClick="location.href=\'end.php\'" value="提交">';
	$gamedata['cmd'] = $cmd;
} elseif(!$cmd) {
	ob_clean();
	if($mode&&file_exists(GAME_ROOT.TPLDIR.'/'.$mode.'.htm')) {
		include template($mode);
	} else {
		include template('command');
	}
	$gamedata['cmd'] = ob_get_contents();
	$gamedata['cmd'] .= '<br><br><input type="button" id="submit" onClick="postCommand();return false;" value="提交">';
} else {
	$gamedata['cmd'] = $cmd;
	$gamedata['cmd'] .= '<br><br><input type="button" id="submit" onClick="postCommand();return false;" value="提交">';
}

$db->query("UPDATE {$tablepre}players SET endtime='$now',hp='$hp',mhp='$mhp',sp='$sp',msp='$msp',att='$att',def='$def',pls='$pls',lvl='$lvl',exp='$exp',money='$money',bid='$bid',inf='$inf',rage='$rage',pose='$pose',tactic='$tactic',state='$state',killnum='$killnum',wp='$wp',wk='$wk',wg='$wg',wc='$wc',wd='$wd',teamID='$teamID',teamPass='$teamPass',wep='$wep',wepk='$wepk',wepe='$wepe',weps='$weps',wepsk='$wepsk',arb='$arb',arbk='$arbk',arbe='$arbe',arbs='$arbs',arbsk='$arbsk',arh='$arh',arhk='$arhk',arhe='$arhe',arhs='$arhs',arhsk='$arhsk',ara='$ara',arak='$arak',arae='$arae',aras='$aras',arask='$arask',arf='$arf',arfk='$arfk',arfe='$arfe',arfs='$arfs',arfsk='$arfsk',art='$art',artk='$artk',arte='$arte',arts='$arts',artsk='$artsk',itm0='$itm0',itmk0='$itmk0',itme0='$itme0',itms0='$itms0',itmsk0='$itmsk0',itm1='$itm1',itmk1='$itmk1',itme1='$itme1',itms1='$itms1',itmsk1='$itmsk1',itm2='$itm2',itmk2='$itmk2',itme2='$itme2',itms2='$itms2',itmsk2='$itmsk2',itm3='$itm3',itmk3='$itmk3',itme3='$itme3',itms3='$itms3',itmsk3='$itmsk3',itm4='$itm4',itmk4='$itmk4',itme4='$itme4',itms4='$itms4',itmsk4='$itmsk4',itm5='$itm5',itmk5='$itmk5',itme5='$itme5',itms5='$itms5',itmsk5='$itmsk5' where pid='$pid'");

if($url){$gamedata['url'] = $url;}
$gamedata['pls'] = $plsinfo[$pls];
$gamedata['anum'] = $alivenum;

init_profile();

ob_clean();
$main ? include template($main) : include template('profile');
$gamedata['main'] = ob_get_contents();

$gamedata['log'] = $log;

ob_clean();
$json = new Services_JSON();
$jgamedata = $json->encode($gamedata);
echo $jgamedata;
ob_end_flush();

?>