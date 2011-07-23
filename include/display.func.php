<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}
function get_itmkwords($itmk){
	global $iteminfo;
	$ikwords = '';
	foreach($iteminfo as $ikey => $ival){
		if(strpos($itmk,$ikey)===0){
			$ikwords = $ival;
			break;
		}
	}
	return $ikwords;
}

function get_itmewords($itmk,$itme){
	if(strpos($itmk,'W')===0){
		return "攻 <span class=\"val\">$itme</span>";
	}elseif(strpos($itmk,'D')===0){
		return "防 <span class=\"val\">$itme</span>";
	}else{
		return "效 <span class=\"val\">$itme</span>";
	}
}

function get_itmswords($itmk,$itms){
	global $nosta;
	if(strpos($itmk,'WG')===0){
		$cst = get_cassette($itmk);
		if($itms == $nosta){
			$show = 0;
		}else{
			$show = $itms;
		}
		return "弹 <span id=\"bullet\" class=\"val\">$show</span>/$cst";
	}elseif(strpos($itmk,'WP')===0 || strpos($itmk,'WK')===0){
		if($itms == $nosta){
			return "<span class=\"val\">耗攻</span>";
		}else{
			return "耐 <span class=\"val\">$itms</span>";
		}
	}elseif(strpos($itmk,'WC')===0 || strpos($itmk,'WD')===0 || strpos($itmk,'WF')===0){
		if($itms == $nosta){
			return "<span class=\"val\">无限</span>";
		}else{
			return "数 <span class=\"val\">$itms</span>";
		}
	}elseif(strpos($itmk,'D')===0 || strpos($itmk,'A')===0){
		if($itms == $nosta){
			return "<span class=\"val\">无限</span>";
		}else{
			return "耐 <span class=\"val\">$itms</span>";
		}
	}elseif(strpos($itmk,'WN')===0){
		return "<span class=\"val\">无限</span>";
	}else{
		if($itms == $nosta){
			return "<span class=\"val\">无限</span>";
		}else{
			return "数 <span class=\"val\">$itms</span>";
		}
	}
}

function get_itmskwords($itmk,$itmsk,$profile=0){
	global $itemspecialinfo,$nospk;
	$itemskinfo = array_merge($itemspecialinfo['att'],$itemspecialinfo['def'],$itemspecialinfo['map']);
	$isklist = Array();
	for ($i = 0; $i < strlen($itmsk); $i+=2) {
		$sub = substr($itmsk,$i,2);
		if(!empty($sub)){
			$isklist[] = $itemskinfo[$sub];
		}
	}
//	if(strpos($itmk,'WG')===0){
//		$cst = get_cassette($itmk);
//		if($cst > 0){
//			$cstwords = '弹夹'.$cst.'发';
//			array_unshift($isklist,$cstwords);
//		}
//	}
	$wepat = get_wephittimes($itmk);
	if($wepat>1){
		$atwords = $wepat.'回攻击';	
		array_unshift($isklist,$atwords);
	}
	$iskwords = '';
	$i = 0;
	foreach($isklist as $val){
		if(!$iskwords){
			$iskwords = $val;
		}elseif($profile){
			$iskwords .= '<br>'.$val;
		}else{
			$iskwords .= '+'.$val;
		}
		$i ++;
	}
	if(!$iskwords){$iskwords = $nospk;}
	return $iskwords;
}


function init_itemwords(&$data,$prf = ''){
	$rdata = Array();
	foreach (Array('wep','arb','arh','ara','arf','art') as $val) {
		$rdata[$prf.$val.'k_words'] = get_itmkwords($data[$val.'k']);
		$rdata[$prf.$val.'e_words'] = get_itmewords($data[$val.'k'],$data[$val.'e']);
		$rdata[$prf.$val.'s_words'] = get_itmswords($data[$val.'k'],$data[$val.'s']);
		if($prf){
			$rdata[$prf.$val.'sk_words'] = get_itmskwords($data[$val.'k'],$data[$val.'sk']);
		} else {
			$rdata[$prf.$val.'sk_words'] = get_itmskwords($data[$val.'k'],$data[$val.'sk'],1);
		}
	}
	for($i = 0;$i<=6;$i++){
		$rdata[$prf.'itmk'.$i.'_words'] = get_itmkwords($data['itmk'.$i]);
		$rdata[$prf.'itme'.$i.'_words'] = get_itmewords($data['itmk'.$i],$data['itme'.$i]);
		$rdata[$prf.'itms'.$i.'_words'] = get_itmswords($data['itmk'.$i],$data['itms'.$i]);
		if($prf || $i == 0){
			$rdata[$prf.'itmsk'.$i.'_words'] = get_itmskwords($data['itmk'.$i],$data['itmsk'.$i]);
		} else {
			$rdata[$prf.'itmsk'.$i.'_words'] = get_itmskwords($data['itmk'.$i],$data['itmsk'.$i],1);
		}
	}
	foreach($rdata as $key => $val){
		$data['display'][$key] = $val;
	}	
	return $rdata;
}

