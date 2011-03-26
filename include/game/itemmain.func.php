<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}


function itemfind() {
	global $mode,$log,$cmd,$iteminfo,$itm0,$itmk0,$itme0,$itms0,$itmsk0;
	
	if(!$itm0||!$itmk0||!$itms0){
		$log .= '获取物品信息错误！';
		$mode = 'command';
		return;
	}
	if($itmk0 == 'TO') {
		global $name,$now,$hp,$db,$tablepre,$bid,$lvl,$pid,$type,$tactic,$club;
		global $wepsk,$arbsk,$arhsk,$arask,$arfsk,$artsk;
		$playerflag = $itmsk0 ? true : false;
		$selflag = $itmsk0 == $pid ? true : false;
		$dice=rand(0,99);
		$escrate = $club == 5 ? 25 + $lvl/3 : 8 + $lvl/3;
		$escrate = $club == 6 ? $escrate + 15 : $escrate;
		$escrate = $tactic == 4 ? $escrate + 20 : $escrate;
		$escrate = $selflag ? $escrate + 50 : $escrate; //自己设置的陷阱容易躲避
		//echo '回避率：'.$escrate.'%';
		$def_key = $wepsk.$arbsk.$arhsk.$arask.$arfsk.$artsk;
		
		if(strpos($def_key,'M') !== false){
			$minedetect = true;
			if($club == 7){//电脑社使用探雷器效率增加
				$escrate += 45;
			}else{
				$escrate += 35;
			}
		}
		$escrate = $escrate >= 90 ? 90 : $escrate;
		//$log .= "回避率 $escrate<br>";
		if($playerflag && !$selflag){
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$itmsk0'");
			$wdata = $db->fetch_array($result);
			$trname = $wdata['name'];$trtype = $wdata['type'];$trperfix = '<span class="yellow">'.$trname.'</span>设置的';
		}elseif($selflag){
			$trname = $name;$trtype = $pid;$trperfix = '你自己设置的';
		}else{
			$trname = $trtype = $trperfix = '';
		}
			
		if($dice >= $escrate){
			$bid = $itmsk0;
			$damage = round(rand(0,$itme0/2)+($itme0/2));
			$hp -= $damage;
			
			if($playerflag){naddnews($now,'trap',$name,$trname,$itm0);}
			$log .= "糟糕，你触发了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>！受到<span class=\"dmg\">$damage</span>点伤害！<br>";

			if($hp <= 0) {
				include_once GAME_ROOT.'./include/state.func.php';
				$killmsg = death('trap',$trname,$trtype,$itm0);
				$log .= "你被{$trperfix}陷阱杀死了！";
				if($killmsg && !$selflag){
					$log .= "<span class=\"yellow\">{$trname}对你说：“{$killmsg}”</span><br>";
				}				
			}
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = 0;
			return;
		} else {
			if($playerflag){
				naddnews($now,'trapmiss',$name,$trname,$itm0);
			}
			$dice = rand(0,99);
			$fdrate = $club == 5 ? 40 + $lvl/3 : 5 + $lvl/3;
			$fdrate = $selflag ? $fdrate + 50 : $fdrate;
			if($dice < $fdrate){
				if($minedetect){
					$log .= "在探雷装备的辅助下，你发现了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>并且拆除了它。陷阱看上去还可以重复使用。<br>";
				}else{
					$log .= "你发现了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>，不过你并没有触发它。陷阱看上去还可以重复使用。<br>";
				}				
				$itmsk0 = '';$itmk0 = 'TN';
				$mode = 'itemfind';
				return;
			}else{
				if($minedetect){
					$log .= "在探雷装备的辅助下，你发现了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>并且拆除了它。不过陷阱好像被你搞坏了。<br>";
				}else{
					$log .= "你触发了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>，不过你成功地回避了陷阱。<br>";
				}		
				
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				$mode = 'command';
				return;
			}
		}
	}else{
		$mode = 'itemfind';
		return;
	}
}


