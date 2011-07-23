<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function findenemy($edata) {
	global $log,$mode,$main,$cmd,$battle_title,$attinfo,$skillinfo,$pdata,$nosta,$fog,$techniqueinfo;
	//global $w_type,$w_name,$w_gd,$w_sNo,$w_icon,$w_hp,$w_mhp,$w_sp,$w_msp,$w_rage,$w_wep,$w_wepk,$w_wepe,$w_lvl,$w_pose,$w_tactic,$w_inf;//,$itmsk0;
	
	if($edata['pid'] !== $pdata['bid']){
		$log .= "<span class=\"yellow\">敌人数据出错。</span><br>";
		$mode = 'command';
		return;
	}
	
	$battle_title = '发现敌人';
	
	extract($pdata,EXTR_REFS);
	extract($edata,EXTR_PREFIX_ALL|EXTR_REFS,'w');
	init_battle($edata);
	init_itemwords($edata);
	if($fog){
		$log .= "你发现了敌人，但是看不清对方的相貌！<br>对方好像完全没有注意到你！<br>";
	}else{
		$log .= "你发现了敌人<span class=\"red\">$w_name</span>！<br>对方好像完全没有注意到你！<br>";
	}
	
	
//	$cmd .= '现在想要做什么？<br><br>';
//	$cmd .= '向对手大喊：<br><input size="30" type="text" name="message" maxlength="60"><br><br>';
//	$cmd .= '<input type="hidden" name="mode" value="combat">';
	$wp_kind = substr($wepk,1,1);
	$w_wp_kind = substr($w_wepk,1,1);
	
	if(($wp_kind == 'G')&&($weps==$nosta)){ $wp_kind = 'g'; }
	if(($w_wp_kind == 'G')&&($w_weps==$nosta)){ $w_wp_kind = 'g'; }
	$techcmd = Array();
	if($technique){
		$a_tech = $techniqueinfo['active']['combat'];
		for($i=0;$i < strlen($technique); $i+=3){
			$tstr = substr($technique,$i,3);
			if(isset($a_tech[$tstr]) && ($a_tech[$tstr]['wep_kind'] == 'A' || strpos($a_tech[$tstr]['wep_kind'],$wp_kind) !== false) && ($a_tech[$tstr]['anti_kind'] == 'A' || strpos($a_tech[$tstr]['anti_kind'],$w_wp_kind) !== false || $fog) ){
				$techcmd[$tstr] = Array('name' => $a_tech[$tstr]['name'], 'dsp' => $a_tech[$tstr]['dsp']);
				//$cmd .= '<input type="radio" name="command" id="'.$tstr.'" value="'.$tstr.'"><a onclick=sl("'.$tstr.'"); href="javascript:void(0);">'.$a_tech[$tstr]['name'].'('.$a_tech[$tstr]['dsp'].')</a><br>';
			}
		}
	}
	ob_start();
	include template('battlecmd');
	$cmd = ob_get_contents();
	ob_end_clean();
	ob_start();
	include template('battle');
	$main = ob_get_contents();
	ob_end_clean();
//	$cmd .= '<input type="radio" name="command" id="natk" value="natk" checked><a onclick=sl("natk"); href="javascript:void(0);">'."$attinfo[$wp_kind]".'</a><br>';
//	if($technique){
//		$a_tech = $techniqueinfo['active']['combat'];
//		for($i=0;$i < strlen($technique); $i+=3){
//			$tstr = substr($technique,$i,3);
//			if(isset($a_tech[$tstr]) && ($a_tech[$tstr]['wep_kind'] == 'A' || strpos($a_tech[$tstr]['wep_kind'],$wp_kind) !== false) && ($a_tech[$tstr]['anti_kind'] == 'A' || strpos($a_tech[$tstr]['anti_kind'],$w_wp_kind) !== false || $fog) ){
//				$cmd .= '<input type="radio" name="command" id="'.$tstr.'" value="'.$tstr.'"><a onclick=sl("'.$tstr.'"); href="javascript:void(0);">'.$a_tech[$tstr]['name'].'('.$a_tech[$tstr]['dsp'].')</a><br>';
//			}
//		}
//	}
//	$cmd .= '<br><input type="radio" name="command" id="back" value="back"><a onclick=sl("back"); href="javascript:void(0);" >逃跑</a><br>';
	$mode = 'combat';
	//$main = 'battle';
	
	return;
}

