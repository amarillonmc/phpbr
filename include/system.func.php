<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function rs_game($mode = 0) {
	global $db,$tablepre,$gamecfg,$now,$gamestate,$mapdata,$typeinfo,$arealist,$areanum,$areaadd;
//	$stime=getmicrotime();
	$dir = GAME_ROOT.'./gamedata/';
	$sqldir = GAME_ROOT.'./gamedata/sql/';
	if ($mode & 1) {
		//重设玩家互动信息、聊天记录、地图道具、地图陷阱、进行状况
		$sql = file_get_contents("{$sqldir}reset.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		
		$db->queries($sql);
		
		//重设游戏进行状况的时间
		if($fp = fopen("{$dir}newsinfo.php", 'wb')) {
			global $checkstr;
			fwrite($fp, $checkstr);
			fclose($fp);
		} else {
			gexit('Can not write to cache files, please check directory ./gamedata/ and ./gamedata/cache/ .', __file__, __line__);
		}
		
		//清空战斗信息
		global $hdamage,$hplayer,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
		$hdamage = 0;
		$hplayer = '';
		$noisetime = 0;
		$noisepls = 0;
		$noiseid = 0;
		$noiseid2 = 0;
		$noisemode = '';
		save_combatinfo();
	}
	if ($mode & 2) {
		//echo " - 禁区初始化 - ";
		global $arealist,$areanum,$weather,$hack,$areatime,$starttime,$startmin,$areaadd,$areahour;
		list($sec,$min,$hour,$day,$month,$year,$wday,$yday,$isdst) = localtime($starttime);
		//$areatime = $starttime + $areahour*60;
		$areatime = (ceil(($starttime + $areahour*60)/600))*600;//$areahour已改为按分钟计算，ceil是为了让禁区分钟为10的倍数
		$plsnum = sizeof($mapdata);
		$arealist = range(1,$plsnum-2);
		shuffle($arealist);
		array_unshift($arealist,0);
		$areanum = 0;
		$weather = rand(0,8);
		$hack = 0;
		movehtm($areatime);
		
//		for($i = 0;$i*$areaadd < $plsnum - 1; $i++){
//			
//		}
		//地图初始化
//		include config('mapdata',$gamecfg);
//		$sql = '';
//		foreach ($mapdata as $mid => $map){
//			if(is_array($map)){
//				$sql .= "('".$mid."','".$map['name']."','".$map['notice']."','".$map['mapx']."','".$map['mapy']."','".$map['locked']."','".$map['roof']."','".$map['event']."','".$map['situation']."','".$map['kind']."','".$map['view']."','".$map['lighting']."','".$map['obstacle']."','".$map['cover']."'),";
//			}
//		}
//		if(!empty($sql)){
//			$sql = "INSERT INTO {$tablepre}mapdata (mid, name ,notice, mapx, mapy ,locked, roof, event, situation, kind, view, lighting, obstacle, cover) VALUES " . substr($sql,0,-1);
//			$db->query($sql);
//			unset($sql);
//		}
	}
	if ($mode & 4) {
		//echo " - 角色数据库初始化 - ";
		global $validnum,$alivenum,$deathnum;
		$sql = file_get_contents("{$sqldir}players.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		$validnum = $alivenum = $deathnum = 0;
	}
	if ($mode & 8) {
		//echo " - NPC初始化 - ";
		global $shadowsystem;
		//$db->query("DELETE FROM {$tablepre}players WHERE type>0 ");
		include config('npc',$gamecfg);
		$typenum = sizeof($typeinfo);
		$plsnum = sizeof($mapdata);
		$npcqry = '';
		$an = $areanum ? ceil($areanum/$areaadd) : 0;
		$passlist = array_slice($arealist,0,$areanum+1);
		foreach($npcinfo as $type => $npcdata){
			if($npcdata['area'] == $an || $npcdata['area'] == 99){
				for($j = 1;$j <= $npcdata['num'];$j++){
					$npc = array_merge($npcinit,$npcdata);
					
					$subnum = sizeof($npc['sub']);
					$sub = $j % $subnum;
					$npc = array_merge($npc,$npc['sub'][$sub]);
					if($npc['num'] > $subnum){$npc['name'] .= ceil($j / $subnum).'号';}
					$npc['type'] = $type;
					$npc['lastcmd'] = $npc['lasteff'] = $now;
					$npc['exp'] = round((2*$npc['lvl']+1)*$GLOBALS['baseexp']);
					$npc['sNo'] = $j;
					$npc['hp'] = $npc['mhp'];
					$npc['sp'] = $npc['msp'];
					$npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];
					if($npc['gd'] == 'r'){
						$npc['gd'] = rand(0,1) ? 'm':'f';
					}
					if($npc['pls'] == 99){
						do {
						$npc['pls'] = rand(1,$plsnum-1);
						} while(in_array($npc['pls'],$passlist));
					}
					$npcqry .= "('".$npc['name']."','".$npc['pass']."','".$npc['type']."','".$npc['lastcmd']."','".$npc['lasteff']."','".$npc['gd']."','".$npc['sNo']."','".$npc['icon']."','".$npc['club']."','".$npc['hp']."','".$npc['mhp']."','".$npc['sp']."','".$npc['msp']."','".$npc['att']."','".$npc['def']."','".$npc['pls']."','".$npc['lvl']."','".$npc['exp']."','".$npc['money']."','".$npc['bid']."','".$npc['inf']."','".$npc['rage']."','".$npc['pose']."','".$npc['tactic']."','".$npc['killnum']."','".$npc['state']."','".$npc['wp']."','".$npc['wk']."','".$npc['wg']."','".$npc['wc']."','".$npc['wd']."','".$npc['wf']."','".$npc['teamID']."','".$npc['teamPass']."','".$npc['wep']."','".$npc['wepk']."','".$npc['wepe']."','".$npc['weps']."','".$npc['arb']."','".$npc['arbk']."','".$npc['arbe']."','".$npc['arbs']."','".$npc['arh']."','".$npc['arhk']."','".$npc['arhe']."','".$npc['arhs']."','".$npc['ara']."','".$npc['arak']."','".$npc['arae']."','".$npc['aras']."','".$npc['arf']."','".$npc['arfk']."','".$npc['arfe']."','".$npc['arfs']."','".$npc['art']."','".$npc['artk']."','".$npc['arte']."','".$npc['arts']."','".$npc['itm0']."','".$npc['itmk0']."','".$npc['itme0']."','".$npc['itms0']."','".$npc['itm1']."','".$npc['itmk1']."','".$npc['itme1']."','".$npc['itms1']."','".$npc['itm2']."','".$npc['itmk2']."','".$npc['itme2']."','".$npc['itms2']."','".$npc['itm3']."','".$npc['itmk3']."','".$npc['itme3']."','".$npc['itms3']."','".$npc['itm4']."','".$npc['itmk4']."','".$npc['itme4']."','".$npc['itms4']."','".$npc['itm5']."','".$npc['itmk5']."','".$npc['itme5']."','".$npc['itms5']."','".$npc['itm6']."','".$npc['itmk6']."','".$npc['itme6']."','".$npc['itms6']."','".$npc['wepsk']."','".$npc['arbsk']."','".$npc['arhsk']."','".$npc['arask']."','".$npc['arfsk']."','".$npc['artsk']."','".$npc['itmsk0']."','".$npc['itmsk1']."','".$npc['itmsk2']."','".$npc['itmsk3']."','".$npc['itmsk4']."','".$npc['itmsk5']."','".$npc['itmsk6']."','".$npc['wepnp']."','".$npc['arbnp']."','".$npc['arhnp']."','".$npc['aranp']."','".$npc['arfnp']."','".$npc['artnp']."','".$npc['itmnp0']."','".$npc['itmnp1']."','".$npc['itmnp2']."','".$npc['itmnp3']."','".$npc['itmnp4']."','".$npc['itmnp5']."','".$npc['itmnp6']."'),";
					
				}
				if($npcdata['attention']){
					if($npcdata['num'] == 1){
						naddnews($now, 'addnpc',$typeinfo[$type].' '.$npc['name']);
					}	elseif($npcdata['num'] > 1){
						naddnews($now, 'addnpcs',$typeinfo[$type],$npcdata['num']);
					}
				}
			}
		}
		if($shadowsystem && $an == 0){//影子玩家系统
			$result = $db->query("SELECT * FROM {$tablepre}winners ORDER BY gid DESC LIMIT 1");
			$sdata = $db->fetch_array($result);
			if(!empty($sdata['name'])){
				$sdata['name'] = '影-'.$sdata['name'];
				$sdata['type'] = 91;
				$sdata['lastcmd'] = $sdata['lasteff'] = $now;
				$sdata['sNo'] = $sdata['bid'] = 0;
				$sdata['state'] = 1;
				$sdata['hp'] = $sdata['mhp'];
				$sdata['sp'] = $sdata['msp'];
				$sdata['inf'] = '';
				for($i=0;$i<=6;$i++){
					$sdata['itm'.$i] = $sdata['itmk'.$i] = $sdata['itmsk'.$i] = '';
					$sdata['itme'.$i] = $sdata['itms'.$i] = $sdata['itmnp'.$i] = 0;
				}
				$sdata['itm1'] = '击倒'.$sdata['name'].'的证明';$sdata['itmk1'] = 'Z';$sdata['itme1'] = 1;$sdata['itms1'] = 1;
				$sdata['pls'] = 0;
				$npcqry .= "('".$sdata['name']."','".$sdata['pass']."','".$sdata['type']."','".$sdata['lastcmd']."','".$sdata['lasteff']."','".$sdata['gd']."','".$sdata['sNo']."','".$sdata['icon']."','".$sdata['club']."','".$sdata['hp']."','".$sdata['mhp']."','".$sdata['sp']."','".$sdata['msp']."','".$sdata['att']."','".$sdata['def']."','".$sdata['pls']."','".$sdata['lvl']."','".$sdata['exp']."','".$sdata['money']."','".$sdata['bid']."','".$sdata['inf']."','".$sdata['rage']."','".$sdata['pose']."','".$sdata['tactic']."','".$sdata['killnum']."','".$sdata['state']."','".$sdata['wp']."','".$sdata['wk']."','".$sdata['wg']."','".$sdata['wc']."','".$sdata['wd']."','".$sdata['wf']."','".$sdata['teamID']."','".$sdata['teamPass']."','".$sdata['wep']."','".$sdata['wepk']."','".$sdata['wepe']."','".$sdata['weps']."','".$sdata['arb']."','".$sdata['arbk']."','".$sdata['arbe']."','".$sdata['arbs']."','".$sdata['arh']."','".$sdata['arhk']."','".$sdata['arhe']."','".$sdata['arhs']."','".$sdata['ara']."','".$sdata['arak']."','".$sdata['arae']."','".$sdata['aras']."','".$sdata['arf']."','".$sdata['arfk']."','".$sdata['arfe']."','".$sdata['arfs']."','".$sdata['art']."','".$sdata['artk']."','".$sdata['arte']."','".$sdata['arts']."','".$sdata['itm0']."','".$sdata['itmk0']."','".$sdata['itme0']."','".$sdata['itms0']."','".$sdata['itm1']."','".$sdata['itmk1']."','".$sdata['itme1']."','".$sdata['itms1']."','".$sdata['itm2']."','".$sdata['itmk2']."','".$sdata['itme2']."','".$sdata['itms2']."','".$sdata['itm3']."','".$sdata['itmk3']."','".$sdata['itme3']."','".$sdata['itms3']."','".$sdata['itm4']."','".$sdata['itmk4']."','".$sdata['itme4']."','".$sdata['itms4']."','".$sdata['itm5']."','".$sdata['itmk5']."','".$sdata['itme5']."','".$sdata['itms5']."','".$sdata['itm6']."','".$sdata['itmk6']."','".$sdata['itme6']."','".$sdata['itms6']."','".$sdata['wepsk']."','".$sdata['arbsk']."','".$sdata['arhsk']."','".$sdata['arask']."','".$sdata['arfsk']."','".$sdata['artsk']."','".$sdata['itmsk0']."','".$sdata['itmsk1']."','".$sdata['itmsk2']."','".$sdata['itmsk3']."','".$sdata['itmsk4']."','".$sdata['itmsk5']."','".$sdata['itmsk6']."','".$sdata['wepnp']."','".$sdata['arbnp']."','".$sdata['arhnp']."','".$sdata['aranp']."','".$sdata['arfnp']."','".$sdata['artnp']."','".$sdata['itmnp0']."','".$sdata['itmnp1']."','".$sdata['itmnp2']."','".$sdata['itmnp3']."','".$sdata['itmnp4']."','".$sdata['itmnp5']."','".$sdata['itmnp6']."'),";
				naddnews($now, 'addnpc',$typeinfo[91].' '.$sdata['name']);
			}
		}
		if(!empty($npcqry)){
			$npcqry = "INSERT INTO {$tablepre}players (name,pass,type,lastcmd,lasteff,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6,wepnp,arbnp,arhnp,aranp,arfnp,artnp,itmnp0,itmnp1,itmnp2,itmnp3,itmnp4,itmnp5,itmnp6) VALUES ".substr($npcqry, 0, -1);
			$db->query($npcqry);
			unset($npcqry);
		}
		
		
	}
	if ($mode & 16) {
		//echo " - 地图道具/陷阱初始化 - ";
		//感谢 Martin1994 提供地图道具数据库化的源代码
		$plsnum = sizeof($mapdata);
		$iqry = $tqry = '';
//		if($gamestate == 0){
//			global $checkstr;
//			dir_clear("{$dir}mapitem/");
//			for($i = 0;$i < $plsnum; $i++){
//				$mapfile = GAME_ROOT."./gamedata/mapitem/{$i}mapitem.php";
//				writeover($mapfile,$checkstr);
//			}
//		}
		$file = config('mapitem',$gamecfg);
		$itemlist = openfile($file);
//		$in = sizeof($itemlist);
		$an = $areanum ? ceil($areanum/$areaadd) : 0;
		//$mapitem = array();
		//$ifqry = $iqry = 'INSERT INTO '.$tablepre.'mapitem (itm,itmk,itme,itms,itmsk,map) VALUES ';
		foreach($itemlist as $item){
			if(is_numeric(substr($item,0,1))){
				list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind,$inump) = explode(',',$item);
				if(($iarea == $an)||($iarea == 99)) {
					for($j = $inum; $j>0; $j--) {
						if($imap == 99) {
							$rmap = rand(1,$plsnum-1);
							if($ikind == 'TO'){
								$tqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$inump','$rmap'),";
							}else{
								$iqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$inump','$rmap'),";
							}
						}else{
							if($ikind == 'TO'){
								$tqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$inump','$imap'),";
							}else{
								$iqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$inump','$imap'),";
							}
						}
					}
				}
			}
		}
		if(!empty($iqry)){
			$iqry = "INSERT INTO {$tablepre}mapitem (itm,itmk,itme,itms,itmsk,itmnp,pls) VALUES ".substr($iqry, 0, -1);
			$db->query($iqry);
		}
		if(!empty($tqry)){
			$tqry = "INSERT INTO {$tablepre}maptrap (itm,itmk,itme,itms,itmsk,itmnp,pls) VALUES ".substr($tqry, 0, -1);
			$db->query($tqry);
		}
		
		unset($itemlist);
		unset($iqry);
		
	}
	if ($mode & 32) {
		//echo " - 商店初始化 - ";
		$sql = file_get_contents("{$sqldir}shopitem.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		
		$file = config('shopitem',$gamecfg);
		$shoplist = openfile($file);
		$qry = '';
		foreach($shoplist as $lst){
			if(is_numeric(substr($lst,0,1))){
				list($kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk,$itmnp)=explode(',',$lst);
				if($kind != 0){
					$qry .= "('$kind','$num','$price','$area','$item','$itmk','$itme','$itms','$itmsk','$itmnp'),";
				}
			}
		}
		if(!empty($qry)){
			$qry = "INSERT INTO {$tablepre}shopitem (kind,num,price,area,item,itmk,itme,itms,itmsk,itmnp) VALUES ".substr($qry, 0, -1);
		}
		$db->query($qry);
	}
}

function rs_sttime() {
	//echo " - 游戏开始时间初始化 - ";
	global $starttime,$now,$startmode,$starthour,$startmin;

	list($sec,$min,$hour,$day,$month,$year,$wday,$yday,$isdst) = localtime($now);
	$month++;
	$year += 1900;
	
	if($startmode == 1) {
		if($hour >= $starthour){ $nextday = $day+1;}
		else{$nextday = $day;}
		$nexthour = $starthour;
		$starttime = mktime($nexthour,$startmin,0,$month,$nextday,$year);
	} elseif($startmode == 2) {
		$starthour = $starthour> 0 ? $starthour : 1;
		$startmin = $startmin> 0 ? $startmin : 1;
		$nexthour = $hour + $starthour;
		$starttime = mktime($nexthour,$startmin,0,$month,$day,$year);
	} elseif($startmode == 3) {
		$starthour = $starthour> 0 ? $starthour : 1;
		$nextmin = $min + $starthour;
		$nexthour = $hour;
//		if($nextmin % 60 >= 40){//回避速1禁
//			$nextmin+=20;
//		}
		if($nextmin % 60 == 0){
			$nextmin +=1;
		}
		$starttime = mktime($nexthour,$nextmin,0,$month,$day,$year);
	} else {
		$starttime = 0;
	}
	return;
}


function add_once_area($atime) {
	//实际上GAMEOVER的判断是在common.inc.php里
	global $db,$tablepre,$now,$gamecfg,$gamestate,$areaesc,$arealist,$areanum,$arealimit,$areaadd,$mapdata,$weather,$hack,$validnum,$alivenum,$deathnum;
	include config('npc',$gamecfg);
	if (($gamestate > 10)&&($now > $atime)) {
		$plsnum = sizeof($mapdata) - 1;
		if(($areanum >= $arealimit*$areaadd)&&($validnum<=0)) {//无人参加GAMEOVER不是因为这里，这里只是保险。
			gameover($atime,4);
			return;
		} elseif(($areanum + $areaadd) >= $plsnum) {
			//$areaaddlist = array_slice($arealist,$areanum+1);
			$areaaddlist = str_replace(',','、',get_areawords(-2));
			naddnews($atime, 'addarea',$areaaddlist,$weather);
			$msg = "下列地点变为禁区：".$areaaddlist;
			systemchat($msg,$atime);
			$areanum = $plsnum;
			$weather = rand(0,8);
			$query = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 AND hp>0");
			while($sub = $db->fetch_array($query)) {
				$pid = $sub['pid'];
				$hp = 0;
				$state = 11;
				$deathpls = $sub['pls'];
				$bid = 0;
				$lasteff = $atime;
				$db->query("UPDATE {$tablepre}players SET hp='$hp', bid='$bid', state='$state', lasteff='$lasteff' WHERE pid=$pid");
				naddnews($atime,"death$state",$sub['name'],$sub['type'],$deathpls);
			}
			$db->free_result($query);
			$alivenum = 0;
			$dquery = $db->query("SELECT pid FROM {$tablepre}players WHERE hp<=0");
			$deathnum = $db->num_rows($dquery);
			$db->free_result($dquery);
			gameover($atime,1);
			return;
		} else {
			//$areaaddlist = array_slice($arealist,$areanum+1,$areaadd);
			
			$areaaddlist = str_replace(',','、',get_areawords(-2));
			naddnews($atime, 'addarea',$areaaddlist,$weather);
			$msg = "下列地点变为禁区：".$areaaddlist;
			systemchat($msg,$atime);
			if($hack > 0){$hack--;}
			$weather = rand(0,8);
			$areanum += $areaadd;
			movehtm();
			$str_arealist = implode(',',array_slice($arealist,0,$areanum+1));
			$query = $db->query("SELECT * FROM {$tablepre}players WHERE pls IN ($str_arealist) AND hp>0");
			while($sub = $db->fetch_array($query)) {
				$pid = $sub['pid'];
				
				if(!$sub['type']) {
					$canmoveto = Array_keys(get_neighbor_map($sub['pls']));
					
					if(($gamestate >= 40)||(!$areaesc&&($sub['tactic']!=4))||(!$canmoveto)) {
						$hp = 0;
						$state = 11;
						$deathpls = $sub['pls'];
						$bid = 0;
						$lasteff = $atime;
						$db->query("UPDATE {$tablepre}players SET hp='$hp', bid='$bid', state='$state', lasteff='$lasteff' WHERE pid=$pid");
						naddnews($atime,"death$state",$sub['name'],$sub['type'],$deathpls);
						$deathnum++;
					} else {
						shuffle($canmoveto);
						$pls = $canmoveto[0];
						//$pls = $arealist[rand($areanum+1,$plsnum)];
						$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid ");
					}
				} elseif(!in_array($sub['type'],$npcstatic)) {
//					$canmoveto = get_neighbor_map($sub['pls']);
//					$canmoveto = shuffle($canmoveto);
					$pls = $arealist[rand($areanum+1,$plsnum-1)];
					$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid");
				}
			}
			$alivenum = $db->result($db->query("SELECT COUNT(*) FROM {$tablepre}players WHERE hp>0 AND type=0"), 0);
			if(($alivenum == 1)&&($gamestate >= 30)) { 
				gameover($atime);
				return;
			} elseif(($alivenum <= 0)&&($gamestate >= 30)) {
				gameover($atime,1);
				return $atime;
			} else {
				rs_game(8+16+32);
				//$areatime += $areahour*3600;
				//addarea($areatime);
				return;
			}
		}
	} else {
		return;
	}
}

function duel($time = 0,$keyitm = ''){
	global $now,$gamestate,$name;
	if($gamestate < 30){
		return 30;
	} elseif($gamestate >= 50) {
		return 51;
	}	else{
		$time = $time == 0 ? $now : $time;
		$gamestate = 50;
		save_gameinfo();
		naddnews($time,'duelkey',$name,$keyitm);
		naddnews($time,'duel');
		return 50;
	}
}

//------游戏结束------
//模式：0保留：程序故障；1：全部死亡；2：最后幸存；3：禁区解除；4：无人参加；5：核爆全灭；6：GM中止
function gameover($time = 0, $mode = 0, $winname = '') {
	global $gamestate,$winmode,$alivenum,$winner,$now,$gamenum,$db,$tablepre,$gamenum,$starttime,$validnum,$hdamage,$hplayer;
	if($gamestate < 10){return;}
	if((!$mode)||(($mode==2)&&(!$winname))) {//在没提供结束模式或者信息不够的情况下
		if($validnum <= 0) {//无激活者情况下，无人参加
			$alivenum = 0;
			$winmode = 4;
			$winner = '';			
		} else {//判断谁是最后幸存者
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
			$alivenum = $db->num_rows($result);
			if(!$alivenum) {//全部死亡
				$winmode = 1;
				$winner = '';
			} elseif($alivenum == 1) {//最后幸存
				$winmode = 2;
				$wdata = $db->fetch_array($result);
				$winner = $wdata['name'];
//				$db->query("UPDATE {$tablepre}players SET state='5' where pid='{$wdata['pid']}'");
			} else {//不满足游戏结束条件，返回，此时相当于重置幸存人数
				save_gameinfo();
				return;
			}
		}
	} else {//提供了游戏结束模式的情况下
		$winmode = $mode;
		$winner = $winname;
	}
	$time = $time ? $time : $now;
	$result = $db->query("SELECT gid FROM {$tablepre}winners ORDER BY gid DESC LIMIT 1");//判断当前游戏局数是否正确，以优胜列表为准
	$rgamenum = $db->result($result, 0);
	if($rgamenum&&($gamenum <= $rgamenum)) {
		$gamenum = $rgamenum + 1;
	}
	if($winmode == 4){//无人参加；不需要记录任何资料
		$getime = $time;
		$db->query("INSERT INTO {$tablepre}winners (gid,wmode,vnum,getime) VALUES ('$gamenum','$winmode','$validnum','$getime')");
	}	elseif(($winmode == 0)||($winmode == 1)||($winmode == 6)){//程序故障、全部死亡、GM中止，不需要记录优胜者资料
		$gstime = $starttime;
		$getime = $time;
		$gtime = $time - $starttime;
		$db->query("INSERT INTO {$tablepre}winners (gid,wmode,vnum,gtime,gstime,getime,hdmg,hdp) VALUES ('$gamenum','$winmode','$validnum','$gtime','$gstime','$getime','$hdamage','$hplayer')");
	} else {//最后幸存、锁定解除、核爆全灭，需要记录优胜者资料
		if(isset($wdata)){
			$pdata = $wdata;
		}else{
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$winner' AND type=0");
			$pdata = $wdata = $db->fetch_array($result);
		}
		$result2 = $db->query("SELECT motto FROM {$tablepre}users WHERE username='$winner'");
		$wdata['motto'] = $db->result($result2, 0);
		$result3 = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
		$hk = $db->fetch_array($result3);
		$wdata['hkill'] = $hk['killnum'];
		$wdata['hkp'] = $hk['name'];
		$wdata['wmode'] = $winmode;
		$wdata['vnum'] = $validnum;
		$wdata['gtime'] = $time - $starttime;
		$wdata['gstime'] = $starttime;
		$wdata['getime'] = $time;
		$wdata['hdmg'] = $hdamage;
		$wdata['hdp'] = $hplayer;
		$db->query("INSERT INTO {$tablepre}winners (gid,name,pass,gamehonour,gd,sNo,icon,club,technique,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,inf,rage,pose,tactic,killnum,wp,wk,wg,wc,wd,wf,teamID,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,motto,wmode,vnum,gtime,gstime,getime,hdmg,hdp,hkill,hkp,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6) VALUES ('".$gamenum."','".$wdata['name']."','".$wdata['pass']."','".$wdata['gamehonour']."','".$wdata['gd']."','".$wdata['sNo']."','".$wdata['icon']."','".$wdata['club']."','".$wdata['technique']."','".$wdata['hp']."','".$wdata['mhp']."','".$wdata['sp']."','".$wdata['msp']."','".$wdata['att']."','".$wdata['def']."','".$wdata['pls']."','".$wdata['lvl']."','".$wdata['exp']."','".$wdata['money']."','".$wdata['inf']."','".$wdata['rage']."','".$wdata['pose']."','".$wdata['tactic']."','".$wdata['killnum']."','".$wdata['wp']."','".$wdata['wk']."','".$wdata['wg']."','".$wdata['wc']."','".$wdata['wd']."','".$wdata['wf']."','".$wdata['teamID']."','".$wdata['wep']."','".$wdata['wepk']."','".$wdata['wepe']."','".$wdata['weps']."','".$wdata['arb']."','".$wdata['arbk']."','".$wdata['arbe']."','".$wdata['arbs']."','".$wdata['arh']."','".$wdata['arhk']."','".$wdata['arhe']."','".$wdata['arhs']."','".$wdata['ara']."','".$wdata['arak']."','".$wdata['arae']."','".$wdata['aras']."','".$wdata['arf']."','".$wdata['arfk']."','".$wdata['arfe']."','".$wdata['arfs']."','".$wdata['art']."','".$wdata['artk']."','".$wdata['arte']."','".$wdata['arts']."','".$wdata['itm0']."','".$wdata['itmk0']."','".$wdata['itme0']."','".$wdata['itms0']."','".$wdata['itm1']."','".$wdata['itmk1']."','".$wdata['itme1']."','".$wdata['itms1']."','".$wdata['itm2']."','".$wdata['itmk2']."','".$wdata['itme2']."','".$wdata['itms2']."','".$wdata['itm3']."','".$wdata['itmk3']."','".$wdata['itme3']."','".$wdata['itms3']."','".$wdata['itm4']."','".$wdata['itmk4']."','".$wdata['itme4']."','".$wdata['itms4']."','".$wdata['itm5']."','".$wdata['itmk5']."','".$wdata['itme5']."','".$wdata['itms5']."','".$wdata['itm6']."','".$wdata['itmk6']."','".$wdata['itme6']."','".$wdata['itms6']."','".$wdata['motto']."','".$wdata['wmode']."','".$wdata['vnum']."','".$wdata['gtime']."','".$wdata['gstime']."','".$wdata['getime']."','".$wdata['hdmg']."','".$wdata['hdp']."','".$wdata['hkill']."','".$wdata['hkp']."','".$wdata['wepsk']."','".$wdata['arbsk']."','".$wdata['arhsk']."','".$wdata['arask']."','".$wdata['arfsk']."','".$wdata['artsk']."','".$wdata['itmsk0']."','".$wdata['itmsk1']."','".$wdata['itmsk2']."','".$wdata['itmsk3']."','".$wdata['itmsk4']."','".$wdata['itmsk5']."','".$wdata['itmsk6']."')");
		if(isset($GLOBALS['pdata']) && $GLOBALS['pdata']['pid'] == $pdata['pid']){
			$GLOBALS['pdata']['story'] = 100;
		}
		$pdata['story'] = 100;
		player_save($pdata);
	}
	rs_sttime();//重置游戏开始时间和当前游戏状态
	$gamestate = 0;
	save_gameinfo();
	//echo '**游戏结束**';
	//$gamestate = 0;
	naddnews($time, "end$winmode",$winner);
	naddnews($time, 'gameover' ,$gamenum);
	systemchat('游戏结束',$time);
	include_once './include/news.func.php';//保存进行状况
	$newsinfo = nparse_news(0,65535);
	writeover(GAME_ROOT."./gamedata/bak/{$gamenum}_newsinfo.php",$newsinfo,'wb+');
	//$t_s=getmicrotime();
	set_credits();//更新玩家积分
	//$t_e=getmicrotime();
	//putmicrotime($t_s,$t_e,'cmd_time','');
	return;
}

function movehtm($atime = 0) {
	global $mapdata,$arealist,$areanum,$hack,$pls,$areahour,$areaadd;

//	$movehtm = GAME_ROOT.TPLDIR.'/move.htm';
//	$movedata = '<option value="main">■ 移动 ■<br />';
//
//	foreach($mapdata as $key => $value) {
//		$plsname = $value['name'];
//		if(array_search($key,$arealist) > $areanum || $hack){
//			$movedata .= "<option value=\"$key\"><!--{if \$pls == $key}--><--现在位置--><!--{else}-->$plsname<!--{/if}--><br />";
//		}
//	} 
//	writeover($movehtm,$movedata);
	
	$areahtm = GAME_ROOT.TPLDIR.'/areainfo.htm';
	$areadata = '<span class="evergreen"><b>现在的禁区是：</b></span>';
	for($i=0;$i<=$areanum;$i++){
		$areadata .= '&nbsp;'.$mapdata[$arealist[$i]]['name'].'&nbsp;';
	}
	$areadata .= '<br><span class="evergreen"><b>下回的禁区是：</b></span>';
	
	if(!$atime){
		global $areatime;
		$atime = $areatime;
	}
	if($areanum < count($mapdata)) {
		$at= getdate($atime);
		$nexthour = $at['hours'];$nextmin = $at['minutes'];
		while($nextmin >= 60){
			$nexthour +=1;$nextmin -= 60;
		}
		if($nexthour >= 24){$nexthour-=24;}
		$areadata .= "<b>{$nexthour}时{$nextmin}分：</b> ";
		for($i=1;$i<=$areaadd;$i++) {
			$areadata .= '&nbsp;'.$mapdata[$arealist[$areanum+$i]]['name'].'&nbsp;';
		}
	}
	if($areanum+$areaadd < count($mapdata)) {
		$at2= getdate($atime + $areahour*60);
		$nexthour2 = $at2['hours'];$nextmin2 = $at2['minutes'];
		while($nextmin2 >= 60){
			$nexthour2 +=1;$nextmin2 -= 60;
		}
		if($nexthour2 >= 24){$nexthour2-=24;}
		$areadata .= "；<b>{$nexthour2}时{$nextmin2}分：</b> ";
		for($i=1;$i<=$areaadd;$i++) {
			$areadata .= '&nbsp;'.$mapdata[$arealist[$areanum+$areaadd+$i]]['name'].'&nbsp;';
		}
	}
	if($areanum+$areaadd*2 < count($mapdata)) {
		$at3= getdate($atime + $areahour*120);
		$nexthour3 = $at3['hours'];$nextmin3 = $at3['minutes'];
		while($nextmin3 >= 60){
			$nexthour3 +=1;$nextmin3 -= 60;
		}
		if($nexthour3 >= 24){$nexthour3-=24;}
		$areadata .= "；<b>{$nexthour3}时{$nextmin3}分：</b> ";
		for($i=1;$i<=$areaadd;$i++) {
			$areadata .= '&nbsp;'.$mapdata[$arealist[$areanum+$areaadd*2+$i]]['name'].'&nbsp;';
		}
	}
	writeover($areahtm,$areadata);
	return;
}


function level_calc($credits)
{
	if($credits < 100){
		return 1;
	}else{
		$level = floor(sqrt($credits/300)*3);
		if($level == 1){
			$level = 2;
		}
		return $level;
	}
	return 0;
}

function get_credit_up($data,$winner = '',$winmode = 0){
	if($data['name'] == $winner){//获胜
		if($winmode == 2){$up = 100;}//幸存
		elseif($winmode == 3){$up = 200;}//解禁
		elseif($winmode == 5){$up = 50;}//核弹
		else{$up = 25;}
	}
	elseif($data['hp']>0){$up = 25;}//存活
	else{$up = 5;}//死亡+5
	if($data['killnum']){
		$up += $data['killnum'] * 2;//杀一人加2
	}
	$skill = $data['wp'] + $data['wk'] + $data['wg'] + $data['wc'] + $data['wd'] + $data['wf'];
	$up += round($skill / 20);//每20点熟练加1
//	foreach(Array('wp','wk','wg','wc','wd','wf') as $val){
//		$skill = $data[$val];
//		$up += round($skill / 100);//每100点熟练加1
//	}
	return $up;
}

function get_honour_obtain($pldata,$udata){
	global $honourinfo;
	$uhonour = $nhonour = Array();
	for($i = 0; $i < strlen($udata['honour']); $i+=2){
		$uhstr = substr($udata['honour'],$i,2);
		if(in_array($uhstr,array_keys($honourinfo)) && !in_array($uhstr,$uhonour)){
			$uhonour[] = $uhstr;
		}
	}
	$gainhonour = $pldata['gainhonour'];
	if($pldata['killnum'] >= 1000){$gainhonour .= 'kA';}
	elseif($pldata['killnum'] >= 500){$gainhonour .= 'k5';}
	elseif($pldata['killnum'] >= 333){$gainhonour .= 'k3';}
	elseif($pldata['killnum'] >= 200){$gainhonour .= 'k2';}
	elseif($pldata['killnum'] >= 100){$gainhonour .= 'k1';}
	
	$plitmstr = $pldata['wep'] .'_'. $pldata['arb'] .'_'. $pldata['arh'] .'_'. $pldata['ara'] .'_'. $pldata['arf'] .'_'. $pldata['art'] .'_'. $pldata['itm0'] .'_'. $pldata['itm1'] .'_'. $pldata['itm2'] .'_'. $pldata['itm3'] .'_'. $pldata['itm4'] .'_'. $pldata['itm5'] .'_'. $pldata['itm6'];
	if(strpos($plitmstr,'★Unlimited Blade Works★')!==false && strpos($plitmstr,'★Unlimited Code Works★')!==false && strpos($plitmstr,'★受★王★拳★')!==false){
		$gainhonour .= 'iz';
	}
	if(strpos($plitmstr,'【KEY系催泪弹】')!==false){
		$gainhonour .= 'ik';
	}
	
	for($i = 0; $i < strlen($gainhonour); $i+=2){
		$nhstr = substr($gainhonour,$i,2);
		if(in_array($nhstr,array_keys($honourinfo)) && !in_array($nhstr,$uhonour) && !in_array($nhstr,$nhonour)){
			$nhonour[] = $nhstr;
		}
	}
	$obtain = implode('',$nhonour);
	return $obtain;
}

function set_credits(){
	global $db,$tablepre,$winmode,$gamenum,$winner;
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='0'");
	$list = $honourlist = $creditlist = $updatelist = Array();
	while($data = $db->fetch_array($result)){
		$list[$data['name']]['players'] = $data;
	}	
	$result = $db->query("SELECT * FROM {$tablepre}users WHERE lastgame='$gamenum'");
	while($data = $db->fetch_array($result)){
		$list[$data['username']]['users'] = $data;
	}
	foreach($list as $key => $val){
		if(isset($val['players']) && isset($val['users'])){
			$credits = get_credit_up($val['players'],$winner,$winmode) + $val['users']['credits'];
			$honour = $val['users']['honour'] . get_honour_obtain($val['players'],$val['users']);
			$updatelist[] = Array('username' => $key, 'credits' => $credits, 'honour' => $honour);
		}
	}
	$db->multi_update("{$tablepre}users", $updatelist, 'username', "lastgame='$gamenum'");
	
	return;
}

function update_gamemap(){//自动生成地图
	global $mapdata;
	return;
	$gamemaphtm = GAME_ROOT.TPLDIR.'/gamemap.htm';
	for($i=0;$i<count($mapdata);$i++){
		$mpp[$mapdata[$i]['mapx']][$mapdata[$i]['mapy']]=$i;
	}
	
	
	$mapcontent = '<TABLE border="1" cellspacing="0" cellpadding="0" align=center background="map/map.jpg" style="position:relative;background-repeat:no-repeat;background-position:right bottom;">';
	
	for($i=1;$i<=10;$i++){
		$mapcontent .= '<tr align="center">';
		for($j=1;$j<=10;$j++){
			if(isset($mpp[$i][$j])){
				if($mpp[$i][$j] == 30){
					$mapcontent .= '<!--{if $arealock}-->
			<td width="38" height="38" class="map2" align=middle><IMG src="map/blank.gif" width="38" height="38" border=0></td>
		<!--{else}-->
			<td width="38" height="38" align="middle" id="30"
				<!--{if CURSCRIPT == \'game\'}-->
					<!--{if $pdata[\'pls\'] == 30}-->
						class="maptdyellow" <!--{if $mode == \'command\'}-->onclick="$(\'command\').value=\'search\';postCommand();"<!--{/if}--> title="{lang search}"
					<!--{elseif in_array(30,array_keys($nmap))}-->
						class="maptdlime" <!--{if $mode == \'command\'}-->onclick="$(\'command\').value=\'move\';$(\'subcmd\').name=\'moveto\',$(\'subcmd\').value=\'30\';postCommand();"<!--{/if}--> title="{$nmap[30]}"
					<!--{else}-->
						class="map2"
					<!--{/if}-->
				<!--{else}-->class="map2"<!--{/if}-->><span class="mapspanlime">'.$mapdata[$mpp[$i][$j]]['name'].'</span></td>
		<!--{/if}-->';
				}else{
					$mapcontent .= '<td width="38" height="38" align="middle" id="'.$mpp[$i][$j].'"
			<!--{if CURSCRIPT == \'game\'}-->
				<!--{if $pdata[\'pls\'] == '.$mpp[$i][$j].'}-->
					class="maptdyellow" <!--{if $mode == \'command\'}-->onclick="$(\'command\').value=\'search\';postCommand();"<!--{/if}--> title="{lang search}"
				<!--{elseif in_array('.$mpp[$i][$j].',array_keys($nmap))}-->
					class="maptdlime" <!--{if $mode == \'command\'}-->onclick="$(\'command\').value=\'move\';$(\'subcmd\').name=\'moveto\',$(\'subcmd\').value=\''.$mpp[$i][$j].'\';postCommand();"<!--{/if}--> title="{$nmap['.$mpp[$i][$j].']}"
				<!--{else}-->
					class="map2"
				<!--{/if}-->
			<!--{else}-->class="map2"<!--{/if}-->><span 
				<!--{if $hack || array_search('.$mpp[$i][$j].',$arealist) > ($areanum + $areaadd)}-->
					class="mapspanlime"
				<!--{elseif array_search('.$mpp[$i][$j].',$arealist) <= $areanum}-->
					class="mapspanred"
				<!--{else}-->
					class="mapspanyellow"
				<!--{/if}-->>'.$mapdata[$mpp[$i][$j]]['name'].'</span></td>';
				}
				
			}else{
				$mapcontent .= '<td width="38" height="38" class="map2" align=middle><IMG src="map/blank.gif" width="38" height="38" border=0></td>';
			}
		}
		$mapcontent .= '</tr>';
	}
	$mapcontent .= '</table>';
	writeover($gamemaphtm,$mapcontent);
	return;
}

function update_radar(){
	global $mapdata,$typeinfo;
	return;
	$filehtm = GAME_ROOT.TPLDIR.'/radar.htm';
	$tplist = Array(0,3,4,11,12,13);
	$mapnamewidth = 100;
	$tdheight = 10;
	$screenheight = count($mapdata)*$tdheight;
	$cantdetect = '??'; $forbidstr='<span class="red">禁</span>';
	
	$radarscreen = '<div style="width:400px;height:400px;overflow:auto;overflow-x:hidden"><table style="width:400px;vertical-align:middle;"><tbody>';
	$radarscreen .= '<tr><td class="td1" style="height:'.$tdheight.'px;width:'.$mapnamewidth.'px"><div></div></td>';
	foreach ($tplist as $value){
		$radarscreen .= '<td class=td1><div>'.$typeinfo[$value].'</div></td>';
	}
	$radarscreen .= '</tr>';
	
	for($i=0;$i<count($mapdata)-1;$i++) {//去掉冬木市
		$radarscreen .= '<tr><td class=td2 style="height:'.$tdheight.'px"><div>'.$mapdata[$i]['name'].'</div></td>';
		foreach ($tplist as $value){
			$radarscreen .= '<td class=td2><div><!--{if array_search('.$i.',$arealist)>$areanum||$hack}--><!--{if $pls == '.$i.'}--><span class="yellow">{$radar['.$i.']['.$value.']}</span><!--{elseif $level > 0}-->{$radar['.$i.']['.$value.']}<!--{else}-->'.$cantdetect.'<!--{/if}--><!--{else}-->'.$forbidstr.'<!--{/if}--></div></td>';
		}
		$radarscreen .= '</tr>';
	}
	$radarscreen .= '</tbody></table></div>';
	writeover($filehtm,$radarscreen);
	return;
}

?>