function itemget() {
	global $log,$nosta,$mode,$itm0,$itmk0,$itme0,$itms0,$itmsk0,$cmd;
	$log .= "获得了物品<span class=\"yellow\">$itm0</span>。<br>";
	
	if(preg_match('/^(WC|WD|WF|Y|C|TN|GB|M|V)/',$itmk0) && $itms0 !== $nosta){
		global $wep,$wepk,$wepe,$weps,$wepsk;
		if($wep == $itm0 && $wepk == $itmk0 && $wepe == $itme0 && $wepsk == $itmsk0){
			$weps += $itms0;
			$log .= "与装备着的武器<span class=\"yellow\">$wep</span>合并了。";
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = 0;
			$mode = 'command';
			return;
		}else{
			for($i = 1;$i <= 5;$i++){
				global ${'itm'.$i},${'itmk'.$i},${'itme'.$i},${'itms'.$i},${'itmsk'.$i};
				if((${'itms'.$i})&&($itm0 == ${'itm'.$i})&&($itmk0 == ${'itmk'.$i})&&($itme0 == ${'itme'.$i})&&($itmsk0 == ${'itmsk'.$i})){
					${'itms'.$i} += $itms0;
					$log .= "与包裹里的<span class=\"yellow\">$itm0</span>合并了。";
					$itm0 = $itmk0 = $itmsk0 = '';
					$itme0 = $itms0 = 0;
					$mode = 'command';
					return;
				}
			}
		}
	} elseif(preg_match('/^H|^P/',$itmk0) && $itms0 !== $nosta){
		$sameitem = array();
		for($i = 1;$i <= 5;$i++){
			global ${'itm'.$i},${'itmk'.$i},${'itme'.$i},${'itms'.$i};
			if(${'itms'.$i}&&($itm0 == ${'itm'.$i})&&($itme0 == ${'itme'.$i})&&(preg_match('/^(H|P)/',${'itmk'.$i}))){
				$sameitem[] = $i;
			}
		}
		if(isset($sameitem[0])){
			$cmd .= '<input type="hidden" name="mode" value="itemmain"><input type="hidden" name="command" value="itemmerge"><input type="hidden" name="merge1" value="0"><br>是否将 <span class="yellow">'.$itm0.'</span> 与以下物品合并？<br><input type="radio" name="merge2" id="itmn" value="n" checked><a onclick=sl("itmn"); href="javascript:void(0);" >不合并</a><br><br>';
			foreach($sameitem as $n) {
				$cmd .= '<input type="radio" name="merge2" id="itm'.$n.'" value="'.$n.'"><a onclick=sl("itm'.$n.'"); href="javascript:void(0);">'."${'itm'.$n}/${'itme'.$n}/${'itms'.$n}".'</a><br>';
			}
			return;
		}
		
	}

	itemadd();
	return;
}


function itemdrop($item) {
	global $db,$log,$mode,$pls,$tablepre;

	if($item == 'wep'){
		global $wep,$wepk,$wepe,$weps,$wepsk;
		$itm = & $wep;
		$itmk = & $wepk;
		$itme = & $wepe;
		$itms = & $weps;
		$itmsk = & $wepsk;
	} elseif(strpos($item,'ar') === 0) {
		$itmn = substr($item,2,1);
		global ${'ar'.$itmn},${'ar'.$itmn.'k'},${'ar'.$itmn.'e'},${'ar'.$itmn.'s'},${'ar'.$itmn.'sk'};
		$itm = & ${'ar'.$itmn};
		$itmk = & ${'ar'.$itmn.'k'};
		$itme = & ${'ar'.$itmn.'e'};
		$itms = & ${'ar'.$itmn.'s'};
		$itmsk = & ${'ar'.$itmn.'sk'};

	} elseif(strpos($item,'itm') === 0) {
		$itmn = substr($item,3,1);
		global ${'itm'.$itmn},${'itmk'.$itmn},${'itme'.$itmn},${'itms'.$itmn},${'itmsk'.$itmn};
		$itm = & ${'itm'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
	}

	if(!$itms) {
		$log .= '此道具不存在，请重新选择。<br>';
		$mode = 'command';
		return;
	}
//	$mapfile = GAME_ROOT."./gamedata/mapitem/{$pls}mapitem.php";
//	$itemdata = "$itm,$itmk,$itme,$itms,$itmsk,\n";
//	writeover($mapfile,$itemdata,'ab');
	$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,pls) VALUES ('$itm', '$itmk', '$itme', '$itms', '$itmsk', '$pls')");
	$log .= "你丢弃了<span class=\"red\">$itm</span>。<br>";
	$mode = 'command';
	if($item == 'wep'){
	$itm = '拳头';
	$itmsk = '';
	$itmk = 'WN';
	$itme = 0;
	$itms = $nosta;
	} else {
	$itm = $itmk = $itmsk = '';
	$itme = $itms = 0;
	}
	return;
}

