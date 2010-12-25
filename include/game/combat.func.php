<?php

if (! defined ( 'IN_GAME' )) {
	exit ( 'Access Denied' );
}

function combat($w_pdata, $active = 1, $wep_kind = '') {
	global $log, $mode, $main, $cmd, $battle_title, $db, $tablepre, $pls, $message, $now, $w_log, $nosta, $hdamage, $hplayer;
	global $pid, $name, $club, $inf, $lvl, $exp, $killnum, $bid, $tactic, $pose, $hp;
	global $wep, $wepk, $wepe, $weps, $wepsk;
	global $w_pid, $w_name, $w_pass, $w_type, $w_endtime, $w_gd, $w_sNo, $w_icon, $w_club, $w_hp, $w_mhp, $w_sp, $w_msp, $w_att, $w_def, $w_pls, $w_lvl, $w_exp, $w_money, $w_bid, $w_inf, $w_rage, $w_pose, $w_tactic, $w_killnum, $w_state, $w_wp, $w_wk, $w_wg, $w_wc, $w_wd, $w_wf, $w_teamID, $w_teamPass;
	global $w_wep, $w_wepk, $w_wepe, $w_weps, $w_arb, $w_arbk, $w_arbe, $w_arbs, $w_arh, $w_arhk, $w_arhe, $w_arhs, $w_ara, $w_arak, $w_arae, $w_aras, $w_arf, $w_arfk, $w_arfe, $w_arfs, $w_art, $w_artk, $w_arte, $w_arts, $w_itm0, $w_itmk0, $w_itme0, $w_itms0, $w_itm1, $w_itmk1, $w_itme1, $w_itms1, $w_itm2, $w_itmk2, $w_itme2, $w_itms2, $w_itm3, $w_itmk3, $w_itme3, $w_itms3, $w_itm4, $w_itmk4, $w_itme4, $w_itms4, $w_itm5, $w_itmk5, $w_itme5, $w_itms5, $w_wepsk, $w_arbsk, $w_arhsk, $w_arask, $w_arfsk, $w_artsk, $w_itmsk0, $w_itmsk1, $w_itmsk2, $w_itmsk3, $w_itmsk4, $w_itmsk5;
	global $infinfo, $w_combat_inf;
	
	$battle_title = '战斗发生';
	
	if (! $wep_kind) {
		$w1 = substr ( $wepk, 1, 1 );
		$w2 = substr ( $wepk, 2, 1 );
		if (($w1 == 'G') && ($weps == $nosta)) {
			$wep_kind = $w2 ? $w2 : 'P';
		} else {
			$wep_kind = $w1;
		}
	}
	$wep_temp = $wep;
	
	if ($active) {
		if ($wep_kind == 'back') {
			$log .= "你逃跑了。";
			$bid = 0;
			$mode = 'command';
			return;
		}
		
		$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$w_pdata'" );
		if (! $db->num_rows ( $result )) {
			$log .= "对方不存在！<br>";
			$bid = 0;
			$mode = 'command';
			return;
		}
		
		$edata = $db->fetch_array ( $result );
		
		if ($edata ['pid'] !== $bid) {
			$log .= "<span class=\"yellow\">一瞬千击！<br>你想千击？<br>玩儿蛋去！<br>来弄我噻！<br>来弄我噻！<br>来弄我噻！<br></span><br>";
			$bid = 0;
			$mode = 'command';
			return;
		}
		
		if ($edata ['pls'] != $pls) {
			$log .= "<span class=\"yellow\">" . $edata ['name'] . "</span>已经离开了<span class=\"yellow\">$plsinfo[$pls]</span>。<br>";
			$bid = 0;
			$mode = 'command';
			return;
		} elseif ($edata ['hp'] <= 0) {
			$log .= "<span class=\"red\">" . $edata ['name'] . "</span>已经死亡，不能被攻击。<br>";
			include_once GAME_ROOT . './include/game/battle.func.php';
			findcorpse ( $edata );
			$bid = 0;
			return;
		}
		
		if ($message) {
			$log .= "<span class=\"lime\">你对 " . $edata ['name'] . " 大喊：$message</span><br>";
			if (! $edata ['type']) {
				$w_log = "<span class=\"lime\">$name 对你大喊：$message</span>";
				logsave ( $edata ['pid'], $now, $w_log );
			}
		}
		
		extract ( $edata, EXTR_PREFIX_ALL, 'w' );
		init_battle ( 1 );
		include_once GAME_ROOT . './include/game/attr.func.php';
		
		$log .= "你向<span class=\"red\">$w_name</span>发起了攻击！<br>";
		$att_dmg = attack ( $wep_kind, 1 );
		$w_hp -= $att_dmg;
		
		if (($w_hp > 0) && ($w_tactic != 4) && ($w_pose != 5)) {
			global $rangeinfo;
			$w_w1 = substr ( $w_wepk, 1, 1 );
			$w_w2 = substr ( $w_wepk, 2, 1 );
			if (($w_w1 == 'G') && ($w_weps == $nosta)) {
				$w_wep_kind = $w_w2 ? $w_w2 : 'P';
			} else {
				$w_wep_kind = $w_w1;
			}
			//if (($rangeinfo [$wep_kind] == $rangeinfo [$w_wep_kind]) || ($rangeinfo [$w_wep_kind] == 'M')) {
			if ($rangeinfo [$wep_kind] <= $rangeinfo [$w_wep_kind] && $rangeinfo [$wep_kind] !== 0) {
				$counter = get_counter ( $w_wep_kind, $w_tactic, $w_club, $w_inf );
				$counter_dice = rand ( 0, 99 );
				if ($counter_dice < $counter) {
					$log .= "<span class=\"red\">{$w_name}的反击！</span><br>";
					if ($w_type == 1 || $w_type == 5 || $w_type == 6|| $w_type == 7) {
						$log .= npc_chat ( $w_type,$w_name, 'defend' );
					}
					$def_dmg = defend ( $w_wep_kind );
				} else {
					if ($w_type == 1 || $w_type == 5 || $w_type == 6|| $w_type == 7) {
						$log .= npc_chat ( $w_type,$w_name, 'escape' );
					}
					$log .= "<span class=\"red\">{$w_name}处于无法反击的状态，逃跑了！</span><br>";
				}
			} else {
				if ($w_type == 1 || $w_type == 5 || $w_type == 6|| $w_type == 7) {
					$log .= npc_chat ( $w_type,$w_name, 'cannot' );
				}
				$log .= "<span class=\"red\">{$w_name}攻击范围不足，不能反击，逃跑了！</span><br>";
			}
		
		} elseif(($w_tactic == 4) || ($w_pose == 5)) {
			$log .= "<span class=\"red\">{$w_name}逃跑了！</span><br>";
		}
	} else {
		extract ( $w_pdata, EXTR_PREFIX_ALL, 'w' );
		init_battle ( 1 );
		include_once GAME_ROOT . './include/game/attr.func.php';
		
		$log .= "<span class=\"red\">$w_name</span>突然向你袭来！<br>";
		
		if ($w_type == 1 || $w_type == 5 || $w_type == 6|| $w_type == 7) {
			$log .= npc_chat ( $w_type,$w_name, 'attack' );
		}
		
		$w_w1 = substr ( $w_wepk, 1, 1 );
		$w_w2 = substr ( $w_wepk, 2, 1 );
		if (($w_w1 == 'G') && ($w_weps == $nosta)) {
			$w_wep_kind = $w_w2 ? $w_w2 : 'P';
		} else {
			$w_wep_kind = $w_w1;
		}
		$def_dmg = defend ( $w_wep_kind, 1 );
		if (($hp > 0) && ($tactic != 4) && ($pose != 5)) {
			global $rangeinfo;
			if ($rangeinfo [$wep_kind] >= $rangeinfo [$w_wep_kind] && $rangeinfo [$w_wep_kind] !== 0) {
				$counter = get_counter ( $wep_kind, $tactic, $club, $inf );
				$counter_dice = rand ( 0, 99 );
				if ($counter_dice < $counter) {
					$log .= "<span class=\"red\">你的反击！</span><br>";
					$wep_kind = substr ( $wepk, 1, 1 );
					$att_dmg = attack ( $wep_kind );
					$w_hp -= $att_dmg;
				} else {
					$log .= "<span class=\"red\">你处于无法反击的状态，逃跑了！</span><br>";
				}
			} else {
				$log .= "<span class=\"red\">你攻击范围不足，不能反击，逃跑了！</span><br>";
			}
		} elseif(($tactic == 4) || ($pose == 5)) {
			$log .= "<span class=\"red\">你逃跑了！</span><br>";
		}
	}
	w_save ( $w_pid );
	$att_dmg = $att_dmg ? $att_dmg : 0;
	$def_dmg = $def_dmg ? $def_dmg : 0;
	
	if (! $w_type) {
		$w_inf_log = '';
		if ($w_combat_inf) {
			global $exdmginf;
			foreach ( $exdmginf as $inf_ky => $w_inf_words ) {
				if (strpos ( $w_combat_inf, $inf_ky ) !== false) {
					$w_inf_log .= "敌人的攻击造成你{$w_inf_words}了！<br>";
				}
			}
			/*if (strpos ( $w_combat_inf, 'p' ) !== false) {
				$w_inf_log .= "敌人的攻击造成你{$exdmginf['p']}了！<br>";
			}
			if (strpos ( $w_combat_inf, 'u' ) !== false) {
				$w_inf_log .= "敌人的攻击造成你{$exdmginf['u']}了！<br>";
			}
			if (strpos ( $w_combat_inf, 'i' ) !== false) {
				$w_inf_log .= "敌人的攻击造成你{$exdmginf['i']}了！<br>";
			}
			if (strpos ( $w_combat_inf, 'h' ) !== false) {
				$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[h]</span>部受伤了！<br>";
			}
			if (strpos ( $w_combat_inf, 'b' ) !== false) {
				$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[b]</span>部受伤了！<br>";
			}
			if (strpos ( $w_combat_inf, 'a' ) !== false) {
				$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[a]</span>部受伤了！<br>";
			}
			if (strpos ( $w_combat_inf, 'f' ) !== false) {
				$w_inf_log .= "敌人的攻击使你的<span class=\"red\">$infinfo[f]</span>部受伤了！<br>";
			}*/
		}
		$w_log = "与手持<span class=\"red\">$wep_temp</span>的<span class=\"yellow\">$name</span>发生战斗！<br>你受到其<span class=\"yellow\">$att_dmg</span>点攻击，对其造成<span class=\"yellow\">$def_dmg</span>点反击。<br>$w_inf_log";
		logsave ( $w_pid, $now, $w_log );
	}
	
	if (($att_dmg > $hdamage) && ($att_dmg >= $def_dmg)) {
		$hdamage = $att_dmg;
		$hplayer = $name;
		save_combatinfo ();
	} elseif (($def_dmg > $hdamage) && (! $w_type)) {
		$hdamage = $def_dmg;
		$hplayer = $w_name;
		save_combatinfo ();
	}
	
	//$bid = $w_pid;
	

	if ($w_hp <= 0) {
		$w_bid = $pid;
		$w_hp = 0;
		$killnum ++;
		include_once GAME_ROOT . './include/state.func.php';
		$killmsg = kill ( $wep_kind, $w_name, $w_type, $w_pid, $wep_temp );
		if ($w_type == 1 || $w_type == 5 || $w_type == 6|| $w_type == 7) {
			$log .= npc_chat ( $w_type,$w_name, 'death' );
		}
		$log .= "<span class=\"red\">{$w_name}被你杀死了！</span><br>";
		$log .= "<span class=\"yellow\">你对{$w_name}说：“{$killmsg}”</span><br>";
		include_once GAME_ROOT . './include/game/battle.func.php';
		$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$w_pid'" );
		$cdata = $db->fetch_array ( $result );
		findcorpse ( $cdata );
		$bid = 0;
		return;
	} else {
		$main = 'battle';
		init_battle ( 1 );
		$cmd = '<br><br><input type="hidden" name="mode" value="command"><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >确定</a><br>';
		$bid = $hp == 0 ? $bid : 0;
		return;
	}

}

