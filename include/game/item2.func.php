<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function poison($itmn = 0) {
	global $mode,$log,$nosta,$art,$club,$pid;
	global $itmp,${'itm'.$itmp},${'itms'.$itmp},${'itmk'.$itmp},${'itme'.$itmp},${'itmsk'.$itmp};
	$poison = & ${'itm'.$itmp};
	$poisonk = & ${'itmk'.$itmp};
	$poisone = & ${'itme'.$itmp};
	$poisons = & ${'itms'.$itmp};
	$poisonsk = & ${'itmsk'.$itmp};
	if ( $itmn < 1 || $itmn > 5 ) {
		$log .= '此道具不存在，请重新选择。';
		$mode = 'command';
		return;
	}
	global ${'itm'.$itmn},${'itmk'.$itmn},${'itmsk'.$itmn};
	$itm = & ${'itm'.$itmn};
	$itmk = & ${'itmk'.$itmn};
	$itmsk = & ${'itmsk'.$itmn};
	if(($poison != '毒药') || (strpos($itmk, 'H') !==0 && strpos($itmk, 'P') !== 0)) {
		$log .= '道具选择错误，请重新选择。<br>';
		$mode = 'command';
		return;
	}
	$itmk = substr_replace($itmk,'P',0,1);
	if($club == 8){ $itmk = substr_replace($itmk,'2',2,1); }
	elseif($art == '毒物说明书'){$itmk = substr_replace($itmk,'1',2,1);};
	if($art == '妖精的羽翼') {$itmk = substr_replace($itmk,'H',0,1);$log .= "一种神秘的力量净化了毒药，你的毒药变成了解毒剂！";}
	$itmsk = $pid;
	if($art == '妖精的羽翼') {$log .= "使用了 <span class=\"red\">$poison</span> ，<span class=\"yellow\">${'itm'.$itmn}</span> 被净化了！<br>";}
	else {$log .= "使用了 <span class=\"red\">$poison</span> ，<span class=\"yellow\">${'itm'.$itmn}</span> 被下毒了！<br>";}
	$poisons--;
	if($poisons <= 0){
		$log .= "<span class=\"red\">$poison</span> 用光了。<br>";
		$poison = $poisonk = '';$poisone = $poisons = 0;
	}


	$mode = 'command';
	return;
}

function wthchange($itm,$itmsk){
	global $now,$log,$weather, $wthinfo, $name;
	if($itmsk==99){$weather = rand ( 0, 13 );}//随机全天气
	elseif($itmsk==98){$weather = rand ( 10, 13 );}//随机恶劣天气
	elseif($itmsk==97){$weather = rand ( 0, 9 );}//随机一般天气
	elseif($itmsk==96){$weather = rand ( 8, 9 );}//随机起雾天气
	elseif(!empty($itmsk) && is_numeric($itmsk)){
		if($itmsk >=0 && $itmsk < count($wthinfo)){
			$weather = $itmsk;
		}else{$weather = 0;}
	}
	else{$weather = 0;}
	include_once GAME_ROOT . './include/system.func.php';
	save_gameinfo ();
	naddnews ( $now, 'wthchange', $name, $weather, $itm );
	$log .= "你使用了{$itm}。<br />天气突然转变成了<span class=\"red b\">$wthinfo[$weather]</span>！<br />";
}

