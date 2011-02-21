<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function move($moveto = 99) {
	global $log,$pls,$plsinfo,$inf,$hp,$mhp,$sp,$club,$arealist,$areanum,$hack,$areainfo,$gamestate,$pose,$weather;
	

	$plsnum = sizeof($plsinfo);
	if(($moveto == 'main')||($moveto < 0 )||($moveto >= $plsnum)){
		$log .= '请选择正确的移动地点。<br>';
		return;
	} elseif($pls == $moveto){
		$log .= '相同地点，不需要移动。<br>';
		return;
	} elseif(array_search($moveto,$arealist) <= $areanum && !$hack){
		$log .= $plsinfo[$moveto].'是禁区，还是离远点吧！<br>';
		return;
	}
	
	//足部受伤，20；足球社，12；冻伤，30；正常，15；去gamecfg里改吧
	$movesp = 15;
	if ($inf) {
		global $inf_move_sp;
		foreach ($inf_move_sp as $inf_ky => $sp_down) {
			if(strpos($inf,$inf_ky)!==false){$movesp+=$sp_down;}
		}
	}
	//if(strpos($inf, 'f') !== false){ $movesp += 5; }
	//if(strpos($inf, 'i') !== false){ $movesp += 15; }
	if($club == 6){ $movesp -= 3; }

	
	if($sp <= $movesp){
		$log .= "体力不足，不能移动！<br>还是先睡会儿吧！<br>";
		return;
	}

	$sp -= $movesp;
	if($weather == 11) {
		if($hack){$pls = rand(0,sizeof($plsinfo)-1);}
		else {$pls = rand($areanum+1,sizeof($plsinfo)-1);}
		$log = ($log . "龙卷风把你吹到了<span class=\"yellow\">$plsinfo[$pls]</span>！<br>");
	} elseif($weather == 13) {
		$damage = round($mhp/8) + rand(0,20);
		$hp -= $damage;
		$log .= "被<span class=\"blue\">冰雹</span>击中，生命减少了<span class=\"red\">$damage</span>点！<br>";
		if($hp <= 0 ) {
			include_once GAME_ROOT.'./include/state.func.php';
			death('hsmove');
			return;
		} else {
			$pls = $moveto;
			$log .= "消耗<span class=\"yellow\">{$movesp}</span>点体力，移动到了<span class=\"yellow\">$plsinfo[$pls]</span>。<br>";
		}
	} else {
		$pls = $moveto;
		$log .= "消耗<span class=\"yellow\">{$movesp}</span>点体力，移动到了<span class=\"yellow\">$plsinfo[$pls]</span>。<br>";
	}
	
	if($inf){
		global $infwords,$inf_move_hp;
		foreach ($inf_move_hp as $inf_ky => $o_dmg) {
			if(strpos($inf,$inf_ky)!==false){
				$damage = round($mhp * $o_dmg) + rand(0,15);
				$hp -= $damage;
				$log .= "{$infwords[$inf_ky]}减少了<span class=\"red\">$damage</span>点生命！<br>";
				if($hp <= 0 ){
					include_once GAME_ROOT.'./include/state.func.php';
					death($inf_ky.'move');
					return;
				}
			}			
		}
	}
	
	/*if(strpos($inf, 'p') !== false){
		$damage = round($mhp/16) + rand(0,10);
		$hp -= $damage;
		$log .= "<span class=\"purple\">毒发</span>减少了<span class=\"red\">$damage</span>点生命！<br>";
		if($hp <= 0 ){
			include_once GAME_ROOT.'./include/state.func.php';
			death('pmove');
			return;
		}
	}
	if(strpos($inf, 'u') !== false){
		$damage = round($mhp/16) + rand(0,15);
		$hp -= $damage;
		$log .= "<span class=\"yellow\">烧伤发作</span>减少了<span class=\"red\">$damage</span>点生命！<br>";
		if($hp <= 0 ){
			include_once GAME_ROOT.'./include/state.func.php';
			death('umove');
			return;
		}
	}*/
	$log .= $areainfo[$pls].'<br>';
	$enemyrate = 70;
	if($gamestate == 40){$enemyrate += 10;}
	elseif($gamestate == 50){$enemyrate += 15;}
	if($pose==3){$enemyrate -= 20;}
	elseif($pose==4){$enemyrate += 10;}
	discover($enemyrate);
//	$log .= '遇敌率'.$enemyrate.'%<br>';
//	if(($gamestate>=40)&&($pose!=3)){
//		discover(90);
//	} else {
//		discover(70);
//	}
	return;

}

