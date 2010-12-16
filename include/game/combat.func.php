<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function combat($w_pdata,$active = 1,$wep_kind = '') {
	global $log,$mode,$main,$cmd,$battle_title,$db,$tablepre,$pls,$message,$now,$w_log,$nosta,$hdamage,$hplayer;
	global $pid,$name,$club,$inf,$lvl,$exp,$killnum,$bid,$tactic,$pose,$hp;
	global $wep,$wepk,$wepe,$weps,$wepsk;
	global $w_pid,$w_name,$w_pass,$w_type,$w_endtime,$w_gd,$w_sNo,$w_icon,$w_club,$w_hp,$w_mhp,$w_sp,$w_msp,$w_att,$w_def,$w_pls,$w_lvl,$w_exp,$w_money,$w_bid,$w_inf,$w_rage,$w_pose,$w_tactic,$w_killnum,$w_state,$w_wp,$w_wk,$w_wg,$w_wc,$w_wd,$w_wf,$w_teamID,$w_teamPass;
	global $w_wep,$w_wepk,$w_wepe,$w_weps,$w_arb,$w_arbk,$w_arbe,$w_arbs,$w_arh,$w_arhk,$w_arhe,$w_arhs,$w_ara,$w_arak,$w_arae,$w_aras,$w_arf,$w_arfk,$w_arfe,$w_arfs,$w_art,$w_artk,$w_arte,$w_arts,$w_itm0,$w_itmk0,$w_itme0,$w_itms0,$w_itm1,$w_itmk1,$w_itme1,$w_itms1,$w_itm2,$w_itmk2,$w_itme2,$w_itms2,$w_itm3,$w_itmk3,$w_itme3,$w_itms3,$w_itm4,$w_itmk4,$w_itme4,$w_itms4,$w_itm5,$w_itmk5,$w_itme5,$w_itms5,$w_wepsk,$w_arbsk,$w_arhsk,$w_arask,$w_arfsk,$w_artsk,$w_itmsk0,$w_itmsk1,$w_itmsk2,$w_itmsk3,$w_itmsk4,$w_itmsk5;
	global $infinfo,$w_combat_inf;
	

	$battle_title = '战斗发生';
	
	if(!$wep_kind) {
		$w1 = substr($wepk,1,1);
		$w2 = substr($wepk,2,1);
		if(($w1 == 'G')&&($weps==$nosta)){
			$wep_kind = $w2 ? $w2 : 'P';
		} else {
			$wep_kind = $w1;
		}
	}
	$wep_temp = $wep;

	if($active) {
		if($wep_kind == 'back'){
			$log .= "你逃跑了。";
			$bid = 0;
			$mode = 'command';
			return;
		}

		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$w_pdata'");
		if(!$db->num_rows($result)){
			$log .= "对方不存在！<br>";
			$bid = 0;
			$mode = 'command';
			return;
		}

		$edata = $db->fetch_array($result);
		
		if($edata['pid'] !== $bid){
			$log .= "<span class=\"yellow\">一瞬千击！<br>你想千击？<br>玩儿蛋去！<br>来弄我噻！<br>来弄我噻！<br>来弄我噻！<br></span><br>";
			$bid = 0;
			$mode = 'command';
			return;
		}
		
		if($edata['pls'] != $pls) {
			$log .= "<span class=\"yellow\">".$edata['name']."</span>已经离开了<span class=\"yellow\">$plsinfo[$pls]</span>。<br>";
			$bid = 0;
			$mode = 'command';
			return;
		} elseif($edata['hp'] <= 0) {
			$log .= "<span class=\"red\">".$edata['name']."</span>已经死亡，不能被攻击。<br>";
			include_once GAME_ROOT.'./include/game/battle.func.php';
			findcorpse($edata);
			$bid = 0;
			return;
		}

		if($message) {
			$log .= "<span class=\"lime\">你对 ".$edata['name']." 大喊：$message</span><br>";
			if(!$edata['type']){
				$w_log = "<span class=\"lime\">$name 对你大喊：$message</span>";
				logsave($edata['pid'],$now,$w_log);
			}
		}

		extract($edata,EXTR_PREFIX_ALL,'w');
		init_battle(1);
		include_once GAME_ROOT.'./include/game/attr.func.php';

		$log .= "你向<span class=\"red\">$w_name</span>发起了攻击！<br>";
		$att_dmg = attack($wep_kind,1);
		$w_hp -= $att_dmg;

		if(($w_hp > 0)&&($w_tactic != 4)&&($w_pose != 5)) {
			global $rangeinfo;
			$w_w1 = substr($w_wepk,1,1);
			$w_w2 = substr($w_wepk,2,1);
			if(($w_w1 == 'G')&&($w_weps==$nosta)) {
				$w_wep_kind = $w_w2 ? $w_w2 : 'P';
			} else {
				$w_wep_kind = $w_w1;
			}

			if(($rangeinfo[$wep_kind] == $rangeinfo[$w_wep_kind]) || ($rangeinfo[$w_wep_kind] == 'M')){
				$counter = get_counter($w_wep_kind,$w_tactic,$w_club,$w_inf);
				$counter_dice = rand(0,99);
				if($counter_dice < $counter) {
					$log .= "<span class=\"red\">{$w_name}的反击！</span><br>";
					$def_dmg = defend($w_wep_kind);
				} else {
					$log .= "<span class=\"red\">{$w_name}逃跑了！</span><br>";
				}
			} else {
				$log .= "<span class=\"red\">{$w_name}不能反击，逃跑了！</span><br>";
			}

		}
	} else {
		extract($w_pdata,EXTR_PREFIX_ALL,'w');
		init_battle(1);
		include_once GAME_ROOT.'./include/game/attr.func.php';

		$log .= "<span class=\"red\">$w_name</span>突然向你袭来！<br>";
		$w_w1 = substr($w_wepk,1,1);
		$w_w2 = substr($w_wepk,2,1);
		if(($w_w1 == 'G')&&($w_weps==$nosta)) {
			$w_wep_kind = $w_w2 ? $w_w2 : 'P';
		} else {
			$w_wep_kind = $w_w1;
		}
		$def_dmg = defend($w_wep_kind,1);
		if(($hp > 0)&&($tactic != 4)&&($pose != 5)) {
			global $rangeinfo;
			if(($rangeinfo[$wep_kind] == $rangeinfo[$w_wep_kind]) || ($rangeinfo[$wep_kind] == 'M')){
				$counter = get_counter($wep_kind,$tactic,$club,$inf);
				$counter_dice = rand(0,99);
				if($counter_dice < $counter) {
					$log .= "<span class=\"red\">你的反击！</span><br>";
					$wep_kind = substr($wepk,1,1);
					$att_dmg = attack($wep_kind);
					$w_hp -= $att_dmg;
				} else {
					$log .= "<span class=\"red\">你逃跑了！</span><br>";
				}
			} else {
				$log .= "<span class=\"red\">你不能反击，逃跑了！</span><br>";
			}
		}
	}
	w_save($w_pid);
	$att_dmg = $att_dmg ? $att_dmg : 0;
	$def_dmg = $def_dmg ? $def_dmg : 0;
	$w_inf_log = "";
	if(strpos($w_combat_inf,'p') !== false){
		$w_inf_log .= "敌人的攻击造成你<span class=\"purple\">中毒</span>了！<br>";
	}
	if(strpos($w_combat_inf,'h') !== false){
		$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[h]</span>部受伤了！<br>";
	}
	if(strpos($w_combat_inf,'b') !== false){
		$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[b]</span>部受伤了！<br>";
	}
	if(strpos($w_combat_inf,'a') !== false){
		$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[a]</span>部受伤了！<br>";
	}
	if(strpos($w_combat_inf,'f') !== false){
		$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[f]</span>部受伤了！<br>";
	}
	if(!$w_type) {
		$w_log = "与手持<span class=\"red\">$wep_temp</span>的<span class=\"yellow\">$name</span>发生战斗！<br>你受到其<span class=\"yellow\">$att_dmg</span>点攻击，对其造成<span class=\"yellow\">$def_dmg</span>点反击。<br>$w_inf_log";
		logsave($w_pid,$now,$w_log);
	}
	
	if(($att_dmg > $hdamage)&&($att_dmg>=$def_dmg)) {
		$hdamage = $att_dmg;
		$hplayer = $name;
		save_combatinfo();
	} elseif (($def_dmg > $hdamage)&&(!$w_type)) {
		$hdamage = $def_dmg;
		$hplayer = $w_name;
		save_combatinfo();
	}

	//$bid = $w_pid;

	if($w_hp <= 0) {
		$w_bid = $pid;
		$w_hp = 0;
		$killnum++;
		include_once GAME_ROOT.'./include/state.func.php';
		$killmsg = kill($wep_kind,$w_name,$w_type,$w_pid,$wep_temp);
		$log .= "<span class=\"red\">{$w_name}被你杀死了！</span><br>";
		$log .= "<span class=\"yellow\">你对{$w_name}说：“{$killmsg}”</span><br>";
		include_once GAME_ROOT.'./include/game/battle.func.php';
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$w_pid'");
		$cdata = $db->fetch_array($result);
		findcorpse($cdata);
		//$itmsk0 = '';
		$bid = 0;
		return;
	} else {
		$main = 'battle';
		//$itmsk0 = '';
		init_battle(1);
		$cmd = '<br><br><input type="hidden" name="mode" value="command"><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >确定</a><br>';
		$bid = $hp == 0 ? $bid : 0;
		return;
	}

}