//function elec(&$itm,&$itmk,&$itme,&$itms,&$itmsk) {
//	global $log,$name,$now;
//	if(strpos( $itmk,'EW' ) ===0){
//		global $weather, $wthinfo, $name;
//		if($itmsk==99){$weather = rand ( 0, 13 );}//随机全天气
//		elseif($itmsk==98){$weather = rand ( 10, 13 );}//随机恶劣天气
//		elseif($itmsk==97){$weather = rand ( 0, 9 );}//随机一般天气
//		elseif($itmsk==96){$weather = rand ( 8, 9 );}//随机起雾天气
//		elseif(!empty($itmsk) && is_numeric($itmsk)){
//			if($itmsk >=0 && $itmsk < count($wthinfo)){
//				$weather = $itmsk;
//			}else{$weather = 0;}
//		}
//		else{$weather = 0;}
//		include_once GAME_ROOT . './include/system.func.php';
//		save_gameinfo ();
//		naddnews ( $now, 'wthchange', $name, $weather, $itm );
//		$log .= "你启动了{$itm}。<br />天气突然转变成了<span class=\"red b\">$wthinfo[$weather]</span>！<br />";
//	} elseif (strpos($itmk,'EC') ===0){
//		global $hack,$hack_obbs,$club,$alivenum,$deathnum,$hp,$state;
//		$hack_dice = rand(0,99);
//		if(($hack_dice < $hack_obbs)||(($club == 7)&&($hack_dice<95))) {
//			$hack = 1;
//			$log .= '入侵禁区控制系统成功了！全部禁区都被解除了！<br>';
//			include_once GAME_ROOT.'./include/system.func.php';
//			movehtm();
//			naddnews($now,'hack',$name);
//			save_gameinfo();
//		} else {
//			$log .= '可是，入侵禁区控制系统失败了……<br>';
//		}
//		
//		$hack_dice2 = rand(0,99);
//
//		if($hack_dice2 < 5) {
//			$log .= '由于你的不当操作，禁区系统防火墙锁定了你的电脑并远程引爆了它。幸好你本人的位置并没有被发现。<br>';
//			$itm = $itmk = $itmsk = '';
//			$itme = $itms = 0;
//		} elseif($hack_dice2 < 8) {
//			$itm = $itmk = $itmsk = '';
//			$itme = $itms = 0;
//			$log .= "<span class=\"evergreen\">“小心隔墙有耳哦。”</span>——林无月<br>";
//			include_once GAME_ROOT.'./include/state.func.php';
//			$log .= '你擅自入侵禁区控制系统，被控制系统远程消灭！<br>';
//			death('hack');
//		}
//	}
//	return;
//}

function hack($itmn = 0) {
	global $log,$hack,$hack_obbs,$club,$now,$name,$alivenum,$deathnum,$hp,$state;
	
	global ${'itm'.$itmn},${'itmk'.$itmn},${'itme'.$itmn},${'itms'.$itmn},${'itmsk'.$itmn};
	$itm = & ${'itm'.$itmn};
	$itmk = & ${'itmk'.$itmn};
	$itme = & ${'itme'.$itmn};
	$itms = & ${'itms'.$itmn};
	$itmsk = & ${'itmsk'.$itmn};

	if(!$itms) {
		$log .= '此道具不存在，请重新选择。<br>';
		$mode = 'command';
		return;
	}

	if(!$itme) {
		$log .= "<span class=\"yellow\">$itm</span>已经没电，请寻找<span class=\"yellow\">电池</span>充电。<br>";
		$mode = 'command';
		return;
	}

	$hack_dice = rand(0,99);
	if(($hack_dice < $hack_obbs)||(($club == 7)&&($hack_dice<95))) {
		$hack = 1;
		$log .= '入侵禁区控制系统成功了！全部禁区都被解除了！<br>';
		include_once GAME_ROOT.'./include/system.func.php';
		movehtm();
		naddnews($now,'hack',$name);
		save_gameinfo();
	} else {
		$log .= '可是，入侵禁区控制系统失败了……<br>';
	}
	$itme--;
	
	$hack_dice2 = rand(0,99);

	if($hack_dice2 < 5) {
		$log .= '由于你的不当操作，禁区系统防火墙锁定了你的电脑并远程引爆了它。幸好你本人的位置并没有被发现。<br>';
		$itm = $itmk = $itmsk = '';
		$itme = $itms = 0;
	} elseif($hack_dice2 < 8) {
			$log .= "<span class=\"evergreen\">“小心隔墙有耳哦。”</span>——林无月<br>";
			include_once GAME_ROOT.'./include/state.func.php';
			$log .= '你擅自入侵禁区控制系统，被控制系统远程消灭！<br>';
			death('hack');
	} elseif($itme <= 0) {
		$log .= "<span class=\"red\">$itm</span>的电池耗尽了。";
	}
	return;
}

/*function radar($m = 0){
	global $mode,$log,$cmd,$main,$pls,$db,$tablepre,$plsinfo,$arealist,$areanum,$rinfo,$hack;
	
	if(!$mode) {
		$log .= '雷达使用失败！<br>';
		return;
	}
	for($i=0;$i<=count($plsinfo);$i++) {
		if((array_search($i,$arealist) > $areanum) || $hack) {
			if($i==$pls) {
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type='0' AND pls=$i");
				$anum = $db->num_rows($result);
				$rinfo[$i] ="<span class=\"red b\">$anum</span>";
			} elseif($m == 2) {
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type='0' AND pls=$i");
				$anum = $db->num_rows($result);
				$rinfo[$i] = $anum;
			} else {
				$rinfo[$i] = '??';
			}
		} else {
			$rinfo[$i] = '<span class="red b">∵</span>';
		}
	}

	$log .= '白色数字：该区域内的人数<br><span class="red b">红色数字</span>：自己所在区域的人数<br><span class="red b">∵</span>：禁区<br><br>';
	$cmd = '<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl("menu"); href="javascript:void(0);" >返回</a><br><br>';
	$main = 'radar';
	return;
}*/

