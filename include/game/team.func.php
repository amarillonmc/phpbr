<?php


if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function teammake($tID,$tPass) {
	global $pdata,$log,$mode,$db,$tablepre,$noitm,$team_sp,$now,$gamestate,$companysystem;
	$name = $pdata['name'];$type = $pdata['type'];
	if($gamestate >= 40) {
		$log .= '连斗时不能组建队伍。<br>';
		$mode = 'command';
		return;
	}
	if($type != 0) {
		$log .= '非参战者不能组建队伍。<br>';
		$mode = 'command';
		return;
	}
	$teamID = & $pdata['teamID'];$teamPass = & $pdata['teamPass'];
	if($companysystem && $pdata['company']){
		global $cdata;
		$cpteamID = & $cdata['teamID'];$cpteamPass = & $cdata['teamPass'];
	}
	$sp = & $pdata['sp'];
	if(!$tID || !$tPass) {
		$log .= '队伍名和密码不能为空，请重新输入。<br>';
		$mode = 'command';
		return;
	}
	if(strlen($tID) > 20){
		$log .= '队伍名称过长，请重新输入。<br>';
		$mode = 'command';
		return;
	}
	if(strlen($tPass) > 20){
		$log .= '队伍密码过长，请重新输入。<br>';
		$mode = 'command';
		return;
	}
	if($tID == $noitm) {
		$log .= '队伍名不能为<span class="red">'.$tID.'</span>，请重新输入。<br>';
		$mode = 'command';
		return;
	}
		
	if($teamID) {
		$log .= '你已经加入了队伍<span class="yellow">'.$teamID.'</span>，请先退出队伍。<br>';
	} elseif($sp <= $team_sp) {
		$log .= '体力不足，不能创建队伍。至少需要<span class="yellow">'.$team_sp.'</span>点体力。<br>';
	} else {
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE teamID='$tID'");
		if($db->num_rows($result)){
			$log .= '队伍<span class="yellow">'.$tID.'</span>已经存在，请更换队伍名。<br>';
		} else {
			if($companysystem && $pdata['company']){
				$teamID = $cpteamID = $tID;$teamPass = $cpteamPass = $tPass;
				player_save($cdata);
			}else{
				$teamID = $tID;$teamPass = $tPass;
			}			
			$sp -= $team_sp;
			$log .= '你创建了队伍<span class="yellow">'.$teamID.'</span>。<br>';
			naddnews($now,'teammake',$teamID,$name);
			//global $gamedata,$chatinfo;
			//$gamedata['chattype'] = "<select name=\"chattype\" value=\"2\"><option value=\"0\" selected>$chatinfo[0]<option value=\"1\" >$chatinfo[1]</select>";
			//$gamedata['team'] = $teamID;
		}
	$mode = 'command';
	return;

	}
}

function teamjoin($tID,$tPass) {
	global $log,$mode,$pdata,$db,$tablepre,$noitm,$team_sp,$teamj_sp,$now,$teamlimit,$gamestate,$companysystem;
	$name = $pdata['name'];$type = $pdata['type'];
	if($gamestate >= 40) {
		$log .= '连斗时不能加入队伍。<br>';
		$mode = 'command';
		return;
	}
	if($type != 0) {
		$log .= '非参战者不能加入队伍。<br>';
		$mode = 'command';
		return;
	}
	$teamID = & $pdata['teamID'];$teamPass = & $pdata['teamPass'];
	if($companysystem && $pdata['company']){
		global $cdata;
		$cpteamID = & $cdata['teamID'];$cpteamPass = & $cdata['teamPass'];
	}
	$sp = & $pdata['sp'];
	if(!$tID || !$tPass){
		$log .= '队伍名和密码不能为空，请重新输入。<br>';
		$mode = 'command';
		return;
	}
	if(strlen($tID) > 20){
		$log .= '队伍名称过长，请重新输入。<br>';
		$mode = 'command';
		return;
	}
	if(strlen($tPass) > 20){
		$log .= '队伍密码过长，请重新输入。<br>';
		$mode = 'command';
		return;
	}
	if($tID == $noitm) {
		$log .= '队伍名不能为<span class="red">'.$tID.'</span>，请重新输入。<br>';
		$mode = 'command';
		return;
	}

	if($teamID) {
		$log .= '你已经加入了队伍<span class="yellow">'.$teamID.'</span>，请先退出队伍。<br>';
	} elseif($sp <= $teamj_sp) {
		$log .= '体力不足，不能加入队伍。至少需要<span class="yellow">'.$teamj_sp.'</span>点体力。<br>';
	} else {
		$result = $db->query("SELECT teamPass FROM {$tablepre}players WHERE teamID='$tID'");
		if(!$db->num_rows($result)){
			$log .= '队伍<span class="yellow">'.$tID.'</span>不存在，请先创建队伍。<br>';
		} elseif($db->num_rows($result) >= $teamlimit) {
			$log .= '队伍<span class="yellow">'.$tID.'</span>人数已满，请更换队伍。<br>';
		} else {
			$password = $db->result($result,0);
			if($tPass == $password) {
				if($companysystem && $pdata['company']){
					$teamID = $cpteamID = $tID;$teamPass = $cpteamPass = $tPass;
					player_save($cdata);
				}else{
					$teamID = $tID;$teamPass = $tPass;
				}	
				$sp -= $teamj_sp;
				$log .= '你加入了队伍<span class="yellow">'.$teamID.'</span>。<br>';
				naddnews($now,'teamjoin',$teamID,$name);
				//global $gamedata,$chatinfo;
				//$gamedata['chattype'] = "<select name=\"chattype\" value=\"2\"><option value=\"0\" selected>$chatinfo[0]<option value=\"1\" >$chatinfo[1]</select>";
				//$gamedata['team'] = $teamID;
			} else {
				$log .= '密码错误，不能加入队伍<span class="yellow">'.$tID.'</span>。<br>';
			}
		}
	}

	$mode = 'command';
	return;
}

function teamquit() {
	global $log,$mode,$pdata,$now,$gamestate,$companysystem;
	$name = $pdata['name'];$type = $pdata['type'];
	if($gamestate >= 40) {
		$log .= '连斗时不能退出队伍。<br>';
		$mode = 'command';
		return;
	}
	if($type != 0) {
		$log .= '非参战者不能退出队伍。<br>';
		$mode = 'command';
		return;
	}
	$teamID = & $pdata['teamID'];$teamPass = & $pdata['teamPass'];
	
	if($companysystem && $pdata['company']){
		global $cdata;
		$cpteamID = & $cdata['teamID'];$cpteamPass = & $cdata['teamPass'];
	}
	if($teamID && $gamestate<40){
		$log .= '你退出了队伍<span class="yellow">'.$teamID.'</span>。<br>';
		naddnews($now,'teamquit',$teamID,$name);
		if($companysystem && $pdata['company']){
			$teamID = $cpteamID = $teamPass = $cpteamPass = '';
			player_save($cdata);
		}else{
			$teamID = $teamPass = '';
		}	
	} else {
		$log .= '你不在队伍中。<br>';
	}
	$mode = 'command';
	return;
}

?>