function init_techniquewords(&$data){
	global $techniqueinfo;
	$data['display']['techwords'] = '';
	$technique = $data['technique'];
	$techwords = & $data['display']['techwords'];
	$techinfo = array_merge($techniqueinfo['active']['combat'],$techniqueinfo['active']['map'],$techniqueinfo['passive']['combat'],$techniqueinfo['passive']['map']);
	for ($i = 0; $i < strlen($technique); $i+=3) {
		$tstr = substr($technique,$i,3);
		if(isset($techinfo[$tstr])){
			if(empty($techwords)){
				$techwords .= '<A title="'.$techinfo[$tstr]['info'].'">'.$techinfo[$tstr]['name'].'</a>';
			}else{
				$techwords .= '<br><A title="'.$techinfo[$tstr]['info'].'">'.$techinfo[$tstr]['name'].'</a>';
			}
		}
	}
	if(empty($techwords)){$techwords = '无';}
	return $techwords;
}

function init_honourwords($honour){
	global $honourinfo;
	$hdata = '';
	for ($i = 0; $i < strlen($honour); $i+=2) {
		$hstr = substr($honour,$i,2);
		if(isset($honourinfo[$hstr])){
			if(empty($hdata)){
				$hdata .= '<A title="'.$honourinfo[$hstr]['info'].'">'.$honourinfo[$hstr]['name'].'</a>';
			}else{
				$hdata .= '<br><A title="'.$honourinfo[$hstr]['info'].'">'.$honourinfo[$hstr]['name'].'</a>';
			}
		}
	}
	return $hdata;
}

function init_displaydata(&$data){
	global $baseexp,$honourinfo,$infdata,$typeinfo;
	$data['display'] = Array();
	$disp = & $data['display'];
	$disp['upexp'] = round ( (2 * $data['lvl'] + 1) * $baseexp );
	$disp['iconImg'] = $data['type'] ? 'n_'.$data['icon'].'.gif' : $data['gd'].'_'.$data['icon'].'.gif';
	$disp['ardef'] = $data['arbe'] + $data['arhe'] + $data['arae'] + $data['arfe'];
	if(!empty($data['gamehonour'])){
		$disp['gamehonourinfo'] = $honourinfo[$data['gamehonour']]['name'];
	}elseif(!empty($data['gainhonour'])){
		$disp['gamehonourinfo'] = $honourinfo[substr($data['gainhonour'],-2,2)]['name'];
	}else{
		$disp['gamehonourinfo'] = $typeinfo[$data['type']];
	}
	if($data['inf']){
		$disp['infwords'] = '';
		foreach(str_split($data['inf'],1) as $val){
			$disp['infwords'] .= $infdata[$val]['short'];
		}
	}else{
		$disp['infwords'] = '无';
	}
	return;
}