function search(){
	global $log,$pls,$arealist,$areanum,$hack,$plsinfo,$club,$sp,$gamestate,$pose,$weather,$hp,$mhp,$inf;
	
	
	if(array_search($pls,$arealist) <= $areanum && !$hack){
		$log .= $plsinfo[$pls].'是禁区，还是赶快逃跑吧！<br>';
		return;
	}

	//腕部受伤，20；冻伤：30；侦探社，12；正常，15；改到gamecfg
	$schsp =15;
	if ($inf) {
		global $inf_search_sp;
		foreach ($inf_search_sp as $inf_ky => $sp_down) {
			if(strpos($inf,$inf_ky)!==false){$schsp+=$sp_down;}
		}
	}
	//if(strpos($inf, 'a') !== false){ $schsp += 5; }
	//if(strpos($inf, 'i') !== false){ $schsp += 15; }
	if($club == 10){ $schsp -= 3; }
	

	if($sp <= $schsp){
		$log .= "体力不足，不能探索！<br>还是先睡会儿吧！<br>";
		return;	
	}

	if($weather == 13) {
		$damage = round($mhp/8) + rand(0,20);
		$hp -= $damage;
		$log .= "被<span class=\"blue\">冰雹</span>击中，生命减少<span class=\"red\">$damage</span>点！<br>";
		if($hp <= 0 ) {
			include_once GAME_ROOT.'./include/state.func.php';
			death('hsmove');
			return;
		}
	}
	
	$sp -= $schsp;
	$log .= "消耗<span class=\"yellow\">{$schsp}</span>点体力，你搜索着周围的一切。。。<br>";
	if($inf){
		global $infwords,$inf_search_hp;
		foreach ($inf_search_hp as $inf_ky => $o_dmg) {
			if(strpos($inf,$inf_ky)!==false){
				$damage = round($mhp * $o_dmg) + rand(0,10);
				$hp -= $damage;
				$log .= "{$infwords[$inf_ky]}减少了<span class=\"red\">$damage</span>点生命！<br>";
				if($hp <= 0 ){
					include_once GAME_ROOT.'./include/state.func.php';
					death($inf_ky.'move');
					return;
				}
			}			
		}
	}
	
	/*if(strpos($inf, 'p') !== false){
		$damage = round($mhp/32) + rand(0,5);
		$hp -= $damage;
		$log .= "<span class=\"purple\">毒发</span>减少了<span class=\"red\">$damage</span>点生命！<br>";
		if($hp <= 0 ){
			include_once GAME_ROOT.'./include/state.func.php';
			death('pmove');
			return;
		}
	}
	if(strpos($inf, 'u') !== false){
		$damage = round($mhp/32) + rand(0,15);
		$hp -= $damage;
		$log .= "<span class=\"yellow\">烧伤发作</span>减少了<span class=\"red\">$damage</span>点生命！<br>";
		if($hp <= 0 ){
			include_once GAME_ROOT.'./include/state.func.php';
			death('umove');
			return;
		}
	}*/
	$enemyrate = 40;
	if($gamestate == 40){$enemyrate += 20;}
	elseif($gamestate == 50){$enemyrate += 30;}
	if($pose==3){$enemyrate -= 20;}
	elseif($pose==4){$enemyrate += 10;}
	discover($enemyrate);
//	$log .= '遇敌率'.$enemyrate.'%<br>';
//	if(($gamestate>=40)&&($pose!=3)) {
//		discover(75);
//	} else {
//		discover(30);
//	}
	return;

}

