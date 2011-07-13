<?php

if (! defined ( 'IN_GAME' )) {
	exit ( 'Access Denied' );
}

function combat($active = 1, $battle_cmd = 'natk') {
	global $db,$tablepre,$now,$nosta,$hdamage,$hplayer,$infinfo,$mapdata,$infdata;
	global $log, $mode, $main, $cmd, $battle_title, $message, $w_combat_inf, $w_log;
	if ($battle_cmd == 'back') {
		$log .= "你逃跑了。";
		$bid = 0;
		$mode = 'command';
		return;
	}
	$pdata = & $GLOBALS['pdata'];
	extract($pdata,EXTR_REFS);
	$battle_title = '战斗发生';
	$battle_fac = $w_battle_fac = array('attack' => 1, 'defend' => 1, 'counter' => 1, 'hitrate' => 1, 'sidestep' => 1, 'hittime' => 0, 'hitfac' => 1);
	
	if ($active) {
		if($bid==0){
			$log .= "<span class=\"yellow\">你没有遇到敌人，或已经离开战场！</span><br>";
			$bid = 0;
			$mode = 'command';
			return;
		}
		
		$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$bid'" );
		if (! $db->num_rows ( $result )) {
			$log .= "对方不存在！<br>";
			$bid = 0;
			$mode = 'command';
			return;
		}		
		$edata = $db->fetch_array ( $result );
		
		if ($edata ['pls'] != $pls) {
			$log .= "<span class=\"yellow\">{$edata['name']}</span>已经离开了<span class=\"yellow\">{$mapdata[$pls]['name']}</span>。<br>";
			$bid = 0;
			$mode = 'command';
			return;
		} elseif ($edata ['hp'] <= 0) {
			global $corpseprotect,$gamestate;
			$log .= "<span class=\"red\">$edata ['name']</span>已经死亡，不能被攻击。<br>";
			if($edata['lasteff'] < $now -$corpseprotect && $gamestate < 40){
				include_once GAME_ROOT . './include/game/battle.func.php';
				findcorpse ( $edata );
			}
			$bid = 0;
			return;
		}
		
		$w_log = '';
		if ($message) {
			$log .= "<span class=\"lime\">你对{$edata ['name']}大喊：{$message}</span><br>";
			if (! $edata ['type']) {
				$w_log .= "<span class=\"lime\">{$name}对你大喊：{$message}</span><br>";
				//logsave ( $edata ['pid'], $now, $w_log ,'c');
			}
		}
		
		$wep_kind = check_wep_kind($wepk,$weps);
		$wep_temp = $wep;
		
		extract ( $edata, EXTR_PREFIX_ALL|EXTR_REFS, 'w' );		
		$w_wep_kind = check_wep_kind($w_wepk,$w_weps);
		$w_wep_temp = $w_wep;
		
		check_technique($battle_cmd, $pdata, $battle_fac, $wep_kind, $w_wep_kind,1);
		
		init_battle ($edata, 1 );
		include_once GAME_ROOT . './include/game/attr.func.php';
		
			
		set_enemy_rev($edata);
		$w_cannot_cmd = check_cannot_cmd($edata);
		
		$w_lasteff = $now;
		$log .= "你向<span class=\"red\">$w_name</span>发起了攻击！<br>";
		$att_dmg = get_dmg($pdata,$edata,$battle_fac,$w_battle_fac,$wep_kind,1);//attack ( $edata,$wep_kind, 1 );
		$w_hp -= $att_dmg;
		
		if (($w_hp > 0) && ($w_tactic != 4) && ($w_pose != 5)) {
			global $rangeinfo;
			
//			$w_wep_kind = substr ( $w_wepk, 1, 1 );
//			if (($w_wep_kind == 'G') && ($w_weps == $nosta)) {
//				$w_wep_kind = 'P';
//			} elseif(!$w_wep_kind) {
//				$w_wep_kind = 'N';
//			}

			//if (($rangeinfo [$wep_kind] == $rangeinfo [$w_wep_kind]) || ($rangeinfo [$w_wep_kind] == 'M')) {
			if ($rangeinfo [$wep_kind] <= $rangeinfo [$w_wep_kind] && $rangeinfo [$wep_kind] !== 0) {
				$counter = get_counter ($w_pls,$w_wep_kind, $w_tactic, $w_club, $w_inf);
				$counter_dice = rand ( 0, 99 );
				if ($counter_dice < $counter) {
					$log .= "<span class=\"red\">{$w_name}的反击！</span><br>";
					$lasteff = $now;
					$log .= auto_chat ( $edata,'defend' );
					
					$def_dmg = get_dmg($edata,$pdata,$w_battle_fac,$battle_fac,$w_wep_kind);//defend ($edata, $w_wep_kind );
					
					$hp -= $def_dmg;
				} else {
					
					$log .= auto_chat ( $edata, 'escape' );
					
					$log .= "<span class=\"red\">{$w_name}处于无法反击的状态，逃跑了！</span><br>";
				}
			} else {
				
				$log .= auto_chat ( $edata, 'cannot' );
				
				$log .= "<span class=\"red\">{$w_name}攻击范围不足，不能反击，逃跑了！</span><br>";
			}
		
		} elseif($w_hp > 0) {
			$log .= "<span class=\"red\">{$w_name}逃跑了！</span><br>";
		}
		
	} else {
		$wep_kind = check_wep_kind($wepk,$weps);
		$wep_temp = $wep;
		$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$bid'" );
		$edata = $db->fetch_array ( $result );
		$w_log = '';
		extract ( $edata, EXTR_PREFIX_ALL|EXTR_REFS, 'w' );
		init_battle ( $edata,1 );
		include_once GAME_ROOT . './include/game/attr.func.php';
		$w_wep_kind = check_wep_kind($w_wepk,$w_weps);
		$w_wep_temp = $w_wep;
		check_technique($battle_cmd, $pdata, $battle_fac, $wep_kind, $w_wep_kind,1);
		set_enemy_rev($edata);
		$w_cannot_cmd = check_cannot_cmd($edata);
		$w_lasteff = $now;
		
		if($w_wep_kind == 'D'){
			$log .= "你突然遭遇爆炸袭击！<br>";
		} elseif ($w_wep_kind == 'G'){
			$log .= "你突然遭遇枪械袭击！<br>";
		} else {
			$log .= "{$w_name}突然向你袭来！<br>";
		}		
		$lasteff = $now;
		$log .= auto_chat ( $edata, 'attack' );
		
		$def_dmg = get_dmg($edata,$pdata,$w_battle_fac,$battle_fac,$w_wep_kind);//defend ($edata, $w_wep_kind, 1 );
		
		$hp -= $def_dmg;
		if (($hp > 0) && ($tactic != 4) && ($pose != 5)) {
			global $rangeinfo;
			if ($rangeinfo [$wep_kind] >= $rangeinfo [$w_wep_kind] && $rangeinfo [$w_wep_kind] !== 0) {
				$counter = get_counter ( $pls,$wep_kind, $tactic, $club, $inf ) * $battle_fac['counter'];
				$counter_dice = rand ( 0, 99 );
				if ($counter_dice < $counter) {
					$log .= "<span class=\"red\">你的反击！</span><br>";
					//$wep_kind = substr ( $wepk, 1, 1 );
					$wep_kind = check_wep_kind($wepk,$weps);
					$att_dmg = get_dmg($pdata,$edata,$battle_fac,$w_battle_fac,$wep_kind,1);//attack ( $edata, $wep_kind );
					$w_hp -= $att_dmg;
				} else {
					$log .= "<span class=\"red\">你处于无法反击的状态，逃跑了！</span><br>";
				}
			} else {
				$log .= "<span class=\"red\">你攻击范围不足，不能反击，逃跑了！</span><br>";
			}
		} elseif($hp > 0) {
			$log .= "<span class=\"red\">你逃跑了！</span><br>";
		}
	}
	if($w_cannot_cmd){ //判断是否解除部分特殊状态，如睡眠
		global $inf_cannot_cmd;
		foreach($inf_cannot_cmd as $key => $val){
			if(strpos($w_inf,$key)!==false && $val['atkref']){
				$w_inf = str_replace($key,'',$w_inf);
				$log .= "<span class=\"yellow\">{$w_name}从{$infdata[$key]['name']}状态中解除了！</span><br>";
				$w_log .= "<span class=\"yellow\">你从{$infdata[$key]['name']}状态中解除了！</span><br>";
			}	
		}
	}
	//player_save ( $edata );
	$att_dmg = isset($att_dmg) ? $att_dmg : 0;
	$def_dmg = isset($def_dmg) ? $def_dmg : 0;
	
	if (! $w_type) {//生成其他玩家的作战简报
		$w_inf_log = '';
		if ($w_combat_inf) {
			for($i=0;$i < strlen($w_combat_inf);$i++){
				$infstr = substr($w_combat_inf,$i,1);
				if(in_array($infstr,array_keys($infdata))){
					$w_inf_log .= "敌人的攻击造成你{$infdata[$infstr]['infnm']}了！<br>";
				}
			}
		}
		if($active){
			$w_log .= "手持<span class=\"red\">$wep_temp</span>的<span class=\"yellow\">$name</span>向你袭击！<br>你受到其<span class=\"yellow\">$att_dmg</span>点攻击，对其做出了<span class=\"yellow\">$def_dmg</span>点反击。<br>$w_inf_log";
		}else{
			$w_log .= "你发现了手持<span class=\"red\">$wep_temp</span>正打算攻击你的<span class=\"yellow\">$name</span>，并且先发制人！<br>你对其做出<span class=\"yellow\">$def_dmg</span>点攻击，受到其<span class=\"yellow\">$att_dmg</span>点反击。<br>$w_inf_log";
		}
		if($hp == 0){
			$w_log .= "<span class=\"yellow\">$name</span><span class=\"red\">被你杀死了！</span><br>";
		}
		
		logsave ( $w_pid, $now, $w_log ,'b');
	}
	
	if (($att_dmg > $hdamage) && ($att_dmg >= $def_dmg)) {//判断是否需要更新最高伤害
		$hdamage = $att_dmg;
		$hplayer = $name;
		save_combatinfo ();
	} elseif (($def_dmg > $hdamage) && (! $w_type)) {
		$hdamage = $def_dmg;
		$hplayer = $w_name;
		save_combatinfo ();
	}
	
	
	if ($hp <= 0) {//被杀
		$hp = 0;
		$w_killnum ++;
		//include_once GAME_ROOT . './include/state.func.php';
		if(set_death ( $pdata,$w_wep_kind,$w_wep_temp,$edata )){
			$w_killmsg = auto_chat ( $edata, 'kill' );
			if($w_killmsg){
				$log .= $w_name.'对你说道：'.$w_killmsg;
			}
		}		
	}

	if ($w_hp <= 0) {//杀敌
		$w_bid = $pid;
		$w_hp = 0;
		$killnum ++;
		//include_once GAME_ROOT . './include/state.func.php';
		if(set_death($edata,$wep_kind,$wep_temp,$GLOBALS['pdata'])){
			$log .= auto_chat ( $edata, 'death' );
			
			$log .= "<span class=\"red\">{$w_name}被你杀死了！</span><br>";
			$killmsg = auto_chat($GLOBALS['pdata'],'kill');
			if($killmsg){
				$log .= "你对{$w_name}说道：{$killmsg}";
			}
			include_once GAME_ROOT . './include/game/battle.func.php';
			findcorpse ( $edata );
		}else{
			$mode = 'command';
			init_battle ( $edata,1 );
			init_itemwords($edata,'w_');
			ob_start();
			include template('battle');
			$main = ob_get_contents();
			ob_end_clean();
			ob_start();
			include template('ok');
			$cmd = ob_get_contents();
			ob_end_clean();
			//$cmd = '<br><br><input type="hidden" name="mode" value="command"><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >确定</a><br>';
			$bid = $hp <= 0 ? $bid : 0;
		}
	}else{
		$mode = 'command';
		init_battle ( $edata,1 );
		init_itemwords($edata,'w_');
		ob_start();
		include template('battle');
		$main = ob_get_contents();
		ob_end_clean();
		ob_start();
		include template('ok');
		$cmd = ob_get_contents();
		ob_end_clean();
		//$cmd = '<br><br><input type="hidden" name="mode" value="command"><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >确定</a><br>';
		$bid = $hp <= 0 ? $bid : 0;
	}	
	player_save ( $edata );
	return;
}