function findteam($edata){
	global $pdata,$log,$mode,$main,$cmd,$battle_title,$gamestate;
	//global $w_type,$w_name,$w_gd,$w_sNo,$w_icon,$w_hp,$w_mhp,$w_sp,$w_msp,$w_rage,$w_wep,$w_wepk,$w_wepe,$w_lvl,$w_pose,$w_tactic,$w_inf;//,$itmsk0;
	
	if($edata['pid'] !== $pdata['bid']){
		$log .= "<span class=\"yellow\">队友数据出错。</span><br>";
		$mode = 'command';
		return;
	}
	if($gamestate>=40){
		$log .= '<span class="yellow">连斗阶段所有队伍取消！</span><br>';
		$pdata['bid'] = 0;
		$mode = 'command';
		return;
	}
	$battle_title = '发现队友';
	extract($edata,EXTR_PREFIX_ALL|EXTR_REFS,'w');
	init_battle($edata,1);
	init_itemwords($edata);
	$log .= "你发现了队友<span class=\"yellow\">$w_name</span>！<br>";
	
	$inotice = '你想转让什么物品给队友？<div class="itmbtn">转让赠言：<input size="30" type="text" name="message" maxlength="60"></div>';
	//$mode = 'senditem';
	$mval = 'senditem';
	$cval = '';
	$dlist = Array('itm1','itm2','itm3','itm4','itm5','itm6');
	ob_start();
	include template('itemmenu');
	$cmd = ob_get_contents();
	ob_end_clean();
	ob_start();
	include template('battle');
	$main = ob_get_contents();
	ob_end_clean();
//	$cmd .= '现在想要做什么？<br><br>';
//	$cmd .= '留言：<br><input size="30" type="text" name="message" maxlength="60"><br><br>';
//	$cmd .= '想要转让什么？<input type="hidden" name="mode" value="senditem"><br><input type="radio" name="command" id="back" value="back" checked><a onclick=sl("back"); href="javascript:void(0);" >不转让</a><br><br>';
//	for($i = 1;$i <= 6; $i++){
//		global ${'itms'.$i};
//		if(${'itms'.$i}) {
//			global ${'itm'.$i},${'itmk'.$i},${'itme'.$i};
//			$cmd .= '<input type="radio" name="command" id="itm'.$i.'" value="itm'.$i.'"><a onclick=sl("itm'.$i.'"); href="javascript:void(0);" >'."${'itm'.$i}/${'itme'.$i}/${'itms'.$i}".'</a><br>';
//		}
//	}
	//$main = 'battle';
	return;
}

function findcorpse($edata){
	global $log,$mode,$main,$battle_title,$cmd,$pdata,$iteminfo,$itemspkinfo;
	//global $w_type,$w_name,$w_gd,$w_sNo,$w_icon,$w_hp,$w_mhp,$w_wep,$w_wepk,$w_wepe,$w_lvl,$w_pose,$w_tactic,$w_inf;//,$itmsk0;
	
	if($edata['pid'] !== $pdata['bid']){
		$log .= "<span class=\"yellow\">尸体数据出错。</span><br>";
		$pdata['bid'] = 0;
		$mode = 'command';
		return;
	}
	$battle_title = '发现尸体';
	//extract($edata,EXTR_PREFIX_ALL|EXTR_REFS,'w');
	init_battle($edata,1);
	init_itemwords($edata);
	//$main = 'battle';
	//$log .= '你发现了<span class="red">'.$edata['name'].'</span>的尸体！<br>';
	ob_start();
	include template('corpse');
	$cmd = ob_get_contents();
	ob_end_clean();
	ob_start();
	include template('battle');
	$main = ob_get_contents();
	ob_end_clean();
	return;
}