function itemoff($item){
	global $log,$mode,$cmd,$itm0,$itmk0,$itme0,$itms0,$itmsk0;

	if($item == 'wep'){
		global $wep,$wepk,$wepe,$weps,$wepsk;
		$itm = & $wep;
		$itmk = & $wepk;
		$itme = & $wepe;
		$itms = & $weps;
		$itmsk = & $wepsk;
	} elseif(strpos($item,'ar') === 0) {
		$itmn = substr($item,2,1);
		global ${'ar'.$itmn},${'ar'.$itmn.'k'},${'ar'.$itmn.'e'},${'ar'.$itmn.'s'},${'ar'.$itmn.'sk'};
		$itm = & ${'ar'.$itmn};
		$itmk = & ${'ar'.$itmn.'k'};
		$itme = & ${'ar'.$itmn.'e'};
		$itms = & ${'ar'.$itmn.'s'};
		$itmsk = & ${'ar'.$itmn.'sk'};
	}

	$log .= "你卸下了装备<span class=\"yellow\">$itm</span>。<br>";

	$itm0 = $itm;
	$itmk0 = $itmk;
	$itme0 = $itme;
	$itms0 = $itms;
	$itmsk0 = $itmsk;
	
	if($item == 'wep'){
	$itm = '拳头';
	$itmsk = '';
	$itmk = 'WN';
	$itme = 0;
	$itms = $nosta;
	} else {
	$itm = $itmk = $itmsk = '';
	$itme = $itms = 0;
	}
	itemget();
	return;
}

function itemadd(){
	global $log,$mode,$cmd,$itm0,$itmk0,$itme0,$itms0,$itmsk0;
	if(!$itms0){
		$log .= '你没有捡取物品。<br>';
		$mode = 'command';
		return;
	}
	for($i = 1;$i <= 5;$i++){
		global ${'itm'.$i},${'itmk'.$i},${'itme'.$i},${'itms'.$i},${'itmsk'.$i};
		if(!${'itms'.$i}){
			$log .= "将<span class=\"yellow\">$itm0</span>放入包裹。<br>";
			${'itm'.$i} = $itm0;
			${'itmk'.$i} = $itmk0;
			${'itme'.$i} = $itme0;
			${'itms'.$i} = $itms0;
			${'itmsk'.$i} = $itmsk0;
			$itm0 = $itmk0 = $itmsk = '';
			$itme0 = $itms0 = 0;
			$mode = 'command';
			return;
		}
	}
	$log .= '你的包裹已经满了。想要丢掉哪个物品？<br>';
	
	$cmd .= '<input type="hidden" name="mode" value="itemmain"><br><input type="radio" name="command" id="dropitm0" value="dropitm0" checked><a onclick=sl("dropitm0"); href="javascript:void(0);" >'."$itm0/$itme0/$itms0".'</a><br><br>';

	for($i = 1;$i <= 5;$i++){
		$cmd .= '<input type="radio" name="command" id="swapitm'.$i.'" value="swapitm'.$i.'"><a onclick=sl("swapitm'.$i.'"); href="javascript:void(0);" >'."${'itm'.$i}/${'itme'.$i}/${'itms'.$i}".'</a><br>';
	}
	return;
}