function attack($wep_kind = 'N', $active = 0) {
	global $now, $nosta, $log, $infobbs, $infinfo, $attinfo, $skillinfo, ${$skillinfo [$wep_kind]}, $wepimprate;
	global $name, $lvl, $gd, $pid, $pls, $hp, $sp, $rage, $exp, $club, $att, $inf, $message;
	global $wep, $wepk, $wepe, $weps, $wepsk;
	global $w_arbe, $w_arbsk, $w_arhe, $w_arae, $w_arfe;
	global $artk, $arhsk, $arbsk, $arask, $arfsk, $artsk;
	global $w_hp, $w_rage, $w_lvl, $w_pid, $w_gd, $w_name, $w_inf, $w_def;
	global $w_wepsk, $w_arhsk, $w_arask, $w_arfsk, $w_artsk, $w_artk;
	
	if ((strpos ( $wepk, 'G' ) == 1) && ($weps == $nosta)) {
		if (($wep_kind == 'G') || ($wep_kind == 'P')) {
			$wep_kind = 'P';
			$is_wpg = true;
			$watt = round ( $wepe / 5 );
		} else {
			$watt = $wepe;
		}
	} elseif ($wep_kind == 'N') {
		$watt = round ( ${$skillinfo [$wep_kind]} / 4 );
	} else {
		$watt = $wepe * 2;
	}
	
	$log .= "使用{$wep}<span class=\"yellow\">$attinfo[$wep_kind]</span>{$w_name}！<br>";
	
	$att_key = getatkkey ( $wepsk, $arhsk, $arbsk, $arask, $arfsk, $artsk, $artk, $is_wpg );
	
	$w_def_key = getdefkey ( $w_wepsk, $w_arhsk, $w_arbsk, $w_arask, $w_arfsk, $w_artsk, $w_artk );
	$wep_skill = & ${$skillinfo [$wep_kind]};
	$hitrate = get_hitrate ( $wep_kind, $wep_skill, $club, $inf );
	
	$damage_p = get_damage_p ( $rage, $club, $message, $att_key, 0, '你' );
	$hit_time = get_hit_time ( $att_key, $wep_skill, $hitrate, $wep_kind, $weps, $infobbs [$wep_kind], $wepimprate [$wep_kind], $is_wpg );
	if ($hit_time [1] > 0) {
		$gender_dmg_p = check_gender ( '你', $w_name, $gd, $w_gd, $att_key );
		if ($gender_dmg_p == 0) {
			$damage = 1;
		} else {
			$w_active = 1 - $active;
			$attack = $att + $watt;
			$defend = $w_def + $w_arbe + $w_arhe + $w_arae + $w_arfe;
			
			$damage = get_original_dmg ( '', 'w_', $attack, $defend, $wep_skill, $wep_kind );
			
			checkarb ( $damage, $wep_kind, $w_def_key );
			//if ($wep_kind == 'D') {爆炸伤害做到属性里面去了
			//	$damage += $wepe;
			//} elseif ($wep_kind == 'F') {
			if ($wep_kind == 'F') {
				$damage = round ( ($wepe + $damage) * get_WF_p ( '', $club, $wepe) ); //get_spell_factor ( 0, $club, $att_key, $sp, $wepe ) );
			}
			$damage *= $damage_p;
			
			$damage = $damage > 1 ? round ( $damage ) : 1;
			$damage *= $gender_dmg_p;
		}
		if ($hit_time [1] > 1) {
			$d_temp = $damage;
			if ($hit_time [1] == 2) {
				$dmg_p = 1.8;
			} elseif ($hit_time [1] == 3) {
				$dmg_p = 2.5;
			} else {
				$dmg_p = 2.5 + 0.5 * ($hit_time [1] - 3);
			}
			$damage = round ( $damage * $dmg_p );
			$log .= "造成{$d_temp}×{$dmg_p}＝<span class=\"red\">$damage</span>点伤害！<br>";
		} else {
			$log .= "造成<span class=\"red\">$damage</span>点伤害！<br>";
		}
		
		$damage += get_ex_dmg ( $w_name, 0, $club, $w_inf, $att_key, $wep_kind, $wepe, $wep_skill, $w_def_key );
		
		checkdmg ( $name, $w_name, $damage );
		
		get_dmg_punish ( '你', $damage, $hp, $att_key );
		
		get_inf ( $w_name, $hit_time [2], $wep_kind);
		
		check_KP_wep ( '你', $hit_time [3], $wep, $wepk, $wepe, $weps, $wepsk );
		
		exprgup ( $lvl, $w_lvl, $exp, 1, $w_rage );
	
	} else {
		$damage = 0;
		$log .= "但是没有击中！<br>";
	}
	check_GCDF_wep ( '你', $hit_time [0], $wep, $wep_kind, $wepk, $wepe, $weps, $wepsk );
	
	addnoise ( $wep_kind, $wepsk, $now, $pls, $pid, $w_pid, $wep_kind );
	
	$wep_skill ++;
	return $damage;
}

