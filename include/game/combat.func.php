<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function combat($w_pdata,$active = 1,$wep_kind = '') {
	global $log,$mode,$main,$cmd,$battle_title,$db,$tablepre,$pls,$message,$now,$w_log,$nosta,$hdamage,$hplayer;
	global $pid,$name,$club,$inf,$wep,$wepk,$wepe,$weps,$wepsk,$lvl,$exp,$killnum,$bid,$tactic,$pose,$hp;
	global $w_pid,$w_name,$w_pass,$w_type,$w_endtime,$w_gd,$w_sNo,$w_icon,$w_club,$w_hp,$w_mhp,$w_sp,$w_msp,$w_att,$w_def,$w_pls,$w_lvl,$w_exp,$w_money,$w_bid,$w_inf,$w_rage,$w_pose,$w_tactic,$w_killnum,$w_state,$w_wp,$w_wk,$w_wg,$w_wc,$w_wd,$w_wf,$w_teamID,$w_teamPass,$w_wep,$w_wepk,$w_wepe,$w_weps,$w_arb,$w_arbk,$w_arbe,$w_arbs,$w_arh,$w_arhk,$w_arhe,$w_arhs,$w_ara,$w_arak,$w_arae,$w_aras,$w_arf,$w_arfk,$w_arfe,$w_arfs,$w_art,$w_artk,$w_arte,$w_arts,$w_itm0,$w_itmk0,$w_itme0,$w_itms0,$w_itm1,$w_itmk1,$w_itme1,$w_itms1,$w_itm2,$w_itmk2,$w_itme2,$w_itms2,$w_itm3,$w_itmk3,$w_itme3,$w_itms3,$w_itm4,$w_itmk4,$w_itme4,$w_itms4,$w_itm5,$w_itmk5,$w_itme5,$w_itms5,$w_wepsk,$w_arbsk,$w_arhsk,$w_arask,$w_arfsk,$w_artsk,$w_itmsk0,$w_itmsk1,$w_itmsk2,$w_itmsk3,$w_itmsk4,$w_itmsk5;

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
	

	if($active) {
		if($wep_kind == 'back'){
			$log .= "你逃跑了。<br>";
			$mode = 'command';
			return;
		}

		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$w_pdata'");
		if(!$db->num_rows($result)){
			$log .= "对方不存在！<br>";
			$mode = 'command';
			return;
		}

		$edata = $db->fetch_array($result);
		if($edata['pls'] != $pls) {
			$log .= "<span class=\"yellow\">".$edata['name']."</span> 已经离开了 <span class=\"yellow\">$plsinfo[$pls]</span> 。<br>";
			$mode = 'command';
			return;
		} elseif($edata['hp'] <= 0) {
			$log .= "<span class=\"red\">".$edata['name']."</span> 已经死亡，不能被攻击。<br>";
			include_once GAME_ROOT.'./include/game/battle.func.php';
			findcorpse($edata);
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

		$log .= "你向 <span class=\"red\">$w_name</span> 发起了攻击！<br>";
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
					$log .= "<span class=\"red\">$w_name 的反击！</span><br>";
					$def_dmg = defend($w_wep_kind);
				} else {
					$log .= "<span class=\"red\">$w_name 逃跑了！</span><br>";
				}
			} else {
				$log .= "<span class=\"red\">$w_name 不能反击，逃跑了！</span><br>";
			}

		}
	} else {
		extract($w_pdata,EXTR_PREFIX_ALL,'w');
		init_battle(1);
		include_once GAME_ROOT.'./include/game/attr.func.php';

		$log .= "<span class=\"red\">$w_name</span> 突然向你袭来！<br>";
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
					$log .= "<span class=\"red\">你 的反击！</span><br>";
					$wep_kind = substr($wepk,1,1);
					$att_dmg = attack($wep_kind);
					$w_hp -= $att_dmg;
				} else {
					$log .= "<span class=\"red\">你 逃跑了！</span>";
				}
			} else {
				$log .= "<span class=\"red\">你 不能反击，逃跑了！</span><br>";
			}
		}
	}
	w_save($w_pid);
	$att_dmg = $att_dmg ? $att_dmg : 0;
	$def_dmg = $def_dmg ? $def_dmg : 0;
	if(!$w_type) {
		$w_log = "<span class=\"yellow\">与 $name 发生战斗！攻：$def_dmg 受：$att_dmg</span>";
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

	//save_combatinfo();

	$bid = $w_pid;

	if($w_hp <= 0) {
		$w_bid = $pid;
		$w_hp = 0;
		$killnum++;
		include_once GAME_ROOT.'./include/state.func.php';
		$killmsg = kill($wep_kind,$w_name,$w_type,$w_pid,$wep);
		$log .= "<span class=\"red\">{$w_name} 被 你 杀死了！</span><br>";
		$log .= "<span class=\"yellow\">你 对 $w_name 说：“{$killmsg}”</span><br>";
		include_once GAME_ROOT.'./include/game/battle.func.php';
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$w_pid'");
		$cdata = $db->fetch_array($result);
		findcorpse($cdata);
		return;
	} else {
		$main = 'battle';
		init_battle(1);
		$cmd = '<br><br><input type="hidden" name="mode" value="command"><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >确定</a><br>';
		return;
	}

}


