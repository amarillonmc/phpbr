<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function get_wephittimes($wepk,$wep_kind = 'N'){
	$atime = substr($wepk,3,1);
	if($wep_kind == 'g'){
		return 1;
	}
	if(!is_numeric($atime)){
		if($atime == 'A' || $atime == 'a'){$atime = 10;}
		elseif($atime == 'b'){$atime = 11;}
		elseif($atime == 'c'){$atime = 12;}
		elseif($atime == 'd'){$atime = 13;}
		elseif($atime == 'e'){$atime = 14;}
		elseif($atime == 'f'){$atime = 15;}
		elseif($atime == 'B'){$atime = 20;}
		elseif($atime == 'C'){$atime = 30;}
		elseif($atime == 'D'){$atime = 40;}
		elseif($atime == 'E'){$atime = 50;}
		else{$atime = 1;}
	}elseif(!$atime){
		$atime = 1;
	}
	return $atime;
}

function get_cassette($wepk){
	if(strpos($wepk,'WG')===0){
		$cst = substr($wepk,4,1);
		if(!$cst){
			if(strpos($wepk,'WGP')===0){
				$cst = 0;
			}else{
				$cst = 6;
			}			
		}
		elseif(!is_numeric($cst)){
			if($cst == 'A'){$cst = 10;}
			elseif($cst == 'a'){$cst = 15;}
			elseif($cst == 'B'){$cst = 20;}
			elseif($cst == 'C'){$cst = 30;}
			elseif($cst == 'D'){$cst = 40;}
			elseif($cst == 'E'){$cst = 50;}
			else{$cst = 6;}
		}
		return $cst;
	}else{
		return 0;
	}
}



function player_property($data,$mode = 'map') {
	global $itemspecialinfo,$ex_dmg_def,$wep_def;
	if(!in_array($mode, array_keys($itemspecialinfo))){
		return;
	}
	$prop = Array();
	$propkeys = array_keys($itemspecialinfo[$mode]);
	foreach(Array('wep','arb','arh','ara','arf','art') as $val){
		$eqpsk = $data[$val.'sk'];
		for ($i = 0; $i < strlen($eqpsk); $i+=2) {
			$sub = substr($eqpsk,$i,2);
			if(!empty($sub) && in_array($sub,$propkeys)){
				if($sub == 'DA'){
					$proplist = $wep_def;
				}elseif($sub == 'Da'){
					$proplist = $ex_dmg_def;
				}else{
					$proplist = Array($sub);
				}
				//
				foreach($proplist as $lval){
					if(isset($prop[$lval])){
						$prop[$lval][] = $val;
					}else{
						$prop[$lval] = Array($val);
					}
				}
			}
		}
	}
	return $prop;
}

function reset_wep(&$wep,&$wepk,&$wepe,&$weps,&$wepsk,&$wepnp){
	global $nowep,$nosta;
	$wep = $nowep;
	$wepk = 'WN';
	$wepsk = '';
	$wepe = $wepnp = 0;
	$weps = $nosta; 
	return;
}

function init_battlefield(){
	global $fog,$weather,$wthdata;
	if($wthdata[$weather]['kind'] == 'FOG') {
		$fog = true;
	}
	return;
}

function init_playerdata($pdata){
	global $baseexp,$upexp,$iconImg,$ardef,$pmapprop;
	extract($pdata);
	$upexp = round(($lvl*$baseexp)+(($lvl+1)*$baseexp));
	$iconImg = $type ? 'n_'.$icon.'.gif' : $gd.'_'.$icon.'.gif';
	$ardef = $arbe + $arhe + $arae + $arfe;
	
	if(!$weps) {
		global $nowep,$nosta;
		$wep = $nowep;$wepk = 'WN';$wepsk = '';
		$wepe = 0; $weps = $nosta;
	}
	if(!$arbs) {
		global $noarb,$nosta;
		$arb = $noarb;$arbk = 'DN'; $arbsk = '';
		$arbe = 0; $arbs = $nosta;
	}
	$pmapprop = player_property($pdata);
	return;
}

