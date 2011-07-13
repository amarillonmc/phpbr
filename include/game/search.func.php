<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function move($moveto = 99) {
	global $pdata,$log,$mapdata,$arealist,$areanum,$hack,$gamestate,$weather,$inf_move_ref_r,$wthdata,$infdata,$companysystem,$arealock;
	extract($pdata,EXTR_REFS);
	
	$canmoveto = get_neighbor_map($pls);
	$plsnum = sizeof($mapdata);
	if(($moveto == 'main')||($moveto < 0 )||($moveto >= $plsnum)){
		$log .= '请选择正确的移动地点。<br>';
		return;
	} elseif($pls == $moveto){
		$log .= '相同地点，不需要移动。<br>';
		return;
	} elseif(!in_array($moveto,array_keys($canmoveto))){
		$log .= '从此处无法到达指定地点。<br>';
		return;
	} elseif(array_search($moveto,$arealist) <= $areanum && !$hack && $moveto != 30){
		$log .= $mapdata[$moveto]['name'].'是禁区，还是离远点吧！<br>';
		return;
	} elseif($moveto == 30 && $arealock){
		$log .= $mapdata[$moveto]['name'].'是什么，可以吃么？<br>';
		return;
	}
	$cannot_cmd = false;
	if($inf){//移动前异常状态效果判断
		global $inf_cannot_move_r;
		foreach ($inf_cannot_move_r as $key => $val) {
			if(strpos($inf,$key)!==false){
				$dice = rand(0,99);
				if($dice < $val){
					$cannot_cmd = $key;
					break;
				}
			}
		}
	}
	if(!empty($wthdata[$weather]['special']) && $wthdata[$weather]['kind'] != 'TELEPORT'){//非传送天气效果判断
		$flag = true;
		$special = $wthdata[$weather]['special'];
		if(isset($special['failmapkind']) && $mapdata[$moveto]['kind'] == $special['failmapkind']){
			$flag = false;
		}
		if($flag){
			$log .= $special['effwords'].'，';
			if(isset($special['hpdown'])){
				$damage = round($mhp * $special['hpdown']) + rand(0,20);
				if($damage > 0){
					$hp -= $damage;
					$log .= "生命减少了<span class=\"red\">$damage</span>点！";
				}else{
					$refresh = -$damage;
					if($hp + $refresh > $mhp){$refresh = $mhp - $hp;}
					if($refresh > 0){
						$hp += $refresh;
						$log .= "生命回复了<span class=\"yellow\">$refresh</span>点！";
					}else{
						$log .= "不过你的生命已经全满了。";
					}
				}				
			}
			if(isset($special['spdown'])){
				$spdown = round($msp * $special['spdown']) + rand(0,20);
				if($spdown > 0){
					$spdown = $spdown >= $sp ? $sp-1 : $spdown;
					$sp -= $spdown;
					$log .= "体力减少了<span class=\"red\">$spdown</span>点！";
				}else{
					$refresh = -$spdown;
					if($sp + $refresh > $msp){$refresh = $msp - $sp;}
					if($refresh > 0){
						$sp += $refresh;
						$log .= "体力回复了<span class=\"yellow\">$refresh</span>点！";
					}else{
						$log .= "不过你的体力已经全满了。";
					}
				}				
			}
			$log .= '<br>';
			if($hp <= 0) {
				//include_once GAME_ROOT.'./include/state.func.php';
				set_death($GLOBALS['pdata'],$special['death']);
				return;
			}
		}else{
			$log .= $special['efffail'];
		}
	}
	if(!$cannot_cmd){//可以移动的情况下
		$movesp = $canmoveto[$moveto];
		//足部受伤，20；足球社，12；冻伤，30；正常，15；去gamecfg里改吧
		if ($inf) {
			global $inf_move_sp;
			foreach ($inf_move_sp as $inf_ky => $sp_down) {
				if(strpos($inf,$inf_ky)!==false){$movesp*=$sp_down;}
			}
		}
		if($club == 6){
			if($lvl>=21){
				$movesp *= 0.2;
			}else{
				$movesp *= 0.9-0.1*floor($lvl/3);
			}
		}
		$movesp = ceil($movesp);
		
		if($sp <= $movesp){
			$log .= "体力不足，不能移动！<br>还是先睡会儿吧！<br>";
			return;
		}
	
		$sp -= $movesp;
		if($wthdata[$weather]['kind'] == 'TELEPORT') {//传送天气效果判断
			if($hack){$pls = rand(0,sizeof($mapdata)-1);}
			else {$pls = rand($areanum+1,sizeof($mapdata)-1);}
			$log  .= $wthdata[$weather]['special']['effwords']."，你被传送到了<span class=\"yellow\">{$mapdata[$pls]['name']}</span>！<br>";
			
		} else{
			$pls0 = $pls;
			$pls = $moveto;
			$log .= "消耗<span class=\"yellow\">{$movesp}</span>点体力，移动到了<span class=\"yellow\">{$mapdata[$pls]['name']}</span>。<br>";
			if($companysystem){
				global $cdata;
				if($cdata['pose'] == 6){//还需要加入同伴体力消耗和伤害判断等，并结合search结构大改
					if($cdata['pls'] == $pls0){
						$cdata['pls'] = $moveto;
						player_save($cdata);
						$log .= "同伴<span class=\"yellow\">{$cdata['name']}</span>跟随着来到了<span class=\"yellow\">{$mapdata[$pls]['name']}</span>。<br>";
					}					
				}
			}
		}
	}else{
		$log .= "{$infdata[$cannot_cmd]['infnm']}使你无法移动！<br>";
	}
	
	
	if($inf){//移动后异常状态效果判断
		global $inf_move_hp;
		foreach ($inf_move_hp as $inf_ky => $o_dmg) {
			if(strpos($inf,$inf_ky)!==false){
				$damage = round($mhp * $o_dmg) + rand(0,15);
				$hp -= $damage;
				$log .= "{$infdata[$inf_ky]['infnm']}减少了<span class=\"red\">$damage</span>点生命！<br>";
				if($hp <= 0 ){
					//include_once GAME_ROOT.'./include/state.func.php';
					set_death($GLOBALS['pdata'],$inf_ky.'move');
					return;
				}
			}			
		}
		if(strpos($inf,'L')!==false){
			$dice = rand(1,3);
			if($dice == 1){
				$damage = round($mhp*0.3 + rand(100,200));
				$hp -= $damage;
				$log .= "<span class=\"linen\">轨道空间站对你发起了攻击！</span>你受到了<span class=\"red\">$damage</span>点伤害！<br>";
			} elseif($dice == 2){
				$sp_down = round($msp*0.3 + rand(100,200));
				if($sp_down >= $sp){$sp_down = $sp -1;}
				$sp -= $sp_down;
				$log .= "<span class=\"linen\">轨道空间站对你发起了攻击！为了躲避攻击，</span>你消耗了<span class=\"red\">$sp_down</span>点体力！<br>";
			}
			if($hp <= 0 ){
				//include_once GAME_ROOT.'./include/state.func.php';
				set_death($GLOBALS['pdata'],'Lmove');
				return;
			}
		}
	}
	if(!empty($wthdata[$weather]['infref'])){
		foreach($wthdata[$weather]['infref'] as $ir => $obbs){
			if(strpos($inf,$ir)!==false){
				$dice = rand(0,99);
				if($dice < $obbs){
					$log .= $wthdata[$weather]['refwords']."，你从{$infdata[$ir]['name']}状态恢复了！</span><br>";
					$inf = str_replace($ir,'',$inf);
				}
			}
		}
	}
	foreach($inf_move_ref_r as $key => $val){
		if(strpos($inf,$key)!==false){
			$dice = rand(0,99);
			if($dice < $val){
				$log .= "你从{$infdata[$key]['name']}状态恢复了！<br>";
				$inf = str_replace($key,'',$inf);
			}
		}
	}
	if(strpos($inf,'L')!==false){
		$dice = rand(0,99);
		if($dice < 10){
			$log .= "<span class=\"yellow\">你设法成功摆脱了空间站的追踪。</span><br>";
			$inf = str_replace('L','',$inf);
		} 
	}
	if(!$cannot_cmd){
		$log .= $mapdata[$pls]['notice'].'<br>';
		$enemyrate = 70;
		if($gamestate == 40){$enemyrate += 10;}
		elseif($gamestate == 50){$enemyrate += 15;}
		if($pose==3){$enemyrate -= 20;}
		elseif($pose==4){$enemyrate += 10;}
		discover($enemyrate);
	}

	return;
}