function itemmerge($itn1,$itn2){
	global $log,$mode;
	
	if($itn1 == $itn2) {
		$log .= '需要选择两个物品才能进行合并！';
		$mode = 'itemmerge';
		return;
	}
	
	global $nosta,${'itm'.$itn1},${'itmk'.$itn1},${'itme'.$itn1},${'itms'.$itn1},${'itmsk'.$itn1},${'itm'.$itn2},${'itmk'.$itn2},${'itme'.$itn2},${'itms'.$itn2},${'itmsk'.$itn2};
	
	$it1 = & ${'itm'.$itn1};
	$itk1 = & ${'itmk'.$itn1};
	$ite1 = & ${'itme'.$itn1};
	$its1 = & ${'itms'.$itn1};
	$itsk1 = & ${'itmsk'.$itn1};
	$it2 = & ${'itm'.$itn2};
	$itk2 = & ${'itmk'.$itn2};
	$ite2 = & ${'itme'.$itn2};
	$its2 = & ${'itms'.$itn2};
	$itsk2 = & ${'itmsk'.$itn2};
	
	if(!$its1 || !$its2) {
		$log .= '请选择正确的物品进行合并！';
		$mode = 'itemmerge';
		return;
	}
	
	if($its1==$nosta || $its2==$nosta) {
		$log .= '耐久是无限的物品不能合并！';
		$mode = 'itemmerge';
		return;
	}

	if(($it1 == $it2)&&($ite1 == $ite2)) {
		if(($itk1==$itk2)&&($itsk1==$itsk2)&&preg_match('/^(WC|WD|WF|Y|C|TN|GB|V|M)/',$itk1)) {
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = 0;
			$log .= "你合并了<span class=\"yellow\">$it2</span>。";
			$mode = 'command';
			return;
		} elseif(preg_match('/^(H|P)/',$itk1)&&preg_match('/^(H|P)/',$itk2)) {
			if((strpos($itk1,'P') === 0)||(strpos($itk1,'P') === 0)){
				$p1 = substr($itk1,2);
				$p2 = substr($itk2,2);
				$k = substr($itk1,1,1);
				if($p2 < $p1){ $p2 = $p1;};
				$itk2 = "P$k$p2";
				if($itsk1 !== ''){
					$itsk2=$itsk1;
					}
			}
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = 0;
			
			$log .= "你合并了 <span class=\"yellow\">$it2</span>。";
			$mode = 'command';
			return;
		} elseif($itk1!=$itk2||$itsk1!=$itsk2) {
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>不是同类型同属性物品，不能合并！";
			$mode = 'itemmerge';
		} else{
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>完全是两个东西，想合并也不可能啊……";
			$mode = 'itemmerge';
		}
	} else {
		$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>不是同名同效果物品，不能合并！";
		$mode = 'itemmerge';
	}

	if(!$itn1 || !$itn2) {
		itemadd();
	}

	//$mode = 'command';
	return;
}

function itemmix($m1=0,$m2=0,$m3=0) {
	global $log,$mode,$gamecfg,$name,$nosta;
	global ${'itm'.$m1},${'itm'.$m2},${'itm'.$m3},$club,$wd;

	if((${'itm'.$m1} && ((${'itm'.$m1} == ${'itm'.$m2}) || (${'itm'.$m1} == ${'itm'.$m3}))) || ((${'itm'.$m2}) && (${'itm'.$m2} == ${'itm'.$m3}))) {
		$log .= '相同道具不能进行合成！<br>';
		$mode = 'itemmix';
		return;
	}

	
	$mi1=${'itm'.$m1};$mi2=${'itm'.$m2};$mi3=${'itm'.$m3};
	foreach(Array('/^锋利的/','/^电气/','/^毒性/','/-改$/') as $value){
		if($mi1){$mi1 = preg_replace($value,'',$mi1);}
		if($mi2){$mi2 = preg_replace($value,'',$mi2);}
		if($mi3){$mi3 = preg_replace($value,'',$mi3);}
	}
	$mixitem = array();
	if($mi1) { $mixitem[] = $mi1; }
	if($mi2) { $mixitem[] = $mi2; }
	if($mi3) { $mixitem[] = $mi3; }
//	if(${'itm'.$m1}) { $mixitem[] = preg_replace('/(钉|^锋利的|-改$|^电气|^毒性)/','',${'itm'.$m1}); }
//	if(${'itm'.$m2}) { $mixitem[] = preg_replace('/(钉|^锋利的|-改$|^电气|^毒性)/','',${'itm'.$m2}); }
//	if(${'itm'.$m3}) { $mixitem[] = preg_replace('/(钉|^锋利的|-改$|^电气|^毒性)/','',${'itm'.$m3}); }

	if(sizeof($mixitem) < 2){
		$log .= '至少需要2个道具才能进行合成！';
		$mode = 'itemmix';
		return;
	}
	
	include_once config('mixitem',$gamecfg);
	$mixflag = false;
	foreach($mixinfo as $minfo) {
		if(!array_diff($mixitem,$minfo['stuff']) && !array_diff($minfo['stuff'],$mixitem)){ 
			$mixflag = true;
			break;			
		}
	}

	if(!$mixflag) {
		$log .= "<span class=\"yellow\">$mixitem[0] $mixitem[1] $mixitem[2]</span> 不能合成！<br>";
		$mode = 'itemmix';
	} else {
		if($m1) {itemreduce('itm'.$m1);}
		if($m2) {itemreduce('itm'.$m2);}
		if($m3) {itemreduce('itm'.$m3);}
		
		global $itm0,$itmk0,$itme0,$itms0,$itmsk0;

		list($itm0,$itmk0,$itme0,$itms0,$itmsk0) = $minfo['result'];
		$log .= "<span class=\"yellow\">$mixitem[0] $mixitem[1] $mixitem[2]</span>合成了<span class=\"yellow\">{$minfo['result'][0]}</span><br>";
		naddnews($now,'itemmix',$name,$itm0);
		//if($club == 5) { $wd += 2; }
		//else { $wd+=1; }
		$wd+=1;
		if((strpos($itmk0,'WD') === 0)&&($club == 5)&&($itms0 !== $nosta)){ $itms0 = ceil($itms0*1.5); }
		elseif((strpos($itmk0,'H') === 0)&&($club == 16)&&($itms0 !== $nosta)){ $itms0 = ceil($itms0*2); }
		elseif(($itm0 == '移动PC' || $itm0 == '广域生命探测器') && ($club == 7)){ $itme0 *= 3; }
		itemget();
	}
	return;
}