function newradar($m = 0){
	global $mode,$log,$cmd,$main,$pls,$db,$tablepre,$plsinfo,$arealist,$areanum,$hack,$gamestate;
	global $pnum,$npc2num,$npc3num,$npc4num,$npc5num,$npc6num,$radarscreen,$typeinfo;
	
	if(!$mode) {
		$log .= '仪器使用失败！<br>';
		return;
	}
	$npctplist = Array(2,3,4,5,6,11);
	$tdheight = 20;
	$screenheight = count($plsinfo)*$tdheight;
	$result = $db->query("SELECT type,pls FROM {$tablepre}players WHERE hp>0");
	while($cd = $db->fetch_array($result)) {
		$chdata[] = $cd;
	}
	foreach ($chdata as $data){
		$radar[$data['pls']][$data['type']]+=1;
	}
	$radarscreen = '<table height='.$screenheight.'px width=640px border="0" cellspacing="0" cellpadding="0" valign="middle"><tbody>';
	$radarscreen .= "<tr>
		<td class=b2 height={$tdheight}px width=120px><div class=nttx></div></td>
		<td class=b2><div class=nttx>{$typeinfo[0]}</div></td>";
	foreach ($npctplist as $value){
		$radarscreen .= "<td class=b2><div class=nttx>{$typeinfo[$value]}</div></td>";
	}
	$radarscreen .= '</tr>';
	for($i=0;$i<count($plsinfo);$i++) {
		$radarscreen .= "<tr><td class=b2 height={$tdheight}px><div class=nttx>{$plsinfo[$i]}</div></td>";
		if((array_search($i,$arealist) > $areanum) || $hack) {
			if($i==$pls) {
				//$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type='0' AND pls=$i");
				//$num0 = $db->num_rows($result);
				$num0 = $radar[$i][0];
				foreach ($npctplist as $j){
					//$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type=$j AND pls=$i");
					//${'num'.$j} = $db->num_rows($result);
					${'num'.$j} = $gamestate == 50 ? 0 : $radar[$i][$j];
				}
				if($num0){
					$pnum[$i] ="<span class=\"yellow b\">$num0</span>";
				} else {
					$pnum[$i] ='<span class="yellow b">-</span>';
				}
				foreach ($npctplist as $j){
					//${'npc'.$j.'num'}[$i] = "<span class=\"yellow b\">${'num'.$j}</span>";
					if(${'num'.$j}){
					${'npc'.$j.'num'}[$i] ="<span class=\"yellow b\">${'num'.$j}</span>";
					} else {
					${'npc'.$j.'num'}[$i] ='<span class="yellow b">-</span>';
					}
				}
			} elseif($m == 2) {
				//$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type='0' AND pls=$i");
				//$num0 = $db->num_rows($result);
				$num0 = $radar[$i][0];
				foreach ($npctplist as $j){
					//$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type=$j AND pls=$i");
					//${'num'.$j} = $db->num_rows($result);
					${'num'.$j} =  $gamestate == 50 ? 0 : $radar[$i][$j];
				}
				if($num0){
					$pnum[$i] =$num0;
				} else {
					$pnum[$i] ='-';
				}
				//$pnum[$i] ="$num0";
				foreach ($npctplist as $j){
					//${'npc'.$j.'num'}[$i] = "${'num'.$j}";;
					if(${'num'.$j}){
					${'npc'.$j.'num'}[$i] =${'num'.$j};
					} else {
					${'npc'.$j.'num'}[$i] ='-';
					}
				}
			} else {
				$pnum[$i] = '？';
				foreach ($npctplist as $j){
					${'npc'.$j.'num'}[$i] = '？';
				}
			}
		} else {
			$pnum[$i] = '<span class="red b">×</span>';
			foreach ($npctplist as $j){
				${'npc'.$j.'num'}[$i] = '<span class="red b">×</span>';
			}
		}
		$radarscreen .= "<td class=b3><div class=nttx>{$pnum[$i]}</div></td>";
		foreach ($npctplist as $j){
			$radarscreen .= "<td class=b3><div class=nttx>{${'npc'.$j.'num'}[$i]}</div></td>";
		}	
		$radarscreen .= '</tr>';
	}
	$radarscreen .= '</tbody></table>';
	$log .= '白色数字：该区域内的人数<br><span class="yellow b">黄色数字</span>：自己所在区域的人数<br><span class="red b">×</span>：禁区<br><br>';
	$cmd = '<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl("menu"); href="javascript:void(0);" >返回</a><br><br>';
	$main = 'radar';
	return;
}