function attack($wep_kind = 'N',$active = 0){
	global $now,$nosta,$log,$infatt,$infobbs,$infinfo,$attinfo,$skillinfo,${$skillinfo[$wep_kind]},$log,$skill_dmg,$weather,$pls,$pid,$name,$pose,$tactic,$club,$att,$inf,$message,$wep,$wepk,$wepsk,$wepe,$weps,$artk,$gd,$w_pid,$w_gd,$w_name,$w_pose,$w_tactic,$w_club,$w_inf,$w_def,$w_arbe,$w_arhe,$w_arae,$w_arfe,$w_arbsk,$w_hp,$exp,$w_rage,$lvl,$w_lvl,$killnum,$rage,$hp;
		
	if((strpos($wepk,'G') == 1)&&($weps == $nosta)) { 
		if(($wep_kind == 'G')||($wep_kind == 'P')){ $wep_kind = 'P';$watt = round($wepe/5);}
		else { $watt = $wepe; }
	}
	elseif($wep_kind == 'N'){ $watt = 0; }
	else{ $watt = $wepe*2; }
	$log .= "&nbsp;使用 $wep $attinfo[$wep_kind] $w_name ！<br>";

	$wep_skill = & ${$skillinfo[$wep_kind]};
	$hitrate = get_hitrate($wep_kind,$wep_skill,$club,$inf);
	$hr_dice = rand(0,99);
	if($hr_dice < $hitrate) {
		if((((substr($artk,1,1) == 'M')&&($gd != $w_gd))||((substr($artk,1,1) == 'F')&&($gd == $w_gd)))&&(!rand(0,4))) {
			$log .= "<span class=\"red\"> 你 被 $w_name 迷惑，无法全力攻击！</span>";
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
			$damage = rand(round($damage*0.3),round($damage));
			checkarb($damage,$wep_kind,$w_arbsk);
			$damage_p = get_damage_p($rage,$wep_skill,$lvl,$club,$message);
			$damage *= $damage_p;
			if($wep_kind == 'D'){$damage += $wepe;}
			$damage = $damage>1 ? round($damage) : 1;
			if((((substr($artk,1,1) == 'M')&&($gd == $w_gd))||((substr($artk,1,1) == 'F')&&($gd != $w_gd)))&&(!rand(0,4))) {
				$log .= "<span class=\"red\"> 你 被 $w_name 激怒，伤害加倍！</span>";
				$damage *= 2;
			}
		}
		$log .= "&nbsp;&nbsp;造成 <span class=\"red\">$damage</span> 点伤害！<br>";

		checkdmg($name,$w_name,$damage,$hp);

		$inf_dice = rand(0,99);
		if($inf_dice < $infobbs[$wep_kind]){
			$infatt_dice = rand(0,4);
			if(($infatt_dice == 1)&&(strpos($infatt[$wep_kind],'b') !== false)){ $inf_att = 'b';}
			elseif(($infatt_dice == 2)&&(strpos($infatt[$wep_kind],'h') !== false)){ $inf_att = 'h';}
			elseif(($infatt_dice == 3)&&(strpos($infatt[$wep_kind],'a') !== false)){ $inf_att = 'a';}
			elseif(($infatt_dice == 4)&&(strpos($infatt[$wep_kind],'f') !== false)){ $inf_att = 'f';}
			if($inf_att) {
				global ${'w_ar'.$inf_att},${'w_ar'.$inf_att.'k'},${'w_ar'.$inf_att.'e'},${'w_ar'.$inf_att.'s'},${'w_ar'.$inf_att.'sk'};
				if(${'w_ar'.$inf_att.'s'}){
					${'w_ar'.$inf_att.'s'} --;
					if(${'w_ar'.$inf_att.'s'} <= 0){
						${'w_ar'.$inf_att} = ${'w_ar'.$inf_att.'k'} = ${'w_ar'.$inf_att.'sk'} = '';
						${'w_ar'.$inf_att.'e'} = ${'w_ar'.$inf_att.'s'} = 0;
						$log .= "$w_name 的 <span class=\"red\">${'w_ar'.$inf_att}</span> 破损了！<br>";
					}
				} else {
					if(strpos($w_inf,$inf_att) === false){
						$w_inf .= $inf_att;
						$log .= "$w_name 的 <span class=\"red\">$infinfo[$inf_att]</span> 部受伤了！<br>";
					}
				}
			}
		}
		if(($weps == $nosta)&&(($wep_kind == 'K')||($wep_kind == 'P'))&&(rand(0,4) == 0)) {
			$wepe --;
			if($wepe <= 0){
				$log .= "<span class=\"red\">$wep 使用过度，毁坏了！</span><br>";
				$wep = $wepk = $wepsk = '';
				$wepe = $weps = 0;
			}
		} elseif((($wep_kind == 'P')||($wep_kind == 'K'))&&($weps != $nosta)&&(rand(0,9) == 0)) {
			$weps --;
			if($weps <= 0){
				$log .= "<span class=\"red\">$wep 使用过度，毁坏了！</span><br>";
				$wep = $wepk = $wepsk = '';
				$wepe = $weps = 0;
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
		$log .= "&nbsp;&nbsp;但是没有击中！<br>";
	}
	
	if((($wep_kind == 'C')||($wep_kind == 'D'))&&($weps != $nosta)){
		$weps --;
		if($weps <= 0){
			$log .= "<span class=\"red\">$wep 用光了！</span><br>";
			$wep = $wepk = $wepsk = '';
			$wepe = $weps = 0;
		}
	} elseif(($wep_kind == 'G')&&($weps != $nosta)) {
		$weps --;
		if($weps <= 0){
			$log .= "<span class=\"red\">$wep 的子弹用光了！</span><br>";
			$weps = $nosta;
		}
	}
	if((($wep_kind == 'G')&&(strpos($wepsk,'S') === false))||($wep_kind == 'D')) {
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
	
	if((strpos($w_wepk,'G') == 1)&&($w_wep_kind == 'P')) { $watt = round($w_wepe/5); }
	elseif($w_wep_kind == 'N') { $watt = 0; }
	else{ $watt = $w_wepe*2; }

	$log .= "&nbsp;$w_name 使用 $w_wep $attinfo[$w_wep_kind] 你！<br>";

	$w_wep_skill = & ${'w_'.$skillinfo[$w_wep_kind]};
	$hitrate = get_hitrate($w_wep_kind,$w_wep_skill,$w_club,$w_inf);
	$hr_dice = rand(0,99);
	if($hr_dice < $hitrate) {
		if((((substr($w_artk,1,1) == 'M')&&($gd != $w_gd))||((substr($w_artk,1,1) == 'F')&&($gd == $w_gd)))&&(!rand(0,4))) {
			$log .= "<span class=\"red\">$w_name 被 你 迷惑，无法全力攻击！</span>";
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
			$damage = rand(round($damage*0.3),round($damage));
			checkarb($damage,$w_wep_kind,$arbsk);
			$damage_p = get_damage_p($w_rage,$w_wep_skill,$w_lvl,$w_club);
			$damage *= $damage_p;
			if($w_wep_kind == 'D'){$damage += $w_wepe;}
			$damage = $damage>1 ? round($damage) : 1;
			if((((substr($w_artk,1,1) == 'M')&&($gd == $w_gd))||((substr($w_artk,1,1) == 'F')&&($gd != $w_gd)))&&(!rand(0,4))) {
				$log .= "<span class=\"red\"> $w_name 被 你 激怒，伤害加倍！</span>";
				$damage *= 2;
			}
		}
		$log .= "&nbsp;&nbsp;造成 <span class=\"red\">$damage</span> 点伤害！<br>";
		checkdmg($w_name,$name,$damage,$w_hp);
		
		$inf_dice = rand(0,99);
		if($inf_dice < $infobbs[$w_wep_kind]) {
			$infatt_dice = rand(1,4);
			$inf_att = '';
			if(($infatt_dice == 1)&&(strpos($infatt[$w_wep_kind],'b') !== false)){ $inf_att = 'b';}
			elseif(($infatt_dice == 2)&&(strpos($infatt[$w_wep_kind],'h') !== false)){ $inf_att = 'h';}
			elseif(($infatt_dice == 3)&&(strpos($infatt[$w_wep_kind],'a') !== false)){ $inf_att = 'a';}
			elseif(($infatt_dice == 4)&&(strpos($infatt[$w_wep_kind],'f') !== false)){ $inf_att = 'f';}
			if($inf_att) {
				global ${'ar'.$inf_att},${'ar'.$inf_att.'k'},${'ar'.$inf_att.'e'},${'ar'.$inf_att.'s'},${'ar'.$inf_att.'sk'};
				if(${'ar'.$inf_att.'s'}) {
					${'ar'.$inf_att.'s'} --;
					if(${'ar'.$inf_att.'s'} <= 0){
						${'ar'.$inf_att} = ${'ar'.$inf_att.'k'} = ${'ar'.$inf_att.'sk'} = '';
						${'ar'.$inf_att.'e'} = ${'ar'.$inf_att.'s'} = 0;
						$log .= "你 的 <span class=\"red\">${'ar'.$inf_att}</span> 破损了！<br>";
					}
				} else {
					if(strpos($inf,$inf_att) === false){
						$inf .= $inf_att;
						$log .= "你 的 <span class=\"red\">$infinfo[$inf_att]</span> 部受伤了！<br>";
					}
				}
			}
		}
		if(($w_weps == $nosta)&&(($w_wep_kind == 'K')||($w_wep_kind == 'P'))&&(rand(0,4) == 0)) {
			$w_wepe --;
			if($w_wepe <= 0) {
				$log .= "<span class=\"red\">$w_wep 使用过度，损坏了！</span><br>";
				$w_wep = $w_wepk = $w_wepsk = '';
				$w_wepe = $w_weps = 0;
			}
		} elseif((($w_wep_kind == 'P')||($w_wep_kind == 'K'))&&($w_weps != $nosta)&&(rand(0,9) == 0)) {
			$w_weps --;
			if($w_weps <= 0){
				$log .= "<span class=\"red\">$w_wep 使用过度，损坏了！</span><br>";
				$w_wep = $w_wepk = $w_wepsk = '';
				$w_wepe = $w_weps = 0;
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
			$killmsg = death($w_wep_kind,$w_name,$w_type,$w_wep);
			$log .= "<span class=\"yellow\">$w_name 对 你 说：“{$killmsg}”</span><br>";
		}
	} else {
		$damage = 0;
		$log .= "&nbsp;&nbsp;但是没有击中！<br>";
	}
	
	if((($w_wep_kind == 'C')||($w_wep_kind == 'D'))&&($w_weps != $nosta)){
		$w_weps --;
		if($w_weps <= 0){
			$w_wep = $w_wepk = $w_wepsk = '';
			$w_wepe = $w_weps = 0;
		}
	} elseif(($w_wep_kind == 'G')&&($w_weps != $nosta)) {
		$w_weps --;
		if($w_weps <= 0){
			$w_weps = $nosta;
		}
	}

	if((($w_wep_kind == 'G')&&(strpos($w_wepsk,'S') === false))||($w_wep_kind == 'D')){
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


function get_damage_p(&$rg,$sk = 0,$lv = 0,$cl = 0,$msg = ''){
	global $log;
	$cri_dice = rand(0,99);
	$damage_p = 1;
	if($cl == 9) {
		if((!$msg)&&($rg>=50)){
			$log .= '<span class="red b">发动必杀技！</span>';
			$damage_p = 1.8;
			$rg -= 50;
		}
	} elseif($cri_dice < 30) {
		if(($rg >= 30)&&($sk >= 20)&&($lv >= 3)){
			$log .= '<span class="red b">重击！</span>';
			$damage_p = 1.4;
			$rg -= 30;
		}
	}
	return $damage_p;
}
function checkdmg($p1,$p2,$d,&$hp) {
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
		$hp = ceil($hp/2);
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

?>


