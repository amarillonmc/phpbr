<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function rs_game($mode = 0) {
	global $db,$tablepre,$gamecfg,$now,$gamestate,$plsinfo,$typeinfo,$areanum,$areaadd;
	$stime=getmicrotime();
	$dir = GAME_ROOT.'./gamedata/';
	$sqldir = GAME_ROOT.'./gamedata/sql/';
	if ($mode & 1) {
		//echo " - 消息初始化 - ";
		//清空玩家log
		//dir_clear("{$dir}log/");
		
		$sql = file_get_contents("{$sqldir}log.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		runquery($sql);
		//清空聊天信息
		$sql = file_get_contents("{$sqldir}chat.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		runquery($sql);
		//清空地图道具
		$sql = file_get_contents("{$sqldir}mapitem.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		runquery($sql);
//		$plsnum = sizeof($plsinfo);
//		for ($p=0;$p < $plsnum;$p++){
//			$rqry = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre.$p, $sql));
//			runquery($rqry);
//		}
		//清空游戏进行状况
		if($fp = fopen("{$dir}newsinfo.php", 'wb')) {
			global $checkstr;
			fwrite($fp, $checkstr);
			fclose($fp);
		} else {
			gexit('Can not write to cache files, please check directory ./gamedata/ and ./gamedata/cache/ .', __file__, __line__);
		}
		
		$sql = file_get_contents("{$sqldir}newsinfo.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		runquery($sql);
		
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
		$areatime = (ceil(($starttime + $areahour*60)/600))*600;//$areahour已改为按分钟计算，ceil是为了让禁区分钟为10的倍数
		$plsnum = sizeof($plsinfo);
		$arealist = range(1,$plsnum-1);
		shuffle($arealist);
		array_unshift($arealist,0);
		$areanum = 0;
		$weather = rand(0,9);
		$hack = 0;
		movehtm($areatime);
	}
	if ($mode & 4) {
		//echo " - 角色数据库初始化 - ";
		global $validnum,$alivenum,$deathnum;
		$sql = file_get_contents("{$sqldir}players.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		runquery($sql);
		$validnum = $alivenum = $deathnum = 0;
	}
	if ($mode & 8) {
		//echo " - NPC初始化 - ";
		$db->query("DELETE FROM {$tablepre}players WHERE type>0 ");
		include_once config('npc',$gamecfg);
		$typenum = sizeof($typeinfo);
		$plsnum = sizeof($plsinfo);
		$npcqry = '';
		
		for($i = 1; $i < $typenum; $i++) {
			if(isset($npcinfo[$i])) {
				for($j = 1; $j <= $npcinfo[$i]['num']; $j++) {
					$npc = $npcinfo[$i];
					$npc['type'] = $i;
					$npc['endtime'] = $now;
					$npc['exp'] = round(($npc['lvl']*$GLOBALS['baseexp'])+(($npc['lvl']+1)*$GLOBALS['baseexp']));
					$npc['sNo'] = $j;
					$npc['hp'] = $npc['mhp'];
					$npc['sp'] = $npc['msp'];
					$npc['state'] = 0;
					$npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];
					if($npc['gd'] == 'r'){$npc['gd'] = rand(0,1) ? 'm':'f';}
					if($npc['pls'] == 99){$npc['pls'] = rand(1,$plsnum-1);}
					/*
					if(($npc['mode'] == 1)&&($npc['num'] <= $npc['sub'])){
						$npc = array_merge($npc,$npc[$j]);
					} elseif($npc['mode'] == 2) {
						$k = rand(1,$npc['sub']);
						$npc = array_merge($npc,$npc[$k]);
					} else {
						$npc = array_merge($npc,$npc[1]);
					}
					*/
					$subnum = sizeof($npc['sub']);
					$sub = $j % $subnum;
					$npc = array_merge($npc,$npc['sub'][$sub]);
					$npcqry .= "('".$npc['name']."','".$npc['pass']."','".$npc['type']."','".$npc['endtime']."','".$npc['gd']."','".$npc['sNo']."','".$npc['icon']."','".$npc['club']."','".$npc['hp']."','".$npc['mhp']."','".$npc['sp']."','".$npc['msp']."','".$npc['att']."','".$npc['def']."','".$npc['pls']."','".$npc['lvl']."','".$npc['exp']."','".$npc['money']."','".$npc['bid']."','".$npc['inf']."','".$npc['rage']."','".$npc['pose']."','".$npc['tactic']."','".$npc['killnum']."','".$npc['death']."','".$npc['wp']."','".$npc['wk']."','".$npc['wg']."','".$npc['wc']."','".$npc['wd']."','".$npc['wf']."','".$npc['teamID']."','".$npc['teamPass']."','".$npc['wep']."','".$npc['wepk']."','".$npc['wepe']."','".$npc['weps']."','".$npc['arb']."','".$npc['arbk']."','".$npc['arbe']."','".$npc['arbs']."','".$npc['arh']."','".$npc['arhk']."','".$npc['arhe']."','".$npc['arhs']."','".$npc['ara']."','".$npc['arak']."','".$npc['arae']."','".$npc['aras']."','".$npc['arf']."','".$npc['arfk']."','".$npc['arfe']."','".$npc['arfs']."','".$npc['art']."','".$npc['artk']."','".$npc['arte']."','".$npc['arts']."','".$npc['itm0']."','".$npc['itmk0']."','".$npc['itme0']."','".$npc['itms0']."','".$npc['itm1']."','".$npc['itmk1']."','".$npc['itme1']."','".$npc['itms1']."','".$npc['itm2']."','".$npc['itmk2']."','".$npc['itme2']."','".$npc['itms2']."','".$npc['itm3']."','".$npc['itmk3']."','".$npc['itme3']."','".$npc['itms3']."','".$npc['itm4']."','".$npc['itmk4']."','".$npc['itme4']."','".$npc['itms4']."','".$npc['itm5']."','".$npc['itmk5']."','".$npc['itme5']."','".$npc['itms5']."','".$npc['wepsk']."','".$npc['arbsk']."','".$npc['arhsk']."','".$npc['arask']."','".$npc['arfsk']."','".$npc['artsk']."','".$npc['itmsk0']."','".$npc['itmsk1']."','".$npc['itmsk2']."','".$npc['itmsk3']."','".$npc['itmsk4']."','".$npc['itmsk5']."'),";
					//$db->query("INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5) VALUES ('".$npc['name']."','".$npc['pass']."','".$npc['type']."','".$npc['endtime']."','".$npc['gd']."','".$npc['sNo']."','".$npc['icon']."','".$npc['club']."','".$npc['hp']."','".$npc['mhp']."','".$npc['sp']."','".$npc['msp']."','".$npc['att']."','".$npc['def']."','".$npc['pls']."','".$npc['lvl']."','".$npc['exp']."','".$npc['money']."','".$npc['bid']."','".$npc['inf']."','".$npc['rage']."','".$npc['pose']."','".$npc['tactic']."','".$npc['killnum']."','".$npc['death']."','".$npc['wp']."','".$npc['wk']."','".$npc['wg']."','".$npc['wc']."','".$npc['wd']."','".$npc['wf']."','".$npc['teamID']."','".$npc['teamPass']."','".$npc['wep']."','".$npc['wepk']."','".$npc['wepe']."','".$npc['weps']."','".$npc['arb']."','".$npc['arbk']."','".$npc['arbe']."','".$npc['arbs']."','".$npc['arh']."','".$npc['arhk']."','".$npc['arhe']."','".$npc['arhs']."','".$npc['ara']."','".$npc['arak']."','".$npc['arae']."','".$npc['aras']."','".$npc['arf']."','".$npc['arfk']."','".$npc['arfe']."','".$npc['arfs']."','".$npc['art']."','".$npc['artk']."','".$npc['arte']."','".$npc['arts']."','".$npc['itm0']."','".$npc['itmk0']."','".$npc['itme0']."','".$npc['itms0']."','".$npc['itm1']."','".$npc['itmk1']."','".$npc['itme1']."','".$npc['itms1']."','".$npc['itm2']."','".$npc['itmk2']."','".$npc['itme2']."','".$npc['itms2']."','".$npc['itm3']."','".$npc['itmk3']."','".$npc['itme3']."','".$npc['itms3']."','".$npc['itm4']."','".$npc['itmk4']."','".$npc['itme4']."','".$npc['itms4']."','".$npc['itm5']."','".$npc['itmk5']."','".$npc['itme5']."','".$npc['itms5']."','".$npc['wepsk']."','".$npc['arbsk']."','".$npc['arhsk']."','".$npc['arask']."','".$npc['arfsk']."','".$npc['artsk']."','".$npc['itmsk0']."','".$npc['itmsk1']."','".$npc['itmsk2']."','".$npc['itmsk3']."','".$npc['itmsk4']."','".$npc['itmsk5']."')");
					unset($npc);
				}
			}
		}
		if(!empty($npcqry)){
			$npcqry = "INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5) VALUES ".substr($npcqry, 0, -1);
			$db->query($npcqry);
			unset($npcqry);
		}
	}
	if ($mode & 16) {
		//echo " - 地图道具初始化 - ";
		//感谢 Martin1994 提供地图道具数据库化的源代码
		$plsnum = sizeof($plsinfo);
		$qrycmd = '';
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
		$in = sizeof($itemlist);
		$an = $areanum ? ceil($areanum/$areaadd) : 0;
		//$mapitem = array();
		//$ifqry = $qrycmd = 'INSERT INTO '.$tablepre.'mapitem (itm,itmk,itme,itms,itmsk,map) VALUES ';
		for($i = 1; $i < $in; $i++) {
			list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
			if(($iarea == $an)||($iarea == 99)) {
				for($j = $inum; $j>0; $j--) {
					if($imap == 99) {
						$rmap = rand(1,$plsnum-1);
						$qrycmd .= "('$iname', '$ikind','$ieff','$ista','$iskind','$rmap'),";
						//$qrycmd[$rmap] .= "('$iname', '$ikind','$ieff','$ista','$iskind'),";
						//$db->query("INSERT INTO {$tablepre}{$rmap}mapitem (itm,itmk,itme,itms,itmsk) VALUES ('$iname', '$ikind','$ieff','$ista','$iskind')");
					}else{
						$qrycmd .= "('$iname', '$ikind','$ieff','$ista','$iskind','$imap'),";
						//$db->query("INSERT INTO {$tablepre}{$imap}mapitem (itm,itmk,itme,itms,itmsk) VALUES ('$iname', '$ikind','$ieff','$ista','$iskind')");
					}
					
					//if($imap == 99) {
					//	$imap = rand(1,$plsnum-1);
						//$mapitem[$rmap] .= "$iname,$ikind,$ieff,$ista,$iskind,\n";
					//} else {
						//$mapitem[$imap] .= "$iname,$ikind,$ieff,$ista,$iskind,\n"; 
					//}
				}
			}
		}
		if(!empty($qrycmd)){
			$qrycmd = "INSERT INTO {$tablepre}mapitem (itm,itmk,itme,itms,itmsk,pls) VALUES ".substr($qrycmd, 0, -1);
			$db->query($qrycmd);
		}
//		for($imap = 0;$imap<$plsnum;$imap++){
//			if(!empty($qrycmd[$imap])){
//				$qrycmd[$imap] = "INSERT INTO {$tablepre}{$imap}mapitem (itm,itmk,itme,itms,itmsk) VALUES ".substr($qrycmd[$imap], 0, -1);
//				$db->query($qrycmd[$imap]);
//			}	
//		}
//		if($ifqry != $qrycmd){//判定是否有数据写入
//			$qrycmd = substr($qrycmd, 0, -1);//去除尾部多余的逗号
//			$db->query($qrycmd);
//		}
//		foreach($mapitem as $map => $itemdata) {
//			$mapfile = GAME_ROOT."./gamedata/mapitem/{$map}mapitem.php";
//			writeover($mapfile,$itemdata,'ab');
//		}
		
		unset($itemlist);unset($qrycmd);
		//unset($mapitem);
		
	}
	if ($mode & 32) {
		//echo " - 商店初始化 - ";
		$sql = file_get_contents("{$sqldir}shopitem.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		runquery($sql);
		
		$file = config('shopitem',$gamecfg);
		$shoplist = openfile($file);
		$qry = '';
		foreach($shoplist as $lst){
			list($kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk)=explode(',',$lst);
			if($kind != 0){
				$qry .= "('$kind','$num','$price','$area','$item','$itmk','$itme','$itms','$itmsk'),";
			}
		}
		if(!empty($qry)){
			$qry = "INSERT INTO {$tablepre}shopitem (kind,num,price,area,item,itmk,itme,itms,itmsk) VALUES ".substr($qry, 0, -1);
		}
		$db->query($qry);
		
//		global $checkstr;
//		dir_clear("{$dir}shopitem/");
//		$file = config('shopitem',$gamecfg);
//		$shoplist = openfile($file);
//		$type = 0;
//		$in = count($shoplist);
//		for($i=1;$i<$in;$i++) {
//			list($a,$b) = explode(',',$shoplist[$i]);
//			if(($a == '0')&&((int)$b>$type)) {
//				$type = $b;
//				$shopitem[$type] = $checkstr;
//			} else {
//				$shopitem[$type] .= $shoplist[$i];
//			}
//		}
//		foreach($shopitem as $s => $sdata) {
//			$sfile = GAME_ROOT."./gamedata/shopitem/{$s}shopitem.php";
//			writeover($sfile,$sdata,'ab');
//		}
	}
	$etime=getmicrotime();
	global $gamenum;
	putmicrotime($stime,$etime,'loadtime.txt','第'.$gamenum.'局游戏');
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
	global $db,$tablepre,$now,$gamestate,$areaesc,$arealist,$areanum,$arealimit,$areaadd,$plsinfo,$weather,$hack,$validnum,$alivenum,$deathnum;
	
	if (($gamestate > 10)&&($now > $atime)) {
		$plsnum = sizeof($plsinfo) - 1;
		if(($areanum >= $arealimit*$areaadd)&&($validnum<=0)) {//无人参加GAMEOVER不是因为这里，这里只是保险。
			gameover($atime,'end4');
			return;
		} elseif(($areanum + $areaadd) >= $plsnum) {
			$areaaddlist = array_slice($arealist,$areanum+1);
			$areanum = $plsnum;
			$weather = rand(0,9);
			//addnews($atime,'addarea',$areaaddlist,$weather);
			naddnews($atime, 'addarea',$areaaddlist,$weather);
			$query = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 AND hp>0");
			while($sub = $db->fetch_array($query)) {
				$pid = $sub['pid'];
				$hp = 0;
				$state = 11;
				$deathpls = $sub['pls'];
				$bid = 0;
				$endtime = $atime;
				$db->query("UPDATE {$tablepre}players SET hp='$hp', bid='$bid', state='$state', endtime='$endtime' WHERE pid=$pid");
				naddnews($endtime,"death$state",$sub['name'],$sub['type'],$deathpls);
			}
			$db->free_result($query);
			$alivenum = 0;
			$dquery = $db->query("SELECT pid FROM {$tablepre}players WHERE hp<=0");
			$deathnum = $db->num_rows($dquery);
			$db->free_result($dquery);
			gameover($atime,'end1');
			return;
		} else {
			$weather = rand(0,9);
			if($hack > 0){$hack--;}
			$areaaddlist = array_slice($arealist,$areanum+1,$areaadd);
			$areanum += $areaadd;
			movehtm();
			//addnews($atime,'addarea',$areaaddlist,$weather);
			naddnews($atime, 'addarea',$areaaddlist,$weather);
			$str_arealist = implode(',',array_slice($arealist,0,$areanum+1));
			$query = $db->query("SELECT * FROM {$tablepre}players WHERE pls IN ($str_arealist) AND hp>0");
			while($sub = $db->fetch_array($query)) {
				$pid = $sub['pid'];
				if(!$sub['type']) {
					if(($gamestate >= 40)||(!$areaesc&&($sub['tactic']!=4))) {
					$hp = 0;
					$state = 11;
					$deathpls = $sub['pls'];
					$bid = 0;
					$endtime = $atime;
					$db->query("UPDATE {$tablepre}players SET hp='$hp', bid='$bid', state='$state', endtime='$endtime' WHERE pid=$pid");
					naddnews($endtime,"death$state",$sub['name'],$sub['type'],$deathpls);
					$deathnum++;
					} else {
					$pls = $arealist[rand($areanum+1,$plsnum)];
					$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid ");
					}
				} elseif($sub['type'] != 1 && $sub['type'] != 7 && $sub['type'] != 9) {
					$pls = $arealist[rand($areanum+1,$plsnum)];
					$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid");
				}
			}
			$alivenum = $db->result($db->query("SELECT COUNT(*) FROM {$tablepre}players WHERE hp>0 AND type=0"), 0);
			if(($alivenum == 1)&&($gamestate >= 30)) { 
				gameover($atime);
				return;
			} elseif(($alivenum <= 0)&&($gamestate >= 30)) {
				gameover($atime,'end1');
				return $atime;
			} else {
				rs_game(16+32);
				//$areatime += $areahour*3600;
				//addarea($areatime);
				return;
			}
		}
	} else {
		return;
	}
}

function runquery($sql) {
	global $dbcharset, $tablepre, $db;

	$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				$db->query(createtable($query, $dbcharset));
			} else {
				$db->query($query);
			}
		}
	}
	return;
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
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

function gameover($time = 0, $mode = '', $winname = '') {
	global $gamestate,$winmode,$alivenum,$winner,$now,$gamenum,$db,$tablepre,$gamenum,$starttime,$validnum,$hdamage,$hplayer;
	if($gamestate < 10){return;}
	if((!$mode)||(($mode==2)&&(!$winname))) {
		if($validnum <= 0) {
			$alivenum = 0;
			$winmode = 4;
			$winner = '';
			
		} else {
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
			$alivenum = $db->num_rows($result);
			if(!$alivenum) { 
				$winmode = 1;
				$winner = '';
			} elseif($alivenum == 1) {
				$winmode = 2;
				$wdata = $db->fetch_array($result);
				$winner = $wdata['name'];
				$db->query("UPDATE {$tablepre}players SET state='5' where pid='{$wdata['pid']}'");
			} else {
				save_gameinfo();
				return;
			}
		}
	} else {
		$winmode = substr($mode,3,1);
		$winner = $winname;
	}
	$time = $time ? $time : $now;
	$result = $db->query("SELECT gid FROM {$tablepre}winners ORDER BY gid DESC LIMIT 1");
	if($db->num_rows($result)&&($gamenum <= $db->result($result, 0))) {
		$gamenum = $db->result($result, 0) + 1;
	}
	if($winmode == 4){//无人参加
		$getime = $time;
		$db->query("INSERT INTO {$tablepre}winners (gid,wmode,vnum,getime) VALUES ('$gamenum','$winmode','$validnum','$getime')");
	}	elseif(($winmode == 0)||($winmode == 1)||($winmode == 6)){//程序故障、全部死亡、GM中止
		$gstime = $starttime;
		$getime = $time;
		$gtime = $time - $starttime;
		$db->query("INSERT INTO {$tablepre}winners (gid,wmode,vnum,gtime,gstime,getime,hdmg,hdp) VALUES ('$gamenum','$winmode','$validnum','$gtime','$gstime','$getime','$hdamage','$hplayer')");
	} else {//最后幸存、锁定解除、核爆全灭
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$winner' AND type=0");
		$pdata = $db->fetch_array($result);
		$result2 = $db->query("SELECT motto FROM {$tablepre}users WHERE username='$winner'");
		$pdata['motto'] = $db->result($result2, 0);
		$result3 = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
		$hk = $db->fetch_array($result3);
		$pdata['hkill'] = $hk['killnum'];
		$pdata['hkp'] = $hk['name'];
		$pdata['wmode'] = $winmode;
		$pdata['vnum'] = $validnum;
		$pdata['gtime'] = $time - $starttime;
		$pdata['gstime'] = $starttime;
		$pdata['getime'] = $time;
		$pdata['hdmg'] = $hdamage;
		$pdata['hdp'] = $hplayer;
		$db->query("INSERT INTO {$tablepre}winners (gid,name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,motto,wmode,vnum,gtime,gstime,getime,hdmg,hdp,hkill,hkp,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5) VALUES ('".$gamenum."','".$pdata['name']."','".$pdata['pass']."','".$pdata['type']."','".$pdata['endtime']."','".$pdata['gd']."','".$pdata['sNo']."','".$pdata['icon']."','".$pdata['club']."','".$pdata['hp']."','".$pdata['mhp']."','".$pdata['sp']."','".$pdata['msp']."','".$pdata['att']."','".$pdata['def']."','".$pdata['pls']."','".$pdata['lvl']."','".$pdata['exp']."','".$pdata['money']."','".$pdata['bid']."','".$pdata['inf']."','".$pdata['rage']."','".$pdata['pose']."','".$pdata['tactic']."','".$pdata['killnum']."','".$pdata['death']."','".$pdata['wp']."','".$pdata['wk']."','".$pdata['wg']."','".$pdata['wc']."','".$pdata['wd']."','".$pdata['wf']."','".$pdata['teamID']."','".$pdata['teamPass']."','".$pdata['wep']."','".$pdata['wepk']."','".$pdata['wepe']."','".$pdata['weps']."','".$pdata['arb']."','".$pdata['arbk']."','".$pdata['arbe']."','".$pdata['arbs']."','".$pdata['arh']."','".$pdata['arhk']."','".$pdata['arhe']."','".$pdata['arhs']."','".$pdata['ara']."','".$pdata['arak']."','".$pdata['arae']."','".$pdata['aras']."','".$pdata['arf']."','".$pdata['arfk']."','".$pdata['arfe']."','".$pdata['arfs']."','".$pdata['art']."','".$pdata['artk']."','".$pdata['arte']."','".$pdata['arts']."','".$pdata['itm0']."','".$pdata['itmk0']."','".$pdata['itme0']."','".$pdata['itms0']."','".$pdata['itm1']."','".$pdata['itmk1']."','".$pdata['itme1']."','".$pdata['itms1']."','".$pdata['itm2']."','".$pdata['itmk2']."','".$pdata['itme2']."','".$pdata['itms2']."','".$pdata['itm3']."','".$pdata['itmk3']."','".$pdata['itme3']."','".$pdata['itms3']."','".$pdata['itm4']."','".$pdata['itmk4']."','".$pdata['itme4']."','".$pdata['itms4']."','".$pdata['itm5']."','".$pdata['itmk5']."','".$pdata['itme5']."','".$pdata['itms5']."','".$pdata['motto']."','".$pdata['wmode']."','".$pdata['vnum']."','".$pdata['gtime']."','".$pdata['gstime']."','".$pdata['getime']."','".$pdata['hdmg']."','".$pdata['hdp']."','".$pdata['hkill']."','".$pdata['hkp']."','".$pdata['wepsk']."','".$pdata['arbsk']."','".$pdata['arhsk']."','".$pdata['arask']."','".$pdata['arfsk']."','".$pdata['artsk']."','".$pdata['itmsk0']."','".$pdata['itmsk1']."','".$pdata['itmsk2']."','".$pdata['itmsk3']."','".$pdata['itmsk4']."','".$pdata['itmsk5']."')");
	}
	rs_sttime();
	$gamestate = 0;
	save_gameinfo();
	//echo '**游戏结束**';
	//$gamestate = 0;
	//addnews($time, "end$winmode" , $winner);
	naddnews($time, "end$winmode",$winner);
	//addnews($time, 'gameover',$gamenum);
	naddnews($time, 'gameover' ,$gamenum);
	require_once './include/news.func.php';
	$newsinfo = nparse_news(0,65535);
	writeover(GAME_ROOT."./gamedata/bak/{$gamenum}_newsinfo.php",$newsinfo,'wb+');
	//writeover(GAME_ROOT."./gamedata/bak/{$gamenum}_newsinfo.php",readover(GAME_ROOT.'./gamedata/newsinfo.php'),'wb+');
	//rs_sttime();
	//save_gameinfo();
	return;
}

function movehtm($atime = 0) {
	global $plsinfo,$arealist,$areanum,$hack,$pls,$xyinfo,$areahour,$areaadd;

	$movehtm = GAME_ROOT.TPLDIR.'/move.htm';
	$movedata = '<option value="main">■ 移动 ■<br />';

	foreach($plsinfo as $key => $value) {
		if(array_search($key,$arealist) > $areanum || $hack){
		$movedata .= "<option value=\"$key\"><!--{if \$pls == $key}--><--现在位置--><!--{else}-->$value($xyinfo[$key])<!--{/if}--><br />";
		}
	} 
	writeover($movehtm,$movedata);
	
	$areahtm = GAME_ROOT.TPLDIR.'/areainfo.htm';
	$areadata = '<span class="evergreen"><b>现在的禁区是：</b></span>';
	for($i=0;$i<=$areanum;$i++){
		$areadata .= '&nbsp;'.$plsinfo[$arealist[$i]];
	}
	$areadata .= '<br><span class="evergreen"><b>下回的禁区是：</b></span>';
	
	if(!$atime){
		global $areatime;
		$atime = $areatime;
	}
	if($areanum < count($plsinfo)) {
		$at= getdate($atime);
		$nexthour = $at['hours'];$nextmin = $at['minutes'];
		while($nextmin >= 60){
			$nexthour +=1;$nextmin -= 60;
		}
		if($nexthour >= 24){$nexthour-=24;}
		$areadata .= "<b>{$nexthour}时{$nextmin}分：</b> ";
		for($i=1;$i<=$areaadd;$i++) {
			$areadata .= '&nbsp;'.$plsinfo[$arealist[$areanum+$i]].'&nbsp;';
		}
	}
	if($areanum+$areaadd < count($plsinfo)) {
		$at2= getdate($atime + $areahour*60);
		$nexthour2 = $at2['hours'];$nextmin2 = $at2['minutes'];
		while($nextmin2 >= 60){
			$nexthour2 +=1;$nextmin2 -= 60;
		}
		if($nexthour2 >= 24){$nexthour2-=24;}
		$areadata .= "；<b>{$nexthour2}时{$nextmin2}分：</b> ";
		for($i=1;$i<=$areaadd;$i++) {
			$areadata .= '&nbsp;'.$plsinfo[$arealist[$areanum+$areaadd+$i]].'&nbsp;';
		}
	}
	if($areanum+$areaadd*2 < count($plsinfo)) {
		$at3= getdate($atime + $areahour*120);
		$nexthour3 = $at3['hours'];$nextmin3 = $at3['minutes'];
		while($nextmin3 >= 60){
			$nexthour3 +=1;$nextmin3 -= 60;
		}
		if($nexthour3 >= 24){$nexthour3-=24;}
		$areadata .= "；<b>{$nexthour3}时{$nextmin3}分：</b> ";
		for($i=1;$i<=$areaadd;$i++) {
			$areadata .= '&nbsp;'.$plsinfo[$arealist[$areanum+$areaadd*2+$i]].'&nbsp;';
		}
	}
	writeover($areahtm,$areadata);
	return;
}

//function addnpc($type,$sub,$num=1,$time=0) {
//	global $now,$db,$tablepre,$log,$plsinfo,$typeinfo;
//	$time = $time == 0 ? $now : $time;
//	$plsnum = sizeof($plsinfo);
//	include_once config('addnpc',$gamecfg);
//	$npc=$npcinfo[$type];
//	if(!$npc){
//		//echo 'no npc.';
//		return;
//	} else {
//		$npc = array_merge($npc,$npc['sub'][$sub]);
//		for($i=0;$i< $num;$i++){
//			$npc['type'] = $type;
//			$npc['endtime'] = $time;
//			$npc['exp'] = round(($npc['lvl']*2+1)*$GLOBALS['baseexp']);
//			$npc['sNo'] = $i;
//			$npc['hp'] = $npc['mhp'];
//			$npc['sp'] = $npc['msp'];
//			$npc['state'] = 0;
//			$npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];
//			if($npc['gd'] == 'r'){$npc['gd'] = rand(0,1) ? 'm':'f';}
//			if($npc['pls'] == 99){$npc['pls'] = rand(1,$plsnum-1);}
//			$db->query("INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5) VALUES ('".$npc['name']."','".$npc['pass']."','".$npc['type']."','".$npc['endtime']."','".$npc['gd']."','".$npc['sNo']."','".$npc['icon']."','".$npc['club']."','".$npc['hp']."','".$npc['mhp']."','".$npc['sp']."','".$npc['msp']."','".$npc['att']."','".$npc['def']."','".$npc['pls']."','".$npc['lvl']."','".$npc['exp']."','".$npc['money']."','".$npc['bid']."','".$npc['inf']."','".$npc['rage']."','".$npc['pose']."','".$npc['tactic']."','".$npc['killnum']."','".$npc['death']."','".$npc['wp']."','".$npc['wk']."','".$npc['wg']."','".$npc['wc']."','".$npc['wd']."','".$npc['wf']."','".$npc['teamID']."','".$npc['teamPass']."','".$npc['wep']."','".$npc['wepk']."','".$npc['wepe']."','".$npc['weps']."','".$npc['arb']."','".$npc['arbk']."','".$npc['arbe']."','".$npc['arbs']."','".$npc['arh']."','".$npc['arhk']."','".$npc['arhe']."','".$npc['arhs']."','".$npc['ara']."','".$npc['arak']."','".$npc['arae']."','".$npc['aras']."','".$npc['arf']."','".$npc['arfk']."','".$npc['arfe']."','".$npc['arfs']."','".$npc['art']."','".$npc['artk']."','".$npc['arte']."','".$npc['arts']."','".$npc['itm0']."','".$npc['itmk0']."','".$npc['itme0']."','".$npc['itms0']."','".$npc['itm1']."','".$npc['itmk1']."','".$npc['itme1']."','".$npc['itms1']."','".$npc['itm2']."','".$npc['itmk2']."','".$npc['itme2']."','".$npc['itms2']."','".$npc['itm3']."','".$npc['itmk3']."','".$npc['itme3']."','".$npc['itms3']."','".$npc['itm4']."','".$npc['itmk4']."','".$npc['itme4']."','".$npc['itms4']."','".$npc['itm5']."','".$npc['itmk5']."','".$npc['itme5']."','".$npc['itms5']."','".$npc['wepsk']."','".$npc['arbsk']."','".$npc['arhsk']."','".$npc['arask']."','".$npc['arfsk']."','".$npc['artsk']."','".$npc['itmsk0']."','".$npc['itmsk1']."','".$npc['itmsk2']."','".$npc['itmsk3']."','".$npc['itmsk4']."','".$npc['itmsk5']."')");
////			$newsname=$typeinfo[$type].' '.$npc['name'];
////			addnews($now, 'addnpc', $newsname);
//		}
//	}
//	if($num > $npc['num']){
//		$newsname=$typeinfo[$type];
//		naddnews($time, 'addnpcs', $newsname,$i);
//	}else{
//		$newsname=$typeinfo[$type].' '.$npc['name'];
//		naddnews($time, 'addnpc', $newsname);
//	}
//	return $i;
//}


?>