function defend($w_wep_kind = 'N', $active = 0) {
	global $now, $nosta, $log, $infobbs, $infinfo, $attinfo, $skillinfo, ${'w_' . $skillinfo [$w_wep_kind]}, $wepimprate;
	global $w_name, $w_lvl, $w_gd, $w_pid, $pls, $w_hp, $w_sp, $w_rage, $w_exp, $w_club, $w_att, $w_inf;
	global $w_wep, $w_wepk, $w_wepe, $w_weps, $w_wepsk;
	global $arbe, $arbsk, $arhe, $arae, $arfe;
	global $w_artk, $w_arhsk, $w_arbsk, $w_arask, $w_arfsk, $w_artsk;
	global $hp, $rage, $lvl, $pid, $gd, $name, $inf, $att, $def;
	global $wepsk, $arhsk, $arask, $arfsk, $artsk, $artk;
	global $w_type, $w_sNo, $w_killnum;
	
	$w_wep_temp = $w_wep;
	
	if ((strpos ( $w_wepk, 'G' ) == 1) && ($w_wep_kind == 'P')) {
		$watt = round ( $w_wepe / 5 );
		$is_wpg = true;
	} elseif ($w_wep_kind == 'N') {
		$watt = round ( ${'w_' . $skillinfo [$w_wep_kind]} / 4 );
	} else {
		$watt = $w_wepe * 2;
	}
	
	$log .= "{$w_name}使用{$w_wep}<span class=\"yellow\">$attinfo[$w_wep_kind]</span>你！<br>";
	
	$w_att_key = getatkkey ( $w_wepsk, $w_arhsk, $w_arbsk, $w_arask, $w_arfsk, $w_artsk, $w_artk, $is_wpg );
	$def_key = getdefkey ( $wepsk, $arhsk, $arbsk, $arask, $arfsk, $artsk, $artk );
	$w_wep_skill = & ${'w_' . $skillinfo [$w_wep_kind]};
	$hitrate = get_hitrate ( $w_wep_kind, $w_wep_skill, $w_club, $w_inf );
	$damage_p = get_damage_p ( $w_rage, $w_club, '', $w_att_key, $w_type, $w_name );
	$hit_time = get_hit_time ( $w_att_key, $w_wep_skill, $hitrate, $w_wep_kind, $w_weps, $infobbs [$w_wep_kind], $wepimprate [$w_wep_kind], $is_wpg );
	
	if ($hit_time [1] > 0) {
		$gender_dmg_p = check_gender ( $w_name, '你', $w_gd, $gd, $w_att_key );
		if ($gender_dmg_p == 0) {
			$damage = 1;
		} else {
			global $w_att;
			$w_active = 1 - $active;
			$attack = $w_att + $watt;
			$defend = $def + $arbe + $arhe + $arae + $arfe;
			
			$damage = get_original_dmg ( 'w_', '', $attack, $defend, $w_wep_skill, $w_wep_kind );
			
			checkarb ( $damage, $w_wep_kind, $def_key );
			//if ($w_wep_kind == 'D') {
			//	$damage += $w_wepe;
			//} elseif ($w_wep_kind == 'F') {
			if ($w_wep_kind == 'F') {
				$damage = round ( ($w_wepe + $damage) * get_WF_p ( 'w_', $w_club, $w_wepe) ); //get_spell_factor ( 1, $w_club, $w_att_key, $w_sp, $w_wepe ) );
			}
			$damage *= $damage_p;
			
			$damage = $damage > 1 ? round ( $damage ) : 1;
			$damage *= $gender_dmg_p;
		}
		
		if ($hit_time [1] > 1) {
			$d_temp = $damage;
			if ($hit_time [1] == 2) {
				$dmg_p = 1.8;
			} elseif ($hit_time [1] == 3) {
				$dmg_p = 2.5;
			} else {
				$dmg_p = 2.5 + 0.5 * ($hit_time [1] - 3);
			}
			$damage = round ( $damage * $dmg_p );
			$log .= "造成{$d_temp}×{$dmg_p}＝<span class=\"red\">$damage</span>点伤害！<br>";
		} else {
			$log .= "造成<span class=\"red\">$damage</span>点伤害！<br>";
		}
		
		$damage += get_ex_dmg ( "你", 1, $w_club, $inf, $w_att_key, $w_wep_kind, $w_wepe, $w_wep_skill, $def_key );
		
		checkdmg ( $w_name, $name, $damage );
		
		get_dmg_punish ( $w_name, $damage, $w_hp, $w_att_key );
		
		get_inf ( '你', $hit_time [2], $w_wep_kind);
		
		check_KP_wep ( $w_name, $hit_time [3], $w_wep, $w_wepk, $w_wepe, $w_weps, $w_wepsk );
		
		exprgup ( $w_lvl, $lvl, $w_exp, 0, $rage );
		
		$hp -= $damage;
		
		if ($hp <= 0) {
			$hp = 0;
			$w_killnum ++;
			include_once GAME_ROOT . './include/state.func.php';
			$killmsg = death ( $w_wep_kind, $w_name, $w_type, $w_wep_temp );
			if ($w_type == 1 || $w_type == 5 || $w_type == 6|| $w_type == 7) {
				$log .= npc_chat ( $w_type,$w_name, 'kill' );
			} else {
				$log .= "<span class=\"yellow\">{$w_name}对你说：“{$killmsg}”</span><br>";
			}
		}
	} else {
		$damage = 0;
		$log .= "但是没有击中！<br>";
	}
	
	check_GCDF_wep ( $w_name, $hit_time [0], $w_wep, $w_wep_kind, $w_wepk, $w_wepe, $w_weps, $w_wepsk );
	
	addnoise ( $w_wep_kind, $w_wepsk, $now, $pls, $w_pid, $pid, $w_wep_kind );
	
	$w_wep_skill ++;
	
	return $damage;
}