function get_dmg($adata,$ddata,$abfac,$dbfac,$wep_kind = 'N',$active = 0){ //计算伤害并生成作战过程
	global $now,$nosta,$infobbs, $infinfo, $attinfo, $skillinfo,  $wepimprate,$region_dmg_fac;
	global $log,$message;
	extract($adata, EXTR_PREFIX_ALL | EXTR_REFS, 'a' );
	extract($ddata, EXTR_PREFIX_ALL | EXTR_REFS, 'd' );
	if($active){
		$a_sh_name = '你';$d_sh_name = $d_name;
	}else{
		$a_sh_name = $a_name;$d_sh_name = '你';
	}
	$log .= "{$a_sh_name}使用{$a_wep}<span class=\"yellow\">$attinfo[$wep_kind]</span>{$d_sh_name}！<br>";
	$att_key = player_property($adata,'att');
	$def_key = player_property($ddata,'def');
	$wep_skill = & ${'a_'.$skillinfo [$wep_kind]};
	$hitrate = get_hitrate ( $wep_kind, $wep_skill, $a_inf, $a_pls ) * $abfac['hitrate'] / $dbfac['sidestep'];
	if($active){
		$damage_p = set_dmg_p($adata, $att_key, $message ,1);
	}else{
		$damage_p = set_dmg_p($adata, $att_key);
	}
	$hit_time = get_hit_time($a_wepk, $a_weps, $a_wepnp, $att_key, $wep_kind, $wep_skill, $hitrate, $abfac);
	if ($hit_time ['hit'] > 0) {//判断是否命中
		$mustdie = get_mustdie($adata,$ddata,$att_key,$def_key,$active);
		if($mustdie){
			$damage = $d_hp;
		}else{
			$attack = get_att($adata,$wep_kind,$att_key);//基础伤害
			$defend = get_def($ddata,$def_key);
			$damage = set_orig_dmg ($adata,$ddata, $abfac, $dbfac, $attack, $defend, $wep_skill, $wep_kind ,$active);
			if ($wep_kind == 'F') {
				$damage = round ( ($a_wepe + $damage) * get_WF_p($a_club, $a_wepe, $active) );
			}
			$gender_p = check_gender($adata, $ddata, $att_key,$active);
			$damage *= checkarb ( $wep_kind, $def_key );
			$damage *= $damage_p * $gender_p;
			//$damage *= $gender_dmg_p;
			
			$damage = get_ex_dmg($damage, $adata, $ddata, $att_key, $def_key, $wep_kind, $wep_skill, $active);
			$damage = $damage > 1 ? round ( $damage ) : 1;
			get_pos_words($ddata, $hit_time,$active);
			get_pos_dmg ($ddata,$damage, $hit_time,$active);
			set_inf($ddata,$hit_time['inf'],$wep_kind,$att_key,$active);
			checkdmg ( $a_name, $d_name, $damage );
			get_dmg_punish($damage, $adata, $att_key, $active);
			get_battlelock($adata,$ddata,$att_key,$active);
		}
		
		check_KP_wep($adata, $hit_time ['imp'], $active);
		set_exprgup($adata,$ddata,$att_key,$def_key,$active);
		
	} else {
		$damage = 0;
		$log .= "但是没有击中！<br>";
	}
	check_GCDF_wep($adata, $hit_time ['atk'], $wep_kind,$active);
	addnoise ( $wep_kind, $att_key, $now, $a_pls, $a_pid, $d_pid);
	$wepsklup = 1;
	if($a_club == 10){$wepsklup *=2;}
	if(isset($att_key['us'])){$wepsklup *= 2;}
	$wep_skill += $wepsklup;
//	if($a_club == 10){
//		$wep_skill +=2;
//	}else{
//		$wep_skill +=1;
//	}
	return $damage;
}

