<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}



function init_playerdata(){
	global $lvl,$baseexp,$exp,$gd,$icon,$arbe,$arhe,$arae,$arfe,$weather,$fog,$weps,$arbs,$log,$upexp,$lvlupexp,$iconImg,$ardef;

	$upexp = round(($lvl*$baseexp)+(($lvl+1)*$baseexp));
	$lvlupexp = $upexp - $exp;
	$iconImg = $gd.'_'.$icon.'.gif';
	$ardef = $arbe + $arhe + $arae + $arfe;
	if(($weather == 8)||($weather == 9)||($weather == 12)) {
		$fog = true;
	}

	if(!$weps) {
		global $nowep,$nosta,$wep,$wepk,$wepsk,$wepe;
		$wep = $nowep;$wepk = 'WN';$wepsk = '';
		$wepe = 0; $weps = $nosta;
	}
	if(!$arbs) {
		global $noarb,$nosta,$arb,$arbk,$arbsk,$arbe;
		$arb = $noarb;$arbk = 'DB'; $arbsk = '';
		$arbe = 0; $arbs = $nosta;
	}


}

function init_profile(){
	global $inf,$infinfo,$hp,$mhp,$sp,$msp,$infdata,$infimg,$newhpimg,$newspimg,$ardef,$arbe,$arhe,$arae,$arfe;
	$ardef = $arbe + $arhe + $arae + $arfe;
	if($inf) {
		$infdata = '<span class="red b">';
		if(strpos($inf,'h') !== false){
			$infdata .= $infinfo['h'];
			$infimg .= '<img src="img/h.gif" style="position:absolute;top:0;left:4;width:146;height:19">';
		}
		if(strpos($inf,'a') !== false){
			$infdata .= $infinfo['a'];
			$infimg .= '<img src="img/a.gif" style="position:absolute;top:20;left:4;width:122;height:20">';
		}
		if(strpos($inf,'b') !== false){
			$infdata .= $infinfo['b'];
			$infimg .= '<img src="img/b.gif" style="position:absolute;top:40;left:4;width:146;height:20">';
		}
		if(strpos($inf,'f') !== false){
			$infdata .= $infinfo['f'];
			$infimg .= '<img src="img/f.gif" style="position:absolute;top:60;left:4;width:139;height:57">';
		}
		$infdata .= '</span>';
		if(strpos($inf,'p') !== false) {
			$infdata .= "<span class=\"purple b\">{$infinfo['p']}</span>";
			$infimg .= '<img src="img/p.gif" style="position:absolute;top:80;left:1;width:95;height:20">';
		}
	} else {
		$infdata = '';
		//$infimg = '<img src="img/ok.gif" style="position:absolute;top:120;left:18;">';
	}


	if($hp <= 0 ){
		//$hstate = 'dead';
		$infimg .= '<img src="img/dead.gif" style="position:absolute;top:120;left:1;width:94;height:40">';
	//} elseif(strpos($inf,'p') !== false) {
		//$infdata .= "<span class=\"purple b\">{$infinfo['p']}</span>";
		//$infimg .= '<img src="img/p.gif" style="position:absolute;top:80;left:1;">';
		//$hstate = 'poison';
	} elseif($hp <= $mhp*0.2){
		//$hstate = 'fine';
		$infimg .= '<img src="img/danger.gif" style="position:absolute;top:120;left:0;width:95;height:37">';
	} elseif($hp <= $mhp*0.5){
		//$hstate = 'caution';
		$infimg .= '<img src="img/caution.gif" style="position:absolute;top:120;left:0;width:95;height:36">';
	} elseif($inf == ''){
		//$hstate = 'danger';
		$infimg .= '<img src="img/fine.gif" style="position:absolute;top:120;left:8;width:81;height:38">';
	}
	
	//$hppre = floor(($hp/$mhp)*100);
	$newhppre = 5+floor(151*(1-$hp/$mhp));
	//$hpimg = '<img src="img/red.gif" width="'.$hppre.'%" height="10" border="0" align="left">';
	$newhpimg = '<img src="img/red2.gif" style="position:absolute; clip:rect('.$newhppre.'px,55px,160px,0px);">';
	//$sppre = floor(($sp/$msp)*100);
	$newsppre = 5+floor(151*(1-$sp/$msp));
	//$spimg = '<img src="img/yellow.gif" width="'.$sppre.'%" height="10" border="0" align="left">';
	$newspimg = '<img src="img/yellow2.gif" style="position:absolute; clip:rect('.$newsppre.'px,55px,160px,0px);">';

	return;
}