function attack($wep_kind = 'N',$active = 0){
	global $now,$nosta,$log,$infatt,$infobbs,$infinfo,$attinfo,$skillinfo,${$skillinfo[$wep_kind]},$log,$skill_dmg,$weather,$pls,$pid,$name,$pose,$tactic,$club,$att,$inf,$message,$w_hp,$exp,$w_rage,$lvl,$w_lvl,$killnum,$rage,$hp,$gd,$w_pid,$w_gd,$w_name,$w_pose,$w_tactic,$w_club,$w_inf,$w_def;
	global $wep,$wepk,$wepe,$weps,$artk,$wepsk,$w_arbe,$w_arbsk,$w_arhe,$w_arae,$w_arfe;
	global $arhsk,$arbsk,$arask,$arfsk,$artsk,$wepimprate,$w_combat_inf;
	global $w_wepsk,$w_arhsk,$w_arask,$w_arfsk,$w_artsk,$w_artk,$dmg_fluc,$sp;
		
	if((strpos($wepk,'G') == 1)&&($weps == $nosta)) { 
		if(($wep_kind == 'G')||($wep_kind == 'P')){ $wep_kind = 'P';$watt = round($wepe/5);}
		else { $watt = $wepe; }
	}
	elseif($wep_kind == 'N'){ $watt = 0; }
	else{ $watt = $wepe*2; }
	$log .= "使用{$wep}<span class=\"yellow\">$attinfo[$wep_kind]</span>{$w_name}！<br>";
	
	$att_key = getatkkey($wepsk,$arhsk,$arbsk,$arask,$arfsk,$artsk,$artk);
	
	$wep_skill = & ${$skillinfo[$wep_kind]};
	$hitrate = get_hitrate($wep_kind,$wep_skill,$club,$inf);
	$hr_dice = rand(0,99);
	$hit_time = get_hit_time($att_key,$wep_skill,$hitrate,$wepk,$wep_kind,$weps,$infobbs[$wep_kind],$wepimprate[$wep_kind]);
	if($hit_time[1] > 0) {
		if((((strpos($att_key,"l")!== false)&&($gd != $w_gd))||((strpos($att_key,"g")!== false)&&($gd == $w_gd)))&&(!rand(0,4))) {
			$log .= "<span class=\"red\">你被{$w_name}迷惑，无法全力攻击！</span>";
			$damage = 1;
		} else {
			global $att;
			$attack = $att + $watt;
			$attack_p = get_attack_p($weather,$pls,$pose,$tactic,$club,$inf,$active);
			$att_pow = $attack * $attack_p;

			$defend = $w_def + $w_arbe + $w_arhe + $w_arae + $w_arfe;
			$w_active = 1-$active;
			$defend_p = get_defend_p($weather,$pls,$w_pose,$w_tactic,$w_club,$w_inf,$w_active);
			$def_pow = $defend * $defend_p;
			$damage = ($att_pow/$def_pow) * $wep_skill * $skill_dmg[$wep_kind];
			$dmg_factor = (100+rand(-$dmg_fluc[$wep_kind],$dmg_fluc[$wep_kind]))/100;
			//$log .= "$dmg_factor<br>";
			$damage = round($damage*$dmg_factor*rand(4,10)/10);
			//$damage = rand(round($damage*0.3),round($damage));
			$w_def_key = getdefkey($w_wepsk,$w_arhsk,$w_arbsk,$w_arask,$w_arfsk,$w_artsk,$w_artk);
			checkarb($damage,$wep_kind,$w_def_key);
			if($wep_kind == 'D'){$damage += $wepe;}
			elseif($wep_kind == 'F'){
				$damage = round(($wepe + $damage)*get_spell_factor(0,$club,$att_key,$sp,$wepe));
			}
			$damage_p = get_damage_p($rage,$wep_skill,$lvl,$club,$message,$att_key,0);
			$damage *= $damage_p;
			
			$damage = $damage>1 ? round($damage) : 1;
			if((((strpos($att_key,"l")!== false)&&($gd == $w_gd))||((strpos($att_key,"g")!== false)&&($gd != $w_gd)))&&(!rand(0,4))) {
				$log .= "<span class=\"red\">你被{$w_name}激怒，伤害加倍！</span>";
				$damage *= 2;
			}
		}
		if($hit_time[1]>1) {
			$d_temp = $damage;
			if($hit_time[1] ==2){$dmg_p = 1.8;}
			elseif($hit_time[1] ==3){$dmg_p = 2.5;}
			else{$dmg_p = 2.5 + 0.5*($hit_time[1]-3);}
			$damage = round($damage * $dmg_p);
			$log .= "造成{$d_temp}×{$dmg_p}＝<span class=\"red\">$damage</span>点伤害！<br>";
		} else {
			$log .= "造成<span class=\"red\">$damage</span>点伤害！<br>";
		}
		
		$damage += extradmg($w_name,0,$club,$w_inf,$att_key,$wepe,$wep_skill,$w_def_key);

		checkdmg($name,$w_name,$damage);
		
		if($damage>=1000) {
			$hp = ceil($hp/2);
			$log .= "惨无人道的攻击对你自己造成了<span class=\"red\">反噬伤害！</span><br>";
		}
		
		$inf_dice = rand(0,99);
		if($hit_time[2]>0){
			$infatt_dice = rand(0,4);
			if(($infatt_dice == 1)&&(strpos($infatt[$wep_kind],'b') !== false)){ $inf_att = 'b';}
			elseif(($infatt_dice == 2)&&(strpos($infatt[$wep_kind],'h') !== false)){ $inf_att = 'h';}
			elseif(($infatt_dice == 3)&&(strpos($infatt[$wep_kind],'a') !== false)){ $inf_att = 'a';}
			elseif(($infatt_dice == 4)&&(strpos($infatt[$wep_kind],'f') !== false)){ $inf_att = 'f';}
			if($inf_att) {
				global ${'w_ar'.$inf_att},${'w_ar'.$inf_att.'k'},${'w_ar'.$inf_att.'e'},${'w_ar'.$inf_att.'s'},${'w_ar'.$inf_att.'sk'};
				if(${'w_ar'.$inf_att.'s'}){
					${'w_ar'.$inf_att.'s'} -= $hit_time[2];
					$log .= "{$w_name}的{${'w_ar'.$inf_att}}的耐久度下降了{$hit_time[2]}！<br>";
					if(${'w_ar'.$inf_att.'s'} <= 0){
						$log .= "{$w_name}的<span class=\"red\">${'w_ar'.$inf_att}</span>受损过重，无法再装备了！<br>";
						${'w_ar'.$inf_att} = ${'w_ar'.$inf_att.'k'} = ${'w_ar'.$inf_att.'sk'} = '';
						${'w_ar'.$inf_att.'e'} = ${'w_ar'.$inf_att.'s'} = 0;
					}
				} else {
					if(strpos($w_inf,$inf_att) === false){
						$w_inf .= $inf_att;
						$w_combat_inf .= $inf_att;
						$log .= "{$w_name}的<span class=\"red\">$infinfo[$inf_att]</span>部受伤了！<br>";
					}
				}
			}
		}
		//if(($weps == $nosta)&&(($wep_kind == 'K')||($wep_kind == 'P'))&&(rand(0,4) == 0)) {
		if($hit_time[3] > 0 && $weps == $nosta) {
			$wepe_m = $hit_time[3] * 1;
			$wepe -= $wepe_m;
			$log .= "你的{$wep}的攻击力下降了{$wepe_m}！<br>";
			if($wepe <= 0){
				$log .= "你的<span class=\"red\">$wep</span>使用过度，已经损坏，无法再装备了！<br>";
				
				$wep = '拳头';
				$wepsk = '';
				$wepk = 'WN';
				$wepe = 0;
				$weps = $nosta;
			}
		} elseif($hit_time[3] > 0 && $weps != $nosta) {
			$weps -= $hit_time[3];
			$log .= "你的{$wep}的耐久度下降了{$hit_time[3]}！<br>";
			if($weps <= 0){
				$log .= "你的<span class=\"red\">$wep</span>使用过度，已经损坏，无法再装备了！<br>";
				$wep = '拳头';
				$wepsk = '';
				$wepk = 'WN';
				$wepe = 0;
				$weps = $nosta;
			}
		}

		$expup = round(($w_lvl - $lvl)/3);
		$exp += $expup>0 ? $expup : 1;

		global $upexp;
		if($exp >= $upexp){ 
			include_once GAME_ROOT.'./include/state.func.php';
			lvlup($lvl,$exp,1);
		}

		$rageup = round(($lvl - $w_lvl)/3);
		$w_rage += $rageup>0 ? $rageup : 1;

	} else {
		$damage = 0;
		$log .= "但是没有击中！<br>";
	}
	
	if((($wep_kind == 'C')||($wep_kind == 'D')||($wep_kind == 'F'))&&($weps != $nosta)){
		$weps -= $hit_time[0];
		$log .= "你用掉了{$hit_time[0]}个{$wep}。<br>";
		if($weps <= 0){
			$log .= "你的<span class=\"red\">$wep</span>用光了！<br>";
			$wep = '拳头';
			$wepsk = '';
			$wepk = 'WN';
			$wepe = 0;
			$weps = $nosta;
		}
	} elseif(($wep_kind == 'G')&&($weps != $nosta)) {
		$weps -= $hit_time[0];
		$log .= "你的{$wep}的子弹数减少了{$hit_time[0]}。<br>";
		if($weps <= 0){
			$log .= "你的<span class=\"red\">$wep</span>的子弹用光了！<br>";
			$weps = $nosta;
		}
	}
	if((($wep_kind == 'G')&&(strpos($wepsk,'S') === false))||($wep_kind == 'D')||($wep_kind == 'F')) {
		global $noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
		$noisetime = $now;
		$noisepls = $pls;
		$noiseid = $pid;
		$noiseid2 = $w_pid;
		$noisemode = $wep_kind;
		save_combatinfo();
	}
	
	$wep_skill ++;
	return $damage;
}