function init_moveto($pls){
	global $mapdata;
	$nmap = get_neighbor_map($pls);
	if(empty($nmap)){
		$moveto = '<option value="main">■ 无法移动 ■<br />';
	}else{
		$moveto = '<option value="main">■ 移动 ■<br />';
		foreach($nmap as $map => $movesp){
			if($map == $pls){
				$moveto .= "<option value=\"$map\">现在位置<br />";
			}else{
				$moveto .= "<option value=\"$map\">".$mapdata[$map]['name']."($movesp)<br />";
			}
			
		}
	}
	$moveto = "<select name=\"moveto\" onclick=sl('move'); href=\"javascript:void(0);\">{$moveto}</select>";
	return $moveto;
}

function set_noise($type, $pls, $pid1, $pid2 = 0, $time = 0) {
	global $now,$db,$tablepre;
	if(!$type){return;}
	if(!$time){$time = $now;}
	$db->query("INSERT INTO {$tablepre}noise (type,time,pls,pid1,pid2) VALUES ('$type','$time','$pls','$pid1','$pid2')");
	return;
}

function set_mapweapon($type, $pls, $lpid, $time = 0){
	global $now,$db,$tablepre,$mapweaponinfo;
	if(!$type || !isset($mapweaponinfo[$type])){return false;}
	if(!$time){$time = $now;}
	$locktime = $mapweaponinfo[$type]['locktime']; $time += $locktime;
	$db->query("INSERT INTO {$tablepre}mapweapon (pls,type,time,lpid) VALUES ('$pls','$type','$time','$lpid')");
	return $locktime;
}

function auto_chat(&$data,$mode) {
	global $npcchaton;
	$type = $data['type'];
	$name = $data['name'];
	$hp = $data['hp'];
	$mhp = $data['mhp'];
	$chatwords = '';
	if(!$type){//玩家
		global $db,$tablepre;
		if($mode == 'critical'){
			$result = $db->query("SELECT criticalmsg FROM {$tablepre}users WHERE username = '$name'");
			$criticalmsg = $db->result($result,0);
			if(!empty($criticalmsg)){
				$chatwords .= '<span class = "yellow">“'.$criticalmsg.'”</span><br />';
			}			
		} elseif($mode == 'death'){
			$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$name'");
			$lastword = $db->result($result,0);
			if(!empty($lastword)){
				$chatwords .= '<span class = "yellow">“'.$lastword.'”</span><br />';
			}
		} elseif($mode == 'kill'){
			$result = $db->query("SELECT killmsg FROM {$tablepre}users WHERE username = '$name'");
			$killmsg = $db->result($result,0);
			if(!empty($killmsg)){
				$chatwords .= '<span class = "yellow">“'.$killmsg.'”</span><br />';
			}
		}
	} elseif($npcchaton) {//NPC
		global $npcchat;
		$meet = & $data['state'];//记录NPC是否初见的开关
		if(isset($npcchat[$type][$name])){
			if($mode == 'attack' || $mode == 'defend'){
				if($meet == 1) {
					$mode = 'meet';
					$meet = 0;
				} elseif ($hp > $mhp / 2){
					$dice = rand ( 1, 2 );
					$mode .= 'fine' . $dice;
				} else {
					$dice = rand ( 1, 2 );
					$mode .= 'hurt' . $dice;
				}
			}
			$chatwords .= $npcchat[$type][$name][$mode];
			if($mode !== 'critical'){
				$chatwords = "“{$chatwords}”";
			}
			if(isset($npcchat[$type][$name]['color'])){
				$chatcolor = $npcchat[$type][$name]['color'];
				$chatwords = "<span class = \"{$chatcolor}\">{$chatwords}</span><br />";
			}else{
				$chatwords = '<span class = "yellow">'.$chatwords.'</span><br />';
			}
		} elseif($mode == 'death'){
			global $lwinfo;
			$chatwords .= '<span class = "yellow">“'.$lwinfo[$type].'”</span><br />';
		} elseif($mode == 'kill') {
			global $killmsginfo;
			$chatwords .= '<span class = "yellow">“'.$killmsginfo[$type].'”</span><br />';
		}
		
	}
	return $chatwords;
}