function discover($schmode = 0) {
	global $log,$mode,$command,$cmd,$event_obbs,$weather,$pls,$club,$pose,$tactic,$inf,$item_obbs,$enemy_obbs,$bid,$db,$tablepre;
	$event_dice = rand(0,99);
	if($event_dice < $event_obbs){
		include_once GAME_ROOT.'./include/game/event.func.php';
		event();
		$mode = 'command';
		return;
	}
	
	include_once GAME_ROOT.'./include/game/attr.func.php';

	$mode_dice = rand(0,99);
	if($mode_dice < $schmode) {
		global $pid,$corpse_obbs,$teamID,$fog,$bid,$gamestate;
//		if($gamestate < 40) {
//			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND pid!='$bid'");
//		} else {
//			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid'");
//		}
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid'");
		if(!$db->num_rows($result)){
			$log .= '<span class="yellow">周围一个人都没有。</span><br>';
			$mode = 'command';
			return;
		}

		$enemynum = $db->num_rows($result);
		$enemyarray = range(0, $enemynum - 1);
		shuffle($enemyarray);
		$find_r = get_find_r($weather,$pls,$pose,$tactic,$club,$inf);
		$find_obbs = $enemy_obbs + $find_r;
		
		foreach($enemyarray as $enum){
			$db->data_seek($result, $enum);
			$edata = $db->fetch_array($result);
			if(!$edata['type'] || $gamestate < 50){
				if($edata['hp'] > 0) {
					$hide_r = get_hide_r($weather,$pls,$edata['pose'],$edata['tactic'],$edata['club'],$edata['inf']);
					$enemy_dice = rand(0,99);
					if($enemy_dice < ($find_obbs - $hide_r)) {
						if($teamID&&(!$fog)&&($teamID == $edata['teamID'])){
							$bid = $edata['pid'];
							include_once GAME_ROOT.'./include/game/battle.func.php';
							findteam($edata);
							return;
						} else {
							$active_r = get_active_r($weather,$pls,$pose,$tactic,$club,$inf);
							$bid = $edata['pid'];
							$active_dice = rand(0,99);
							if($active_dice <  $active_r) {
								include_once GAME_ROOT.'./include/game/battle.func.php';
								findenemy($edata);
								return;
							} else {
								include_once GAME_ROOT.'./include/game/combat.func.php';
								combat(0);
								return;
							}
						}
					}else{
						$hideflag = true;
					}
				} else {
					$corpse_dice = rand(0,99);
					if($corpse_dice < $corpse_obbs) {
						if($gamestate <40 &&(($edata['weps'] && $edata['wepe'])||($edata['arbs'] && $edata['arbe'])||$edata['arhs']||$edata['aras']||$edata['arfs']||$edata['arts']||$edata['itms0']||$edata['itms1']||$edata['itms2']||$edata['itms3']||$edata['itms4']||$edata['itms5']||$edata['money'])){
							
							$bid = $edata['pid'];
							include_once GAME_ROOT.'./include/game/battle.func.php';
							findcorpse($edata);
							return;
						} else {
							discover(50);
							return;
						}
					}
				}
			}
		}
		if($hideflag == true){
			$log .= '似乎有人隐藏着……<br>';
		}else{
			$log .= '<span class="yellow">周围一个人都没有。</span><br>';
		}
		$mode = 'command';
		return;
	} else {
		$find_r = get_find_r($weather,$pls,$pose,$tactic,$club,$inf);
		$find_obbs = $item_obbs + $find_r;
		$item_dice = rand(0,99);
		if($item_dice < $find_obbs) {
			//$mapfile = GAME_ROOT."./gamedata/mapitem/{$pls}mapitem.php";
			//$mapitem = openfile($mapfile);
			//$itemnum = sizeof($mapitem) - 1;
//			$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE map='$pls'");
//			$itemnum = $db->num_rows($result);
			$result = $db->query("SELECT * FROM {$tablepre}{$pls}mapitem");
			$itemnum = $db->num_rows($result);
			if($itemnum <= 0){
				$log .= '<span class="yellow">周围找不到任何物品。</span><br>';
				$mode = 'command';
				return;
			}
			$itemno = rand(0,$itemnum-1);
			$db->data_seek($result,$itemno);
			$mi=$db->fetch_array($result);
			global $itm0,$itmk0,$itme0,$itms0,$itmsk0;
			$itm0=$mi['itm'];
			$itmk0=$mi['itmk'];
			$itme0=$mi['itme'];
			$itms0=$mi['itms'];
			$itmsk0=$mi['itmsk'];
			$iid=$mi['iid'];
			$db->query("DELETE FROM {$tablepre}{$pls}mapitem WHERE iid='$iid'");
			//list($itm0,$itmk0,$itme0,$itms0,$itmsk0) = explode(',', $mapitem[$itemno]);
			//array_splice($mapitem,$itemno,1);
			//writeover($mapfile,implode('', $mapitem),'wb');
			//unset($mapitem);

			if($itms0){
				include_once GAME_ROOT.'./include/game/itemmain.func.php';
				itemfind();
				return;
			} else {
				$log .= "但是什么都没有发现。<br>";
			}
		} else {
			$log .= "但是什么都没有发现。<br>";
		}
	}
	$mode = 'command';
	return;

}



?>