function divining(){
	global $log;
	
	$dice = rand(0,99);
	if($dice < 20) {
		$up = 5;
		list($uphp,$upatt,$updef) = explode(',',divining1($up));
		$log .= "是大吉！要有什么好事发生了！<BR><span class=\"yellow b\">【命】+$uphp 【攻】+$upatt 【防】+$updef</span><BR>";
	} elseif($dice < 40) {
		$up = 3;
		list($uphp,$upatt,$updef) = explode(',',divining1($up));
		$log .= "中吉吗？感觉还不错！<BR><span class=\"yellow b\">【命】+$uphp 【攻】+$upatt 【防】+$updef</span><BR>";
	} elseif($dice < 60) {
		$up = 1;
		list($uphp,$upatt,$updef) = explode(',',divining1($up));
		$log .= "小吉吗？有跟无也没有什么分别。<BR><span class=\"yellow b\">【命】+$uphp 【攻】+$upatt 【防】+$updef</span><BR>";
	} elseif($dice < 80) {
		$up = 1;
		list($uphp,$upatt,$updef) = explode(',',divining2($up));
		$log .= "凶，真是不吉利。<BR><span class=\"red b\">【命】-$uphp 【攻】-$upatt 【防】-$updef</span><BR>";
	} else {
		$up = 3;
		list($uphp,$upatt,$updef) = explode(',',divining2($up));
		$log .= "大凶？总觉得有什么可怕的事快要发生了<BR><span class=\"red b\">【命】-$uphp 【攻】-$upatt 【防】-$updef</span><BR>";
	}
	return;
}

function divining1($u) {
	global $hp,$mhp,$att,$def;
	$uphp = rand(0,$u);
	$upatt = rand(0,$u);
	$updef = rand(0,$u);
	
	$hp+=$uphp;
	$mhp+=$uphp;
	$att+=$upatt;
	$def+=$updef;

	return "$uphp,$upatt,$updef";

}

function divining2($u) {
	global $hp,$mhp,$att,$def;
	$uphp = rand(0,$u);
	$upatt = rand(0,$u);
	$updef = rand(0,$u);
	
	$hp-=$uphp;
	$mhp-=$uphp;
	$att-=$upatt;
	$def-=$updef;

	return "$uphp,$upatt,$updef";

}

function deathnote($itmd=0,$dnname='',$dndeath='',$dngender='m',$dnicon=1,$sfn) {
	global $db,$tablepre,$log,$killnum,$mode;
	global ${'itm'.$itmd},${'itms'.$itmd},${'itmk'.$itmd},${'itme'.$itmd},${'itmsk'.$itmd};
	$dn = & ${'itm'.$itmd};
	$dnk = & ${'itmk'.$itmd};
	$dne = & ${'itme'.$itmd};
	$dns = & ${'itms'.$itmd};
	$dnsk = & ${'itmsk'.$itmd};

	$mode = 'command';

	if($dn != '■DeathNote■'){
		$log .= '道具使用错误！<br>';
		return;
	} elseif($dns <= 0) {
		$dn = $dnk = $dnsk = '';
		$dne = $dns = 0;
		$log .= '道具不存在！<br>';
		return;
	}

	if(!$dnname){return;}
	if($dnname == $sfn){
		$log .= "你不能自杀。<br>";
		return;
	}
	if(!$dndeath){$dndeath = '心脏麻痹';}
	//echo "name=$dnname,gender = $dngender,icon=$dnicon,";
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$dnname' AND type = 0");
	if(!$db->num_rows($result)) { 
		$log .= "你使用了■DeathNote■，但是什么都没有发生。<br>哪里出错了？<br>"; 
	} else {
		$edata = $db->fetch_array($result);
		
		if(($dngender != $edata['gd'])||($dnicon != $edata['icon'])) {
			$log .= "你使用了■DeathNote■，但是什么都没有发生。<br>哪里出错了？<br>"; 
		} else {
			$log .= "你将<span class=\"yellow b\">$dnname</span>的名字写在了■DeathNote■上。<br><span class=\"yellow b\">$dnname</span>被你杀死了。";
			include_once GAME_ROOT.'./include/state.func.php';
			kill('dn',$dnname,0,$edata['pid'],$dndeath);
			$killnum++;
		}
	}
	$dns--;
	if($dns<=0){
		$log .= '■DeathNote■突然燃烧起来，转瞬间化成了灰烬。<br>';
		$dn = $dnk = $dnsk = '';
		$dne = $dns = 0;
	}
		return;
}

?>