function active_AI(){
	//$t_s=getmicrotime();
	global $now,$db,$tablepre,$NPCAI,$gamestate;
	if(empty($NPCAI) || $gamestate < 20 || $gamestate >= 50){return;}
	$keys = implode(',',array_keys($NPCAI));
	$narray = $ndbarray = array();		
//		$result = $db->query("SELECT pid,name,pls FROM {$tablepre}players WHERE type = 0 AND hp > 0");
//		while($pd = $db->fetch_array($result)){
//			$parray[] = $pd;
//		}
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE type IN ($keys) AND hp > 0 AND state != 1");
	while($nd = $db->fetch_array($result)){
		$narray[$nd['pid']] = $nd;
	}
	foreach($narray as & $val){
		$pls = & $val['pls'];
		$stng = $NPCAI[$val['type']];
		$flag = false;
		if($stng['move'] && $now - $val['lastcmd'] > $stng['move']){
			$dice = rand(0,99);
			if($dice < $stng['mrate']){//移动
				$canmoveto = Array_keys(get_neighbor_map($pls));
				if(!empty($canmoveto)){
					shuffle($canmoveto);
					$pls = $canmoveto[0];
				}
				//echo $val['name'].'移动到'.$pls.'<br>';
				$flag = true;	
			}//此外是原地探索
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE type = 0 AND hp > 0 AND pls = '$pls'");		
			$num = $db->num_rows($result);
			$dice = rand(0,99);
			if($num && $dice < $stng['arate']){//攻击随机玩家
				$randrow = rand(0,$num-1);
				$db->data_seek($result,$randrow);
				$pldata = $db->fetch_array($result);
				global $inf_att_p,$inf_def_p,$skill_dmg,$ex_dmg_def,$wep_def;
				include_once GAME_ROOT.'./include/game/attr.func.php';				
				include_once GAME_ROOT.'./include/game/combat.func.php';				
				$abfac = $dbfac = array('attack' => 1, 'defend' => 1, 'counter' => 1, 'hitrate' => 1, 'sidestep' => 1, 'hittime' => 0, 'hitfac' => 1);
				$natt = $val['att'] + $val['wepe'] * 2;
				$pldef = $pldata['def'];
				foreach(Array('b','h','a','f') as $dval){
					if($pldata['ar'.$dval.'s']){
						$pldef += $pldata['ar'.$dval.'e'];
					}
				}
				$nws = $val['wp']; $nwp_kind = substr($val['wepk'],1,1);
				$dmg = set_orig_dmg ($val,$pldata,$abfac, $dbfac,$natt,$pldef,$nws,$nwp_kind,1);
				
				if(strpos($val['wepsk'],'Ad')!=false){
					if($nwp_kind == 'D'){$dmg += $val['wepe'];}
					else{$dmg += round($val['wepe']/2);}
				}
				if($nwp_kind == 'F'){
					$dmg += $val['wepe'];
				}
				if(substr($val['wepk'],3,1)){
					if(is_numeric(substr($val['wepk'],3,1))){
						$ht = substr($val['wepk'],3,1);
						$dmg *= rand(1,ceil($ht*0.7));
					}else{
						$ht = 10;
						$dmg *= rand(2,7);
					}
				}
				//echo $val['name'].'攻击'.$pldata['name'].'造成了'.$dmg.'伤害<br>';
				checkdmg($val['name'], $pldata['name'], $dmg);
				$fakelog = "手持<span class=\"red\">{$val['wep']}</span>的<span class=\"yellow\">{$val['name']}</span>向你袭击！<br>你受到其<span class=\"yellow\">$dmg</span>点攻击，对其做出了<span class=\"yellow\">0</span>点反击。<br>";
				$pldata['hp'] -= $dmg;
				if($pldata['hp'] <= 0){
					global $honourinfo,$typeinfo,$deathnum,$pdata;
					$dname = $pldata['name'];					
					if ($nwp_kind == 'N') {$dstate = 20;}
					elseif ($nwp_kind == 'P') {$dstate = 21;}
					elseif ($nwp_kind == 'K') {$dstate = 22;}
					elseif ($nwp_kind == 'G') {$dstate = 23;}
					elseif ($nwp_kind == 'C') {$dstate = 24;}
					elseif ($nwp_kind == 'D') {$dstate = 25;}
					elseif ($nwp_kind == 'F') {$dstate = 29;}
					if($pdata['pid'] == $pldata['pid']){
						$pdata['hp'] = 0;
						$pdata['bid'] = $val['pid'];
						$pdata['state'] = $dstate;
					}else{
						$pldata['hp'] = 0;
						$pldata['bid'] = $val['pid'];
						$pldata['state'] = $dstate;
					}
					$val['killnum'] ++ ;
					$nkillmsg = auto_chat ( $val, 'kill' );
					$fakelog .= "{$val['name']}对你说道：{$nkillmsg}<br>";
					
					$lwname = $pldata['gamehonour'] ? $honourinfo[$pldata['gamehonour']]['name'] . ' ' . $dname : $typeinfo [$pldata['type']] . ' ' . $dname;
					
					$result = $db->query ( "SELECT lastword FROM {$tablepre}users WHERE username = '$dname'" );
					$lastword = $db->result ( $result, 0 );
					$db->query ( "INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$now','$lwname','$pls','$lastword')" );
					naddnews ( $now, 'death' . $dstate, $lwname, 0, $val['name'], $val['wep'], $lastword );
					$deathnum ++;
					save_gameinfo ();
					
				}
				logsave ( $pldata['pid'], $now, $fakelog ,'b');
				
				player_save($pldata);
				
				$flag = true;
			}
					
		}
		if($stng['heal'] && $val['hp']/$val['mhp'] <= $stng['heal'] && $val['state'] != 2){//自动转换治疗状态
			$val['state'] = 2;
			//echo $val['name'].'转换到治疗状态<br>';
			$flag = true;		
		}elseif($val['hp'] / $val['mhp'] > 0.9 && $val['state'] == 2){
			$val['state'] = 0;
			//echo $val['name'].'转换到正常状态<br>';
			$flag = true;		
		}
		if($flag){
			$val['lastcmd'] = $now;
			unset($val['name'],$val['type'],$val['sNo']);
			$ndbarray[$val['pid']] = $val;
		}
	}
	unset($narray);
	if(!empty($ndbarray)){
		$pidarray = implode(',',array_keys($ndbarray));
		$db->multi_update("{$tablepre}players", $ndbarray, 'pid',"pid IN ($pidarray)");
	}
	//$t_e=getmicrotime();
	//putmicrotime($t_s,$t_e,'cmd_time','');
	return;
}