function itemreduce($item){ //只限合成使用！！
	global $log;
	if(strpos($item,'itm') === 0) {
		$itmn = substr($item,3,1);
		global ${'itm'.$itmn},${'itmk'.$itmn},${'itme'.$itmn},${'itms'.$itmn},${'itmsk'.$itmn};
		$itm = & ${'itm'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
	} else {
		return;
	}

	if(!$itms) { return; }
	if(preg_match('/^(Y|C|X|TN|GB|H|V|M)/',$itmk)){$itms--;}
	else{$itms=0;}
	if($itms <= 0) {
		$itms = 0;
		$log .= "<span class=\"red\">$itm</span>用光了。<br>";
		$itm = $itmk = $itmsk = '';
		$itme = $itms = 0;
	}
	return;
}


function itembuy($item,$shop,$bnum=1) {
	global $log,$name,$now,$money,$areanum,$areaadd,$itm0,$itmk0,$itme0,$itms0,$itmsk0,$pls,$shops,$club;
	global $db,$tablepre;
	$result=$db->query("SELECT * FROM {$tablepre}shopitem WHERE sid = '$item'");
	$iteminfo = $db->fetch_array($result);
	$price = $club == 11 ? round($iteminfo['price']*0.75) : $iteminfo['price'];
	//$file = GAME_ROOT."./gamedata/shopitem/{$shop}shopitem.php";
	//$itemlist = openfile($file);
	//$iteminfo = $itemlist[$item];
	if(!$iteminfo) {
		$log .= '要购买的道具不存在！<br>';
		return;
	}

//	if(!in_array($pls,$shops)) {
//		$log .= '你所在的位置没有商店。<br>';
//		return;
//	}
	$bnum = (int)$bnum;
	//list($num,$price,$iname,$ikind,$ieff,$ista,$isk) = explode(',',$iteminfo);
	if($iteminfo['num'] <= 0) {
		$log .= '此物品已经售空！<br>';
		return;
	} elseif($bnum<=0) {
		$log .= '购买数量必须为大于0的整数。<br>';
		return;
	} elseif($bnum>$iteminfo['num']) {
		$log .= '购买数量必须小于存货数量。<br>';
		return;
	} elseif($money < $price*$bnum) {
		$log .= '你的钱不够，不能购买此物品！<br>';
		return;
	} elseif(!preg_match('/^(WC|WD|WF|Y|C|TN|GB|H|V|M)/',$iteminfo['itmk'])&&$bnum>1) {
		$log .= '此物品一次只能购买一个。<br>';
		return;
	}elseif($iteminfo['area']> $areanum/$areaadd){
		$log .= '此物品尚未开放出售！<br>';
		return;
	}
//	if (strpos($ikind,'_') !== false) {
//		list($ik,$it) = explode('_',$ikind);
//		if($areanum < $it*$areaadd) {
//			$log .= '此物品尚未开放出售！<br>';
//			return;
//		}
//	} else {
//		$ik = $ikind;
//	}
	$inum = $iteminfo['num']-$bnum;
	$sid = $iteminfo['sid'];
	$db->query("UPDATE {$tablepre}shopitem SET num = '$inum' WHERE sid = '$sid'");
//	$num-=$bnum;
	$money -= $price*$bnum;
//	$itemlist[$item] = "$num,$price,$iname,$ikind,$ieff,$ista,$isk,\n";
//	writeover($file,implode('',$itemlist));
	naddnews($now,'itembuy',$name,$iteminfo['item']);
	$log .= "购买成功。";
	$itm0 = $iteminfo['item'];
	$itmk0 = $iteminfo['itmk'];
	$itme0 = $iteminfo['itme'];
	$itms0 = $iteminfo['itms']*$bnum;
	$itmsk0 = $iteminfo['itmsk'];

	itemget();	
	return;
}





function getcorpse($item){
	global $db,$tablepre,$log,$mode;
	global $itm0,$itmk0,$itme0,$itms0,$itmsk0,$money,$pls,$bid;
	if($bid==0){
		$log .= '<span class="yellow">你没有遇到尸体，或已经离开现场！</span><br>';
		$bid = 0;
		$mode = 'command';
		return;
	}

	$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$bid'");
	if(!$db->num_rows($result)){
		$log .= '对方不存在！<br>';
		$bid = 0;
		$mode = 'command';
		return;
	}

	$edata = $db->fetch_array($result);
	
	if($edata['hp']>0) {
		$log .= '对方尚未死亡！<br>';
		$bid = 0;
		$mode = 'command';
		return;
	} elseif($edata['pls'] != $pls) {
		$log .= '对方跟你不在同一个地图！<br>';
		$bid = 0;
		$mode = 'command';
		return;
	}

	if($item == 'wep') {
		$itm0 = $edata['wep'];
		$itmk0 = $edata['wepk'];
		$itme0 = $edata['wepe'];
		$itms0 = $edata['weps'];
		$itmsk0 = $edata['wepsk'];
		$edata['wep'] = $edata['wepk'] = $edata['wepsk'] = '';
		$edata['wepe'] = $edata['weps'] = 0;  
	} elseif(strpos($item,'ar') === 0) {
		$itm0 = $edata[$item];
		$itmk0 = $edata[$item.'k'];
		$itme0 = $edata[$item.'e'];
		$itms0 = $edata[$item.'s'];
		$itmsk0 = $edata[$item.'sk'];
		$edata[$item] = $edata[$item.'k'] = $edata[$item.'sk'] = '';
		$edata[$item.'e'] = $edata[$item.'s'] = 0;  
	} elseif(strpos($item,'itm') === 0) {
		$itmn = substr($item,3,1);
		$itm0 = $edata['itm'.$itmn];
		$itmk0 = $edata['itmk'.$itmn];
		$itme0 = $edata['itme'.$itmn];
		$itms0 = $edata['itms'.$itmn];
		$itmsk0 = $edata['itmsk'.$itmn];
		$edata['itm'.$itmn] = $edata['itmk'.$itmn] = $edata['itmsk'.$itmn] = '';
		$edata['itme'.$itmn] = $edata['itms'.$itmn] = 0;  
	} elseif($item == 'money') {
		$money += $edata['money'];
		$log .= '获得了金钱 <span class="yellow">'.$edata['money'].'</span>。<br>';
		$edata['money'] = 0;
		w_save2($edata);
		$bid = 0;
		$mode = 'command';
		return;
	} else {
		$bid = 0;
		return;
	}

	w_save2($edata);

	if(!$itme0 || !$itms0) {
		$log .= '该物品不存在！';
	} else {
		itemget();
	}
	$bid = 0;
	$mode = 'command';
	return;
}




?>