function defend($w_wep_kind = 'N',$active = 0){
	global $nosta,$log,$infatt,$infobbs,$infinfo,$attinfo,$skillinfo,${'w_'.$skillinfo[$w_wep_kind]},$log,$skill_dmg,$weather,$pls,$now,$pid,$name;
	global $w_pid,$w_att,$gd,$w_gd,$w_pose,$w_tactic,$w_club,$w_inf,$w_wep,$w_wepk,$w_wepsk,$w_wepe,$w_weps,$w_artk,$name,$w_name,$pose,$tactic,$club,$inf,$def,$arbe,$arhe,$arae,$arfe,$arbsk,$hp,$w_exp,$rage,$lvl,$w_lvl,$w_type,$w_sNo,$w_killnum,$w_rage,$w_hp;
	global $w_arhsk,$w_arbsk,$w_arask,$w_arfsk,$w_artsk,$wepimprate;
	global $wepsk,$arhsk,$arask,$arfsk,$artsk,$artk,$dmg_fluc,$w_sp;
	
	$w_wep_temp = $w_wep;
	
	if((strpos($w_wepk,'G') == 1)&&($w_wep_kind == 'P')) { $watt = round($w_wepe/5); }
	elseif($w_wep_kind == 'N') { $watt = 0; }
	else{ $watt = $w_wepe*2; }

	$log .= "{$w_name}使用{$w_wep}<span class=\"yellow\">$attinfo[$w_wep_kind]</span>你！<br>";

	$w_att_key = getatkkey($w_wepsk,$w_arhsk,$w_arbsk,$w_arask,$w_arfsk,$w_artsk,$w_artk);

	$w_wep_skill = & ${'w_'.$skillinfo[$w_wep_kind]};
	$hitrate = get_hitrate($w_wep_kind,$w_wep_skill,$w_club,$w_inf);

	$hr_dice = rand(0,99);
	$hit_time = get_hit_time($w_att_key,$w_wep_skill,$hitrate,$w_wepk,$w_wep_kind,$w_weps,$infobbs[$w_wep_kind],$wepimprate[$w_wep_kind]);

	if($hit_time[1] > 0) {
		if((((strpos($w_att_key,"l")!== false)&&($gd != $w_gd))||((strpos($w_att_key,"g")!== false)&&($gd == $w_gd)))&&(!rand(0,4))) {
			$log .= "<span class=\"red\">{$w_name}被你迷惑，无法全力攻击！</span>";
			$damage = 1;
		} else {
			global $w_att;
			$attack = $w_att + $watt;
			$attack_p = get_attack_p($weather,$pls,$w_pose,$w_tactic,$w_club,$w_inf,$active);
			$att_pow = $attack * $attack_p;

			$defend = $def + $arbe + $arhe + $arae + $arfe;
			$w_active = 1-$active;
			$defend_p = get_defend_p($weather,$pls,$pose,$tactic,$club,$inf,$w_active);
			$def_pow = $defend * $defend_p;

			$damage = ($att_pow/$def_pow) * $w_wep_skill * $skill_dmg[$w_wep_kind];
			$dmg_factor = (100+rand(-$dmg_fluc[$w_wep_kind],$dmg_fluc[$w_wep_kind]))/100;
			$damage = round($damage*$dmg_factor*rand(4,10)/10);
			//$damage = rand(round($damage*0.3),round($damage));
			$def_key = getdefkey($wepsk,$arhsk,$arbsk,$arask,$arfsk,$artsk,$artk);
			checkarb($damage,$w_wep_kind,$def_key);
			if($w_wep_kind == 'D'){$damage += $w_wepe;}
			elseif($w_wep_kind == 'F'){
				$damage = round(($w_wepe + $damage)*get_spell_factor(1,$w_club,$w_att_key,$w_sp,$w_wepe));
			}
			$damage_p = get_damage_p($w_rage,$w_wep_skill,$w_lvl,$w_club,'',$w_att_key,1);
			$damage *= $damage_p;
			
			$damage = $damage>1 ? round($damage) : 1;
			if((((strpos($w_att_key,"l")!== false)&&($gd == $w_gd))||((strpos($w_att_key,"g")!== false)&&($gd != $w_gd)))&&(!rand(0,4))) {
				$log .= "<span class=\"red\">{$w_name}被你激怒，伤害加倍！</span>";
				$damage *= 2;
			}
		}
		
		if($hit_time[1]>1) {
			$d_temp = $damage;
			if($hit_time[1] ==2){$dmg_p = 1.8;}
			elseif($hit_time[1] ==3){$dmg_p = 2.5;}
			else{$dmg_p = 2.5 + 0.5*($hit_time[1]-3);}
			$damage = round($damage * $dmg_p);
			$log .= "造成{$d_temp}×{$dmg_p}＝<span class=\"red\">$damage</span>点伤害！<br>";
		} else {
			$log .= "造成<span class=\"red\">$damage</span>点伤害！<br>";
		}

		$damage += extradmg("你",1,$w_club,$inf,$w_att_key,$wepe,$w_wep_skill,$def_key);
		
		checkdmg($w_name,$name,$damage);
		
		if($damage>=1000) {
			$w_hp = ceil($w_hp/2);
			$log .= "惨无人道的攻击对{$w_name}自身造成了<span class=\"red\">反噬伤害！</span><br>";
		}
		
		$inf_dice = rand(0,99);
		if($hit_time[2]>0) {
			$infatt_dice = rand(1,4);
			$inf_att = '';
			if(($infatt_dice == 1)&&(strpos($infatt[$w_wep_kind],'b') !== false)){ $inf_att = 'b';}
			elseif(($infatt_dice == 2)&&(strpos($infatt[$w_wep_kind],'h') !== false)){ $inf_att = 'h';}
			elseif(($infatt_dice == 3)&&(strpos($infatt[$w_wep_kind],'a') !== false)){ $inf_att = 'a';}
			elseif(($infatt_dice == 4)&&(strpos($infatt[$w_wep_kind],'f') !== false)){ $inf_att = 'f';}
			if($inf_att) {
				global ${'ar'.$inf_att},${'ar'.$inf_att.'k'},${'ar'.$inf_att.'e'},${'ar'.$inf_att.'s'},${'ar'.$inf_att.'sk'};
				if(${'ar'.$inf_att.'s'}) {
					${'ar'.$inf_att.'s'} -= $hit_time[2];
					$log .= "你的{${'ar'.$inf_att}}的耐久度下降了{$hit_time[2]}！<br>";
					if(${'ar'.$inf_att.'s'} <= 0){
						$log .= "你的<span class=\"red\">${'ar'.$inf_att}</span>受损过重，无法再装备了！<br>";
						${'ar'.$inf_att} = ${'ar'.$inf_att.'k'} = ${'ar'.$inf_att.'sk'} = '';
						${'ar'.$inf_att.'e'} = ${'ar'.$inf_att.'s'} = 0;
					}
				} else {
					if(strpos($inf,$inf_att) === false){
						$inf .= $inf_att;
						$log .= "你的<span class=\"red\">$infinfo[$inf_att]</span>部受伤了！<br>";
					}
				}
			}
		}
		if($hit_time[3] > 0 && $w_weps == $nosta) {
			$w_wepe_m = $hit_time[3] * 1;
			$w_wepe -= $w_wepe_m;
			$log .= "{$w_name}的{$w_wep}的攻击力下降了{$w_wepe_m}！<br>";
			if($w_wepe <= 0) {
				$log .= "{$w_name}的<span class=\"red\">$w_wep</span>使用过度，已经损坏，无法再装备了！<br>";
				$w_wep = '拳头';
				$w_wepsk = '';
				$w_wepk = 'WN';
				$w_wepe = 0;
				$w_weps = $nosta;
			}
		} elseif($hit_time[3] > 0 && $weps != $nosta) {
			$w_weps -= $hit_time[3];
			$log .= "{$w_name}的{$w_wep}的耐久度下降了{$hit_time[3]}！<br>";
			if($w_weps <= 0){
				$log .= "{$w_name}的<span class=\"red\">$w_wep</span>使用过度，已经损坏，无法再装备了！<br>";
				$w_wepe = $w_weps = 0;
				$w_wep = '拳头';
				$w_wepsk = '';
				$w_wepk = 'WN';
				$w_wepe = 0;
				$w_weps = $nosta;
			}
		}

		$expup = round(($lvl - $w_lvl)/3);
		$w_exp += $expup>0 ? $expup : 1;
		global $w_upexp;
		if($w_exp >= $w_upexp){ 
			include_once GAME_ROOT.'./include/state.func.php';
			lvlup($w_lvl,$w_exp,0); 
		}


		$rageup = round(($w_lvl - $lvl)/3);
		$rage += $rageup>0 ? $rageup : 1;

		$hp -= $damage;
		if($hp <= 0) {
			$hp = 0;
			$w_killnum++;
			include_once GAME_ROOT.'./include/state.func.php';
			$killmsg = death($w_wep_kind,$w_name,$w_type,$w_wep_temp);
			$log .= "<span class=\"yellow\">{$w_name}对你说：“{$killmsg}”</span><br>";
		}
	} else {
		$damage = 0;
		$log .= "但是没有击中！<br>";
	}
	
	if((($w_wep_kind == 'C')||($w_wep_kind == 'D')||($w_wep_kind == 'F'))&&($w_weps != $nosta)){
		$w_weps -= $hit_time[0];
		$log .= "{$w_name}用掉了{$hit_time[0]}个{$w_wep}。<br>";
		if($w_weps <= 0){
			$log .= "{$w_name}的<span class=\"red\">$w_wep</span>用光了！<br>";
			$w_wepe = $w_weps = 0;
			$w_wep = '拳头';
			$w_wepsk = '';
			$w_wepk = 'WN';
			$w_wepe = 0;
			$w_weps = $nosta;
		}
	} elseif(($w_wep_kind == 'G')&&($w_weps != $nosta)) {
		$w_weps -= $hit_time[0];
		$log .= "{$w_name}的{$w_wep}的子弹数减少了{$hit_time[0]}。<br>";
		if($w_weps <= 0){
			$log .= "{$w_name}的<span class=\"red\">$w_wep</span>的子弹用光了！<br>";
			$w_weps = $nosta;
		}
	}

	if((($w_wep_kind == 'G')&&(strpos($w_wepsk,'S') === false))||($w_wep_kind == 'D')||($wep_kind == 'F')){
		global $noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
		$noisetime = $now;
		$noisepls = $pls;
		$noiseid = $w_pid;
		$noiseid2 = $pid;
		$noisemode = $w_wep_kind;
		save_combatinfo();
	}
	$w_wep_skill ++;
	
	return $damage;
}