function auto_bind(&$data){
	global $npcbind;
	if($data['type']!=0){
		$dice = rand(0,99);
		if($dice < $npcbind){
			$inf = & $data['inf'];
			$inf = str_replace('h','',$inf);
			$inf = str_replace('b','',$inf);
			$inf = str_replace('a','',$inf);
			$inf = str_replace('f','',$inf);
			//echo $data['name'].'自动包扎了伤口';
		}
		player_save($data);
	}
	return;
}

//discription of state:  0 alive, 1 sp ref, 2 hp ref, 3 both ref, 10-97 death, 98-99 distroyed
function set_death(&$ddata,$death,$annex = '',$kdata = ''){//死亡处理以及复活判断
	global $now, $db, $tablepre, $alivenum, $deathnum,$bossdeath,$killmsginfo, $typeinfo,$lwinfo,$npcchat,$killmsginfo,$honourinfo;
	
	$dname = $ddata['name'];
	$dgamehonour = $ddata['gamehonour'];
	$dstate = & $ddata['state'];
	$dtype = $ddata['type'];
	$dbid = $ddata['bid'];
	$dhp = & $ddata['hp'];
	$dpls = $ddata['pls'];
	$dcompany = $ddata['company'];
	if (! $death) {
		return;
	}
	
	$dhp = 0;
	if ($death == 'N') {
		$dstate = 20;
	} elseif ($death == 'P') {
		$dstate = 21;
	} elseif ($death == 'K') {
		$dstate = 22;
	} elseif ($death == 'G') {
		$dstate = 23;
	} elseif ($death == 'C') {
		$dstate = 24;
	} elseif ($death == 'D') {
		$dstate = 25;
	} elseif ($death == 'F') {
		$dstate = 29;
	} elseif ($death == 'dn') {
		$dstate = 28;
	} elseif ($death == 'poison') {
		$dstate = 26;
	} elseif ($death == 'trap') {
		$dstate = 27;
	} elseif ($death == 'event') {
		$dstate = 13;
	} elseif ($death == 'hack') {
		$dstate = 14;
	} elseif ($death == 'pmove') {
		$dstate = 12;
	} elseif ($death == 'hsmove') {
		$dstate = 17;
	} elseif ($death == 'umove') {
		$dstate = 18;
	} elseif ($death == 'Lmove') {
		$dstate = 19;
	} elseif ($death == 'button') {
		$dstate = 30;
	} elseif ($death == 'suiside') {
		$dstate = 31;
	} elseif ($death == 'gradius') {
		$dstate = 33;
	} else {
		$dstate = 10;
	}
	if($dtype == 100 && $dcompany){
		$result = $result = $db->query ( "SELECT name FROM {$tablepre}players WHERE pid = '$dcompany'" );
		$dcpname = $db->result ( $result, 0 );
		$lwname = $dcpname. '的同伴 ' . $dname;
	}else{
		$lwname = $dgamehonour ? $honourinfo[$dgamehonour]['name'] . ' ' . $dname : $typeinfo [$dtype] . ' ' . $dname;
	}
	if (! $dtype) {
		$result = $db->query ( "SELECT lastword FROM {$tablepre}users WHERE username = '$dname'" );
		$lastword = $db->result ( $result, 0 );
	} else {
		if(isset($npcchat[$dtype][$dname])){
			$lastword = $npcchat[$dtype][$dname]['death'];
		}else{
			$lastword = $lwinfo [$dtype];
		}
	}
	if(!empty($kdata)){
		$kname = $kdata['name'];
		$ktype = $kdata['type'];
//		if($ktype==0 && $kname){
//			$result = $db->query ( "SELECT killmsg FROM {$tablepre}users WHERE username = '$kname'" );
//			$killmsg = $db->result ( $result, 0 );
//		}else{
//			$killmsg = $killmsginfo [$ktype];
//		}
	}else{
		$kname = '';
		$killmsg = '';
	}
	
	$db->query ( "INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$now','$lwname','$dpls','$lastword')" );
	naddnews ( $now, 'death' . $dstate, $lwname, $dtype, $kname, $annex, $lastword );
	
	if(set_revive($ddata,$death)){
		return false;
	}
	if(!$dtype){
		$alivenum --;
	}elseif($dtype == 1){
		$bossdeath = 1;
		global $pdata;
		if(!empty($kdata) && $pdata['pid'] == $kdata['pid']){
			set_ingame_honour($pdata,'K1');
		}
	}elseif($dtype == 2){
		global $pdata;
		if(!empty($kdata) && $pdata['pid'] == $kdata['pid']){
			set_ingame_honour($pdata,'K2');
		}
	}
	
	$deathnum ++;
	save_gameinfo ();
	return true;	
}