function set_orig_dmg ($adata,$ddata,$abfac, $dbfac,$att,$def,$ws,$wp_kind,$active = 0){
	global $skill_dmg, $dmg_fluc, $weather;
	$pls = $adata['pls'];
	$attack_p = get_attack_p ( $weather, $pls, $adata['pose'], $adata['tactic'], $adata['club'], $adata['inf'], $active);
	$att_pow = $att * $attack_p * $abfac['attack'];
	$defend_p = get_defend_p ( $weather, $pls, $ddata['pose'], $ddata['tactic'], $ddata['club'], $ddata['inf'], 1-$active);
	$def_pow = $def * $defend_p * $dbfac['defend'];
	$damage = ($att_pow / $def_pow) * $ws * $skill_dmg [$wp_kind];
	$dmg_factor = (100 + rand ( - $dmg_fluc [$wp_kind], $dmg_fluc [$wp_kind] )) / 100;
	$damage = round ( $damage * $dmg_factor * rand ( 4, 10 ) / 10 );
	return $damage;
}

function get_pos_words($data,$htime,$active = 0){
	global $log,$infdata;
	if($htime['hit']<=0){return;}
	if(!$active){$name = '你';}
	else{$name = $data['name'];}
	
	$inflistwords = '';
	$inflist = Array();
	foreach(Array('h','b','a','f') as $val){
		if($htime['hitlist'][$val] > 0){
			$inflist[] = $val;
		}
	}
	$count = count($inflist);
	for($i = 0;$i < $count;$i++){
		if($i == 0){$inflistwords .= $infdata[$inflist[$i]]['short'].'部';}
		elseif($i == $count-1){$inflistwords .= '和'.$infdata[$inflist[$i]]['short'].'部';}
		else{$inflistwords .= '、'.$infdata[$inflist[$i]]['short'].'部';}
	}
	$log .= "击中了{$name}的{$inflistwords}！<br>";
	return;
}

function get_pos_dmg ($data, & $dmg, $htime ,$active = 0){
	global $log,$region_dmg_fac;
	if($htime['hit']<=0){return;}
	$odmg = $dmg;
	$dmg_p = 0;
	foreach($htime['hitlist'] as $key => $val){
		$region_p = $region_dmg_fac[$key];
		if($val <=2){$hitnum_p = $val;}
		elseif($val <=5){$hitnum_p = 2+($val-2)*0.8;}
		else{$hitnum_p = 4.4+($val-5)*0.5;}
		$dmg_p += $region_p*$hitnum_p;
	}
	$dmg = ceil($dmg * $dmg_p);
	if($htime['hit']>1){
		$log .= "造成了{$odmg}×{$dmg_p}=<span class=\"red\">$dmg</span>点伤害！<br>";
	}else{
		$log .= "造成了<span class=\"red\">$dmg</span>点伤害！<br>";
	}
	
	return;
}

function set_dmg_p(&$data, $a_ky, $msg = '',$active = 0){
	$cri_dice = rand ( 0, 99 );
	$club = $data['club'];
	$type = $data['type'];
	$nm = $data['name'];
	$rg = & $data['rage'];
	
	if ($club == 9) {
		$rg_m = 50;
		$dmg_p = 2;
		if (!empty($msg) || $rg >= 255) {
			$max_dice = 100;
		} elseif ($type != 0) {
			$max_dice = 40;
		} else {
			$max_dice = 0;
		}
		$cri_word = '发动必杀技';
	} else {
		$rg_m = 30;
		$dmg_p = 1.5;
		if ($rg >= 255) {
			$max_dice = 100;
		} else {
			$max_dice = 30;
		}
		$cri_word = '使出重击';
	}
	
	if (isset($a_ky['cr'])) {
		$rg_m = $club == 9 ? 20 : 10;
		if ($max_dice != 0) {
			$max_dice += 30;
		}
	}
	if ($cri_dice <= $max_dice && $rg >= $rg_m) {
		global $log;
		
		$log .= auto_chat ( $data, 'critical' );
		
		if ($active) {
			$log .= "你消耗<span class=\"yellow\">$rg_m</span>点怒气，<span class=\"red\">{$cri_word}</span>！<br>";
		} else {
			$log .= "{$nm}<span class=\"red\">{$cri_word}</span>！<br>";
		}
		$rg -= $rg_m;
		return $dmg_p;
	} else {
		return 1;
	}
}


