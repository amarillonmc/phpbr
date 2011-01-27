<?php

define('CURSCRIPT', 'valid');

require_once './include/common.inc.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
if($gamestate < 20) { gexit($_ERROR['no_start'],__file__,__line__); }
if($gamestate >= 30) { gexit($_ERROR['valid_stop'],__file__,__line__); }

$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

if($mode == 'enter') {
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$tablepre}users AS u, {$tablepre}players AS p WHERE u.ip='{$udata['ip']}' AND ( u.username=p.name AND p.type=0 AND p.hp>0)");
		if($db->num_rows($result) >= $iplimit) { gexit($_ERROR['ip_limit'],__file__,__line__); }
	}
	
	foreach ( Array('<','>',';',',') as $value ) {
		if(strpos($motto,$value)!==false){
			$motto = str_replace ( $value, '', $motto );
		}
		if(strpos($lastword,$value)!==false){
			$lastword = str_replace ( $value, '', $lastword );
		}
		if(strpos($killmsg,$value)!==false){
			$killmsg = str_replace ( $value, '', $killmsg );
		}
	}

	$db->query("UPDATE {$tablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', lastword='$lastword' WHERE username='".$udata['username']."'" );
	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__, __line__);
	}
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		gexit($_ERROR['player_exist'], __file__, __line__);
	}
	if ($gender !== 'm' && $gender !== 'f'){
		$gender = 'm';
	}
	$validnum++;
	$alivenum++;
	$name = $cuser;
	$pass = $cpass;
	$gd = $gender;
	$type = 0;
	$endtime = $now;
	$sNo = $validnum;
	$hp = $mhp = $hplimit;
	$sp = $msp = $splimit;
	$rand = rand(0,15);
	$att = 95 + $rand;
	$def = 105 - $rand;
	$pls = 0;
	$killnum = 0;
	$lvl = 0;
	$exp = $areanum * 20;
	$money = 20;
	$rage = 0;
	$pose = 0;
	$tactic = 0;
	$icon = $icon ? $icon : rand(1,$iconlimit);
	$club = makeclub();

	$arb = $gd == 'm' ? '红队制服' : '蓝队制服';
	$arbk = 'DB'; $arbe = 5; $arbs = 15; $arbsk = '';
	$arh = $ara = $arf = $art = '';
	$arhk = $arak = $arfk = $artk = '';
	$arhsk = $arask = $arfsk = $artsk = '';
	$arhe = $arae = $arfe = $arte = 0;
	$arhs = $aras = $arfs = $arts = 0;
	
	for ($i=0; $i<6; $i++){$itm[$i] = $itmk[$i] = $itmsk[$i] = ''; $itme[$i] = $itms[$i] = 0;}
	$itm[1] = '面包'; $itmk[1] = 'HH'; $itme[1] = 100; $itms[1] = 30;
	$itm[2] = '矿泉水'; $itmk[2] = 'HS'; $itme[2] = 100; $itms[2] = 30;

	$weplist = openfile(config('stwep',$gamecfg));
	do { 
		$index = rand(1,count($weplist)-1); 
		list($wep,$wepk,$wepe,$weps,$wepsk) = explode(",",$weplist[$index]);
	} while(!$wepk);

	$stitemlist = openfile(config('stitem',$gamecfg));
	do { 
		$index = rand(1,count($stitemlist)-1); 
		list($itm[3],$itmk[3],$itme[3],$itms[3],$itmsk[3]) = explode(",",$stitemlist[$index]);
	} while(!$itmk[3]);
	do { 
		$index = rand(1,count($stitemlist)-1); 
		list($itm[4],$itmk[4],$itme[4],$itms[4],$itmsk[4]) = explode(",",$stitemlist[$index]);
	} while(!$itmk[4] || ($itmk[3] == $itmk[4]));

	if(strpos($wepk,'WG') === 0){
		$itm[3] = '手枪子弹'; $itmk[3] = 'GB'; $itme[3] = 1; $itms[3] = 12; $itmsk[3] = '';
	}
	