function init_profile(&$pdata){
	global $wordsdata,$infinfo,$infdata,$idata,$infimg,$hpcolor,$spcolor,$newhpimg,$newspimg,$iteminfo,$itemspkinfo;
	extract($pdata);
	$idata = '';
	//$gamehonourinfo = $gamehonour ? $honourinfo[$gamehonour]['name'] : $typeinfo[0];
	
	if(strpos($inf,'h') !== false || strpos($inf,'b') !== false ||strpos($inf,'a') !== false ||strpos($inf,'f') !== false){
		//$idata = '<span class="red b">';
		$infimg .= '<img src="img/injured.gif" style="position:absolute;top:0;left:10;width:84;height:20">';
		if(strpos($inf,'h') !== false){
			$idata .= $infdata['h']['short'];
			$infimg .= '<img src="img/hurt.gif" style="position:absolute;top:0;left:121;width:37;height:37">';
		}
		if(strpos($inf,'a') !== false){
			$idata .= $infdata['a']['short'];
			$infimg .= '<img src="img/hurt.gif" style="position:absolute;top:17;left:102;width:37;height:37">';
		}
		if(strpos($inf,'b') !== false){
			$idata .= $infdata['b']['short'];
			$infimg .= '<img src="img/hurt.gif" style="position:absolute;top:43;left:121;width:37;height:37">';
		}
		if(strpos($inf,'f') !== false){
			$idata .= $infdata['f']['short'];
			$infimg .= '<img src="img/hurt.gif" style="position:absolute;top:111;left:121;width:37;height:37">';
		}
	} else {
		$infimg .= '<img src="img/injured2.gif" style="position:absolute;top:0;left:10;width:84;height:20">';
	}
	if(strpos($inf,'p') !== false) {
		$idata .= $infdata['p']['short'];
		$infimg .= '<img src="img/p.gif" style="position:absolute;top:20;left:4;width:98;height:20">';
	} else {
		$infimg .= '<img src="img/p2.gif" style="position:absolute;top:20;left:4;width:98;height:20">';
	}
	if(strpos($inf,'u') !== false) {
		$idata .= $infdata['u']['short'];
		$infimg .= '<img src="img/u.gif" style="position:absolute;top:40;left:11;width:81;height:20">';
	} else {
		$infimg .= '<img src="img/u2.gif" style="position:absolute;top:40;left:11;width:81;height:20">';
	}
	if(strpos($inf,'i') !== false) {
		$idata .= $infdata['i']['short'];
		$infimg .= '<img src="img/i.gif" style="position:absolute;top:60;left:13;width:77;height:20">';
	} else {
		$infimg .= '<img src="img/i2.gif" style="position:absolute;top:60;left:13;width:77;height:20">';
	}
	if(strpos($inf,'e') !== false) {
		$idata .= $infdata['e']['short'];
		$infimg .= '<img src="img/e.gif" style="position:absolute;top:80;left:2;width:101;height:20">';
	} else {
		$infimg .= '<img src="img/e2.gif" style="position:absolute;top:80;left:2;width:101;height:20">';
	}
	if(strpos($inf,'w') !== false) {
		$idata .= $infdata['w']['short'];
		$infimg .= '<img src="img/w.gif" style="position:absolute;top:100;left:3;width:100;height:20">';
	} else {
		$infimg .= '<img src="img/w2.gif" style="position:absolute;top:100;left:3;width:100;height:20">';
	}
	if(strpos($inf,'s') !== false) {
		$idata .= $infdata['s']['short'];
	}
	if(strpos($inf,'L') !== false) {
		$idata .= $infdata['L']['short'];
	}
	if(strpos($inf,'S') !== false) {
		$idata .= $infdata['S']['short'];
	}
	$hpcolor = 'aqua';
	if($hp <= 0 ){
		$infimg .= '<img src="img/dead.gif" style="position:absolute;top:120;left:6;width:94;height:40">';
		$hpcolor = 'red';
	} elseif($hp <= $mhp*0.2){
		$infimg .= '<img src="img/danger.gif" style="position:absolute;top:120;left:5;width:95;height:37">';
		$hpcolor = 'red';
	} elseif($hp <= $mhp*0.5){
		$infimg .= '<img src="img/caution.gif" style="position:absolute;top:120;left:5;width:95;height:36">';
		$hpcolor = 'yellow';
	} elseif($inf == ''){
		$infimg .= '<img src="img/fine.gif" style="position:absolute;top:120;left:12;width:81;height:38">';
	}
	
	if($sp <= $msp*0.2){
		$spcolor = 'grey';
	} elseif($sp <= $msp*0.5){
		$spcolor = 'yellow';
	} else {
		$spcolor = 'aqua';
	}
	
	if($hp<=0 || $mhp<=0){
		$pdata['display']['hpimgwidth'] = 0;
	}elseif($hp >= $mhp){
		$pdata['display']['hpimgwidth'] = 100;
	}else{
		$pdata['display']['hpimgwidth'] = floor(100*($hp/$mhp));
	}
	if($sp<=0 || $msp<=0){
		$pdata['display']['spimgwidth'] = 0;
	}elseif($sp >= $msp){
		$pdata['display']['spimgwidth'] = 100;
	}else{
		$pdata['display']['spimgwidth'] = floor(100*($sp/$msp));
	}

	return;
}