function checkdmg($p1, $p2, $d) {
	if (($d >= 100) && ($d < 150)) {
		$words = "{$p1}对{$p2}做出了{$d}点的攻击，一定是有练过。";
	} elseif (($d >= 150) && ($d < 200)) {
		$words = "{$p1}拿了什么神兵？{$p2}被打了{$d}滴血。";
	} elseif (($d >= 200) && ($d < 250)) {
		$words = "{$p1}简直不是人！{$p2}瞬间被打了{$d}点伤害。";
	} elseif (($d >= 250) && ($d < 300)) {
		$words = "{$p1}发出会心一击！{$p2}损失了{$d}点生命！";
	} elseif (($d >= 300) && ($d < 400)) {
		$words = "{$p1}使出浑身解数奋力一击！{$d}点伤害！{$p2}还活着吗？";
	} elseif (($d >= 400) && ($d < 500)) {
		$words = "{$p1}使出呼风唤雨的力量！可怜的{$p2}受到了{$d}点的伤害！";
	} elseif (($d >= 500) && ($d < 600)) {
		$words = "{$p1}发挥所有潜能使出绝招！{$p2}招架不住，生命减少{$d}点！";
	} elseif (($d >= 600) && ($d < 750)) {
		$words = "{$p1}手中的武器闪耀出七彩光芒！{$p2}招架不住，生命减少{$d}点！";
	} elseif (($d >= 750) && ($d < 1000)) {
		$words = "{$p1}受到天神的加护，打出惊天动地的一击——{$p2}被打掉{$d}点生命值！";
	} elseif ($d >= 1000) {
		$words = "{$p1}燃烧自己的生命得到了不可思议的力量！【{$d}】点的伤害值，没天理啊……{$p2}死——定——了！";
	} else {
		$words = '';
	}
	if ($words) {
		naddnews ( 0, 'damage', $words );
	}
	return;
}

function checkarb($wk, $dk) {
	global $log,$wep_def,$ar_wep_def_fac,$ar_wep_def_r;
	$dmg_r = 1;
	if (isset($dk[$wep_def[$wk]])) {
		$dice = rand ( 0, 99 );
		if ($dice < 90) {
			$dmg_r = 0.5;
			$log .= "攻击被防具抵消了！<br>";

		}else{
			$log .= "防具没能发挥防御效果！<br>";
		}
	}
	return $dmg_r;
}

function get_hit_time($wepk, $weps, $wepnp, $att_key, $wep_kind, $wep_skill, $htr, $battle_fac) {
	global $log, $nosta, $infobbs, $wepimprate,$region_hit_r;
	$atime = get_wephittimes($wepk,$wep_kind);
	$batime = 1;
	//$atime = is_numeric(substr($wepk,3,1)) ? substr($wepk,3,1) : 1;
	$infr = $infobbs[$wep_kind];
	if(isset($att_key['br'])){
		$infr += $wepnp;
	}
	$wimpr = $wepimprate[$wep_kind];
	//echo $wimpr;
	if ($weps == $nosta && $wep_kind != 'g') {
		$wimpr *= 2;
	}
	if($battle_fac['hittime']){
		$atime += $battle_fac['hittime'];
		$batime += $battle_fac['hittime'];
	}elseif($battle_fac['hitfac']){
		$atime = ceil($atime * $battle_fac['hitfac']);
		$batime = ceil($batime * $battle_fac['hitfac']);
	}
	if($atime > 1){
		$batime = $wep_skill >= 100*($atime-$batime) ? $atime : $batime + floor($wep_skill/100);
		$atime = rand($batime,$atime);
	}	
	//$atk_t = $ws >= 800 ? 6 : 2 + floor ( $ws / 200 );
	if (($wep_kind == 'G' || $wep_kind == 'C' || $wep_kind == 'D' || $wep_kind == 'F') && $weps!==$nosta && $atime > $weps) {
		$atime = $weps;
	}
	$htime = 0;
	$hitlist = Array('h' => 0,'b' => 0,'a' => 0,'f' => 0);
	$inflist = Array('h' => 0,'b' => 0,'a' => 0,'f' => 0);
	$wimptime = 0;
	if($htr > 100){$htr = 100;}
	for($i = 1; $i <= $atime; $i ++) {
		$dice = rand ( 0, 99 );
		if ($dice < $htr) {
			$htime +=1;
			$dice = rand(0,99);
			foreach($region_hit_r as $key => $val){
				if($dice <= $val){
					$hitlist[$key]+=1;
					$dice2 = rand( 0, 99 );
					if($dice2 <= $infr){
						$inflist[$key]+=1;
					}
					break;
				}else{
					$dice -= $val;
				}
			}
			$dice = rand ( 0, 99 );
			if ($dice < $wimpr) {
				$wimptime ++;
			}
		}
		if($htr > 60){$htr *= 0.9;}
		elseif($htr > 25){$htr *= 0.95;}
		$wimpr *= $wimpr <= 0 ? 1 : 1.2;
	}
	if ($atime > 1 && $htime > 0) {
		$log .= "{$atime}次连续攻击命中<span class=\"yellow\">{$htime}</span>次！";
	}	
	return Array ('atk' => $atime, 'hit'=> $htime, 'hitlist' => $hitlist, 'inf' => $inflist, 'imp' => $wimptime );
}