function get_damage_p(&$rg,$sk = 0,$lv = 0,$cl = 0,$msg = '',$atkcdt,$sd = 0){
	global $log;
	$cri_dice = rand(0,99);
	$damage_p = 1;
	if(strpos($atkcdt,"c") !== false) {
		$rg_m = 10;
		$max_dice = 75;
	} elseif($cl ==9) {
		$rg_m = 50;
		$max_dice = 50;
	} else {
		$rg_m = 30;
		$max_dice = 30;
	}
	if($cl == 9) {
		if($sd == 0){
			if((!empty($msg))&&($rg>=$rg_m) || $rg == 255){
				$log .= "你消耗<span class=\"yellow\">$rg_m</span>点怒气，<span class=\"red\">发动必杀技</span>！";
				$damage_p = 2;
				$rg -= $rg_m;
			}
		} else {
			if($cri_dice < $max_dice || $rg==255){
				$log .= "<span class=\"red\">发动必杀技</span>！";
				$damage_p = 2;
				$rg -= $rg_m;
			}
		}
	} elseif($cri_dice < $max_dice || $rg==255) {
		if(($rg >= $rg_m)&&($sk >= 20)&&($lv >= 3)){
			if($sd == 0) {
				$log .= "你消耗<span class=\"yellow\">$rg_m</span>点怒气，使出";
			}
			$log .= "<span class=\"red\">重击</span>！";
			$damage_p = 1.5;
			$rg -= $rg_m;
		}
	}
	return $damage_p;
}