function senditem($sendtype = 't'){
	global $db,$tablepre,$pdata,$companysystem,$log,$mode,$main,$command,$cmd,$battle_title,$mapdata,$message,$now,$w_log,$gamestate;
	$pls = $pdata['pls'];$name = $pdata['name'];$teamID = $pdata['teamID'];$company = $pdata['company'];
	$bid = & $pdata['bid'];
	if($sendtype == 't'){
		if($bid==0){
			$log .= '<span class="yellow">你没有遇到队友，或已经离开现场！</span><br>';
			$bid = 0;
			$mode = 'command';
			return;
		}
		if($gamestate>=40){
			$log .= '<span class="yellow">连斗阶段无法赠送物品！</span><br>';
			$bid = 0;
			$mode = 'command';
			return;
		}
		$sid = $bid;
	}elseif($sendtype == 'c'){
		if(!$companysystem){
			$log .= '<span class="yellow">同伴系统未开启！</span><br>';
			$bid = 0;
			$mode = 'command';
			return;
		}elseif(!$company){
			$log .= '<span class="yellow">你没有同伴！</span><br>';
			$bid = 0;
			$mode = 'command';
			return;
		}
		$sid = $company;
	}else{
		$log .= '<span class="yellow">指令错误！</span><br>';
		$bid = 0;
		$mode = 'command';
		return;
	}
	
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$sid'");
	if(!$db->num_rows($result)){
		$log .= "对方不存在！<br>";
		$bid = 0;
		$mode = 'command';
		return;
	}

	$edata = $db->fetch_array($result);
	if($sendtype == 't' && $edata['pls'] != $pls) {
		$log .= '<span class="yellow">'.$edata['name'].'</span>已经离开了<span class="yellow">'.$mapdata[$pls]['name'].'</span>。<br>';
		$mode = 'command';
		$bid = 0;
		return;
	} elseif($sendtype == 'c' && $edata['pls'] != $pls) {
		$log .= '<span class="yellow">'.$edata['name'].'</span>位于<span class="yellow">'.$mapdata[$edata['pls']]['name'].'</span>，请先移动到同一地图！<br>';
		$mode = 'command';
		$bid = 0;
		return;
	} elseif($edata['hp'] <= 0) {
		$log .= '<span class="yellow">'.$edata['name'].'</span>已经死亡，不能接受物品。<br>';
		$mode = 'command';
		$bid = 0;
		return;
	} elseif($sendtype == 't' && (!$teamID || $edata['teamID']!=$teamID)){
		$log .= '<span class="yellow">'.$edata['name'].'</span>并非你的队友，不能接受物品。<br>';
		$mode = 'command';
		$bid = 0;
		return;
	}

	if($message){
		$log .= "<span class=\"lime\">你对{$edata['name']}说：“{$message}”</span><br>";
		$w_log = "<span class=\"lime\">{$name}对你说：“{$message}”</span><br>";
		if($edata['type'] == 0 || $edata['type'] == 100){logsave($edata['pid'],$now,$w_log,'c');}
	}
	
	if($command != 'back'){
		$itmn = substr($command, 3);
		
		//global ${'itm'.$itmn},${'itmk'.$itmn},${'itme'.$itmn},${'itms'.$itmn},${'itmsk'.$itmn};
		if (!$pdata['itms'.$itmn]) {
			$log .= '此道具不存在！';
			$bid = 0;
			$mode = 'command';
			return;
		}
		$itm = & $pdata['itm'.$itmn];
		$itmk = & $pdata['itmk'.$itmn];
		$itme = & $pdata['itme'.$itmn];
		$itms = & $pdata['itms'.$itmn];
		$itmsk = & $pdata['itmsk'.$itmn];
		$itmnp = & $pdata['itmnp'.$itmn];

		//global $w_pid,$w_name,$w_pass,$w_type,$w_lastcmd,$w_lasteff,$w_gd,$w_sNo,$w_icon,$w_club,$w_hp,$w_mhp,$w_sp,$w_msp,$w_att,$w_def,$w_pls,$w_lvl,$w_exp,$w_money,$w_bid,$w_inf,$w_rage,$w_pose,$w_tactic,$w_killnum,$w_state,$w_wp,$w_wk,$w_wg,$w_wc,$w_wd,$w_wf,$w_teamID,$w_teamPass,$w_wep,$w_wepk,$w_wepe,$w_weps,$w_arb,$w_arbk,$w_arbe,$w_arbs,$w_arh,$w_arhk,$w_arhe,$w_arhs,$w_ara,$w_arak,$w_arae,$w_aras,$w_arf,$w_arfk,$w_arfe,$w_arfs,$w_art,$w_artk,$w_arte,$w_arts,$w_itm0,$w_itmk0,$w_itme0,$w_itms0,$w_itm1,$w_itmk1,$w_itme1,$w_itms1,$w_itm2,$w_itmk2,$w_itme2,$w_itms2,$w_itm3,$w_itmk3,$w_itme3,$w_itms3,$w_itm4,$w_itmk4,$w_itme4,$w_itms4,$w_itm5,$w_itmk5,$w_itme5,$w_itms5,$w_itm6,$w_itmk6,$w_itme6,$w_itms6,$w_wepsk,$w_arbsk,$w_arhsk,$w_arask,$w_arfsk,$w_artsk,$w_itmsk0,$w_itmsk1,$w_itmsk2,$w_itmsk3,$w_itmsk4,$w_itmsk5,$w_itmsk6;
		extract($edata,EXTR_PREFIX_ALL|EXTR_REFS,'w');


		for($i = 1;$i <= 6; $i++){
			if(!${'w_itms'.$i}) {
				${'w_itm'.$i} = $itm;
				${'w_itmk'.$i} = $itmk;
				${'w_itme'.$i} = $itme;
				${'w_itms'.$i} = $itms;
				${'w_itmsk'.$i} = $itmsk;
				${'w_itmnp'.$i} = $itmnp;
				$log .= "你将<span class=\"yellow\">${'w_itm'.$i}</span>送给了<span class=\"yellow\">$w_name</span>。<br>";
				if($sendtype == 't'){
					$w_log = "<span class=\"yellow\">$name</span>将<span class=\"yellow\">${'w_itm'.$i}</span>送给了你。";
					if($edata['type'] == 0 || $edata['type'] == 100){logsave($w_pid,$now,$w_log,'t');}
					naddnews($now,'senditem',$name,$w_name,$itm);
				}				
				player_save($edata);
				$itm = $itmk = $itmsk = '';
				$itme = $itms = $itmnp = 0;
				$bid = 0;
				$mode = 'command';
				return;
			}
		}
		$log .= "<span class=\"yellow\">$w_name</span> 的包裹已经满了，不能赠送物品。<br>";
	}
	$bid = 0;
	$mode = 'command';
	return;
}

?>