function get_ex_dmg($dmg, $adata, &$ddata, $attkey, $defkey, $wep_kind, $wep_skill, $active = 0) {
//function get_ex_dmg($nm, $sd, $clb, &$inf, $attkey, $wk, $we, $ws, $defkey) {
	$aname = $adata['name'];
	$dname = $ddata['name'];
	$awepe = $adata['wepe'];
	$aclub = $adata['club'];
	$dinf = & $ddata['inf'];
	
	if($active){
		$sh_aname = '你';$sh_dname = $dname;
	}else{
		$sh_aname = $aname;$sh_dname = '你';
	}
	if ($attkey) {
		global $now,$log, $infdata, $ex_attack;
		global $ex_dmg_def,$ex_dmg_def_index, $ex_dmg_fluc, $ex_inf, $ex_inf_r, $ex_max_inf_r, $ex_skill_inf_r, $ex_good_wep, $ex_good_club,$ex_dmg_fac,$ex_inf_dmg_fac,$inf_cannot_cmd;
		//$log .= "原始伤害： $dmg <br>";
		$ex_f_dmg_fac = 1;
		$ex_f_inf_dmg_fac = 1;
		$ex_f_dmg_gua = 0;
		$ex_f_def_index = 0;
		foreach ( $ex_attack as $ex_dmg_sign ) {
			if (isset($attkey['A'.$ex_dmg_sign])) {
				$dmgnm = $infdata [$ex_dmg_sign]['dmgnm'];
				$def = $ex_dmg_def [$ex_dmg_sign];
				$defi = $ex_dmg_def_index[$ex_dmg_sign];
				$dmgf = $ex_dmg_fac [$ex_dmg_sign];
//				$bdmg = $ex_base_dmg [$ex_dmg_sign];
//				$mdmg = $ex_max_dmg [$ex_dmg_sign];
//				$wdmg = $ex_wep_dmg [$ex_dmg_sign];
//				$sdmg = $ex_skill_dmg [$ex_dmg_sign];
				$fluc = $ex_dmg_fluc [$ex_dmg_sign];
				if (in_array($ex_dmg_sign,array_keys($ex_inf))) {
					$dmginf = $infdata [$ex_dmg_sign]['name'];
					$ex_inf_sign = $ex_inf [$ex_dmg_sign];
					$infr = $ex_inf_r [$ex_inf_sign];
					$minfr = $ex_max_inf_r [$ex_inf_sign];
					$sinfr = $ex_skill_inf_r [$ex_inf_sign];
					
					$dmgp = $ex_inf_dmg_fac [$ex_inf_sign];
					
//					$punish = $ex_inf_punish [$ex_inf_sign];
					$ehtr = $ex_good_club [$ex_inf_sign] == $aclub ? 20 : 0;
				} else {
					$ex_inf_sign = '';
					
					$dmgp = 1;
					
//					$punish = 1;
					$ehtr = 0;
					$infr = 0;
					$minfr = 0;
					$sinfr = 0;
				}
//				$wk_dmg_p = $ex_good_wep [$ex_dmg_sign] == $wep_kind ? 1.5 : 1;
//				$e_dmg = $bdmg + $we*$wdmg + $ws*$sdmg; 
				
				if($dmgf == 0){
					$dmg_fac = 1;
					$dmg_gua = $ex_good_wep [$ex_dmg_sign] == $wep_kind ? $awepe : floor($awepe/2);
					//$log .= "{$dmgnm}对{$sh_dname}造成了{$dmg_gua}点额外伤害！<br>";
				}else{
					$dmg_fac = $ex_good_wep [$ex_dmg_sign] == $wep_kind ? $dmgf *1.5 : $dmgf;
					$dmg_gua = 0;
					//$log .= "{$dmgnm}使{$sh_dname}受到的伤害增加了！<br>";
				}
				
//				if($mdmg>0){
//					//$e_dmg = $e_dmg > $mdmg ? round($wk_dmg_p*$mdmg*rand(100 - $fluc, 100 + $fluc)/100) : round($wk_dmg_p*$e_dmg*rand(100 - $fluc, 100 + $fluc)/100);
//					$e_dmg = round($wk_dmg_p*$mdmg*($e_dmg/($e_dmg+$mdmg/2))*rand(100 - $fluc, 100 + $fluc)/100);
//				} else{
//					$e_dmg =  round($wk_dmg_p*$e_dmg*rand(100 - $fluc, 100 + $fluc)/100);
//				}
				//$e_dmg += round ( ($we / ($we + $wdmg) + $ws / ($ws + $sdmg)) * rand ( 100 - $fluc, 100 + $fluc ) / 200 * $bdmg * $wk_dmg_p );
				$ex_def_dice = rand(0,99);
				$inf_dmg_fac = 1;
				$def_index = 0;
				if (!isset($defkey[$def]) || $ex_def_dice > 90) { //没防御
					if(isset($defkey[$def])){
						$log .= "属性防御装备失效了！";
					}
					
					if($dmgf == 0){
						$log .= "{$dmgnm}造成了{$dmg_gua}点额外伤害！";
					}else{
						$log .= "{$dmgnm}提升了伤害值！";
					}
					
					if (strpos ( $dinf, $ex_dmg_sign ) !== false && $dmgp > 1) {
						$log .= "由于{$sh_dname}已经{$dmginf}，{$dmgnm}伤害倍增！";
						//$e_dmg *= $punish;
						$inf_dmg_fac = $dmgp;
					} elseif (strpos ( $dinf, $ex_dmg_sign ) !== false && $dmgp < 1) {
						$log .= "由于{$sh_dname}已经{$dmginf}，{$dmgnm}伤害减少！";
						//$e_dmg *= $punish;
						$inf_dmg_fac = $dmgp;
					} else {
						$ehtr += $infr + $wep_skill * $sinfr;
						$ehtr = $ehtr > $minfr ? $minfr : $ehtr;
					}
					
					if (!empty($ex_inf_sign) && (strpos ( $dinf, $ex_inf_sign ) === false)) {
						$dice = rand ( 0, 99 );
						if ($dice < $ehtr) {
							$dinf .= $ex_inf_sign;
							if ($active) {
								global $w_combat_inf;
								$w_combat_inf .= $ex_inf_sign;
							}
							if(in_array($ex_inf_sign,array_keys($inf_cannot_cmd))){
								$ddata[$inf_cannot_cmd[$ex_inf_sign]['field']] = $now;
							}
							elseif($ex_inf_sign == 's'){$ddata['lastslp'] = $now;}
							$log .= "并造成{$sh_dname}{$dmginf}了！";
							naddnews($now,'inf',$aname,$dname,$ex_inf_sign);
						}
					}
					
					$log .= '<br />';
					
				} else { //属性防御
					$log .= "{$dmgnm}被防具抵消了！";
					
					if($dmgf == 0){
						$dmg_gua = floor($dmg_gua * 0.75);
					}else{
						$def_index = $defi;
					}
					
					if($dmgf == 0){
						$log .= "造成了{$dmg_gua}点额外伤害！";
					}else{
						//$log .= "{$dmgnm}使{$sh_dname}受到了额外的伤害！";
					}
					
					//$e_dmg = round ( $e_dmg / 2 );
					
					
					$log .= '<br />';
				}
				//$log .= "{$dmgnm}：伤害因数： $dmg_fac , 伤害保证：$dmg_gua , 受伤伤害因数： $inf_dmg_fac <br>";
				//$ex_final_dmg += $e_dmg;
				$ex_f_dmg_fac *= $dmg_fac;
				$ex_f_inf_dmg_fac *= $inf_dmg_fac;
				$ex_f_dmg_gua += $dmg_gua;
				$ex_f_def_index += $def_index;
			}
		}
		$ex_f_def_fac=0.25 + 30/ ($ex_f_def_index*4+40);
		//$log .= "总结：伤害因数： $ex_f_dmg_fac , 伤害保证：$ex_f_dmg_gua , 受伤伤害因数： $ex_f_inf_dmg_fac , 防御因数： $ex_f_def_fac <br>";
		
		return round($dmg * $ex_f_dmg_fac * $ex_f_inf_dmg_fac * $ex_f_def_fac + $ex_f_dmg_gua);
	} else {
		return $dmg;
	}
}