function checkdmg($p1,$p2,$d) {
	if(($d>=100)&&($d<150)) {
		$words = "$p1 对 $p2 做出了 $d 点的攻击！ 一定是有练过！ ";
	} elseif(($d>=150)&&($d<200)) {
		$words = "$p1 拿了什么神兵！？ $p2 被打了 $d 滴血！！  ";
	} elseif(($d>=200)&&($d<250)) {
		$words = "$p1 简直不是人！！ $p2 瞬间被打了 $d 点伤害！！  ";
	} elseif(($d>=250)&&($d<300)) {
		$words = "$p1 发出会心一击！！！ $p2 损失了 $d 点生命！！！  ";
	} elseif(($d>=300)&&($d<400)) {
		$words = "$p1 使出浑身解数奋力一击！！！ $d 点伤害！！！ $p2 还活着吗？ ";
	} elseif(($d>=400)&&($d<500)) {
		$words = "$p1 使出呼风唤雨的力量！！！！可怜的 $p2 受到了 $d 点的伤害！！！！  ";
	} elseif(($d>=500)&&($d<600)) {
		$words = "$p1 发挥所有潜能使出绝招！！！！ $p2 招架不住，生命减少 $d 点！！！！  ";
	} elseif(($d>=600)&&($d<750)) {
		$words = "$p1 手中的武器闪耀出七彩光芒！！！！ $p2 招架不住，生命减少 $d 点！！！！  ";
	} elseif(($d>=750)&&($d<1000)) {
		$words = "$p1 受到天神的加护，打出惊天动地的一击 – $p2 被打掉 $d 点生命值！！！！！  ";
	} elseif($d>=1000) {
		$words = "$p1 燃烧自己的生命得到了不可思议的力量！！！！！ 「 $d 」 点的伤害值，没天理啊… $p2 死-定-了！！！！！  ";
	} else {
		$words = '';
	}
	if($words) {
		addnews(0,'damage',$words);
	}
	return;
}