function get_original_dmg($w1, $w2, $att, $def, $ws, $wp_kind) {
	global $skill_dmg, $dmg_fluc, $weather, $pls;
	global ${$w1 . 'pose'}, ${$w1 . 'tactic'}, ${$w1 . 'club'}, ${$w1 . 'inf'}, ${$w1 . 'active'}, ${$w2 . 'pose'}, ${$w2 . 'tactic'}, ${$w2 . 'club'}, ${$w2 . 'inf'}, ${$w2 . 'active'};
	$attack_p = get_attack_p ( $weather, $pls, ${$w1 . 'pose'}, ${$w1 . 'tactic'}, ${$w1 . 'club'}, ${$w1 . 'inf'}, ${$w1 . 'active'} );
	$att_pow = $att * $attack_p;
	$defend_p = get_defend_p ( $weather, $pls, ${$w2 . 'pose'}, ${$w2 . 'tactic'}, ${$w2 . 'club'}, ${$w2 . 'inf'}, ${$w2 . 'active'} );
	$def_pow = $def * $defend_p;
	$damage = ($att_pow / $def_pow) * $ws * $skill_dmg [$wp_kind];
	
	$dmg_factor = (100 + rand ( - $dmg_fluc [$wp_kind], $dmg_fluc [$wp_kind] )) / 100;
	
	$damage = round ( $damage * $dmg_factor * rand ( 4, 10 ) / 10 );
	return $damage;
}