function get_WF_p($clb, $we, $active = 0) {
	global $log;
	if (!$active) {
		$factor = 0.5;
	} else {
		$sp = & $GLOBALS['pdata']['sp'];
		$we = $we > 0 ? $we : 1;
		if ($clb == 9) {
			$spd0 = round ( 0.25*$we);
		} else {
			$spd0 = round ( 0.3*$we);
		}
		if ($spd0 >= $sp) {
			$spd = $sp - 1;
		} else {
			$spd = $spd0;
		}
		$factor = 0.5 + $spd / $spd0 / 2;
		$f = round ( 100 * $factor );
		$log .= "你消耗{$spd}点体力，发挥了灵力武器{$f}％的威力！";
		$sp -= $spd;
	}
	return $factor;
}

function check_KP_wep(&$data, $ht, $active=0) {
	global $log, $nosta;
	if($active){
		$nm = '你';
	}else{
		$nm = & $data['name'];
	}
	$wp = & $data['wep'];
	$wk = & $data['wepk'];
	$we = & $data['wepe'];
	$ws = & $data['weps'];
	$wsk = & $data['wepsk'];
	$wnp = & $data['wepnp'];
	if ($ht > 0 && $ws == $nosta) {
		$we -= $ht;
		$log .= "{$nm}的{$wp}的攻击力下降了{$ht}！<br>";
		if ($we <= 0) {
			$log .= "{$nm}的<span class=\"red\">$wp</span>使用过度，已经损坏，无法再装备了！<br>";
			reset_wep($wp,$wk,$we,$ws,$wsk,$wnp);
		}
	} elseif ($ht > 0 && $ws != $nosta) {
		$ws -= $ht;
		$log .= "{$nm}的{$wp}的耐久度下降了{$ht}！<br>";
		if ($ws <= 0) {
			$log .= "{$nm}的<span class=\"red\">$wp</span>使用过度，已经损坏，无法再装备了！<br>";
			reset_wep($wp,$wk,$we,$ws,$wsk,$wnp);
		}
	}
	return;
}

function check_GCDF_wep(& $data, $ht, $wp_kind,$active = 0) {
	global $log, $nosta;
	$nm = $data['name'];
	$wp = & $data['wep'];
	$wk = & $data['wepk'];
	$we = & $data['wepe'];
	$ws = & $data['weps'];
	$wsk = & $data['wepsk'];
	$wnp = & $data['wepnp'];
	if ((($wp_kind == 'C') || ($wp_kind == 'D')|| ($wp_kind == 'F')) && ($ws != $nosta)) {
		$ws -= $ht;
		if ($active) {
			$log .= "你消耗了{$ht}个{$wp}。<br>";
		}
		if ($ws <= 0) {
			if ($active) {
				$log .= "你的<span class=\"red\">$wp</span>用光了！<br>";
			}else{
				$log .= "{$nm}的<span class=\"red\">$wp</span>用光了！<br>";
			}
			reset_wep($wp,$wk,$we,$ws,$wsk,$wnp);
		}
	} elseif (($wp_kind == 'G') && ($ws != $nosta)) {
		$ws -= $ht;
		if ($active) {
			$log .= "你消耗了{$ht}发弹药。<br>";
		}
		if ($ws <= 0) {
			if ($active) {
				$log .= "你的<span class=\"red\">$wp</span>的弹药用光了！<br>";
			}else{
				$log .= "{$nm}的<span class=\"red\">$wp</span>的弹药用光了！<br>";
			}
			$ws = $nosta;
		}
	}
	return;
}

function set_inf(& $ddata,$ht,$wp_kind,$att_key,$active = 0){
	if($ht > 0) {
		global $infatt,$log,$infdata,$arinf_base_r,$arinf_max_r,$arinf_eff,$nosta,$infobbs;
		$dinf = & $ddata['inf'];
		if($active){
			$nm = $ddata['name'];
		}else{
			$nm = '你';
		}
		$inflist = Array('h' => false,'b' => false,'a' => false,'f' => false);
		$arimplist = Array('h' => 0,'b' => 0,'a' => 0,'f' => 0);
		foreach($ht as $key => $val){
			if($val){
				$dar = $ddata['ar'.$key];
				$dare = $ddata['ar'.$key.'e'];
				$dars = $ddata['ar'.$key.'s'];
				if($dars > 0 || $dars == $nosta){
					for($i=0;$i < $val;$i++){
						$dice = rand(0,99);
						$arinf_r = $arinf_base_r[$key] + $arinf_eff[$key]*$dare;
						$arinf_r = $arinf_r > $arinf_max_r[$key] ? $arinf_max_r[$key] : $arinf_r;
						//$log .= $dar.'的防御概率是'.$arinf_r.'<br>';
						if($dice <= $arinf_r){
							$arimplist[$key] += 1;
							if(isset($att_key['br'])){$arimplist[$key] += 4;}
						}else{
							$inflist[$key] = true;
							if(isset($att_key['br'])){$arimplist[$key] += 3;}							
						}
						
					}
				}else{
					$inflist[$key] = true;
				}				
			}			
		}
//		for($i=0;$i<$ht;$i++){
//			$inf_pos = Array_rand($inflist,1);
//			$dar = $ddata['ar'.$inf_pos];
//			$dare = $ddata['ar'.$inf_pos.'e'];
//			$dars = $ddata['ar'.$inf_pos.'s'];
//			if($dars > 0 || $dars == $nosta){
//				$dice = rand(0,99);
//				$arinf_r = $arinf_base_r[$inf_pos] + $arinf_eff[$inf_pos]*$dare;
//				$arinf_r = $arinf_r > $arinf_max_r[$inf_pos] ? $arinf_max_r[$inf_pos] : $arinf_r;
//				//$log .= $dar.'的防御概率是'.$arinf_r.'<br>';
//				if($dice <= $arinf_r){
//					$arimplist[$inf_pos] += 1;
//				}else{
//					$inflist[$inf_pos] = true;
//				}
//			}else{
//				$inflist[$inf_pos] = true;
//			}
//		}
		foreach($arimplist as $key => $val){
			if($val){
				$dar = & $ddata['ar'.$key];
				$dark = & $ddata['ar'.$key.'k'];
				$dare = & $ddata['ar'.$key.'e'];
				$dars = & $ddata['ar'.$key.'s'];
				$darsk = & $ddata['ar'.$key.'sk'];				
				if($dars != $nosta){
					$dars -= $val;
					$log .= "{$nm}的{$dar}的耐久度下降了{$val}！<br>";		
					if($dars <= 0){
						$log .= "{$nm}的<span class=\"red\">$dar</span>受损过重，无法再装备了！<br>";
						$dar = $dark = $darsk = '';
						$dare = $dars = 0;
					}
				}				
			}
		}
		foreach($inflist as  $key => $val){
			if($val && strpos ( $dinf, $key ) === false){
				$dinf .= $key;
				$log .= "{$nm}的<span class=\"red\">{$infdata[$key]['short']}</span>部受伤了！<br>";
				if($active){
					global $w_combat_inf;
					$w_combat_inf .= $key;
				}
			}
		}
	}	
	return;
}