function checkarb(&$dmg,$w,$ar) {
	global $log;
	if(strpos($ar,$w) !== false) {
		$dice = rand(0,99);
		if($dice < 90) {
			$dmg /= 2;
			$log .= "<span class=\"red\">攻击被防具抵消了！</span>";
		}
	}
}
function getatkkey($w,$ah,$ab,$aa,$af,$at,$atkind) {
	
	$atkcdt = "";
	$eqpkey = $w.$ah.$ab.$aa.$af.$at.substr($atkind,1,1);
	if(strpos($eqpkey,"c")!==false) {
		$atkcdt .= "c"; 
	}
	if(strpos($eqpkey,"l")!==false) {
		$atkcdt .= "l"; 
	}
	if(strpos($eqpkey,"g")!==false) {
		$atkcdt .= "g"; 
	}
	if(strpos($w,"r")!==false) {
		$atkcdt .= "r"; 
	}
	if(strpos($w,"p")!==false) {
		$atkcdt .= "p"; 
	}
	return $atkcdt;
}
function get_hit_time($ky,$ws,$htr,$wk0,$wk,$lmt,$infr,$wimpr) {
	global $log,$nosta;
	if($lmt == $nosta){
		$wimpr *= 2;
		if(strpos($wk0,'G') != false){
			$wimpr *= 4;
		}
	}
	if(strpos($ky,"r")!==false){
		$tt = $ws >=800 ? 6 : 2+floor($ws/200);
		if($wk == 'C' || $wk == 'D' || $wk == 'F'){
			if($lmt == $nosta){$lmt=99;}
			if($tt>$lmt){$tt=$lmt;}
		}
		if($wk == 'G' && $tt>$lmt){$tt=$lmt;}
		if($wk == 'P' && strpos($wk0,'G') != false){$tt=1;}
		$ht_t = 0;
		$inf_t = 0;
		$wimp_t = 0;
		//if($htr>100){$htr=100;}
		for ($i=1;$i<=$tt;$i++){
			$dice = rand(0,99);
			$dice2 = rand(0,99);
			$dice3 = rand(0,99);
			if($dice < $htr) {
				$ht_t++;
				if($dice2 < $infr){
				$inf_t++;
				}
				if($dice3 < $wimpr){
				$wimp_t++;
				}
			}
			$htr *= 0.8;
			$infr *= 0.9;
			$wimpr *= $wimpr <= 0 ? 1 : 1.2;
		}
	} else {
		$tt = 1;
		$ht_t = 0;
		$inf_t = 0;
		$wimp_t = 0;
		$dice=rand(0,99);
		$dice2=rand(0,99);
		$dice3=rand(0,99);
		if($dice < $htr) {
			$ht_t = 1;
			if($dice2 < $infr) {
			$inf_t = 1;
			}
			if($dice3 < $wimpr) {
			$wimp_t = 1;
			}
		}
	}
	if($tt>1 && $ht_t>0){$log .= "{$tt}次连续攻击命中<span class=\"yellow\">{$ht_t}</span>次！";}
	return Array($tt,$ht_t,$inf_t,$wimp_t);
}