function set_ingame_honour(&$data,$ih){
	global $honourinfo,$log,$now;
	$data['ghonour'] .= $ih;
	
	$honour = Array();
	for($i = 0; $i < strlen($data['ghonour']); $i+=2){
		$hstr = substr($data['ghonour'],$i,2);
		
		if(in_array($hstr,array_keys($honourinfo)) && !in_array($hstr,$honour)){
			$honour[] = $hstr;
		}
	}
	$data['ghonour'] = implode('',$honour);
	
	$log .= '你获得了称号：<span class="yellow">'.$honourinfo[$ih]['name'].'</span><br>';
	naddnews($now, 'honour', $data['name'], $honourinfo[$ih]['name']);
	return;
}

function set_revive(&$data,$death){
	global $nosta,$now,$log,$pdata;
	$def_key = player_property($data,'def');
	if($death != 'dn' && isset($def_key['rv'])){
		
		$active = $data['pid'] == $pdata['pid'] ? 1 : 0;
		$rvpos = $def_key['rv'][0];
		$rv = & $data[$rvpos];$rve = & $data[$rvpos.'e'];$rvs = & $data[$rvpos.'s'];$rvk = & $data[$rvpos.'k'];$rvsk = & $data[$rvpos.'sk'];$rvnp = & $data[$rvpos.'np'];
		if($rvnp < 1){$rvnp = 1;}
		if(rand(0,99)<=$rvnp){
			$data['hp'] = $data['mhp'];
			$data['sp'] = $data['msp'];
			$data['state'] = 0;
			$data['inf'] = '';
			$data['bid'] = 0;
			if($active){$log .= "<span class = \"red\">你死了，不过……</span><br>在<span class=\"yellow\">$rv</span>的作用下，<span class=\"yellow\">你满血满状态复活了！</span><br>";}
			else{$log .= "<span class = \"red\">{$data['name']}死了，不过……</span><br>在<span class=\"yellow\">$rv</span>的作用下，<span class=\"yellow\">{$data['name']}满血满状态复活了！</span><br>";}
			naddnews ( $now, 'revive', $data['name'], $rv );
			if($rvs != $nosta){
				$rvs -= 1;
				if($rvs <= 0){
					if($active){$log .= "你的<span class=\"yellow\">$rv</span>耗尽了力量，损坏了！<br>";}
					else{$log .= "{$data['name']}的<span class=\"yellow\">$rv</span>耗尽了力量，损坏了！<br>";}
					$rv = $rvk = $rvsk = '';
					$rve = $rvs = $rvnp = 0;				
				}
			}		
			return true;
		}else{
			if($active){$log .= "你的<span class=\"yellow\">$rv</span>没能发挥作用！<br>";}
			else{$log .= "{$data['name']}的<span class=\"yellow\">$rv</span>没能发挥作用！<br>";}
		}		
	}
	return false;
}