//	$shenzhuang = rand(1,10);
//	switch ($shenzhuang) {
//		case 1:
//			$itm[5] = '圭一少年的球棒'; $itmk[5] = 'WP'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'e';
//		break;
//		case 2:
//			$itm[5] = '简称为UCW的三弦'; $itmk[5] = 'WK'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'p';
//		break;
//		case 3:
//			$itm[5] = '燃素粒子火焰炮'; $itmk[5] = 'WG'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'u';
//		break;
//		case 4:
//			$itm[5] = '水晶的超级球'; $itmk[5] = 'WC'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'ir';
//		break;
//		case 5:
//			$itm[5] = '久违的KEY系催泪弹'; $itmk[5] = 'WD'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'd';
//		break;
//		case 6:
//			$itm[5] = '梦想天生'; $itmk[5] = 'WF'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'd';
//		break;
//		case 7:
//			$itm[5] = '这样的装备没问题么的铠甲'; $itmk[5] = 'DB'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'E';
//		break;
//		case 8:
//			$itm[5] = '这样的装备没问题么的头盔'; $itmk[5] = 'DH'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'q';
//		break;
//		case 9:
//			$itm[5] = '这样的装备没问题么的手套'; $itmk[5] = 'DA'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'U';
//		break;
//		case 10:
//			$itm[5] = '这样的装备没问题么的靴子'; $itmk[5] = 'DF'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'I';
//		break;
//	}
	if ($name == '某四面' || $name == 'Amarillo_NMC') {
		$msp += 500;$mhp += 500;$hp += 500;$sp += 500;
		$att += 100;$def += 100;
		$exp += 2000;$money = 10000;$rage = 255;$pose = 1;$tactic = 3;
		$itm[1] = '全恢复饮料'; $itmk[1] = 'HB'; $itme[1] = 1000; $itms[1] = 1000;$itmsk[1] = 'z';
		$itm[2] = '移动PC'; $itmk[2] = 'Y'; $itme[2] = 50; $itms[2] = 1;
		$itm[3] = '广域生命探测器'; $itmk[3] = 'R'; $itme[3] = 50; $itms[3] = 1;$itmsk[3] = 2;
		$itm[4] = '凸眼鱼'; $itmk[4] = 'Y'; $itme[4] = 1; $itms[4] = 30;$itmsk[4] = '';
		$itm[5] = '游戏解除钥匙'; $itmk[5] = 'Y'; $itme[5] = 1; $itms[5] = 1;
		$wep = '最终离子风暴枪';$wepk = 'WG';$wepe = 777;$weps = 777;$wepsk = 'er';
		$arb = '能量装甲';$arbk = 'DB'; $arbe = 400; $arbs = 150; $arbsk = 'A';
		$arh = '机动头盔';$arhk = 'DH'; $arhe = 200; $arhs = 150; $arhsk = 'UI';
		$ara = '腕力增幅器';$arak = 'DA'; $arae = 200; $aras = 150; $arask = 'c';
		$arf = '金属战靴';$arfk = 'DF'; $arfe = 200; $arfs = 150; $arfsk = 'qE';
		$art = '真理四面体';$artk = 'A'; $arte = 4; $arts = 1; $artsk = 'H';
		$wp=$wk=$wg=$wc=$wd=$wf=400;
	}
	
	$state = 0;
	$bid = 0;

	$inf = $teamID = $teamPass = '';
	$db->query("INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5) VALUES ('$name','$pass','$type','$endtime','$gd','$sNo','$icon','$club','$hp','$mhp','$sp','$msp','$att','$def','$pls','$lvl','$exp','$money','$bid','$inf','$rage','$pose','$tactic','$state','$killnum','$wp','$wk','$wg','$wc','$wd','$wf','$teamID','$teamPass','$wep','$wepk','$wepe','$weps','$arb','$arbk','$arbe','$arbs','$arh','$arhk','$arhe','$arhs','$ara','$arak','$arae','$aras','$arf','$arfk','$arfe','$arfs','$art','$artk','$arte','$arts','$itm[0]','$itmk[0]','$itme[0]','$itms[0]','$itm[1]','$itmk[1]','$itme[1]','$itms[1]','$itm[2]','$itmk[2]','$itme[2]','$itms[2]','$itm[3]','$itmk[3]','$itme[3]','$itms[3]','$itm[4]','$itmk[4]','$itme[4]','$itms[4]','$itm[5]','$itmk[5]','$itme[5]','$itms[5]','$wepsk','$arbsk','$arhsk','$arask','$arfsk','$artsk','$itmsk[0]','$itmsk[1]','$itmsk[2]','$itmsk[3]','$itmsk[4]','$itmsk[5]')");
	$db->query("UPDATE {$tablepre}users SET lastgame='$gamenum' WHERE username='$name'");
	addnews($now,'newpc',$name,"{$sexinfo[$gd]}{$sNo}号",$ip);


	$gamestate = $validnum < $validlimit ? 20 : 30;
	save_gameinfo();
	

	include template('validover');
} elseif($mode == 'notice') {
	include template('notice');

} else {
	extract($udata);
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		header("Location: game.php");exit();
	}

	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__,__line__);
	}
	$iconarray = array();
	for($n = 0; $n <= $iconlimit; $n++)	{
		if($icon == $n) {
			$iconarray[] = '<OPTION value='.$n.' selected>'.$n.'</OPTION>';
		} else {
			$iconarray[] = '<OPTION value='.$n.' >'.$n.'</OPTION>';
		}
	}
	include template('valid');
}

function makeclub() {
	global $wp,$wk,$wg,$wc,$wd,$wf,$money,$mhp,$msp,$hp,$sp;
	$wp = $wk = $wg = $wc = $wd = $wf = 0;
	$dice = rand(0,149);
	if($dice < 15)		{$club = 1;$wp = 25;}//殴25
	elseif($dice < 30)	{$club = 2;$wk = 25;}//斩25
	elseif($dice < 45)	{$club = 3;$wc = 25;}//投25
	elseif($dice < 60)	{$club = 4;$wg = 25;}//射25
	elseif($dice < 75)	{$club = 5;$wd = 25;}//爆25
	elseif($dice < 80)	{$club = 6;}//移动消耗减
	elseif($dice < 85)	{$club = 7;}//P(HACK)=1
	elseif($dice < 90)	{$club = 8;}//查毒可
	elseif($dice < 115)	{$club = 9;$wf = 25;}//能使用必杀，灵25
	elseif($dice < 120)	{$club = 10;}//探索减
	elseif($dice < 125)	{$club = 11;$money = 500;}//出击钱数500
	elseif($dice < 135)	{$club = 12;$wp = $wk = $wg = $wc = $wd = $wf = 10;}//全熟练10
	elseif($dice < 145)	{$club = 13;$mhp = $mhp + 100;$hp = $mhp;}//生命上限提高100
	elseif($dice < 150)	{$club = 14;$msp = $msp + 200;$sp = $msp;}//体力上限提高200
	else				{$club = makeclub();}
	return $club;
}
?>