function search(){
	global $pdata,$log,$arealist,$areanum,$hack,$mapdata,$gamestate,$weather,$inf_search_ref_r,$wthdata,$infdata,$arealock;
	extract($pdata,EXTR_REFS);
	
	if(array_search($pls,$arealist) <= $areanum && !$hack && $pls != 30){
		$log .= $mapdata[$pls]['name'].'是禁区，还是赶快逃跑吧！<br>';
		return;
	}elseif($pls == 30 && $arealock){
		$log .= $mapdata[$pls]['name'].'是什么，可以吃么？<br>';
		return;
	}
	
	$cannot_cmd = false;
	if($inf){//探索前异常状态效果判断
		global $inf_cannot_search_r;
		foreach ($inf_cannot_search_r as $key => $val) {
			if(strpos($inf,$key)!==false){
				$dice = rand(0,99);
				if($dice < $val){
					$cannot_cmd = $key;
					break;
				}
			}
		}
	}
	if($wthdata[$weather]['kind'] !== 'TELEPORT' && !empty($wthdata[$weather]['special'])){//天气判断
		$flag = true;
		$special = $wthdata[$weather]['special'];
		if(isset($special['failmapkind']) && $mapdata[$pls]['kind'] == $special['failmapkind']){
			$flag = false;
		}
		if($flag){
			$log .= $special['effwords'].'，';
			if(isset($special['hpdown'])){
				$damage = round($mhp * $special['hpdown']) + rand(0,20);
				if($damage > 0){
					$hp -= $damage;
					$log .= "生命减少了<span class=\"red\">$damage</span>点！";
				}else{
					$refresh = -$damage;
					if($hp + $refresh > $mhp){$refresh = $mhp - $hp;}
					if($refresh > 0){
						$hp += $refresh;
						$log .= "生命回复了<span class=\"yellow\">$refresh</span>点！";
					}else{
						$log .= "不过你的生命已经全满了。";
					}
				}				
			}
			if(isset($special['spdown'])){
				$spdown = round($msp * $special['spdown']) + rand(0,20);
				if($spdown > 0){
					$spdown = $spdown >= $sp ? $sp-1 : $spdown;
					$sp -= $spdown;
					$log .= "体力减少了<span class=\"red\">$spdown</span>点！";
				}else{
					$refresh = -$spdown;
					if($sp + $refresh > $msp){$refresh = $msp - $sp;}
					if($refresh > 0){
						$sp += $refresh;
						$log .= "体力回复了<span class=\"yellow\">$refresh</span>点！";
					}else{
						$log .= "不过你的体力已经全满了。";
					}
				}				
			}
			$log .= '<br>';
			if($hp <= 0) {
				//include_once GAME_ROOT.'./include/state.func.php';
				set_death($GLOBALS['pdata'],$special['death']);
				return;
			}
		}else{
			$log .= $special['efffail'];
		}
	}
	if(!$cannot_cmd){
		//腕部受伤，20；冻伤：30；侦探社，12；正常，15；改到gamecfg
		$schsp =15;
		if ($inf) {
			global $inf_search_sp;
			foreach ($inf_search_sp as $inf_ky => $sp_down) {
				if(strpos($inf,$inf_ky)!==false){$schsp*=$sp_down;}
			}
		}
		if($club == 6){
			if($lvl>=21){
				$schsp *= 0.2;
			}else{
				$schsp *= 0.9-0.1*floor($lvl/3);
			}
		}
		$schsp = ceil($schsp);
	
		if($sp <= $schsp){
			$log .= "体力不足，不能探索！<br>还是先睡会儿吧！<br>";
			return;	
		}
		$sp -= $schsp;
		$log .= "消耗<span class=\"yellow\">{$schsp}</span>点体力，你搜索着周围的一切……<br>";	
	}else{
		$log .= "{$infdata[$cannot_cmd]['infnm']}使你无法探索！<br>";
	}
	if($inf){
		global $inf_search_hp;
		foreach ($inf_search_hp as $inf_ky => $o_dmg) {
			if(strpos($inf,$inf_ky)!==false){
				$damage = round($mhp * $o_dmg) + rand(0,10);
				$hp -= $damage;
				$log .= "{$infdata[$inf_ky]['infnm']}减少了<span class=\"red\">$damage</span>点生命！<br>";
				if($hp <= 0 ){
					//include_once GAME_ROOT.'./include/state.func.php';
					set_death($GLOBALS['pdata'],$inf_ky.'move');
					return;
				}
			}			
		}
		if(strpos($inf,'L')!==false){
			$dice = rand(1,3);
			if($dice == 1){
				$damage = round($mhp*0.3 + rand(100,200));
				$hp -= $damage;
				$log .= "<span class=\"linen\">轨道空间站对你发起了攻击！</span>你受到了<span class=\"red\">$damage</span>点伤害！<br>";
			} elseif($dice == 2){
				$sp_down = round($msp*0.3 + rand(100,200));
				if($sp_down >= $sp){$sp_down = $sp -1;}
				$sp -= $sp_down;
				$log .= "<span class=\"linen\">轨道空间站对你发起了攻击！为了躲避攻击，</span>你消耗了<span class=\"red\">$sp_down</span>点体力！<br>";
			}
			if($hp <= 0 ){
				//include_once GAME_ROOT.'./include/state.func.php';
				set_death($GLOBALS['pdata'],'Lmove');
				return;
			}
		}
	}
	if(!empty($wthdata[$weather]['infref'])){
		foreach($wthdata[$weather]['infref'] as $ir => $obbs){
			if(strpos($inf,$ir)!==false){
				$dice = rand(0,99);
				if($dice < $obbs){
					$log .= $wthdata[$weather]['refwords']."，你从{$infdata[$ir]['name']}状态恢复了！<br>";
					$inf = str_replace($ir,'',$inf);
				}
			}
		}
	}
	foreach($inf_search_ref_r as $key => $val){
		if(strpos($inf,$key)!==false){
			$dice = rand(0,99);
			if($dice < $val){
				$log .= "你从{$infdata[$key]['name']}状态恢复了！<br>";
				$inf = str_replace($key,'',$inf);
			}
		}
	}
	if(strpos($inf,'L')!==false){
		$dice = rand(0,99);
		if($dice < 5){
			$log .= "<span class=\"yellow\">你设法成功摆脱了空间站的追踪。</span><br>";
			$inf = str_replace('L','',$inf);
		} 
	}
	if(!$cannot_cmd){
		$enemyrate = 40;
		if($gamestate == 40){$enemyrate += 20;}
		elseif($gamestate == 50){$enemyrate += 30;}
		if($pose==3){$enemyrate -= 20;}
		elseif($pose==4){$enemyrate += 10;}
		discover($enemyrate);
	}
	return;

}