function set_lvlup(&$data,$isplayer = 0){
	global $baseexp;
	$lvl = & $data['lvl'];
	$exp = $data['exp'];
	$club = $data['club'];
	$hp = & $data['hp']; $mhp = & $data['mhp'];
	$sp = & $data['sp']; $msp = & $data['msp'];
	$up_exp_temp = round ( (2 * $lvl + 1) * $baseexp );
	
	if ($exp >= $up_exp_temp && $lvl < 255) {
		$sklanginfo = Array ('wp' => '殴熟', 'wk' => '斩熟', 'wg' => '射熟', 'wc' => '投熟', 'wd' => '爆熟', 'wf' => '灵熟', 'all' => '全系熟练度' );
		$sknlist = Array (1 => 'wp', 2 => 'wk', 3 => 'wc', 4 => 'wg', 5 => 'wd', 9 => 'wf', 12 => 'all' );
		if(isset($sknlist [$club])){
			$skname = $sknlist[$club];
		}else{
			$skname = '';
		}
		
		$lvup = 1 + floor ( ($exp - $up_exp_temp) / $baseexp / 2 );
		$lvup = $lvup > 255 - $lvl ? 255 - $lvl : $lvup;
		$lvuphp = $lvupatt = $lvupdef = $lvupskill = $lvupsp = $lvupspref = 0;
		
		for($i = 0; $i < $lvup; $i += 1) {
			if ($club == 13) {
				$lvuphp += rand ( 14, 18 );
			} else {
				$lvuphp += rand ( 8, 10 );
			}
			$lvupsp += rand( 4,6);
			if ($club == 14) {
				$lvupatt += rand ( 7, 12 );
				$lvupdef += rand ( 9, 15 );
			} else {
				$lvupatt += rand ( 2, 4 );
				$lvupdef += rand ( 3, 5 );
			}
			
			if ($skname == 'all') {
				$lvupskill += rand ( 2, 4 );
			} elseif ($skname == 'wd' || $skname == 'wf') {
				$lvupskill += rand ( 3, 5 );
			}elseif($skname){
				$lvupskill += rand ( 4, 6 );
			}
			$lvupspref += round($msp * 0.1);		
		}
		
		$lvl += $lvup;
		$up_exp_temp = round ( (2 * $lvl + 1) * $baseexp );
		if ($lvl >= 255) {
			$lvl = 255;
			$exp = $up_exp_temp;
		}
		$hp += $lvuphp; $mhp += $lvuphp;
		$sp += $lvupsp; $msp += $lvupsp;
		$data['att'] += $lvupatt;
		$data['def'] += $lvupdef;
		if ($skname == 'all') {
			$data['wp'] += $lvupskill;
			$data['wk'] += $lvupskill;
			$data['wg'] += $lvupskill;
			$data['wc'] += $lvupskill;
			$data['wd'] += $lvupskill;
			$data['wf'] += $lvupskill;
		} elseif ($skname) {
			$data[$skname] += $lvupskill;
		}
		
		if ($sp+$lvupspref >= $msp) {
			$lvupspref =  $msp - $sp;
			
		}
		$sp += $lvupspref;
		if ($skname) {
			$sklog = "，{$sklanginfo[$skname]}+{$lvupskill}";
		}else{
			$sklog = '';
		}
		if ($isplayer) {
			global $log,$upexp;
			$upexp = $up_exp_temp;
			$log .= "<span class=\"yellow\">你升了{$lvup}级！生命上限+{$lvuphp}，体力上限+{$lvupsp}，攻击+{$lvupatt}，防御+{$lvupdef}{$sklog}，体力恢复了{$lvupspref}！</span><br>";
		} elseif (! $data['type']) {
			global $now,$w_upexp;
			$w_upexp = $up_exp_temp;
			$w_log = "<span class=\"yellow\">你升了{$lvup}级！生命上限+{$lvuphp}，体力上限+{$lvupsp}，攻击+{$lvupatt}，防御+{$lvupdef}{$sklog}，体力恢复了{$lvupspref}！</span><br>";
			logsave ( $data['pid'], $now, $w_log,'s');
		}
		
		
	} elseif ($lvl >= 255) {
		$lvl = 255;
		$exp = $up_exp_temp;
	}
	return;
}

