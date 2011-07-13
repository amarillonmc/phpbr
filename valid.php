<?php

define('CURSCRIPT', 'valid');

require './include/common.inc.php';
require './include/user.func.php';

if($gamestate < 20) { gexit($_ERROR['no_start'],__file__,__line__); }
if($gamestate >= 30) { gexit($_ERROR['valid_stop'],__file__,__line__); }
if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }


$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }
$onlineip = real_ip();
if($mode == 'enter') {
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$tablepre}users AS u, {$tablepre}players AS p WHERE u.ip='$onlineip' AND ( u.username=p.name AND p.type=0)");
		if($db->num_rows($result) >= $iplimit) { gexit($_ERROR['ip_limit'],__file__,__line__); }
	}	


	//$db->query("UPDATE {$tablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', lastword='$lastword' WHERE username='".$udata['username']."'" );
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
	gsetcookie('ctrl','pl');
	$validnum++;
	$alivenum++;
	$pl = Array();
	$pl['type'] = 0;
	$pl['name'] = $cuser;
	$pl['pass'] = $cpass;
	$pl['company'] = 0;
	$pl['gd'] = $gender;
	$pl['sNo'] = $validnum;
	$pl['icon'] = $icon ? $icon : rand(1,$iconlimit);
	
	if(!$gamehonour || !isset($honourinfo[$gamehonour])){
		$pl['gamehonour'] = $gamehonourinfo = '';
	}else{
		$pl['gamehonour'] = $gamehonour;
		$gamehonourinfo = $honourinfo[$gamehonour]['name'].' ';
	}
	$pl['gainhonour'] = $pl['technique'] = '';
	$pl['techlevel'] = $pl['story'] = 0;
	$pl['lastcmd'] = $pl['lasteff'] = $now;
	$pl['laststn'] = $pl['lastslp'] = $pl['cdmsec'] = $pl['cdtime']= 0;
	$pl['hp'] = $pl['mhp'] = $hplimit;
	$pl['sp'] = $pl['msp'] = $splimit;
	$rand = rand(0,15);
	$pl['att'] = 95 + $rand;
	$pl['def'] = 105 - $rand;
	$pl['pls'] = $pl['lvl'] = 0;
	$pl['exp'] = $areanum * 20;
	$pl['money'] = 20;
	$pl['bid'] = 0;
	$pl['inf'] = '';
	$pl['rage'] = $pl['pose'] = $pl['tactic'] = $pl['killnum'] = 0;
	$pl['state'] = 0;
	$pl['club'] = makeclub(); 
	$pl['teamID'] = $pl['teamPass'] = '';

	$pl['arb'] = $pl['gd'] == 'm' ? '男生校服' : '女生校服';
	$pl['arbk'] = 'DB'; $pl['arbe'] = 5; $pl['arbs'] = 15; $pl['arbsk'] = ''; $pl['arbnp'] = 0;
	$pl['arh'] = $pl['ara'] = $pl['arf'] = $pl['art'] = '';
	$pl['arhk'] = $pl['arak'] = $pl['arfk'] = $pl['artk'] = '';
	$pl['arhsk'] = $pl['arask'] = $pl['arfsk'] = $pl['artsk'] = '';
	$pl['arhe'] = $pl['arae'] = $pl['arfe'] = $pl['arte'] = 0;
	$pl['arhs'] = $pl['aras'] = $pl['arfs'] = $pl['arts'] = 0;
	$pl['arhnp'] = $pl['aranp'] = $pl['arfnp'] = $pl['artnp'] = 0;
	
	for ($i=0; $i<=6; $i++){
		$pl['itm'.$i] = $pl['itmk'.$i] = $pl['itmsk'.$i] = ''; $pl['itme'.$i] = $pl['itms'.$i] = $pl['itmnp'.$i] = 0;
	}
	$pl['itm1'] = '面包'; $pl['itmk1'] = 'HH'; $pl['itme1'] = 100; $pl['itms1'] = 30;
	$pl['itm2'] = '矿泉水'; $pl['itmk2'] = 'HS'; $pl['itme2'] = 100; $pl['itms2'] = 30;

	$weplist = openfile(config('stwep',$gamecfg));
	do { 
		$index = rand(1,count($weplist)-1); 
		list($pl['wep'],$pl['wepk'],$pl['wepe'],$pl['weps'],$pl['wepsk'],$pl['wepnp']) = explode(",",$weplist[$index]);
	} while(!$pl['wepk']);

	$stitemlist = openfile(config('stitem',$gamecfg));
	do { 
		$index = rand(1,count($stitemlist)-1); 
		list($pl['itm3'],$pl['itmk3'],$pl['itme3'],$pl['itms3'],$pl['itmsk3'],$pl['itmnp3']) = explode(",",$stitemlist[$index]);
	} while(!$pl['itmk3']);
	do { 
		$index = rand(1,count($stitemlist)-1); 
		list($pl['itm4'],$pl['itmk4'],$pl['itme4'],$pl['itms4'],$pl['itmsk4'],$pl['itmnp4']) = explode(",",$stitemlist[$index]);
	} while(!$pl['itmk4'] || ($pl['itmk3'] == $pl['itmk4']));

	if(strpos($pl['wepk'],'WG') === 0){
		$pl['itm3'] = '实体弹药'; $pl['itmk3'] = 'GBS'; $pl['itme3'] = 1; $pl['itms3'] = 12; $pl['itmsk3'] = ''; $pl['itmnp3'] = 0;
	}
	if ($udata['groupid']>=8 || $cuser == $gamefounder) {
//		$msp += 2000;$mhp += 2000;$hp += 2000;$sp += 2000;
//		$att += 400;$def += 400;
//		$exp += 5000;$money = 20000;$rage = 200;$pose = 4;$tactic = 3;
//		$itm[1] = '回复饮剂'; $itmk[1] = 'HB'; $itme[1] = 5000; $itms[1] = 50; $itmsk[1] = '';
//		$itm[2] = '移动PC'; $itmk[2] = 'Y'; $itme[2] = 20; $itms[2] = 1;$itmsk[2] = '';
//		$itm[3] = '广域生命探测器'; $itmk[3] = 'R2'; $itme[3] = 10; $itms[3] = 1;$itmsk[3] = '';
//		$itm[4] = '凸眼鱼'; $itmk[4] = 'Y'; $itme[4] = 1; $itms[4] = 20;$itmsk[4] = '';
//		$itm[5] = '离子炮信标'; $itmk[5] = 'EM'; $itme[5] = 1; $itms[5] = 5;$itmsk[5] = '';$itmnp[5] = 1;
//		$itm[6] = '游戏解除钥匙'; $itmk[6] = 'Y'; $itme[6] = 1; $itms[6] = 1;$itmsk[6] = '';$itmnp[6] = 0;
//		$wep = '螺旋钻头';$wepk = 'WKN4';$wepe = 3333;$weps = 33;$wepsk = 'br';$wepnp = 75;
//		$arb = '能量铠甲';$arbk = 'DB'; $arbe = 2400; $arbs = 250; $arbsk = 'DADa';
//		$arh = 'U.N.I.O.N.天线';$arhk = 'DH'; $arhe = 1600; $arhs = 200; $arhsk = 'uAuDueusMD';$arhnp = 25;
//		$ara = '机械辅助手臂';$arak = 'DA'; $arae = 1700; $aras = 200; $arask = 'cr';
//		$arf = '机械辅助义足';$arfk = 'DF'; $arfe = 1800; $arfs = 150; $arfsk = 'MD';
//		$art = '精神增幅器';$artk = 'A'; $arte = 1; $arts = 12; $artsk = 'HPrv';$artnp = 100;
//		$technique = 'AA0DA0';
//		foreach(Array('wp','wk','wg','wc','wd','wf') as $val){
//			${$val} += rand(600,1200);
//		}
	}
	
	if($companysystem){
		include './gamedata/cache/company_1.php';
		$cp = $cpinit;
		$dice = rand(0,count($cpinfo)-1);
		if(isset($cpinfo[$dice])){
			$cp = array_merge($cp,$cpinfo[$dice]);
		}
		unset($cp['fac']);
		$cp['name'] .= $validnum . '号';
		$cp['type'] = 100;
		$cp['hp'] = $cp['mhp'];$cp['sp'] = $cp['msp'];
		$cp['lastcmd'] = $cp['lasteff'] = $now;
		$cp['laststn'] = $cp['lastslp'] = $cp['cdmsec'] = $cp['cdtime']= 0;
		$db->array_insert("{$tablepre}players", $cp);
		$cid = $pl['company'] = $db->insert_id();
	}
	
	$validtimes = $udata['validtimes'] + 1;
	
	
	$db->array_insert("{$tablepre}players", $pl);
	if($companysystem){
		$pid = $db->insert_id();
		$db->query("UPDATE {$tablepre}players SET company='$pid' where pid = '$cid'");
	}
	//$db->query("INSERT INTO {$tablepre}players (name,pass,type,gamehonour,lastcmd,lasteff,gd,sNo,icon,club,technique,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6,wepnp,arbnp,arhnp,aranp,arfnp,artnp,itmnp0,itmnp1,itmnp2,itmnp3,itmnp4,itmnp5,itmnp6) VALUES ('$name','$pass','$type','$gamehonour','$lastcmd','$lasteff','$gd','$sNo','$icon','$club','$technique','$hp','$mhp','$sp','$msp','$att','$def','$pls','$lvl','$exp','$money','$bid','$inf','$rage','$pose','$tactic','$state','$killnum','$wp','$wk','$wg','$wc','$wd','$wf','$teamID','$teamPass','$wep','$wepk','$wepe','$weps','$arb','$arbk','$arbe','$arbs','$arh','$arhk','$arhe','$arhs','$ara','$arak','$arae','$aras','$arf','$arfk','$arfe','$arfs','$art','$artk','$arte','$arts','$itm[0]','$itmk[0]','$itme[0]','$itms[0]','$itm[1]','$itmk[1]','$itme[1]','$itms[1]','$itm[2]','$itmk[2]','$itme[2]','$itms[2]','$itm[3]','$itmk[3]','$itme[3]','$itms[3]','$itm[4]','$itmk[4]','$itme[4]','$itms[4]','$itm[5]','$itmk[5]','$itme[5]','$itms[5]','$itm[6]','$itmk[6]','$itme[6]','$itms[6]','$wepsk','$arbsk','$arhsk','$arask','$arfsk','$artsk','$itmsk[0]','$itmsk[1]','$itmsk[2]','$itmsk[3]','$itmsk[4]','$itmsk[5]','$itmsk[6]','$wepnp','$arbnp','$arhnp','$aranp','$arfnp','$artnp','$itmnp[0]','$itmnp[1]','$itmnp[2]','$itmnp[3]','$itmnp[4]','$itmnp[5]','$itmnp[6]')");
	$db->query("UPDATE {$tablepre}users SET gender='$gender', icon='$icon', motto='$motto', criticalmsg='$criticalmsg', killmsg='$killmsg', lastword='$lastword',lastgame='$gamenum',ip='$onlineip',validtimes='$validtimes' WHERE username='$cuser'");
	naddnews($now,'newpc',$gamehonourinfo.$cuser,"{$sexinfo[$gender]}{$pl['sNo']}号",$onlineip);


	$gamestate = $validnum < $validlimit ? 20 : 30;
	save_gameinfo();
	

	include template('validover');
} elseif($mode == 'notice') {
	include template('notice');

} else {
	$ustate = 'valid';
	extract($udata);
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		header("Location: game.php");exit();
	}

	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__,__line__);
	}
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;
	$select_honour = Array();
	if($honour){		
		for($i = 0; $i < strlen($honour); $i+=2){
			$hstr = substr($honour,$i,2);
			if(isset($honourinfo[$hstr])){
				$select_honour[$hstr] = $honourinfo[$hstr]['name'];
			}
		}
	}
	include template('valid');
}

