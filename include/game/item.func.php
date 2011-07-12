<?php

if (! defined ( 'IN_GAME' )) {
	exit ( 'Access Denied' );
}

function itemuse($itmn) {
	global $db, $tablepre,$iteminfo, $mode, $log, $nosta, $pdata,$now;
	$pid = $pdata['pid'];$name = $pdata['name'];$pls = $pdata['pls'];$type = $pdata['type'];
	$state = & $pdata['state'];
	$hp = & $pdata['hp'];	$mhp = & $pdata['mhp'];
	$sp = & $pdata['sp'];	$msp = & $pdata['msp'];
	$club = & $pdata['club'];
	$lvl = & $pdata['lvl'];$exp = & $pdata['exp'];
	$inf = & $pdata['inf'];$bid = & $pdata['bid'];
	if ($itmn < 1 || $itmn > 6) {
		$log .= '此道具不存在，请重新选择。';
		$mode = 'command';
		return;
	}
	
	$itm = & $pdata['itm' . $itmn];
	$itmk = & $pdata['itmk' . $itmn];
	$itme = & $pdata['itme' . $itmn];
	$itms = & $pdata['itms' . $itmn];
	$itmsk = & $pdata['itmsk' . $itmn];
	$itmnp = & $pdata['itmnp' . $itmn];
	
	if (($itms <= 0) && ($itms != $nosta)) {
		$itm = $itmk = $itmsk = '';
		$itme = $itms = $itmnp = 0;
		$log .= '此道具不存在，请重新选择。<br>';
		$mode = 'command';
		return;
	}
	if($type != 0 && ($itm == '游戏解除钥匙' || $itm == '奇怪的按钮')){
		$log .= '非参战者不能使用此物品。';
		$mode = 'command';
		return;
	}
	if(strpos ( $itmk, 'W' ) === 0 || strpos ( $itmk, 'D' ) === 0 || strpos ( $itmk, 'A' ) === 0){
		if(strpos ( $itmk, 'W' ) === 0) {
			$eqp = 'wep';
			$noeqp = 'WN';
		}elseif(strpos ( $itmk, 'DB' ) === 0) {
			$eqp = 'arb';
			$noeqp = 'DN';
		}elseif(strpos ( $itmk, 'DH' ) === 0) {
			$eqp = 'arh';
			$noeqp = '';
		}elseif(strpos ( $itmk, 'DA' ) === 0) {
			$eqp = 'ara';
			$noeqp = '';
		}elseif(strpos ( $itmk, 'DF' ) === 0) {
			$eqp = 'arf';
			$noeqp = '';
		}elseif (strpos ( $itmk, 'A' ) === 0) {
			$eqp = 'art';
			$noeqp = '';
		}
		if(substr($itmk,2,1) == 'C' && $pdata['type'] != 100){
			$log .= '这是同伴专用装备，玩家无法使用！<br>';
			$mode = 'command';
			return;
		}
		if (($noeqp && strpos ( $pdata[$eqp.'k'], $noeqp ) === 0) || ! $pdata[$eqp.'s']) {
			$pdata[$eqp] = $itm;
			$pdata[$eqp.'k'] = $itmk;
			$pdata[$eqp.'e'] = $itme;
			$pdata[$eqp.'s'] = $itms;
			$pdata[$eqp.'sk'] = $itmsk;
			$pdata[$eqp.'np'] = $itmnp;
			$log .= "装备了<span class=\"yellow\">$itm</span>。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
		} else {
			$itmt = $pdata[$eqp];
			$itmkt = $pdata[$eqp.'k'];
			$itmet = $pdata[$eqp.'e'];
			$itmst = $pdata[$eqp.'s'];
			$itmskt = $pdata[$eqp.'sk'];
			$itmnpt = $pdata[$eqp.'np'];
			$pdata[$eqp] = $itm;
			$pdata[$eqp.'k'] = $itmk;
			$pdata[$eqp.'e'] = $itme;
			$pdata[$eqp.'s'] = $itms;
			$pdata[$eqp.'sk'] = $itmsk;
			$pdata[$eqp.'np'] = $itmnp;
			$itm = $itmt;
			$itmk = $itmkt;
			$itme = $itmet;
			$itms = $itmst;
			$itmsk = $itmskt;
			$itmnp = $itmnpt;
			$log .= "卸下了<span class=\"red\">$itm</span>，装备了<span class=\"yellow\">$pdata[$eqp]</span>。<br>";
		}
	}

	elseif (strpos ( $itmk, 'HS' ) === 0) {
		if ($sp < $msp) {
			$oldsp = $sp;
			if($club == 16){
				$spup = round($itme*2.5);
			}else{
				$spup = $itme;
			}
			$sp += $spup;
			$sp = $sp > $msp ? $msp : $sp;
			$oldsp = $sp - $oldsp;
			$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldsp</span>点体力。<br>";
			if ($itms != $nosta) {
				$itms --;
				if ($itms <= 0) {
					$log .= "<span class=\"red\">$itm</span>用光了。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = $itmnp = 0;
				}
			}
		} else {
			$log .= '你的体力不需要恢复。<br>';
		}
	} elseif (strpos ( $itmk, 'HH' ) === 0) {
		if ($hp < $mhp) {
			$oldhp = $hp;
			if($club == 16){
				$hpup = round($itme*2.5);
			}else{
				$hpup = $itme;
			}
			$hp += $hpup;
			$hp = $hp > $mhp ? $mhp : $hp;
			$oldhp = $hp - $oldhp;
			$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldhp</span>点生命。<br>";
			if ($itms != $nosta) {
				$itms --;
				if ($itms <= 0) {
					$log .= "<span class=\"red\">$itm</span>用光了。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = $itmnp = 0;
				}
			
			}
		} else {
			$log .= '你的生命不需要恢复。<br>';
		}
	} elseif (strpos ( $itmk, 'HB' ) === 0) {
		if (($hp < $mhp) || ($sp < $msp)) {
			if($club == 16){
				$bpup = round($itme*2.5);
			}else{
				$bpup = $itme;
			}
			$oldsp = $sp;
			$sp += $bpup;
			$sp = $sp > $msp ? $msp : $sp;
			$oldsp = $sp - $oldsp;
			$oldhp = $hp;
			$hp += $bpup;
			$hp = $hp > $mhp ? $mhp : $hp;
			$oldhp = $hp - $oldhp;
			$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldhp</span>点生命和<span class=\"yellow\">$oldsp</span>点体力。<br>";
			if ($itms != $nosta) {
				$itms --;
				if ($itms <= 0) {
					$log .= "<span class=\"red\">$itm</span>用光了。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = $itmnp = 0;
				}
			}
		} else {
			$log .= '你的生命和体力都不需要恢复。<br>';
		}
		
	} elseif (strpos ( $itmk, 'P' ) === 0) {
		if (strpos ( $itmk, '2' ) === 2) {
			$damage = round ( $itme * 2 );
		} elseif (strpos ( $itmk, '1' ) === 2) {
			$damage = round ( $itme * 1.5 );
		} else {
			$damage = round ( $itme );
		}
		if (strpos ( $inf, 'p' ) === false) {
			$inf .= 'p';
		}
		$hp -= $damage;
		if ($itmnp) {
			$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$itmnp'" );
			$wdata = $db->fetch_array ( $result );
			$log .= "糟糕，<span class=\"yellow\">$itm</span>中被<span class=\"yellow\">{$wdata['name']}</span>掺入了毒药！你受到了<span class=\"dmg\">$damage</span>点伤害！<br>";
			naddnews ( $now, 'poison', $name, $wdata ['name'], $itm );
		} else {
			$log .= "糟糕，<span class=\"yellow\">$itm</span>有毒！你受到了<span class=\"dmg\">$damage</span>点伤害！<br>";
		}
		if ($hp <= 0) {
			if ($itmnp) {
				$bid = $itmnp;
				$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$itmnp'" );
				$wdata = $db->fetch_array ( $result );
				$log .= "你被<span class=\"red\">" . $wdata ['name'] . "</span>毒死了！<br>";
				//include_once GAME_ROOT . './include/state.func.php';
				if(set_death ( $GLOBALS['pdata'], 'poison',$itm,$wdata)){
					$killmsg = auto_chat($wdata,'kill');
					if($killmsg){
						$log .= "{$w_name}对你说道：{$killmsg}";
					}
				}
				
			} else {
				$bid = 0;
				$log .= "你被毒死了！<br>";
				//include_once GAME_ROOT . './include/state.func.php';
				set_death ( $GLOBALS['pdata'], 'poison',$itm );				
			}
		}
		if ($itms != $nosta) {
			$itms --;
			if ($itms <= 0) {
				$log .= "<span class=\"red\">$itm</span>用光了。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			}
		}
	
	} elseif (strpos ( $itmk, 'T' ) === 0) {
		$wd = & $pdata['wd'];$upexp = & $pdata['display']['upexp'];
		$trapk = str_replace('TN','TO',$itmk);
		$db->query("INSERT INTO {$tablepre}maptrap (itm, itmk, itme, itms, itmsk,itmnp, pls) VALUES ('$itm', '$trapk', '$itme', '1', '$itmsk','$pid', '$pls')");
		$log .= "设置了陷阱<span class=\"red\">$itm</span>。<br>小心，自己也很难发现。<br>";
		if($club == 5){$exp += 2;$wd+=2;}
		else{$exp++;$wd++;}
		
		if ($exp >= $upexp) {
			set_lvlup($GLOBALS['pdata'],1);
		}
		if ($itms != $nosta) {
			$itms --;
			if ($itms <= 0) {
				$log .= "<span class=\"red\">$itm</span>用光了。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			}
		}
	} elseif (strpos ( $itmk, 'GB' ) === 0) {
		global $iteminfo;
		$wep = & $pdata['wep'];$wepk = & $pdata['wepk'];$weps = & $pdata['weps'];$wepsk = & $pdata['wepsk'];
		if (strpos ( $wepk, 'WG' ) !== 0) {
			$log .= "<span class=\"red\">你没有装备枪械，不能使用子弹。</span><br>";
			$mode = 'command';
			return;
		}
		$wepbk = substr($wepk,2,1);
		if(!$wepbk){$wepbk = 'P';}
		if ($wepbk != 'S' && $wepbk != 'E') {
			$log .= "<span class=\"red\">{$wep}不能装填弹药。</span><br>";
			$mode = 'command';
			return;
		}
		
		$bullet = get_cassette($wepk);
		if($itmk == 'GB'.$wepbk){
			if ($weps == $nosta) {
				$weps = 0;
			}
			$bullet = $bullet - $weps;
			if ($bullet <= 0) {
				$log .= "<span class=\"red\">{$wep}的弹匣是满的，不能装弹。</span>";
				return;
			} elseif ($bullet >= $itms) {
				$bullet = $itms;
			}
			$itms -= $bullet;
			$weps += $bullet;
			$log .= "为<span class=\"red\">$wep</span>装填了<span class=\"red\">$itm</span>，<span class=\"red\">$wep</span>残弹数增加<span class=\"yellow\">$bullet</span>。<br>";
			if ($itms <= 0) {
				$log .= "<span class=\"red\">$itm</span>用光了。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			}
		}else{
			$log .= "<span class=\"red\">枪械和弹药不匹配，需要".$iteminfo['GB'.$wepbk]."。</span><br>";
			$mode = 'command';
			return;
		}
	} elseif (strpos ( $itmk, 'R' ) === 0) {
		if ($itme > 0) {
			$log .= "使用了<span class=\"red\">$itm</span>。<br>";
			include_once GAME_ROOT . './include/game/item2.func.php';
			radar ( substr($itmk,1,1) );
			$itme --;
			if ($itme <= 0) {
				$log .= $itm . '的电力用光了，请使用电池充电。<br>';
			}
		} else {
			$itme = 0;
			$log .= $itm . '没有电了，请先充电。<br>';
		}
	} elseif (strpos ( $itmk, 'C' ) === 0) {
		global $infdata,$ex_inf;
		$ck=substr($itmk,1,1);
		if($ck == 'a'){
			$flag=false;
			$log .= "服用了<span class=\"red\">$itm</span>。<br>";
			foreach ($ex_inf as $value) {
				if(strpos ( $inf, $value ) !== false){
					$inf = str_replace ( $value, '', $inf );
					$log .= "{$infdata[$value]['name']}状态解除了。<br>";
					$flag=true;
				}
			}
			if(!$flag){
				$log .= '但是什么也没发生。<br>';
			}
		}elseif(in_array($ck,$ex_inf)){
			if(strpos ( $inf, $ck ) !== false){
				$inf = str_replace ( $ck, '', $inf );
				$log .= "服用了<span class=\"red\">$itm</span>，{$infdata[$ck]['name']}状态解除了。<br>";
			}else{
				$log .= "服用了<span class=\"red\">$itm</span>，但是什么效果也没有。<br>";
			}
		}else{
			$log .= "服用了<span class=\"red\">$itm</span>……发生了什么？<br>";
		}
		
		$itms --;

		if ($itms <= 0) {
			$log .= "<span class=\"red\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
		}
	
	} elseif (strpos ( $itmk, 'V' ) === 0) {
		$wp = & $pdata['wp'];
		$wk = & $pdata['wk'];
		$wg = & $pdata['wg'];
		$wc = & $pdata['wc'];
		$wd = & $pdata['wd'];
		$wf = & $pdata['wf'];
		$skill_minimum = 100;
		$skill_limit = 300;
		$log .= "你阅读了<span class=\"red\">$itm</span>。<br>";
		$dice = rand ( - 10, 10 );
		if (strpos ( $itmk, 'VV' ) === 0) {
			$ws_sum = $wp + $wk + $wg + $wc + $wd + $wf;
			if ($ws_sum < $skill_minimum * 5) {
				$vefct = $itme;
			} elseif ($ws_sum < $skill_limit * 5) {
				$vefct = round ( $itme * (1 - ($ws_sum - $skill_minimum * 5) / ($skill_limit * 5 - $skill_minimum * 5)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wp += $vefct; //$itme;
			$wk += $vefct; //$itme;
			$wg += $vefct; //$itme;
			$wc += $vefct; //$itme;
			$wd += $vefct; //$itme; 
			$wf += $vefct; //$itme;
			$wsname = "全系熟练度";
		} elseif (strpos ( $itmk, 'VP' ) === 0) {
			if ($wp < $skill_minimum) {
				$vefct = $itme;
			} elseif ($wp < $skill_limit) {
				$vefct = round ( $itme * (1 - ($wp - $skill_minimum) / ($skill_limit - $skill_minimum)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wp += $vefct; //$itme;
			$wsname = "斗殴熟练度";
		} elseif (strpos ( $itmk, 'VK' ) === 0) {
			if ($wk < $skill_minimum) {
				$vefct = $itme;
			} elseif ($wk < $skill_limit) {
				$vefct = round ( $itme * (1 - ($wk - $skill_minimum) / ($skill_limit - $skill_minimum)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wk += $vefct; //$itme; 
			$wsname = "斩刺熟练度";
		} elseif (strpos ( $itmk, 'VG' ) === 0) {
			if ($wg < $skill_minimum) {
				$vefct = $itme;
			} elseif ($wg < $skill_limit) {
				$vefct = round ( $itme * (1 - ($wg - $skill_minimum) / ($skill_limit - $skill_minimum)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wg += $vefct; //$itme; 
			$wsname = "射击熟练度";
		} elseif (strpos ( $itmk, 'VC' ) === 0) {
			if ($wc < $skill_minimum) {
				$vefct = $itme;
			} elseif ($wc < $skill_limit) {
				$vefct = round ( $itme * (1 - ($wc - $skill_minimum) / ($skill_limit - $skill_minimum)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wc += $vefct; //$itme; 
			$wsname = "投掷熟练度";
		} elseif (strpos ( $itmk, 'VD' ) === 0) {
			if ($wd < $skill_minimum) {
				$vefct = $itme;
			} elseif ($wd < $skill_limit) {
				$vefct = round ( $itme * (1 - ($wd - $skill_minimum) / ($skill_limit - $skill_minimum)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wd += $vefct; //$itme; 
			$wsname = "引爆熟练度";
		} elseif (strpos ( $itmk, 'VF' ) === 0) {
			if ($wf < $skill_minimum) {
				$vefct = $itme;
			} elseif ($wf < $skill_limit) {
				$vefct = round ( $itme * (1 - ($wf - $skill_minimum) / ($skill_limit - $skill_minimum)) );
			} else {
				$vefct = 0;
			}
			if ($vefct < 10) {
				if ($vefct < $dice) {
					$vefct = - $dice;
				}
			}
			$wf += $vefct; //$itme; 
			$wsname = "灵击熟练度";
		}
		if ($vefct > 0) {
			$log .= "嗯，有所收获。<br>你的{$wsname}提高了<span class=\"yellow\">$vefct</span>点！<br>";
		} elseif ($vefct == 0) {
			$log .= "对你来说书里的内容过于简单了。<br>你的熟练度没有任何提升。<br>";
		} else {
			$vefct = - $vefct;
			$log .= "对你来说书里的内容过于简单了。<br>而且由于盲目相信书上的知识，你反而被编写者的纰漏所误导了！<br>你的{$wsname}下降了<span class=\"red\">$vefct</span>点！<br>";
		}
		if ($itms != $nosta) {
			$itms --;
			if ($itms <= 0) {
				$log .= "<span class=\"red\">$itm</span>用光了。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			}
		}
	} elseif (strpos ( $itmk, 'M' ) === 0) {
		$log .= "你服用了<span class=\"red\">$itm</span>。<br>";
		$att = & $pdata['att'];$def = & $pdata['def'];
		$wp = & $pdata['wp'];
		$wk = & $pdata['wk'];
		$wg = & $pdata['wg'];
		$wc = & $pdata['wc'];
		$wd = & $pdata['wd'];
		$wf = & $pdata['wf'];
		if (strpos ( $itmk, 'MA' ) === 0) {
			$att_min = 200;
			$att_limit = 500;
			$dice = rand ( - 5, 5 );
			if ($att < $att_min) {
				$mefct = $itme;
			} elseif ($att < $att_limit) {
				$mefct = round ( $itme * (1 - ($att - $att_min) / ($att_limit - $att_min)) );
			} else {
				$mefct = 0;
			}
			if ($mefct < 5) {
				if ($mefct < $dice) {
					$mefct = - $dice;
				}
			}
			$att += $mefct;
			$mdname = "基础攻击力";
		} elseif (strpos ( $itmk, 'MD' ) === 0) {
			$def_min = 200;
			$def_limit = 500;
			$dice = rand ( - 5, 5 );
			if ($def < $def_min) {
				$mefct = $itme;
			} elseif ($def < $def_limit) {
				$mefct = round ( $itme * (1 - ($def - $def_min) / ($def_limit - $def_min)) );
			} else {
				$mefct = 0;
			}
			if ($mefct < 5) {
				if ($mefct < $dice) {
					$mefct = - $dice;
				}
			}
			$def += $mefct;
			$mdname = "基础防御力";
		} elseif (strpos ( $itmk, 'ME' ) === 0) {
			global $baseexp;
			$upexp = & $pdata['display']['upexp'];
			$lvlup_objective = $itme / 10;
			$mefct = round ( $baseexp * 2 * $lvlup_objective + rand ( 0, 5 ) );
			$exp += $mefct;
			$mdname = "经验值";
		} elseif (strpos ( $itmk, 'MS' ) === 0) {
			$mefct = $itme;
			$sp += $mefct;
			$msp += $mefct;
			$mdname = "体力上限";
		} elseif (strpos ( $itmk, 'MH' ) === 0) {
			$mefct = $itme;
			$hp += $mefct;
			$mhp += $mefct;
			$mdname = "生命上限";
		} elseif (strpos ( $itmk, 'MV' ) === 0) {
			$skill_minimum = 100;
			$skill_limit = 300;
			$dice = rand ( - 10, 10 );
			$ws_sum = $wp + $wk + $wg + $wc + $wd + $wf;
			if ($ws_sum < $skill_minimum * 5) {
				$mefct = $itme;
			} elseif ($ws_sum < $skill_limit * 5) {
				$mefct = round ( $itme * (1 - ($ws_sum - $skill_minimum * 5) / ($skill_limit * 5 - $skill_minimum * 5)) );
			} else {
				$mefct = 0;
			}
			if ($mefct < 10) {
				if ($mefct < $dice) {
					$mefct = - $dice;
				}
			}
			$wp += $mefct;
			$wk += $mefct;
			$wg += $mefct;
			$wc += $mefct;
			$wd += $mefct;
			$wf += $mefct;
			$mdname = "全系熟练度";
		}
		if ($mefct > 0) {
			$log .= "身体里有种力量涌出来！<br>你的{$mdname}提高了<span class=\"yellow\">$mefct</span>点！<br>";
		} elseif ($mefct == 0) {
			$log .= "已经很强了，却还想靠药物继续强化自己，是不是太贪心了？<br>你的能力没有任何提升。<br>";
		} else {
			$mefct = - $mefct;
			$log .= "已经很强了，却还想靠药物继续强化自己，是不是太贪心了？<br>你贪婪的行为引发了药物的副作用！<br>你的{$mdname}下降了<span class=\"red\">$mefct</span>点！<br>";
		}
		if (strpos ( $itmk, 'ME' ) === 0) {
			
			if ($exp >= $upexp) {
				set_lvlup($GLOBALS['pdata'],1);
			}
		}
		if ($itms != $nosta) {
			$itms --;
			if ($itms <= 0) {
				$log .= "<span class=\"red\">$itm</span>用光了。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			}
		}
	} elseif ( strpos( $itmk,'EW' ) ===0 )	{
		include_once GAME_ROOT . './include/game/item2.func.php';
		wthchange ( $itm,$itmnp);
		$itms--;
		if ($itms <= 0) {
			$log .= "<span class=\"red\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
		}
	} elseif ( strpos( $itmk,'EM' ) ===0 )	{
		global $mapdata,$mapweaponinfo;
		$locktime = set_mapweapon($itmnp, $pls, $pid, $now);
		if($locktime){
			$mapwname = $mapweaponinfo[$itmnp]['dmgnm'];
			$locklog = "{$name}召唤了{$mapweaponinfo[$itmnp]['name']}，将在{$locktime}秒后对{$mapdata[$pls]['name']}实施打击！";
			systemchat($locklog,$now);
			$log .= "使用了{$itm}召唤了{$mapwname}，将在{$locktime}秒后对{$mapdata[$pls]['name']}实施打击！请迅速撤离作战地点。<br>";
			naddnews($now, 'MAPWset', $name, $itm ,$locktime, $pls, $mapwname);
		}else{
			$log .= "使用了{$itm}，但是什么也没发生。";
		}
		
		$itms--;
		if ($itms <= 0) {
			$log .= "<span class=\"red\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
		}
	} elseif (strpos ( $itmk, 'Y' ) === 0||strpos ( $itmk, 'Z' ) === 0) {
		if ($itm == '电池') {
			//功能需要修改，改为选择道具使用YE类型道具可充电
			$flag = false;
			for($i = 1; $i <= 6; $i ++) {
				if ($pdata['itm' . $i] == '移动PC') {
					$pdata['itme' . $i] += $itme;
					$itms --;
					$flag = true;
					$log .= "为<span class=\"yellow\">${'itm'.$i}</span>充了电。";
					break;
				}
			}
			if (! $flag) {
				$log .= '你没有需要充电的物品。<br>';
			}
		} elseif ($itm == '毒药') {
			global $cmd;
			$cmd = '<input type="hidden" name="mode" value="item"><input type="hidden" name="usemode" value="poison"><input type="hidden" name="itmp" value="' . $itmn . '">你想对什么下毒？<br><input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl("menu"); href="javascript:void(0);" >返回</a><br><br>';
			for($i = 1; $i <= 6; $i ++) {
				if ((strpos ( $pdata['itmk' . $i], 'H' ) === 0) || (strpos ( $pdata['itmk' . $i], 'P' ) === 0)) {
					$cmd .= '<input type="radio" name="command" id="itm' . $i . '" value="itm' . $i . '"><a onclick=sl("itm' . $i . '"); href="javascript:void(0);" >' . $pdata['itm' . $i] . '/' . $pdata['itme' . $i] .'/'. $pdata['itms' . $i]. '</a><br>';
				}
			}
			return;
		} elseif (strpos ( $itm, '磨刀石' ) !== false) {
			$wep = & $pdata['wep'];
			$wepk = & $pdata['wepk'];
			$wepe = & $pdata['wepe'];
			$weps = & $pdata['weps'];
			$wepsk = & $pdata['wepsk'];
			$wepnp = & $pdata['wepnp'];
			if (strpos ( $wepk, 'K' ) == 1) {
				$dice = rand ( 0, 100 );
				if ($dice >= 15) {
					$wepe += $itme;					
					$log .= "使用了<span class=\"yellow\">$itm</span>，<span class=\"yellow\">$wep</span>的攻击力变成了<span class=\"yellow\">$wepe</span>。<br>";
					if (strpos ( $wep, '锋利的' ) === false) {
						$wep = '锋利的'.$wep;
					}
				} else {
					$wepe -= ceil ( $itme / 2 );
					if ($wepe <= 0) {
						$log .= "<span class=\"red\">$itm</span>使用失败，<span class=\"red\">$wep</span>损坏了！<br>";
						$wep = $wepk = $wepsk = '';
						$wepe = $weps = $wepnp = 0;
					} else {
						$log .= "<span class=\"red\">$itm</span>使用失败，<span class=\"red\">$wep</span>的攻击力变成了<span class=\"red\">$wepe</span>。<br>";
					}
				}
				
				$itms --;
			} else {
				$log .= '你没装备锐器，不能使用磨刀石。<br>';
			}
		} elseif (preg_match ( "/钉$/", $itm )) {
			$wep = & $pdata['wep'];
			$wepk = & $pdata['wepk'];
			$wepe = & $pdata['wepe'];
			$weps = & $pdata['weps'];
			$wepsk = & $pdata['wepsk'];
			$wepnp = & $pdata['wepnp'];
			if (( strpos ( $wep, '棍棒' ) !== false) && ($wepk == 'WP')) {
				$dice = rand ( 0, 100 );
				if ($dice >= 10) {
					$wepe += $itme;
					$log .= "使用了<span class=\"yellow\">$itm</span>，<span class=\"yellow\">$wep</span>的攻击力变成了<span class=\"yellow\">$wepe</span>。<br>";
					if (strpos ( $wep, '钉' ) === false) {
						$wep = str_replace ( '棍棒', '钉棍棒', $wep );
					}
				} else {
					$wepe -= ceil ( $itme / 2 );
					if ($wepe <= 0) {
						$log .= "<span class=\"red\">$itm</span>使用失败，<span class=\"red\">$wep</span>损坏了！<br>";
						$wep = $wepk = $wepsk = '';
						$wepe = $weps = $wepnp = 0;
					} else {
						$log .= "<span class=\"red\">$itm</span>使用失败，<span class=\"red\">$wep</span>的攻击力变成了<span class=\"red\">$wepe</span>。<br>";
					}
				}
				
				$itms --;
			} else {
				$log .= '你没装备棍棒，不能安装钉子。<br>';
			}
		} elseif ($itm == '针线包') {
			global $noarb;
			$arb = & $pdata['arb'];
			$arbk = & $pdata['arbk'];
			$arbe = & $pdata['arbe'];
			$arbs = & $pdata['arbs'];
			$arbsk = & $pdata['arbsk'];
			$arbnp = & $pdata['arbnp'];
			if (($arb == $noarb) || ! $arb) {
				$log .= '你没有装备防具，不能使用针线包。<br>';
			} elseif(strpos($arbsk,'Z')!==false){
				$log .= '<span class="yellow">该防具太单薄以至于不能使用针线包。</span><br>你感到一阵蛋疼菊紧，你的蛋疼度增加了<span class="yellow">233</span>点。<br>';
			}else {
				$arbe += (rand ( 0, 2 ) + $itme);
				$log .= "用<span class=\"yellow\">$itm</span>给防具打了补丁，<span class=\"yellow\">$arb</span>的防御力变成了<span class=\"yellow\">$arbe</span>。<br>";
				$itms --;
			}
		} elseif ($itm == '消音器') {
			$wep = & $pdata['wep'];
			$wepk = & $pdata['wepk'];
			$wepe = & $pdata['wepe'];
			$weps = & $pdata['weps'];
			$wepsk = & $pdata['wepsk'];
			$wepnp = & $pdata['wepnp'];
			if (strpos ( $wepk, 'WG' ) !== 0) {
				$log .= '你没有装备枪械，不能使用消音器。<br>';
			} elseif (strpos ( $wepsk, 'S' ) === false) {
				$wepsk .= 'S';
				$log .= "你给<span class=\"yellow\">$wep</span>安装了<span class=\"yellow\">$itm</span>。<br>";
				$itms --;
			} else {
				$log .= "你的武器已经安装了消音器。<br>";
			}
		} elseif ($itm == '移动PC') {
			if($itme<=0){
				$log .= "<span class=\"yellow\">$itm</span>已经没电，请寻找<span class=\"yellow\">电池</span>充电。<br>";
				$mode = 'command';
				return;
			}
			$mode = 'hack';
			$log .= "<span class=\"yellow\">你启动了{$itm}。</span><br>";
			if($club == 7){
				$log .= '你小心翼翼地侵入了禁区控制系统，现在禁区任你摆布了。<br>';
			}else{
				$log .= '你小心翼翼地侵入了禁区控制系统，但是随时都有可能被发现。<br>';
			}
			return;
//			include_once GAME_ROOT . './include/game/item2.func.php';
//			hack ( $itmn );
		} elseif ($itm == '探测器电池') {
			$flag = false;
			for($i = 1; $i <= 6; $i ++) {
				if (strpos($pdata['itmk' . $i],'R') === 0) {
					$pdata['itme' . $i] += $itme;
					$itms --;
					$flag = true;
					$log .= "为<span class=\"yellow\">{$pdata['itm'.$i]}</span>充了电。";
					break;
				}
			}
			if (! $flag) {
				$log .= '你没有探测仪器。<br>';
			}
		} elseif ($itm == '御神签') {
			$log .= "使用了<span class=\"yellow\">$itm</span>。<br>";
			include_once GAME_ROOT . './include/game/item2.func.php';
			divining ();
			$itms --;
		} elseif (strpos($itm ,'凸眼鱼')!==false) {
			global $corpseprotect;
			$tm = $now - $corpseprotect;//尸体保护
			$db->query ( "UPDATE {$tablepre}players SET state = 98 WHERE hp <= 0 AND (lasteff <= $tm OR bid = $pid)" );
			$cnum = $db->affected_rows ();
			naddnews ( $now, 'corpseclear', $name, $cnum );
			$log .= "使用了<span class=\"yellow\">$itm</span>。<br>突然刮起了一阵怪风，吹走了地上的{$cnum}具尸体！<br>";
			$itms --;

		} elseif ($itm == '武器师安雅的奖赏') {
			$wep = & $pdata['wep'];
			$wepk = & $pdata['wepk'];
			$wepe = & $pdata['wepe'];
			$weps = & $pdata['weps'];
			$wepsk = & $pdata['wepsk'];
			$wepnp = & $pdata['wepnp'];
			$wp = & $pdata['wp'];
			$wk = & $pdata['wk'];
			$wg = & $pdata['wg'];
			$wc = & $pdata['wc'];
			$wd = & $pdata['wd'];
			$wf = & $pdata['wf'];
			if (! $weps || ! $wepe) {
				$log .= '请先装备武器。<br>';
				return;
			}
			$dice = rand ( 0, 99 );
			$dice2 = rand ( 0, 99 );
			$skill = array ('WP' => $wp, 'WK' => $wk, 'WG' => $wg, 'WC' => $wc, 'WD' => $wd, 'WF' => $wf );
			arsort ( $skill );
			$skill_keys = array_keys ( $skill );
			$nowsk = substr ( $wepk, 0, 2 );
			$maxsk = $skill_keys [0];
			if (($skill [$nowsk] != $skill [$maxsk]) && ($dice < 30)) {
				$wepk = str_replace($nowsk,$maxsk,$wepk);
				$kind = "更改了{$wep}的<span class=\"yellow\">类别</span>！";
			} elseif (($weps != $nosta) && ($dice2 < 70)) {
				$weps += ceil ( $wepe / 2 );
				$kind = "增强了{$wep}的<span class=\"yellow\">耐久</span>！";
			} else {
				$wepe += ceil ( $wepe / 2 );
				$kind = "提高了{$wep}的<span class=\"yellow\">攻击力</span>！";
			}
			$log .= "你使用了<span class=\"yellow\">$itm</span>，{$kind}";
			naddnews ( $now, 'newwep', $name, $itm, $wep );
			if (strpos ( $wep, '-改' ) === false) {
				$wep = $wep . '-改';
			}
			$itms --;
		} elseif ($itm == '■DeathNote■') {
			$mode = 'deathnote';
			$log .= '你翻开了■DeathNote■<br>';
			return;
		} elseif ($itm == '游戏解除钥匙') {
			global $url;
			$url = 'end.php';
			include_once GAME_ROOT . './include/system.func.php';
			gameover ( $now, 3, $name );
		} elseif ($itm == '冴月 麟的ID卡' || $itm == '四面的ID卡') {
			include_once GAME_ROOT . './include/system.func.php';
			$duelstate = duel($now,$itm);
			if($duelstate == 50){
				$log .= "<span class=\"yellow\">你使用了{$itm}。</span><br><span class=\"evergreen\">“干得不错呢，看来咱应该专门为你清扫一下战场……”</span><br><span class=\"evergreen\">“所有的NPC都离开战场了。好好享受接下来的杀戮吧，祝你好运。”</span>——林无月<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			}elseif($duelstate == 51){
				$log .= "你使用了<span class=\"yellow\">{$itm}</span>，不过什么反应也没有。<br><span class=\"evergreen\">“咱已经帮你准备好舞台了，请不要要求太多哦。”</span>——林无月<br>";
			} else {
				$log .= "你使用了<span class=\"yellow\">{$itm}</span>，不过什么反应也没有。<br><span class=\"evergreen\">“表演的时机还没到呢，请再忍耐一下吧。”</span>——林无月<br>";
			}
		} elseif ($itm == '奇怪形状的ID卡') {
			global $arealock;
			$arealock = 0;
			save_gameinfo();
			$log .= "<span class=\"yellow\">你使用了{$itm}。</span><br><span class=\"evergreen\">“你还真用心呢，那么也许我应该送你一个礼物？”</span><br><span class=\"evergreen\">“所有的NPC都离开战场了。好好享受接下来的杀戮吧，祝你好运。”</span>——林无月<br>";
			$log .= "<span class=\"yellow\">地图上出现了新的地点！</span><br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
		} elseif ($itm == '奇怪的按钮') {
			$button_dice = rand ( 1, 10 );
			if ($button_dice < 5) {
				$log .= "你按下了<span class=\"yellow\">$itm</span>，不过好像什么都没有发生！";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			} elseif ($button_dice < 8) {
				global $url;
				$url = 'end.php';
				include_once GAME_ROOT . './include/system.func.php';
				gameover ( $now, 5, $name );
			} else {
				$log .= '好像什么也没发生嘛？<br>咦，按钮上的标签写着什么？“危险，勿触”……？<br>';
				$log .= '呜哇，按钮爆炸了！<br>';
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
				$bid = 0;
				set_death($pdata,'button',$itm);
			}
		} elseif ($itm == '装有H173的注射器') {
			$wp = & $pdata['wp'];
			$wk = & $pdata['wk'];
			$wg = & $pdata['wg'];
			$wc = & $pdata['wc'];
			$wd = & $pdata['wd'];
			$wf = & $pdata['wf'];
			$att = & $pdata['att'];$def = & $pdata['def'];
			$log .= '你考虑了一会，<br>把袖子卷了起来，给自己注射了H173。<br>';
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
			$deathdice = rand ( 0, 8191 );
			if ($deathdice == 8191) {
				$log .= '你突然感觉到一种不可思议的力量贯通全身！<br>';
				$wp += 1500; $wk += 1500; $wg += 1500; $wc += 1500; $wd += 1500; $wf += 1500;
				$att *= 4; $def *= 4;
				$club = 15;
				naddnews ( $now, 'suisidefail', $name );
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
			} else {
				$log .= '你失去了知觉。<br>';
				$bid = 0;
				set_death($pdata,'suiside',$itm);
			}
		} elseif ($itm == 'NPC增加机') {
//			include_once GAME_ROOT . './include/system.func.php';
//			echo addnpc ( 10, 0,1);
		} elseif (strpos($itm,'灵魂的集合')===0) {
				$log .= "你使用了{$itm}，跟英灵们缔结了契约！<br>你成为了魔法少女！<br>（这啥设定……）<br>";
				$pdata['club'] = 18;
				$pdata['mhp'] /=2; $pdata['hp'] = $pdata['mhp'];
				$pdata['msp'] /=2; $pdata['sp'] = $pdata['msp'];
				$pdata['wf'] *= 10;
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
		} elseif ($itm == '圣杯') {
				global $url;
				$url = 'end.php';
				include_once GAME_ROOT . './include/system.func.php';
				gameover ( $now, 7, $name );
		} elseif ($itm == '水果刀') {
			$flag = false;
			
			for($i = 1; $i <= 6; $i ++) {
				$wk = & $pdata['wk'];
				${'itm' . $i} = & $pdata['itm' . $i];
				${'itmk' . $i} = & $pdata['itmk' . $i];
				${'itme' . $i} = & $pdata['itme' . $i];
				${'itms' . $i} = & $pdata['itms' . $i];
				foreach(Array('香蕉','苹果','西瓜') as $fruit){
					
					if ( strpos ( ${'itm' . $i} , $fruit ) !== false && strpos ( ${'itm' . $i} , '皮' ) === false && (strpos ( ${'itmk' . $i} , 'H' ) === 0 || strpos ( ${'itmk' . $i} , 'P' ) === 0 )) {
						if($wk >= 120){
							$log .= "练过刀就是好啊。你娴熟地削着果皮。<br><span class=\"yellow\">${'itm'.$i}</span>变成了<span class=\"yellow\">★残骸★</span>！<br>咦为什么会出来这种东西？算了还是不要吐槽了。<br>";
							${'itm' . $i} = '★残骸★';
							${'itme' . $i} *= rand(2,4);
							${'itms' . $i} *= rand(3,5);
							$flag = true;
							$wk++;
						}else{
							$log .= "想削皮吃<span class=\"yellow\">${'itm'.$i}</span>，没想到削完发现只剩下一堆果皮……<br>手太笨拙了啊。<br>";
							${'itm' . $i} = str_replace($fruit, $fruit.'皮',${'itm' . $i} );
							${'itmk' . $i} = 'TN';
							${'itms' . $i} *= rand(2,4);
							$flag = true;
							$wk++;
						}
						break;
					}
				}
				if($flag == true) {break;};
			}
			if (! $flag) {
				$log .= '包裹里没有水果。<br>';
			} else {
				$dice = rand(1,5);
				if($dice==1){
					$log .= "<span class=\"red\">$itm</span>变钝了，无法再使用了。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = $itmnp = 0;
				}
			}
		} else {
			$log .= " <span class=\"yellow\">$itm</span> 该如何使用呢？<br>";
		}
		
		if (($itms <= 0) && ($itm)) {
			$log .= "<span class=\"red\">$itm</span> 用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = $itmnp = 0;
		}
	} else {
		$log .= "你使用了道具 <span class=\"yellow\">$itm</span> 。<br>但是什么也没有发生。<br>";
	}
	$mode = 'command';
	return;
}


?>