function get_rest_val($cmd,$data,$forced = 0){
	global $now, $sleep_time, $heal_time;
	$lastcmd = $data['lastcmd']; $lasteff = $data['lasteff'];
	$hp = $data['hp'];$sp = $data['sp'];
	$mhp = $data['mhp'];$msp = $data['msp'];
	$pose = $data['pose'];$inf = $data['inf'];$club = $data['club'];
	$resttime1 = $now - $lastcmd;
	$resttime2 = $now - $lasteff;
	$resttime = $resttime1 > $resttime2 ? $resttime2 : $resttime1;
	$upsp = $uphp = 0;
	if(!$forced){
		$sp_fac = 1 / $sleep_time / 100; $hp_fac = 1 / $heal_time / 100;
	}else{
		$sp_fac = $forced / 1000; $hp_fac = $forced / 1000;
	}
	if($cmd == 1 || $cmd == 3){
		$upsp = round ( $msp * $resttime * $sp_fac );
		if ($pose == 5) {
			$upsp *= 2;
		}
		if (strpos ( $inf, 'b' ) !== false) {
			$upsp = round ( $upsp / 2 );
		}
		if ($club ==16){
			$upsp *= 2;
		}
		if($upsp + $sp > $msp){$upsp = $msp - $sp;}
	}
	if($cmd == 2 || $cmd == 3){
		$uphp = round ( $mhp * $resttime * $hp_fac );
		if ($pose == 5) {
			$uphp *= 2;
		}
		if (strpos ( $inf, 'b' ) !== false) {
			$uphp = round ( $uphp / 2 );
		}
		if ($club ==16){
			$uphp *= 2;
		}
		if($uphp + $hp > $mhp){$uphp = $mhp - $hp;}
	}
	return Array('uphp' => $uphp,'upsp' => $upsp);
}

function set_rest($command,&$data,$active = 0,$forced = 0){
	global $now, $restinfo;
	if($active){global $mode,$cmd;}
	if($data['state']!=1 && $data['state']!=2 && $data['state']!=3 && !$forced){
		if($active){$mode = 'command';}
		return;
	}
	$state = & $data['state'];$hp = & $data['hp'];$sp = & $data['sp'];
	if(!$forced){$rest = get_rest_val($state,$data);}
	else{$rest = get_rest_val($command,$data,$forced);}
	$uphp = $rest['uphp'];$upsp = $rest['upsp'];
	$hp += $uphp; $sp += $upsp;
	$restlog = '';
	if($active){
		if($uphp && $upsp){
			$restlog = "你的体力恢复了<span class=\"yellow\">$upsp</span>点，生命恢复了<span class=\"yellow\">$uphp</span>点。<br>";
		}elseif($upsp){
			$restlog = "你的体力恢复了<span class=\"yellow\">$upsp</span>点。<br>";
		}elseif($uphp){
			$restlog = "你的生命恢复了<span class=\"yellow\">$uphp</span>点。<br>";
		}
		if(!$forced){
			if ($command=='rest') {
				$cmd = '你正在' . $restinfo [$state] . '。<br><input type="hidden" name="mode" value="rest"><br><input type="radio" name="command" id="rest" value="rest" checked><a onclick=sl("rest"); href="javascript:void(0);" >' . $restinfo [$state] . '</a><br><input type="radio" name="command" id="back" value="back"><a onclick=sl("back"); href="javascript:void(0);" >返回</a>';
			} elseif($command=='back') {
				$state = 0;
				$mode = 'command';
			}
		}		
	}elseif($uphp > 0){
		$restlog = "的生命恢复了<span class=\"yellow\">$uphp</span>点。<br>";
	}
	return $restlog;
}