function init_battle(&$edata,$ismeet = 0){
	//global $edata['type'],$w_name,$w_gd,$w_sNo,$w_icon,$w_lvl,$w_rage,$edata['hp'],$edata['sp'],$edata['mhp'],$edata['msp'],$edata['wep'],$edata['wep']k,$edata['wepe'],$w_sNoinfo,$w_iconImg,$w_hpstate,$w_spstate,$w_ragestate,$w_wepestate,$w_isdead,
	global $hpinfo,$spinfo,$rageinfo,$wepeinfo,$fog,$typeinfo,$sexinfo,$infdata,$baseexp,$honourinfo,$poseinfo,$tacinfo;
	//,$w_exp,$w_upexp,$baseexp,$w_pose,$w_tactic,$w_inf,$w_infdata,$honourinfo;
	//extract($edata,EXTR_PREFIX_ALL,'w');
	$edata['display'] = Array();
	$disp = & $edata['display'];
	$disp['hpstate'] = $disp['spstate'] = $disp['ragestate'] = $disp['wepestate'] = $disp['gamehonourinfo'] = '';
	$w_hpstate = & $disp['hpstate'];
	$w_spstate = & $disp['spstate'];
	$w_ragestate = & $disp['ragestate'];
	$w_wepestate = & $disp['wepestate'];
	//$w_upexp = round(($w_lvl*2+1)*$baseexp);
	
	
	
	if($edata['hp'] <= 0) {
		$w_hpstate = "<span class=\"red\">$hpinfo[3]</span>";
		$w_spstate = "<span class=\"red\">$spinfo[3]</span>";
		$w_ragestate = "<span class=\"red\">$rageinfo[3]</span>";
	} else{
		if($edata['hp'] < $edata['mhp']*0.2) {
		$w_hpstate = "<span class=\"red\">$hpinfo[2]</span>";
		} elseif($edata['hp'] < $edata['mhp']*0.5) {
		$w_hpstate = "<span class=\"yellow\">$hpinfo[1]</span>";
		} else {
		$w_hpstate = "<span class=\"clan\">$hpinfo[0]</span>";
		}
		if($edata['sp'] < $edata['msp']*0.2) {
		$w_spstate = "$spinfo[2]";
		} elseif($edata['sp'] < $edata['msp']*0.5) {
		$w_spstate = "$spinfo[1]";
		} else {
		$w_spstate = "$spinfo[0]";
		}
		if($edata['rage'] >= 100) {
		$w_ragestate = "<span class=\"red\">$rageinfo[2]</span>";
		} elseif($edata['rage'] >= 30) {
		$w_ragestate = "<span class=\"yellow\">$rageinfo[1]</span>";
		} else {
		$w_ragestate = "$rageinfo[0]";
		}
	}
	
	if($edata['wepe'] >= 400) {
		$w_wepestate = "$wepeinfo[3]";
	} elseif($edata['wepe'] >= 200) {
		$w_wepestate = "$wepeinfo[2]";
	} elseif($edata['wepe'] >= 60) {
		$w_wepestate = "$wepeinfo[1]";
	} else {
		$w_wepestate = "$wepeinfo[0]";
	}
	
	if(!$fog||$ismeet) {
		$disp['name'] = $edata['name'];$disp['wep'] = $edata['wep'];
		$disp['pose'] = $poseinfo[$edata['pose']];$disp['tactic'] = $tacinfo[$edata['tactic']];
		$disp['lvl'] = $edata['lvl'];$disp['wepk'] = get_itmkwords($edata['wepk']);
		$disp['gamehonourinfo'] = $edata['gamehonour'] ? $honourinfo[$edata['gamehonour']]['name'] : $typeinfo[$edata['type']];
		$disp['sNoinfo'] = $edata['type'] == 0 ? $sexinfo[$edata['gd']].$edata['sNo'].'号' : $sexinfo[$edata['gd']];
	  //$w_i = $edata['type'] > 0 && $edata['type'] != 91 ? 'n' : $edata['gd'];
		$disp['iconImg'] = $edata['type'] > 0 && $edata['type'] != 91 ? 'n_'.$edata['icon'].'.gif' : $edata['gd'].'_'.$edata['icon'].'.gif';
		if($edata['inf']) {
			$disp['infdata'] = '';
			foreach ($infdata as $inf_ky => $inf_val) {
				if(strpos($edata['inf'],$inf_ky) !== false) {
					$disp['infdata'] .= $inf_val['short'];
				}
			}
		} else {
			$disp['infdata'] = '无';
		}
	} else {
		$disp['sNoinfo'] = '？？？';
		$disp['iconImg'] = 'question.gif';
		$disp['name'] = '？？？';
		$disp['gamehonourinfo'] = '？？？';
		$disp['wep'] = '？？？';
		$disp['infdata'] = '？？？';
		$disp['pose'] = '？？？';
		$disp['tactic'] = '？？？';
		$disp['lvl'] = '？';
		$w_hpstate = '？？？';
		$w_spstate = '？？？';
		$w_ragestate = '？？？';
		$w_wepestate = '？？？';
		$disp['wepk'] = '？？？';
	}
	return;
}

?>