function getdefkey($w,$ah,$ab,$aa,$af,$at,$atkind){
	$defcdt = "";
	$eqpkey = $w.$ah.$ab.$aa.$af.$at.substr($atkind,1,1);
	if(strpos($eqpkey,"A")!==false) {$defcdt .= "NPKGCDF";}
	else{
		if(strpos($eqpkey,"N")!==false) {$defcdt .= "N";}
		if(strpos($eqpkey,"P")!==false) {$defcdt .= "P";}
		if(strpos($eqpkey,"K")!==false) {$defcdt .= "K";}
		if(strpos($eqpkey,"G")!==false) {$defcdt .= "G";}
		if(strpos($eqpkey,"C")!==false) {$defcdt .= "C";}
		if(strpos($eqpkey,"D")!==false) {$defcdt .= "D";}
		if(strpos($eqpkey,"F")!==false) {$defcdt .= "F";}
	}
	if(strpos($eqpkey,"q")!==false) {$defcdt .= "q";}
	return $defcdt;
}

function extradmg($nm,$sd,$clb,&$inf,$ky,$wepe,$ws,$dky) {
	global $log,$w_combat_inf;
	$e_dmg = 0;
	if(strpos($ky,"p")!==false) {
		$e_dmg = 1+round(($wepe/($wepe+100)+$ws/($ws+1000)) * (100+rand(-20,20)));
		if (strpos($dky,"q")==false){
			if (strpos($inf,"p")!==false){
				$e_dmg*=2;
				$log .= "由于对方已经中毒，毒性攻击伤害加倍！造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！<br>";
			} else{
				$log .= "毒性攻击造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！";
				$dice = rand(0,99);
				if($clb == 8){$e_htr = $ws >= 300 ? 90 : 30+$ws/5;}
				else{$e_htr = $ws >=500 ? 60 : 10+$ws/10;}
				if($dice < $e_htr){
					$inf .= 'p';
					if($sd==0){$w_combat_inf .= 'p';}
					$log .= "并造成{$nm}<span class=\"purple\">中毒</span>了！<br>";
				}
			}
		} else {
			$e_dmg=round($e_dmg/2);
			$log .= "毒性攻击被防御效果抵消了！造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！<br>";
		}
	}
	return $e_dmg;
}

function get_spell_factor($sd,$clb,$ky,&$sp,$we) {
	global $log;
	if($sd == 0){
		if($clb == 9){
			$spd = 0.4*$we;
			if ($spd >= $sp) {$spd=$sp -1;}
			$factor = 0.5+$spd/(($we+1)*0.4)/2;
		} else {
			$spd = 0.5*$we;
			if ($spd >= $sp) {$spd=$sp -1;}
			$factor = 0.5+$spd/(($we+1)*0.5)/2;
		}
		$factor = round($factor*100);
		if($sd == 0){
			$log .= "你消耗{$spd}点体力，发挥了灵力武器{$factor}％的威力！";
		}
	}else {$spd = 0;$factor = 50;}
	$sp -= $spd;$factor /= 100;
	return $factor;
}

?>