function check_cannot_cmd(&$data,$active = 0,$update = 0){
	global $now,$log,$inf_cannot_cmd,$infdata;
	$inf = & $data['inf'];
	$cannot_cmd = false; $need_update = false;
	foreach($inf_cannot_cmd as $key => $val){
		if(strpos($inf,$key)!==false){
			$lastinf = $data[$val['field']];
			$rmtime = $val['time'] - ($now - $lastinf);
			if($rmtime > 0){
				if($active){
					$log .= "<span class=\"red\">由于{$infdata[$key]['infnm']}，你无法活动！</span><br>剩余{$infdata[$key]['name']}时间：<span class=\"yellow\">{$rmtime}秒</span><br>";
				}			
				$cannot_cmd = true;
			}else{
				if($active){
					$log .= "<span class=\"yellow\">你从{$infdata[$key]['name']}状态中解除了！</span><br>";
				}else{
					$log .= "<span class=\"yellow\">{$data['name']}从{$infdata[$key]['name']}状态中解除了！</span><br>";
					global $w_log;
					$w_log .= "<span class=\"yellow\">你从{$infdata[$key]['name']}状态中解除了！</span><br>";
				}
				$inf = str_replace($key,'',$inf);
				$need_update = true;
			}
		}
	}
	if($need_update){player_save($data);}
	
	return $cannot_cmd;
}

function get_new_tech($data){
	global $techniqueinfo;
	$tech = Array_merge($techniqueinfo['active']['combat'], $techniqueinfo['active']['map'], $techniqueinfo['passive']['combat'], $techniqueinfo['passive']['map']);
	$technique = $data['technique'];$lvl = $data['lvl'];$techlevel = $data['techlevel'];
	$techlearnt = $subseqlist = Array();
	for($i=0;$i < strlen($technique); $i+=3){
		$tstr = substr($technique,$i,3);
		if(isset($tech[$tstr])){
			$techlearnt[] = $tstr;
			if(isset($tech[$tstr]['subseq'])){
				foreach($tech[$tstr]['subseq'] as $val){
					if($lvl >= $tech[$val]['lvl'] * 5 && $techlevel + 1 == $tech[$val]['lvl']){
						$subseqlist[] = $val;
					}
				}
			}			
		}
	}
	$subseqlist = Array_unique($subseqlist);
	$subseqlist = array_diff($subseqlist, $techlearnt);
	//var_dump($subseqlist);
	return $subseqlist;
}

function show_new_tech($data,$list = 0){
	global $techniqueinfo,$showtechlist;
	$techlist = get_new_tech($data);
	if(!$techlist){return false;}
	if($list){
		$cmd = Array();
		$tech = Array_merge($techniqueinfo['active']['combat'], $techniqueinfo['active']['map'], $techniqueinfo['passive']['combat'], $techniqueinfo['passive']['map']);
		foreach($techlist as $val){
			$cstr = 'tech'.$val;
			$cmd0 = '<input type="radio" name="command" id="'.$cstr.'" value="'.$cstr.'"><a onclick=sl("'.$cstr.'"); href="javascript:void(0);">'.$tech[$val]['name'].'：'.$tech[$val]['info'].'</a>';
			$cmd[$cstr] = $tech[$val]['name'].'：'.$tech[$val]['info'];
		}
		return $cmd;
	}else{
		return true;
	}	
}


?>