function get_damage_p(&$rg, $cl = 0, $msg = '', $atkcdt, $type, $nm) {
	$cri_dice = rand ( 0, 99 );
	if ($cl == 9) {
		$rg_m = 50;
		$dmg_p = 2;
		if ($msg || $rg >= 255) {
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
	
	if (strpos ( $atkcdt, "c" ) !== false) {
		$rg_m = 10;
		if ($max_dice != 0) {
			$max_dice += 30;
		}
	}
	if ($cri_dice <= $max_dice && $rg >= $rg_m) {
		global $log;
		if ($type == 1 || $type == 5 || $type == 6|| $type == 7) {
			$log .= npc_chat ( $type,$nm, 'critical' );
		}
		if ($nm == '你') {
			$log .= "{$nm}消耗<span class=\"yellow\">$rg_m</span>点怒气，<span class=\"red\">{$cri_word}</span>！";
		} else {
			$log .= "{$nm}<span class=\"red\">{$cri_word}</span>！";
		}
		$rg -= $rg_m;
		return $dmg_p;
	} else {
		return 1;
	}
	/*if ($cl == 9) {
		if ($sd == 0) {
			if ((! empty ( $msg )) && ($rg >= $rg_m) || $rg == 255) {
				$log .= "你消耗<span class=\"yellow\">$rg_m</span>点怒气，<span class=\"red\">发动必杀技</span>！";
				$damage_p = 2;
				$rg -= $rg_m;
			}
		} else {
			if (($cri_dice < $max_dice && ($rg >= $rg_m)) || $rg == 255) {
				global $w_type;
				if ($w_type == 1) {
					$log .= npc_chat ( $w_type, 'critical' );
				}
				$log .= "<span class=\"red\">发动必杀技</span>！";
				$damage_p = 2;
				$rg -= $rg_m;
			}
		}
	} elseif ($cri_dice < $max_dice || $rg == 255) {
		if (($rg >= $rg_m) && ($sk >= 20) &&($lv > 3)) {
			if ($sd == 0) {
				$log .= "你消耗<span class=\"yellow\">$rg_m</span>点怒气，使出";
			} else {
				global $w_type;
				if ($w_type == 1) {
					$log .= npc_chat ( $w_type, 'critical' );
				}
			}
			$log .= "<span class=\"red\">重击</span>！";
			$damage_p = 1.5;
			$rg -= $rg_m;
		}
	}
	return $damage_p;*/
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
		addnews ( 0, 'damage', $words );
	}
	return;
}

function checkarb(&$dmg, $w, $ar) {
	global $log;
	if (strpos ( $ar, $w ) !== false) {
		$dice = rand ( 0, 99 );
		if ($dice < 90) {
			$dmg /= 2;
			$log .= "攻击被防具抵消了！";
		
		//$log .= "<span class=\"red\">攻击被防具抵消了！</span>";
		}
	}
	return;
}

function getatkkey($w, $ah, $ab, $aa, $af, $at, $atkind, $is_wpg) {
	global $ex_attack;
	$atkcdt = '';
	$eqpkey = $w . $ah . $ab . $aa . $af . $at . substr ( $atkind, 1, 1 );
	
	if (strpos ( $eqpkey, 'c' ) !== false) {
		$atkcdt .= '_c';
	}
	if (strpos ( $eqpkey, 'l' ) !== false) {
		$atkcdt .= '_l';
	}
	if (strpos ( $eqpkey, 'g' ) !== false) {
		$atkcdt .= '_g';
	}
	if (strpos ( $eqpkey, 'H' ) !== false) {
		$atkcdt .= '_H';
	}
	if (strpos ( $w, 'r' ) !== false && ! $is_wpg) {
		$atkcdt .= '_r';
	}
	foreach ($ex_attack as $value) {
		if (strpos ( $w, $value ) !== false && ! $is_wpg) {
			$atkcdt .= '_'.$value;
		}
	}
	/*if (strpos ( $w, 'p' ) !== false && ! $is_wpg) {
		$atkcdt .= '_p';
	}
	if (strpos ( $w, 'u' ) !== false && ! $is_wpg) {
		$atkcdt .= '_u';
	}
	if (strpos ( $w, 'i' ) !== false && ! $is_wpg) {
		$atkcdt .= '_i';
	}*/
	return $atkcdt;
}

function get_hit_time($ky, $ws, $htr, $wk, $lmt, $infr, $wimpr, $is_wpg = false) {
	global $log, $nosta;
	if ($lmt == $nosta) {
		$wimpr *= 2;
		if ($is_wpg) {
			$wimpr *= 4;
		}
	}
	if (strpos ( $ky, 'r' ) !== false) {
		$atk_t = $ws >= 800 ? 6 : 2 + floor ( $ws / 200 );
		if ($wk == 'C' || $wk == 'D' || $wk == 'F') {
			if ($lmt == $nosta) {
				$lmt = 99;
			}
			if ($atk_t > $lmt) {
				$atk_t = $lmt;
			}
		}
		if ($wk == 'G' && $atk_t > $lmt) {
			$atk_t = $lmt;
		}
		
		$ht_t = 0;
		$inf_t = 0;
		$wimp_t = 0;
		//if($htr>100){$htr=100;}
		for($i = 1; $i <= $atk_t; $i ++) {
			$dice = rand ( 0, 99 );
			$dice2 = rand ( 0, 99 );
			$dice3 = rand ( 0, 99 );
			if ($dice < $htr) {
				$ht_t ++;
				if ($dice2 < $infr) {
					$inf_t ++;
				}
				if ($dice3 < $wimpr) {
					$wimp_t ++;
				}
			}
			$htr *= 0.8;
			$infr *= 0.9;
			$wimpr *= $wimpr <= 0 ? 1 : 1.2;
		}
	} else {
		$atk_t = 1;
		$ht_t = 0;
		$inf_t = 0;
		$wimp_t = 0;
		$dice = rand ( 0, 99 );
		$dice2 = rand ( 0, 99 );
		$dice3 = rand ( 0, 99 );
		if ($dice < $htr) {
			$ht_t = 1;
			if ($dice2 < $infr) {
				$inf_t = 1;
			}
			if ($dice3 < $wimpr) {
				$wimp_t = 1;
			}
		}
	}
	if ($atk_t > 1 && $ht_t > 0) {
		$log .= "{$atk_t}次连续攻击命中<span class=\"yellow\">{$ht_t}</span>次！";
	}
	return Array ($atk_t, $ht_t, $inf_t, $wimp_t );
}

function getdefkey($w, $ah, $ab, $aa, $af, $at, $atkind) {
	global $ex_dmg_def;
	$defcdt = '';
	$eqpkey = $w . $ah . $ab . $aa . $af . $at . substr ( $atkind, 1, 1 );
	if (strpos ( $eqpkey, 'A' ) !== false) {
		$defcdt .= 'N_P_K_G_C_D_F';
	} else {
		if (strpos ( $eqpkey, 'N' ) !== false) {
			$defcdt .= '_N';
		}
		if (strpos ( $eqpkey, 'P' ) !== false) {
			$defcdt .= '_P';
		}
		if (strpos ( $eqpkey, 'K' ) !== false) {
			$defcdt .= '_K';
		}
		if (strpos ( $eqpkey, 'G' ) !== false) {
			$defcdt .= '_G';
		}
		if (strpos ( $eqpkey, 'C' ) !== false) {
			$defcdt .= '_C';
		}
		if (strpos ( $eqpkey, 'D' ) !== false) {
			$defcdt .= '_D';
		}
		if (strpos ( $eqpkey, 'F' ) !== false) {
			$defcdt .= '_F';
		}
	}
	foreach ($ex_dmg_def as $value) {
		if (strpos ( $eqpkey, $value ) !== false) {
			$defcdt .= '_'.$value;
		}
	}
	/*if (strpos ( $eqpkey, 'q' ) !== false) {
		$defcdt .= '_q';
	}
	if (strpos ( $eqpkey, 'U' ) !== false) {
		$defcdt .= '_U';
	}
	if (strpos ( $eqpkey, 'I' ) !== false) {
		$defcdt .= '_I';
	}
	if (strpos ( $eqpkey, 'I' ) !== false) {
		$defcdt .= '_I';
	}*/
	return $defcdt;
}

function get_ex_dmg($nm, $sd, $clb, &$inf, $ky, $wk, $we, $ws, $dky) {
	if ($ky) {
		global $log, $exdmgname, $exdmginf, $ex_attack;
		global $ex_dmg_def, $ex_base_dmg,$ex_max_dmg, $ex_wep_dmg, $ex_skill_dmg, $ex_dmg_fluc, $ex_inf, $ex_inf_r, $ex_max_inf_r, $ex_skill_inf_r, $ex_inf_punish, $ex_good_wep, $ex_good_club;
		$ex_final_dmg = 0;
		foreach ( $ex_attack as $ex_dmg_sign ) {
			if (strpos ( $ky, $ex_dmg_sign ) !== false) {
				$dmgnm = $exdmgname [$ex_dmg_sign];
				$dmginf = $exdmginf [$ex_dmg_sign];
				$def = $ex_dmg_def [$ex_dmg_sign];
				$bdmg = $ex_base_dmg [$ex_dmg_sign];
				$mdmg = $ex_max_dmg [$ex_dmg_sign];
				$wdmg = $ex_wep_dmg [$ex_dmg_sign];
				$sdmg = $ex_skill_dmg [$ex_dmg_sign];
				$fluc = $ex_dmg_fluc [$ex_dmg_sign];
				if (!empty($ex_inf [$ex_dmg_sign])) {
					$ex_inf_sign = $ex_inf [$ex_dmg_sign];
					$infr = $ex_inf_r [$ex_inf_sign];
					$minfr = $ex_max_inf_r [$ex_inf_sign];
					$sinfr = $ex_skill_inf_r [$ex_inf_sign];
					$punish = $ex_inf_punish [$ex_inf_sign];
					$e_htr = $ex_good_club [$ex_inf_sign] == $clb ? 20 : 0;
				} else {
					$ex_inf_sign = '';
					$punish = 1;
					$e_htr = 0;
				}
				$wk_dmg_p = $ex_good_wep [$ex_dmg_sign] == $wk ? 2 : 1;
				$e_dmg = $bdmg + $we/$wdmg + $ws/$sdmg; 
				if($mdmg>0){
					$e_dmg = round($wk_dmg_p*$mdmg*($e_dmg/($e_dmg+$mdmg/2))*rand(100 - $fluc, 100 + $fluc)/100);
				} else{
					$e_dmg =  round($wk_dmg_p*$e_dmg*rand(100 - $fluc, 100 + $fluc)/100);
				}
				//$e_dmg += round ( ($we / ($we + $wdmg) + $ws / ($ws + $sdmg)) * rand ( 100 - $fluc, 100 + $fluc ) / 200 * $bdmg * $wk_dmg_p );
				if (strpos ( $dky, $def ) == false) {
					if (strpos ( $inf, $ex_dmg_sign ) !== false && $punish > 1) {
						$log .= "由于{$nm}已经{$dmginf}，{$dmgnm}伤害倍增！";
						$e_dmg *= $punish;
					} elseif (strpos ( $inf, $ex_dmg_sign ) !== false && $punish < 1) {
						$log .= "由于{$nm}已经{$dmginf}，{$dmgnm}伤害减少！";
						$e_dmg *= $punish;
					} else {
						$e_htr += $infr + $ws * $sinfr;
						$e_htr = $e_htr > $minfr ? $minfr : $e_htr;
					}
					$e_dmg = round($e_dmg);
					$log .= "{$dmgnm}造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！<br>";
					if (!empty($ex_inf_sign) && strpos ( $inf, $ex_dmg_sign ) == false) {
						$dice = rand ( 0, 99 );
						if ($dice < $e_htr) {
							$inf .= $ex_inf_sign;
							if ($sd == 0) {
								global $w_combat_inf;
								$w_combat_inf .= $ex_inf_sign;
							}
							$log .= "并造成{$nm}{$dmginf}了！<br>";
							global $name,$w_name;
							if($nm == '你'){
								addnews($now,'inf',$w_name,$name,$ex_inf_sign);
							}else{
								addnews($now,'inf',$name,$w_name,$ex_inf_sign);
							}	
						}
					}
				} else {
					$e_dmg = round ( $e_dmg / 2 );
					$log .= "{$dmgnm}被防御效果抵消了！造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！<br>";
				}
				$ex_final_dmg += $e_dmg;
			}
		}
		return $ex_final_dmg;
	} else {
		return 0;
	}
	/*
	if (strpos ( $ky, 'p' ) !== false) {
		$ex_dmg_sign = 'p';
		if ($clb == 8) {
			$e_htr = 20;
		} else {
			$e_htr = 0;
		}
	}
	if (strpos ( $ky, 'u' ) !== false) {
		$ex_dmg_sign = 'u';
		$e_htr = 0;
		if ($wk == 'G') {
			//echo 'g';
			$wk_dmg_p = 2;
		}
	}
	if (strpos ( $ky, 'i' ) !== false) {
		$ex_dmg_sign = 'i';
		$e_htr = 0;
	}
	if (isset ( $ex_dmg_sign )) {
		$dmgnm = $exdmgname [$ex_dmg_sign];
		$dmginf = $exdmginf [$ex_dmg_sign];
		$def = $ex_dmg_def [$ex_dmg_sign];
		$bdmg = $ex_base_dmg [$ex_dmg_sign];
		$wdmg = $ex_wep_dmg [$ex_dmg_sign];
		$sdmg = $ex_skill_dmg [$ex_dmg_sign];
		$fluc = $ex_dmg_fluc [$ex_dmg_sign];
		$infr = $ex_inf_r [$ex_dmg_sign];
		$minfr = $ex_max_inf_r [$ex_dmg_sign];
		$sinfr = $ex_skill_inf_r [$ex_dmg_sign];
		$punish = $ex_inf_punish [$ex_dmg_sign];
		$e_dmg = 1 + round ( ($we / ($we + $wdmg) + $ws / ($ws + $sdmg)) * rand (100 - $fluc,100 + $fluc ) / 200 * $bdmg * $wk_dmg_p );
		if (strpos ( $dky, $def ) == false) {
			if (strpos ( $inf, $ex_dmg_sign ) !== false && $punish > 1) {
				$log .= "由于{$nm}已经{$dmginf}，{$dmgnm}伤害倍增！";
				$e_htr = 0;
			} elseif (strpos ( $inf, $ex_dmg_sign ) !== false && $punish < 1) {
				$log .= "由于{$nm}已经{$dmginf}，{$dmgnm}伤害减少！";
				$e_htr = 0;
			} else {
				$e_htr += $infr + $ws * $sinfr;
				$e_htr = $e_htr > $minfr ? $minfr : $e_htr;
			}
			$e_dmg = round ( $e_dmg * $punish );
			$log .= "{$dmgnm}造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！<br>";
			$dice = rand ( 0, 99 );
			if ($dice < $e_htr) {
				$inf .= $ex_dmg_sign;
				if ($sd == 0) {
					global $w_combat_inf;
					$w_combat_inf .= $ex_dmg_sign;
				}
				$log .= "并造成{$nm}{$dmginf}了！<br>";
			}
		} else {
			$e_dmg = round ( $e_dmg / 2 );
			$log .= "{$dmgnm}被防御效果抵消了！造成了<span class=\"red\">{$e_dmg}</span>点额外伤害！<br>";
		}
		return $e_dmg;
	} else {
		return;
	}
*/
}

function get_WF_p($w, $clb, $we) {
	global $log, ${$w . 'sp'};
	if (! empty ( $w )) {
		$factor = 0.5;
	} else {
		$we = $we > 0 ? $we : 1;
		if ($clb == 9) {
			$spd0 = round ( 0.2*$we);
		} else {
			$spd0 = round ( 0.25*$we);
		}
		if ($spd0 >= ${$w . 'sp'}) {
			$spd = ${$w . 'sp'} - 1;
		} else {
			$spd = $spd0;
		}
		$factor = 0.5 + $spd / $spd0 / 2;
		$f = round ( 100 * $factor );
		$log .= "你消耗{$spd}点体力，发挥了灵力武器{$f}％的威力！";
		${$w . 'sp'} -= $spd;
	}
	return $factor;
}

function check_KP_wep($nm, $ht, &$wp, &$wk, &$we, &$ws, &$wsk) {
	global $log, $nosta;
	if ($ht > 0 && $ws == $nosta) {
		$we -= $ht;
		if ($nm == '你') {
			$log .= "{$nm}的{$wp}的攻击力下降了{$ht}！<br>";
		}
		if ($we <= 0) {
			$log .= "{$nm}的<span class=\"red\">$wp</span>使用过度，已经损坏，无法再装备了！<br>";
			$wp = '拳头';
			$wk = 'WN';
			$we = 0;
			$ws = $nosta;
			$wsk = '';
		}
	} elseif ($ht > 0 && $ws != $nosta) {
		$ws -= $ht;
		if ($nm == '你') {
			$log .= "{$nm}的{$wp}的耐久度下降了{$ht}！<br>";
		}
		if ($ws <= 0) {
			$log .= "{$nm}的<span class=\"red\">$wp</span>使用过度，已经损坏，无法再装备了！<br>";
			$wp = '拳头';
			$wk = 'WN';
			$we = 0;
			$ws = $nosta;
			$wsk = '';
		}
	}
	return;
}

function check_GCDF_wep($nm, $ht, &$wp, $wp_kind, &$wk, &$we, &$ws, &$wsk) {
	global $log, $nosta;
	if ((($wp_kind == 'C') || ($wp_kind == 'D')|| ($wp_kind == 'F')) && ($ws != $nosta)) {
		$ws -= $ht;
		if ($nm == '你') {
			$log .= "{$nm}用掉了{$ht}个{$wp}。<br>";
		}
		if ($ws <= 0) {
			$log .= "{$nm}的<span class=\"red\">$wp</span>用光了！<br>";
			$wp = '拳头';
			$wsk = '';
			$wk = 'WN';
			$we = 0;
			$ws = $nosta;
		}
	} elseif (($wp_kind == 'G') && ($ws != $nosta)) {
		$ws -= $ht;
		if ($nm == '你') {
			$log .= "{$nm}的{$wp}的弹药数减少了{$ht}。<br>";
		}
		if ($ws <= 0) {
			$log .= "{$nm}的<span class=\"red\">$wp</span>的弹药用光了！<br>";
			$ws = $nosta;
		}
	}
	return;
}

function get_inf($nm, $ht, $wp_kind) {
	if ($ht > 0) {
		global $infatt;
		$infatt_dice = rand ( 1, 4 );
		if (($infatt_dice == 1) && (strpos ( $infatt [$wp_kind], 'b' ) !== false)) {
			$inf_att = 'b';
		} elseif (($infatt_dice == 2) && (strpos ( $infatt [$wp_kind], 'h' ) !== false)) {
			$inf_att = 'h';
		} elseif (($infatt_dice == 3) && (strpos ( $infatt [$wp_kind], 'a' ) !== false)) {
			$inf_att = 'a';
		} elseif (($infatt_dice == 4) && (strpos ( $infatt [$wp_kind], 'f' ) !== false)) {
			$inf_att = 'f';
		}
		if($nm == '你'){
			$w = '';
		} else {
			$w = 'w_';
		}
		if ($inf_att) {
			global $log, ${$w . 'ar' . $inf_att}, ${$w . 'ar' . $inf_att . 'k'}, ${$w . 'ar' . $inf_att . 'e'}, ${$w . 'ar' . $inf_att . 's'}, ${$w . 'ar' . $inf_att . 'sk'};
			if (${$w . 'ar' . $inf_att . 's'}) {
				${$w . 'ar' . $inf_att . 's'} -= $ht;
				if ($nm == '你') {
					$log .= "你的${$w.'ar'.$inf_att}的耐久度下降了{$ht}！<br>";
				}
				if (${$w . 'ar' . $inf_att . 's'} <= 0) {
					$log .= "{$nm}的<span class=\"red\">${$w.'ar'.$inf_att}</span>受损过重，无法再装备了！<br>";
					${$w . 'ar' . $inf_att} = ${$w . 'ar' . $inf_att . 'k'} = ${$w . 'ar' . $inf_att . 'sk'} = '';
					${$w . 'ar' . $inf_att . 'e'} = ${$w . 'ar' . $inf_att . 's'} = 0;
				}
			} else {
				global $log, ${$w . 'inf'}, $infinfo;
				if (strpos ( ${$w . 'inf'}, $inf_att ) === false) {
					${$w . 'inf'} .= $inf_att;
					if ($w == 'w_') {
						global ${$w . 'combat_inf'};
						${$w . 'combat_inf'} .= $inf_att;
					}
					$log .= "{$nm}的<span class=\"red\">$infinfo[$inf_att]</span>部受伤了！<br>";
					global $name,$w_name;
					if($nm == '你'){
						addnews($now,'inf',$w_name,$name,$inf_att);
					}else{
						addnews($now,'inf',$name,$w_name,$inf_att);
					}					
				}
			}
		}
	}
	return;
}

function get_dmg_punish($nm, $dmg, &$hp, $a_ky) {
	if ($dmg >= 1000) {
		global $log;
		if ($dmg < 2000) {
			$hp_d = floor ( $hp / 2 );
		} elseif ($dmg < 5000) {
			$hp_d = floor ( $hp * 2 / 3 );
		} else {
			$hp_d = floor ( $hp * 4 / 5 );
		}
		if (strpos ( $a_ky, 'H' ) != false) {
			$hp_d = floor ( $hp_d / 10 );
		}
		$log .= "惨无人道的攻击对{$nm}自身造成了<span class=\"red\">$hp_d</span>点<span class=\"red\">反噬伤害！</span><br>";
		$hp -= $hp_d;
	}
	return;
}

function exprgup(&$lv_a, $lv_d, &$exp, $isplayer, &$rg) {
	global $log;
	$expup = round ( ($lv_d - $lv_a) / 3 );
	$expup = $expup > 0 ? $expup : 1;
	$exp += $expup;
	//$log .= "$isplayer 的经验值增加 $expup 点<br>";
	if ($isplayer) {
		global $upexp;
		$nl_exp = $upexp;
	} else {
		global $w_upexp;
		$nl_exp = $w_upexp;
	}
	if ($exp >= $nl_exp) {
		include_once GAME_ROOT . './include/state.func.php';
		lvlup ( $lv_a, $exp, $isplayer );
	}
	$rgup = round ( ($lv_a - $lv_d) / 3 );
	$rg += $rgup > 0 ? $rgup : 1;
	return;
}

function addnoise($wp_kind, $wsk, $ntime, $npls, $nid1, $nid2, $nmode) {
	if ((($wp_kind == 'G') && (strpos ( $wsk, 'S' ) === false)) || ($wp_kind == 'F')) {
		global $noisetime, $noisepls, $noiseid, $noiseid2, $noisemode;
		$noisetime = $ntime;
		$noisepls = $npls;
		$noiseid = $nid1;
		$noiseid2 = $nid2;
		$noisemode = $nmode;
		save_combatinfo ();
	} elseif (strpos ( $wsk, 'd' ) !== false){
		global $noisetime, $noisepls, $noiseid, $noiseid2, $noisemode;
		$noisetime = $ntime;
		$noisepls = $npls;
		$noiseid = $nid1;
		$noiseid2 = $nid2;
		$noisemode = 'D';
		save_combatinfo ();
	}
	return;
}

function check_gender($nm_a, $nm_d, $gd_a, $gd_d, $a_ky) {
	$gd_dmg_p = 1;
	if ((((strpos ( $a_ky, "l" ) !== false) && ($gd_a != $gd_d)) || ((strpos ( $a_ky, "g" ) !== false) && ($gd_a == $gd_d))) && (! rand ( 0, 4 ))) {
		global $log;
		$log .= "<span class=\"red\">{$nm_a}被{$nm_d}迷惑，无法全力攻击！</span>";
		$gd_dmg_p = 0;
	} elseif ((((strpos ( $a_ky, "l" ) !== false) && ($gd_a == $gd_d)) || ((strpos ( $a_ky, "g" ) !== false) && ($gd_a != $gd_d))) && (! rand ( 0, 4 ))) {
		global $log;
		$log .= "<span class=\"red\">{$nm_a}被{$nm_d}激怒，伤害加倍！</span>";
		$gd_dmg_p = 2;
	}
	return $gd_dmg_p;
}

function npc_chat($type,$nm, $mode) {
	global $npcchaton;
	if ($npcchaton) {
		global $npcchat, $w_itmsk0, $w_hp, $w_mhp;
//		if ($type == 1|| $type == 7) {
//			$npcwords = '<span class="evergreen">';
//		} elseif ($type == 5 || $type == 6) {
//			$npcwords = '<span class="yellow">';
//		} else {
//			$npcwords = '<span>';
//		}
		$chatcolor = $npcchat[$type][$nm]['color'];
		if(!empty($chatcolor)){
			$npcwords = "<span class = \"{$chatcolor}\">";
		}else{
			$npcwords = '<span>';
		}
		switch ($mode) {
			case 'attack' :
				if (empty ( $w_itmsk0 )) {
					$npcwords .= "{$npcchat[$type][$nm][0]}<br>";
					$w_itmsk0 = '1';
				} elseif ($w_hp > ($w_mhp / 2)) {
					$dice = rand ( 1, 2 );
					$npcwords .= "{$npcchat[$type][$nm][$dice]}<br>";
				} else {
					$dice = rand ( 3, 4 );
					$npcwords .= "{$npcchat[$type][$nm][$dice]}<br>";
				}
				break;
			case 'defend' :
				if (empty ( $w_itmsk0 )) {
					$npcwords .= "{$npcchat[$type][$nm][0]}<br>";
					$w_itmsk0 = '1';
				} elseif ($w_hp > ($w_mhp / 2)) {
					$dice = rand ( 5, 6 );
					$npcwords .= "{$npcchat[$type][$nm][$dice]}<br>";
				} else {
					$dice = rand ( 7, 8 );
					$npcwords .= "{$npcchat[$type][$nm][$dice]}<br>";
				}
				break;
			case 'death' :
				$npcwords .= "{$npcchat[$type][$nm][9]}<br>";
				break;
			case 'escape' :
				$npcwords .= "{$npcchat[$type][$nm][10]}<br>";
				break;
			case 'cannot' :
				$npcwords .= "{$npcchat[$type][$nm][11]}<br>";
				break;
			case 'critical' :
				$npcwords .= "{$npcchat[$type][$nm][12]}<br>";
				break;
			case 'kill' :
				$npcwords .= "{$nm}对你说道：{$npcchat[$type][$nm][13]}<br>";
				break;
		}
		/*if ($mode == 'attack') {
			if (empty ( $w_itmsk0 )) {
				$npcwords .= "{$npcchat[$type][0]}<br>";
				$w_itmsk0 = '1';
			} elseif ($w_hp > ($w_mhp / 2)) {
				$dice = rand ( 1, 2 );
				$npcwords .= "{$npcchat[$type][$dice]}<br>";
			} else {
				$dice = rand ( 3, 4 );
				$npcwords .= "{$npcchat[$type][$dice]}<br>";
			}
		} elseif ($mode == 'defend') {
			if (empty ( $w_itmsk0 )) {
				$npcwords .= "{$npcchat[$type][0]}<br>";
				$w_itmsk0 = '1';
			} elseif ($w_hp > ($w_mhp / 2)) {
				$dice = rand ( 5, 6 );
				$npcwords .= "{$npcchat[$type][$dice]}<br>";
			} else {
				$dice = rand ( 7, 8 );
				$npcwords .= "{$npcchat[$type][$dice]}<br>";
			}
		} elseif ($mode == 'death') {
			$npcwords .= "{$npcchat[$type][9]}<br>";
		} elseif ($mode == 'escape') {
			$npcwords .= "{$npcchat[$type][10]}<br>";
		} elseif ($mode == 'cannot') {
			$npcwords .= "{$npcchat[$type][11]}<br>";
		} elseif ($mode == 'critical') {
			$npcwords .= "{$npcchat[$type][12]}<br>";
		}*/
		$npcwords .= '</span>';
		return $npcwords;
	} else {
		return;
	}
}
?>