function init_battle($ismeet = 0){
	global $w_type,$w_name,$w_gd,$w_sNo,$w_icon,$w_lvl,$w_rage,$w_hp,$w_sp,$w_mhp,$w_msp,$w_wep,$w_wepk,$w_wepe,$w_sNoinfo,$w_iconImg,$w_hpstate,$w_spstate,$w_ragestate,$w_wepestate,$w_isdead,$hpinfo,$spinfo,$rageinfo,$wepeinfo,$fog,$typeinfo,$sexinfo,$infinfo,$w_exp,$w_upexp,$baseexp,$w_pose,$w_tactic,$w_inf,$w_infdata;
	$w_upexp = round(($w_lvl*$baseexp)+(($w_lvl+1)*$baseexp));
	
	if($w_hp <= 0) {
		$w_hpstate = "<span class=\"red\">$hpinfo[3]</span>";
		$w_spstate = "<span class=\"red\">$spinfo[3]</span>";
		$w_ragestate = "<span class=\"red\">$rageinfo[3]</span>";
		$w_isdead = true;
	} else{
		if($w_hp < $w_mhp*0.1) {
		$w_hpstate = "<span class=\"red\">$hpinfo[2]</span>";
		} elseif($w_hp < $w_mhp*0.4) {
		$w_hpstate = "<span class=\"yellow\">$hpinfo[1]</span>";
		} else {
		$w_hpstate = "<span class=\"clan\">$hpinfo[0]</span>";
		}
		if($w_sp < $w_msp*0.25) {
		$w_spstate = "<span class=\"red\">$spinfo[2]</span>";
		} elseif($w_sp < $w_msp*0.5) {
		$w_spstate = "<span class=\"yellow\">$spinfo[1]</span>";
		} else {
		$w_spstate = "<span class=\"clan\">$spinfo[0]</span>";
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
		$w_wepestate = "<span class=\"red\">$wepeinfo[3]</span>";
	} elseif($w_wepe >= 200) {
		$w_wepestate = "<span class=\"yellow\">$wepeinfo[2]</span>";
	} elseif($w_wepe >= 60) {
		$w_wepestate = "<span class=\"clan\">$wepeinfo[1]</span>";
	} else {
		$w_wepestate = "$wepeinfo[0]";
	}
	
	if(!$fog||$ismeet) {
		$w_sNoinfo = "$typeinfo[$w_type]($sexinfo[$w_gd] $w_sNo 号)";
	  $w_i = $w_type > 0 ? 'n' : $w_gd;
		$w_iconImg = $w_i.'_'.$w_icon.'.gif';
		$w_infdata = "$infinfo[$w_inf]";
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


function w_save($id){
	global $db,$tablepre,$w_name,$w_pass,$w_type,$w_endtime,$w_gd,$w_sNo,$w_icon,$w_club,$w_hp,$w_mhp,$w_sp,$w_msp,$w_att,$w_def,$w_pls,$w_lvl,$w_exp,$w_money,$w_bid,$w_inf,$w_rage,$w_pose,$w_tactic,$w_killnum,$w_state,$w_wp,$w_wk,$w_wg,$w_wc,$w_wd,$w_teamID,$w_teamPass,$w_wep,$w_wepk,$w_wepe,$w_weps,$w_arb,$w_arbk,$w_arbe,$w_arbs,$w_arh,$w_arhk,$w_arhe,$w_arhs,$w_ara,$w_arak,$w_arae,$w_aras,$w_arf,$w_arfk,$w_arfe,$w_arfs,$w_art,$w_artk,$w_arte,$w_arts,$w_itm0,$w_itmk0,$w_itme0,$w_itms0,$w_itm1,$w_itmk1,$w_itme1,$w_itms1,$w_itm2,$w_itmk2,$w_itme2,$w_itms2,$w_itm3,$w_itmk3,$w_itme3,$w_itms3,$w_itm4,$w_itmk4,$w_itme4,$w_itms4,$w_itm5,$w_itmk5,$w_itme5,$w_itms5,$w_wepsk,$w_arbsk,$w_arhsk,$w_arask,$w_arfsk,$w_artsk,$w_itmsk0,$w_itmsk1,$w_itmsk2,$w_itmsk3,$w_itmsk4,$w_itmsk5;
	
	$db->query("UPDATE {$tablepre}players SET name='$w_name',pass='$w_pass',type='$w_type',endtime='$w_endtime',gd='$w_gd',sNo='$w_sNo',icon='$w_icon',club='$w_club',hp='$w_hp',mhp='$w_mhp',sp='$w_sp',msp='$w_msp',att='$w_att',def='$w_def',pls='$w_pls',lvl='$w_lvl',exp='$w_exp',money='$w_money',bid='$w_bid',inf='$w_inf',rage='$w_rage',pose='$w_pose',tactic='$w_tactic',state='$w_state',killnum='$w_killnum',wp='$w_wp',wk='$w_wk',wg='$w_wg',wc='$w_wc',wd='$w_wd',teamID='$w_teamID',teamPass='$w_teamPass',wep='$w_wep',wepk='$w_wepk',wepe='$w_wepe',weps='$w_weps',wepsk='$w_wepsk',arb='$w_arb',arbk='$w_arbk',arbe='$w_arbe',arbs='$w_arbs',arbsk='$w_arbsk',arh='$w_arh',arhk='$w_arhk',arhe='$w_arhe',arhs='$w_arhs',arhsk='$w_arhsk',ara='$w_ara',arak='$w_arak',arae='$w_arae',aras='$w_aras',arask='$w_arask',arf='$w_arf',arfk='$w_arfk',arfe='$w_arfe',arfs='$w_arfs',arfsk='$w_arfsk',art='$w_art',artk='$w_artk',arte='$w_arte',arts='$w_arts',artsk='$w_artsk',itm0='$w_itm0',itmk0='$w_itmk0',itme0='$w_itme0',itms0='$w_itms0',itmsk0='$w_itmsk0',itm1='$w_itm1',itmk1='$w_itmk1',itme1='$w_itme1',itms1='$w_itms1',itmsk1='$w_itmsk1',itm2='$w_itm2',itmk2='$w_itmk2',itme2='$w_itme2',itms2='$w_itms2',itmsk2='$w_itmsk2',itm3='$w_itm3',itmk3='$w_itmk3',itme3='$w_itme3',itms3='$w_itms3',itmsk3='$w_itmsk3',itm4='$w_itm4',itmk4='$w_itmk4',itme4='$w_itme4',itms4='$w_itms4',itmsk4='$w_itmsk4',itm5='$w_itm5',itmk5='$w_itmk5',itme5='$w_itme5',itms5='$w_itms5',itmsk5='$w_itmsk5' WHERE pid='$id'");

	return ;
}

function w_save2(&$data){
	global $db,$tablepre;
	if(isset($data)){
		extract($data,EXTR_PREFIX_ALL,'w');
		$db->query("UPDATE {$tablepre}players SET name='$w_name',pass='$w_pass',type='$w_type',endtime='$w_endtime',gd='$w_gd',sNo='$w_sNo',icon='$w_icon',club='$w_club',hp='$w_hp',mhp='$w_mhp',sp='$w_sp',msp='$w_msp',att='$w_att',def='$w_def',pls='$w_pls',lvl='$w_lvl',exp='$w_exp',money='$w_money',bid='$w_bid',inf='$w_inf',rage='$w_rage',pose='$w_pose',tactic='$w_tactic',state='$w_state',killnum='$w_killnum',wp='$w_wp',wk='$w_wk',wg='$w_wg',wc='$w_wc',wd='$w_wd',teamID='$w_teamID',teamPass='$w_teamPass',wep='$w_wep',wepk='$w_wepk',wepe='$w_wepe',weps='$w_weps',wepsk='$w_wepsk',arb='$w_arb',arbk='$w_arbk',arbe='$w_arbe',arbs='$w_arbs',arbsk='$w_arbsk',arh='$w_arh',arhk='$w_arhk',arhe='$w_arhe',arhs='$w_arhs',arhsk='$w_arhsk',ara='$w_ara',arak='$w_arak',arae='$w_arae',aras='$w_aras',arask='$w_arask',arf='$w_arf',arfk='$w_arfk',arfe='$w_arfe',arfs='$w_arfs',arfsk='$w_arfsk',art='$w_art',artk='$w_artk',arte='$w_arte',arts='$w_arts',artsk='$w_artsk',itm0='$w_itm0',itmk0='$w_itmk0',itme0='$w_itme0',itms0='$w_itms0',itmsk0='$w_itmsk0',itm1='$w_itm1',itmk1='$w_itmk1',itme1='$w_itme1',itms1='$w_itms1',itmsk1='$w_itmsk1',itm2='$w_itm2',itmk2='$w_itmk2',itme2='$w_itme2',itms2='$w_itms2',itmsk2='$w_itmsk2',itm3='$w_itm3',itmk3='$w_itmk3',itme3='$w_itme3',itms3='$w_itms3',itmsk3='$w_itmsk3',itm4='$w_itm4',itmk4='$w_itmk4',itme4='$w_itme4',itms4='$w_itms4',itmsk4='$w_itmsk4',itm5='$w_itm5',itmk5='$w_itmk5',itme5='$w_itme5',itms5='$w_itms5',itmsk5='$w_itmsk5' WHERE pid='$w_pid'");
	}
	return;

}



?>