function makeclub() {
	global $pl;
	$pl['wp'] = $pl['wk'] = $pl['wg'] = $pl['wc'] = $pl['wd'] = $pl['wf'] = 0;
	$dice = rand(0,105);
	if($dice < 10)		{$club = 1;$pl['wp'] = 30;}//殴25
	elseif($dice < 20)	{$club = 2;$pl['wk'] = 30;}//斩25
	elseif($dice < 30)	{$club = 3;$pl['wc'] = 30;}//投25
	elseif($dice < 40)	{$club = 4;$pl['wg'] = 30;}//射25
	elseif($dice < 50)	{$club = 5;$pl['wd'] = 20;}//爆25
	elseif($dice < 55)	{$club = 6;}//移动、探索消耗减
	elseif($dice < 60)	{$club = 7;}//P(HACK)=1
	elseif($dice < 65)	{$club = 8;}//查毒可
	elseif($dice < 75)	{$club = 9;$pl['wf'] = 20;}//能使用必杀，灵25
	elseif($dice < 80)	{$club = 10;}//攻击熟练+2
	elseif($dice < 85)	{$club = 11;$pl['money'] = 500;}//出击钱数500
	elseif($dice < 90)	{$club = 12;$pl['wp'] = $pl['wk'] = $pl['wg'] = $pl['wc'] = $pl['wd'] = $pl['wf'] = 50;}//全熟练50
	elseif($dice < 95)	{$club = 13;$pl['mhp'] += 200;$pl['hp'] = $pl['mhp'];}//生命上限提高200
	elseif($dice < 100)	{$club = 14;$pl['att'] += 200;$pl['def'] += 200;}//攻防+200
	elseif($dice <= 105) {$club = 16;}//回复量增加
	else				{$club = makeclub();}
	return $club;
}
?>