function get_dmg_punish($dmg, &$data, $a_ky, $active = 0) {
	if ($dmg >= 1000) {
		global $log;
		$hp = & $data['hp'];
		$nm = $data['name'];
		if ($dmg < 2000) {
			$hp_d = floor ( $hp / 2 );
		} elseif ($dmg < 5000) {
			$hp_d = floor ( $hp * 2 / 3 );
		} else {
			$hp_d = floor ( $hp * 4 / 5 );
		}
		if (isset($a_ky['HP'])) {
			$hp_d = floor ( $hp_d / 5 );
		}
		if($active){
			$log .= "惨无人道的攻击对你自身造成了<span class=\"red\">$hp_d</span>点<span class=\"red\">反噬伤害！</span><br>";
		}else{
			$log .= "惨无人道的攻击对{$nm}自身造成了反噬伤害！</span><br>";
		}
		$hp -= $hp_d;
	}
	return;
}

function set_exprgup(&$adata,&$ddata,$attkey,$defkey,$active = 0) {
	global $log;
	$alv = & $adata['lvl'];
	$aexp = & $adata['exp'];
	//$arg = & $adata['rage'];
	$dlv = & $ddata['lvl'];
	//$dexp = & $ddata['exp'];
	$drg = & $ddata['rage'];
	$expup = round ( ($dlv - $alv) / 3 );
	if($expup <= 0){$expup = 1;}
	if(isset($attkey['ue'])){$expup *= 2;}
	$aexp += $expup;
	if($active){
		global $upexp;
		$anl_exp = $upexp;
	} else {
		global $w_upexp;
		$anl_exp = $w_upexp;
	}
	if ($aexp >= $anl_exp) {
		//include_once GAME_ROOT . './include/state.func.php';
		set_lvlup($adata,$active);
		//lvlup ( $lv_a, $exp, $isplayer );
	}
	$rgup = round ( ($alv - $dlv) / 3 );
	$drg += $rgup > 0 ? $rgup : 1;
	return;
}

function get_mustdie($adata,$ddata,$attkey,$defkey,$active = 0){
	global $now,$log;
	$flag = false;
	if(isset($attkey['AD'])){
		$alv = & $adata['lvl'];
		$dlv = & $ddata['lvl'];
		$nm_a = $active ? '你' : $adata['name'];
		$nm_d = $active ? $ddata['name'] : '你';
		$prob = $adata[$attkey['AD'][0].'np'] * (1 + $alv/($alv+$dlv));
		$dice = rand(0,999);
		if($dice < $prob){
			$log .= "{$nm_a}使出的<span class=\"red\">即死攻击</span>命中了{$nm_d}！<span class=\"yellow\">{$nm_d}被直接杀死了！</span><br>";
			naddnews ( $now, 'mustdie', $adata['name'], $ddata['name']);
			$flag = true;
		}else{
			$log .= "{$nm_a}凭借武器使出了<span class=\"red\">即死攻击</span>，但是没有命中！<br>";
		}
		
	}
	return $flag;
}

function addnoise($wp_kind, $attkey ,$ntime, $npls, $nid1, $nid2) {
	if ((($wp_kind == 'G') && (!isset($attkey['si']))) || ($wp_kind == 'F')) {
		set_noise ($wp_kind, $npls, $nid1, $nid2, $ntime);
	} elseif ($wp_kind == 'D' || isset($attkey['Ad'])){
		set_noise ('D', $npls, $nid1, $nid2, $ntime);
	}
	return;
}

function check_gender($adata, $ddata, $attkey,$active = 0) {
	global $log;
	$gd_dmg_p = 1;
	$nm_a = $active ? '你' : $adata['name'];
	$nm_d = $active ? $ddata['name'] : '你';
	$gd_a = $adata['gd'];$gd_d = $ddata['gd'];
	if ( ( ( ( isset ( $attkey['sl'] ) ) && ( $gd_a != $gd_d ) ) || ( ( isset($attkey['sg']) ) && ($gd_a == $gd_d ) ) ) && (! rand ( 0, 4 ))) {
		
		$log .= "<span class=\"red\">{$nm_a}被{$nm_d}迷惑，无法全力攻击！</span><br>";
		$gd_dmg_p = 0;
	} elseif ( ( ( ( isset($attkey['sl']) ) && ($gd_a == $gd_d)) || ((isset($attkey['sg'])) && ($gd_a != $gd_d))) && (! rand ( 0, 4 ))) {
		
		$log .= "<span class=\"red\">{$nm_a}被{$nm_d}激怒，伤害加倍！</span><br>";
		$gd_dmg_p = 2;
	}
	return $gd_dmg_p;
}

function get_battlelock($adata,& $ddata,$att_key,$active = 0){
	global $log,$now;
	if($active){$aname = '你';$dname = $ddata['name'];}
	else{$aname = $adata['name'];$dname = '你';}
	$dinf = & $ddata['inf'];
	if(isset($att_key['AL'])){
		$dice = rand(0,99);
		if($dice < 80 && strpos($dinf,'L')===false){
			if($adata['club'] == 17){
				$log .= "{$aname}为轨道空间站指示了目标！<span class=\"yellow\">{$dname}被空间站锁定了！</span><br>";
				$dinf .= 'L';
				naddnews($now,'battlelock',$ddata['name']);
			}else{
				$log .= "{$aname}想给空间站发送信息，但是得不到回应。<br>";
			}
		}
	}
}

