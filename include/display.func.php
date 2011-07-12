<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}
function get_itmkwords($itmk){
	global $iteminfo,$noitmk;
	$ikwords = $noitmk;
	foreach($iteminfo as $ikey => $ival){
		if(strpos($itmk,$ikey)===0){
			$ikwords = $ival;
			break;
		}
	}
	return $ikwords;
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
	if(strpos($itmk,'WG')===0){
		$cst = get_cassette($itmk);
		if($cst > 0){
			$cstwords = '弹夹'.$cst.'发';
			array_unshift($isklist,$cstwords);
		}
	}
	$wepat = get_wephittimes($itmk);
	if($wepat>1){
		$atwords = $wepat.'回攻击';	
		array_unshift($isklist,$atwords);
	}
	$iskwords = '';
	foreach($isklist as $val){
		if(!$iskwords){
			$iskwords = $val;
		}elseif($profile){
			$iskwords .= '<br>'.$val;
		}else{
			$iskwords .= '+'.$val;
		}		
	}
	if(!$iskwords){$iskwords = $nospk;}
	return $iskwords;
}


function init_itemwords(&$data,$prf = ''){
	$rdata = Array();
	foreach (Array('wep','arb','arh','ara','arf','art') as $val) {
		$rdata[$prf.$val.'k_words'] = get_itmkwords($data[$val.'k']);
		if($prf){
			$rdata[$prf.$val.'sk_words'] = get_itmskwords($data[$val.'k'],$data[$val.'sk']);
		} else {
			$rdata[$prf.$val.'sk_words'] = get_itmskwords($data[$val.'k'],$data[$val.'sk'],1);
		}
	}
	for($i = 0;$i<=6;$i++){
		$rdata[$prf.'itmk'.$i.'_words'] = get_itmkwords($data['itmk'.$i]);
		if($prf || $i == 0){
			$rdata[$prf.'itmsk'.$i.'_words'] = get_itmskwords($data['itmk'.$i],$data['itmsk'.$i]);
		} else {
			$rdata[$prf.'itmsk'.$i.'_words'] = get_itmskwords($data['itmk'.$i],$data['itmsk'.$i],1);
		}
	}
	foreach($rdata as $key => $val){
		$data['display'][$key] = $GLOBALS[$key] = $val;//回头删掉这个
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
	$disp['gamehonourinfo'] = $data['gamehonour'] ? $honourinfo[$data['gamehonour']]['name'] : $typeinfo[$data['type']];
	
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

function init_profile($pdata){
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
	$hpcolor = 'clan';
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
		$spcolor = 'clan';
	}
	
	$newhppre = 5+floor(151*(1-$hp/$mhp));
	$newhpimg = '<img src="img/red2.gif" style="position:absolute; clip:rect('.$newhppre.'px,55px,160px,0px);">';
	$newsppre = 5+floor(151*(1-$sp/$msp));
	$newspimg = '<img src="img/yellow2.gif" style="position:absolute; clip:rect('.$newsppre.'px,55px,160px,0px);">';

	return;
}

function init_battle($edata,$ismeet = 0){
	global $w_type,$w_name,$w_gd,$w_sNo,$w_icon,$w_lvl,$w_rage,$w_hp,$w_sp,$w_mhp,$w_msp,$w_wep,$w_wepk,$w_wepe,$w_sNoinfo,$w_iconImg,$w_hpstate,$w_spstate,$w_ragestate,$w_wepestate,$w_isdead,$hpinfo,$spinfo,$rageinfo,$wepeinfo,$fog,$typeinfo,$sexinfo,$infdata,$w_exp,$w_upexp,$baseexp,$w_pose,$w_tactic,$w_inf,$w_infdata,$honourinfo;
	extract($edata,EXTR_PREFIX_ALL,'w');
	
	$w_upexp = round(($w_lvl*2+1)*$baseexp);
	
	
	
	if($w_hp <= 0) {
		$w_hpstate = "<span class=\"red\">$hpinfo[3]</span>";
		$w_spstate = "<span class=\"red\">$spinfo[3]</span>";
		$w_ragestate = "<span class=\"red\">$rageinfo[3]</span>";
		$w_isdead = true;
	} else{
		if($w_hp < $w_mhp*0.2) {
		$w_hpstate = "<span class=\"red\">$hpinfo[2]</span>";
		} elseif($w_hp < $w_mhp*0.5) {
		$w_hpstate = "<span class=\"yellow\">$hpinfo[1]</span>";
		} else {
		$w_hpstate = "<span class=\"clan\">$hpinfo[0]</span>";
		}
		if($w_sp < $w_msp*0.2) {
		$w_spstate = "$spinfo[2]";
		} elseif($w_sp < $w_msp*0.5) {
		$w_spstate = "$spinfo[1]";
		} else {
		$w_spstate = "$spinfo[0]";
		}
		if($w_rage >= 100) {
		$w_ragestate = "<span class=\"red\">$rageinfo[2]</span>";
		} elseif($w_rage >= 30) {
		$w_ragestate = "<span class=\"yellow\">$rageinfo[1]</span>";
		} else {
		$w_ragestate = "$rageinfo[0]";
		}
	}
	
	if($w_wepe >= 400) {
		$w_wepestate = "$wepeinfo[3]";
	} elseif($w_wepe >= 200) {
		$w_wepestate = "$wepeinfo[2]";
	} elseif($w_wepe >= 60) {
		$w_wepestate = "$wepeinfo[1]";
	} else {
		$w_wepestate = "$wepeinfo[0]";
	}
	
	if(!$fog||$ismeet) {
		$w_gamehonourinfo = $w_gamehonour ? $honourinfo[$w_gamehonour]['name'] : $typeinfo[$w_type];
		$w_sNoinfo = $w_type == 0 ? "$w_gamehonourinfo({$sexinfo[$w_gd]}{$w_sNo}号)" : "$w_gamehonourinfo({$sexinfo[$w_gd]})";
	  $w_i = $w_type > 0 && $w_type != 91 ? 'n' : $w_gd;
		$w_iconImg = $w_i.'_'.$w_icon.'.gif';
		if($w_inf) {
			$w_infdata = '';
			foreach ($infdata as $inf_ky => $inf_val) {
				if(strpos($w_inf,$inf_ky) !== false) {
					$w_infdata .= $inf_val['short'];
				}
			}
		} else {
			$w_infdata = '';
		}
	} else {
		$w_sNoinfo = '？？？';
		$w_iconImg = 'question.gif';
		$w_name = '？？？';
		$w_wep = '？？？';
		$w_infdata = '？？？';
		$w_pose = -1;
		$w_tactic = -1;
		$w_lvl = '？';
		$w_hpstate = '？？？';
		$w_spstate = '？？？';
		$w_ragestate = '？？？';
		$w_wepestate = '？？？';
		$w_wepk = '';
	}
	return;
}

?>