function discover($schmode = 0) {
	global $pdata,$now,$weather,$db,$tablepre,$gamestate,$log,$mode,$command,$cmd,$event_obbs,$item_obbs,$enemy_obbs,$trap_min_obbs,$trap_max_obbs,$trap_per_obbs,$corpseprotect,$mapdata;
	extract($pdata,EXTR_REFS);
	$event_dice = rand(0,99);
	if($event_dice < $event_obbs && $mapdata[$pls]['event']){
		include_once GAME_ROOT.'./include/game/event.func.php';
		if(get_event($mapdata[$pls]['event'])){
				
			//event();
			$mode = 'command';
			return;
		}
	}
	$trap_dice=rand(0,99);//随机数，开始判断是否踩陷阱
	if($trap_dice < $trap_max_obbs){ //踩陷阱概率最大值
		
		$trapresult = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls'");

		$trapnum = $db->num_rows($trapresult);
		if($trapnum){//看地图上有没有陷阱
			$real_trap_obbs = $trap_min_obbs + $trapnum * $trap_per_obbs;
			if($pose==1){$real_trap_obbs+=1;}
			elseif($pose==3){$real_trap_obbs+=3;}//攻击和探索姿势略容易踩陷阱
			if($gamestate >= 40){$real_trap_obbs+=3;}//连斗以后略容易踩陷阱
			if($pls == 0){$real_trap_obbs+=15;}//在后台非常容易踩陷阱
			if($club == 6){$real_trap_obbs-=5;}//人肉搜索称号遭遇陷阱概率减少
			//echo "踩陷阱概率：{$real_trap_obbs}%";
			if($trap_dice < $real_trap_obbs){//踩陷阱判断
				$itemno = rand(0,$trapnum-1);
				$db->data_seek($trapresult,$itemno);
				$mi=$db->fetch_array($trapresult);
				$itm0=$mi['itm'];
				$itmk0=$mi['itmk'];
				$itme0=$mi['itme'];
				$itms0=$mi['itms'];
				$itmsk0=$mi['itmsk'];
				$itmnp0=$mi['itmnp'];
				$tid=$mi['tid'];
				$db->query("DELETE FROM {$tablepre}maptrap WHERE tid='$tid'");
				if($itms0){
					include_once GAME_ROOT.'./include/game/itemmain.func.php';
					itemfind();
					return;
				}
			}
		}
	}

	include_once GAME_ROOT.'./include/game/attr.func.php';

	$mode_dice = rand(0,99);
	if($mode_dice < $schmode) {
		global $corpse_obbs,$enemy_min_obbs,$fog,$gamestate;
//		if($gamestate < 40) {
//			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND pid!='$bid'");
//		} else {
//			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid'");
//		}
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND pid!='$company' AND state<98");
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
		$hideflag = false;
		foreach($enemyarray as $enum){
			$db->data_seek($result, $enum);
			$edata = $db->fetch_array($result);
			if(!$edata['type'] || $gamestate < 50){
				if($edata['hp'] > 0) {
					$hide_r = get_hide_r($weather,$pls,$edata['pose'],$edata['tactic'],$edata['club'],$edata['inf']);
					$enemy_dice = rand(0,99);
					//echo $enemy_dice.' '.($find_obbs-$hide_r).'<br>';
					if($enemy_dice < ($find_obbs - $hide_r) || $enemy_dice < $enemy_min_obbs) {
						if($teamID&&(!$fog)&&($gamestate<40)&&($teamID == $edata['teamID'])){
							$bid = $edata['pid'];
							include_once GAME_ROOT.'./include/game/battle.func.php';
							findteam($edata);
							return;
						} else {
							$wep_kind = substr($wepk,1,1);
							$active_r = get_active_r($wep_kind,$weather,$pls,$pose,$tactic,$club,$inf,$edata['inf']);
							//$log .= '先攻几率：'.$active_r.'<br>';
							auto_bind($edata);
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
//						if($gamestate < 40 && ($edata['lasteff'] < $now - $corpseprotect || $edata['bid'] == $pid)){
						if($gamestate <40 && $edata['lasteff'] < $now - $corpseprotect && (($edata['weps'] && $edata['wepe'])||($edata['arbs'] && $edata['arbe'])||$edata['arhs']||$edata['aras']||$edata['arfs']||$edata['arts']||$edata['itms0']||$edata['itms1']||$edata['itms2']||$edata['itms3']||$edata['itms4']||$edata['itms5']||$edata['money'])){
							
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
			$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls'");
			$itemnum = $db->num_rows($result);
			if($itemnum <= 0){
				$log .= '<span class="yellow">周围找不到任何物品。</span><br>';
				$mode = 'command';
				return;
			}
			$itemno = rand(0,$itemnum-1);
			$db->data_seek($result,$itemno);
			$mi=$db->fetch_array($result);
			$itm0=$mi['itm'];
			$itmk0=$mi['itmk'];
			$itme0=$mi['itme'];
			$itms0=$mi['itms'];
			$itmsk0=$mi['itmsk'];
			$itmnp0=$mi['itmnp'];
			$iid=$mi['iid'];
			$db->query("DELETE FROM {$tablepre}mapitem WHERE iid='$iid'");
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