function check_wep_kind($wepk,$weps){
	global $nosta;
	$wep_kind = substr ( $wepk, 1, 1 );
	if (($wep_kind == 'G') && ($weps == $nosta)) {
		$wep_kind = 'g';
	} elseif(!$wep_kind) {
		$wep_kind = 'N';
	}
	return $wep_kind;
}

function get_att($adata,$wep_kind,$att_key){
	global $skillinfo;
	$oatt = $adata['att'];
	$wepe = $adata['wepe'];
	$attfac = 100;
	if($wep_kind == 'g'){
		$watt = round ( $wepe / 4 );
	}elseif($wep_kind == 'N'){
		$watt = round ($adata[$skillinfo [$wep_kind]] * 2/3);
	}else{
		$watt = $wepe * 2;
	}
	if(isset($att_key['uA'])){
		foreach($att_key['uA'] as $val){
			$uanp = $adata[$val.'np'];
			if($uanp <= 0){$uanp = 1;}
			$attfac += $uanp;
		}
	}
	if(isset($att_key['dA'])){
		foreach($att_key['dA'] as $val){
			$danp = $adata[$val.'np'];
			if($danp <= 0){$danp = 1;}
			$attfac -= $danp;
		}
		if($attfac < 0){$attfac = 1;}
	}
	//echo '攻击力比例：'.$attfac.'%<br>';
	$att = round(($oatt + $watt) * $attfac / 100);
	return $att;
}

function get_def($ddata,$def_key){
	$odef = $ddata['def'];
	$ardef = 0;
	$deffac = 100;
	foreach(Array('b','h','a','f') as $val){
		if($ddata['ar'.$val.'s']){
			$ardef += $ddata['ar'.$val.'e'];
		}
	}
	if(isset($def_key['uD'])){
		foreach($def_key['uD'] as $val){
			$udnp = $ddata[$val.'np'];
			if($udnp <= 0){$udnp = 1;}
			$deffac += $udnp;
		}
	}
	if(isset($def_key['dD'])){
		foreach($def_key['dD'] as $val){
			$ddnp = $ddata[$val.'np'];
			if($ddnp <= 0){$ddnp = 1;}
			$deffac -= $ddnp;
		}
		if($deffac < 0){$deffac = 1;}
	}
	$def = round(($odef + $ardef) * $deffac / 100);
	//echo '防御力比例：'.$deffac.'%<br>';
	return $def;
}

function set_enemy_rev(&$data){
	global $log,$w_log,$restinfo;
	$mapprop = player_property($data);
	$state = $data['state'];$hp = $data['hp'];$mhp = $data['mhp'];$name = $data['name'];
	//$lasteff = & $data['lasteff'];
	if($hp < $mhp){
		if(isset($mapprop['HH'])){
			$hhitm = $data[$mapprop['HH'][0]];$hhitmnp = $data[$mapprop['HH'][0].'np'];
			if($hhitmnp <=0){$hhitmnp = 1;}
			$restlog = set_rest(2,$data,0,$hhitmnp);
			if($restlog){
				$log .= "在<span class=\"yellow\">$hhitm</span>的作用下，{$name}".$restlog;
				$w_log .= "在<span class=\"yellow\">$hhitm</span>的作用下，你".$restlog;
			}
		}
		if($state == 2 || $state == 3){
			$restlog = set_rest('rest',$data);
			if($restlog){
				$log .= "出于<span class=\"yellow\">$restinfo[$state]</span>的效果，{$name}".$restlog;
				$w_log .= "出于<span class=\"yellow\">$restinfo[$state]</span>的效果，你".$restlog;
				
			}
		}
	}
	//$lasteff = $now;
	return;
}


function check_technique(&$b_cmd, &$data, &$b_fac, $wep_kind = 'N', $w_wep_kind = 'N', $active = 0){
	global $log, $techniqueinfo;
	//echo '攻击武器：'.$wep_kind;
	$sp = & $data['sp'];
	$technique = $data['technique'];
	$a_tech = $techniqueinfo['active']['combat'];
	$p_tech = $techniqueinfo['passive']['combat'];
	$a_known = $p_known = Array();
	for($i=0;$i < strlen($technique); $i+=3){
		$tstr = substr($technique,$i,3);
		if(isset($a_tech[$tstr])){
			$a_known[] = $tstr;
		}elseif(isset($p_tech[$tstr])){
			$p_known[] = $tstr;
		}
	}

	if($b_cmd != 'natk'){
		if(!isset($a_tech[$b_cmd])){
			if($active){$log .= '技能指令错误！将进行普通攻击。<br>';}
			$b_cmd = 'natk';
		}elseif($a_tech[$b_cmd]['dsp'] > $sp){
			if($active){$log .= '体力不足！将进行普通攻击。<br>';}
			$b_cmd = 'natk';
		}elseif(!in_array($b_cmd,$a_known)){
			if($active){$log .= '你不懂得此技能！将进行普通攻击。<br>';}
			$b_cmd = 'natk';
		}elseif($a_tech[$b_cmd]['wep_kind'] != 'A' && strpos($a_tech[$b_cmd]['wep_kind'],$wep_kind)===false){
			if($active){$log .= '技能和武器类型不匹配！将进行普通攻击。<br>';}
			$b_cmd = 'natk';
		}else{
			if($active){
				$sp -= $a_tech[$b_cmd]['dsp'];
				$log .= "你消耗<span class=\"yellow\">{$a_tech[$b_cmd]['dsp']}</span>点体力，使出了<span class=\"yellow\">{$a_tech[$b_cmd]['name']}</span>！<br>";
			}			
			foreach(array_keys($b_fac) as $fval){
				if(isset($a_tech[$b_cmd][$fval]) && ($a_tech[$b_cmd]['anti_kind'] == 'A' || strpos($a_tech[$b_cmd]['anti_kind'],$w_wep_kind) !== false)){
					if($fval == 'hittime'){
						$htstr = $a_tech[$b_cmd][$fval];
						$httime = rand(substr($htstr,0,1),substr($htstr,2,1));
						$b_fac[$fval] += $httime;
					}else{
						$b_fac[$fval] *= $a_tech[$b_cmd][$fval];
					}					
				}
			}
		}
	}	
	if(!empty($p_known)){
		foreach($p_known as $pval){
			foreach(array_keys($b_fac) as $fval){
				if(isset($p_tech[$pval][$fval]) && ($p_tech[$pval]['wep_kind'] == 'A' || strpos($p_tech[$pval]['wep_kind'],$wep_kind)!==false) && ($p_tech[$pval]['anti_kind'] == 'A' || strpos($p_tech[$pval]['anti_kind'],$w_wep_kind) !== false)){
					$b_fac[$fval] *= $p_tech[$pval][$fval];
				}
			}
		}
	}
	//var_dump($b_fac);
	return;
}
?>