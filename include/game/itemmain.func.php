<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function trap(){
	global $pdata,$now,$db,$tablepre,$log,$cmd,$mode,$iteminfo,$ex_attack,$ex_trap_inf_r,$ex_dmg_def,$infdata,$inf_cannot_cmd;
	
	$pid = $pdata['pid'];
	$name = $pdata['name'];
	$pmapprop = $pdata['mapprop'];
	$hp = & $pdata['hp'];
	$def = $pdata['def'];
	$ardef = $pdata['arb']+$pdata['arh']+$pdata['ara']+$pdata['arf'];
	$bid = & $pdata['bid'];
	$lvl = $pdata['lvl'];
	$pls = $pdata['pls'];
	$inf = & $pdata['inf'];
	$tactic = $pdata['tactic'];
	$club = $pdata['club'];
	$itm0 = & $pdata['itm0'];
	$itmk0 = & $pdata['itmk0'];
	$itme0 = & $pdata['itme0'];
	$itms0 = & $pdata['itms0'];
	$itmsk0 = & $pdata['itmsk0'];
	$itmnp0 = & $pdata['itmnp0'];
	//$trspk = $itmsk0;
	$playerflag = $itmnp0 ? true : false;
	$selflag = $itmnp0 == $pid ? true : false;
	$dice=rand(0,99);
	$escrate = $club == 5 ? 25 + $lvl/3 : 8 + $lvl/3;
	$escrate = $club == 6 ? $escrate + 15 : $escrate;
	$escrate = $tactic == 4 ? $escrate + 20 : $escrate;
	$escrate = $selflag ? $escrate + 50 : $escrate; //自己设置的陷阱容易躲避
	//echo '回避率：'.$escrate.'%';
	
	$map_key = $pmapprop;//player_property($GLOBALS['pdata'],'map');
	$def_key = player_property($GLOBALS['pdata'],'def');
	//echo $ardef;
	
	if(isset($map_key['MD'])){
		$minedetect = true;
		if($club == 7){//电脑社使用探雷器效率增加
			$escrate += 45;
		}else{
			$escrate += 35;
		}
	}else{
		$minedetect = false;
	}
	$escrate = $escrate >= 90 ? 90 : $escrate;//最大回避率
	$escrate = substr($itmk0,2,1) == 'c' ? 1 : $escrate;//必中陷阱
	//$log .= "回避率: $escrate<br>";
	if($playerflag && !$selflag){
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$itmnp0'");
		$wdata = $db->fetch_array($result);
		$trname = $wdata['name'];$trtype = $wdata['type'];$trprefix = '<span class="yellow">'.$trname.'</span>设置的';
	}elseif($selflag){
		$trname = $name;$trtype = $pid;$trprefix = '你自己设置的';
	}else{
		$trname = $trtype = $trprefix = '';
	}
	if($dice >= $escrate){
		if($playerflag){naddnews($now,'trap',$name,$trname,$itm0);}
		$log .= "糟糕，你触发了{$trprefix}陷阱<span class=\"yellow\">$itm0</span>！<br>";
		$bid = $itmnp0;
		$o_dmg = rand($itme0*0.5,$itme0*1);
		$damage = round($o_dmg * (0.5 + 1000 / (2000+$def+$ardef)));
		//$damage = round(rand(0,$itme0/2)+($itme0/2));
		$trexlog = '';
		$explodmg = 0;
		$trexdefindex = 0;
		$trexdmgfac = 1;
		//$trexsign = $trexinf = '';
		for($i=0;$i < strlen($itmsk0);$i+=2){
			$trspk = str_replace('A','',substr($itmsk0,$i,2));
			if(in_array($trspk,$ex_attack)){
				if($trspk == 'd'){
					if(isset($def_key[$ex_dmg_def[$trspk]])){
						$explodmg = round($itme0/4);
						$trexlog .= $infdata[$trspk]['dmgnm'].'被防具抵消了！造成了'.$explodmg.'点额外伤害！<br>';
					}else{
						$explodmg = round($itme0/2);
						$trexlog .= $infdata[$trspk]['dmgnm'].'造成了'.$explodmg.'点额外伤害！<br>';
					}					
					set_noise('D', $pls, $pid);
				}else{
					$trexdmgfac *= 1.2;
					if(isset($def_key[$ex_dmg_def[$trspk]])){
						$trexdefindex += 1;
						$trexlog .= $infdata[$trspk]['dmgnm'].'被防具抵消了！<br>';
					}else{
						$trexlog .= $infdata[$trspk]['dmgnm'].'对你造成了额外的伤害！';
						$dice = rand(0,99);
						if($dice < $ex_trap_inf_r[$trspk] && strpos($inf,$trspk)===false){
							$trexlog .= "，并导致你{$infdata[$trspk]['infnm']}了";
							$inf .= $trspk;
							if(in_array($trspk,array_keys($inf_cannot_cmd))){
								$pdata[$inf_cannot_cmd[$trspk]['field']] = $now;
							}
							naddnews($now,'trapinf',$name,$trname,$itm0,$trspk);
						}
						$trexlog .= '！<br>';
					}	
				}
			}
		}
		$log .= $trexlog;
		$trexdeffac=0.5 + 1/($trexdefindex+2);
		//$log .= "原始伤害： $damage , 伤害因数： $trexdmgfac , 防御因数： $trexdeffac , 爆炸伤害： $explodmg <br>";
		$damage = round($damage * $trexdmgfac * $trexdeffac + $explodmg);
		$hp -= $damage;
		$log .= "陷阱使你受到了<span class=\"dmg\">$damage</span>点伤害！<br>";

		if($hp <= 0) {
			//include_once GAME_ROOT.'./include/state.func.php';
			//$killmsg = death('trap',$trname,$trtype,$itm0);
			if($playerflag && !$selflag){
				$wdata['killnum']++;
				player_save($wdata);
				$log .= "你被{$trprefix}陷阱杀死了！";
				if(set_death($GLOBALS['pdata'],'trap',$itm0,$wdata)){
					$killmsg = auto_chat($wdata,'kill');
					if($killmsg && !$selflag){
						$log .= "陷阱上，能看到{$trname}留下的字条——{$killmsg}</span>";
					}
				}					
			}else{
				if(set_death($GLOBALS['pdata'],'trap',$itm0)){
					$log .= "你被{$trprefix}陷阱杀死了！";
				}				
			}					
		}
		$itm0 = $itmk0 = $itmsk0 = '';
		$itme0 = $itms0 = $itmnp0 = 0;
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
				$log .= "在探雷装备的辅助下，你发现了{$trprefix}陷阱<span class=\"yellow\">$itm0</span>并且拆除了它。陷阱看上去还可以重复使用。<br>";
			}else{
				$log .= "你发现了{$trprefix}陷阱<span class=\"yellow\">$itm0</span>，不过你并没有触发它。陷阱看上去还可以重复使用。<br>";
			}				
			$itmnp0 = 0;$itmk0 = str_replace('TO','TN',$itmk0);
			$mode = 'itemfind';
			return;
		}else{
			if($minedetect){
				$log .= "在探雷装备的辅助下，你发现了{$trprefix}陷阱<span class=\"yellow\">$itm0</span>并且拆除了它。不过陷阱好像被你搞坏了。<br>";
			}else{
				$log .= "你触发了{$trprefix}陷阱<span class=\"yellow\">$itm0</span>，不过你成功地回避了陷阱。<br>";
			}		
			
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = $itmnp0 = 0;
			$mode = 'command';
			return;
		}
	}
}

function itemfind() {
	global $mode,$log,$pdata;
	$itm0 = $pdata['itm0'];$itmk0 = $pdata['itmk0'];$itms0 = $pdata['itms0'];
	if(!$itm0||!$itmk0||!$itms0){
		$log .= '获取物品信息错误！';
		$mode = 'command';
		return;
	}
	if(strpos($itmk0,'TO')===0) {
		trap();
	}else{
		$mode = 'itemfind';
		return;
	}
}


function itemget() {
	global $log,$nosta,$mode,$pdata,$cmd;
	$itm0 = &$pdata['itm0'];
	$itmk0 = &$pdata['itmk0'];
	$itme0 = &$pdata['itme0'];
	$itms0 = &$pdata['itms0'];
	$itmsk0 = &$pdata['itmsk0'];
	$itmnp0 = &$pdata['itmnp0'];
	$log .= "获得了物品<span class=\"yellow\">$itm0</span>。<br>";
	
	if(preg_match('/^(WC|WD|WF|Y|C|TN|GB|M|V)/',$itmk0) && $itms0 !== $nosta){
		//global $wep,$wepk,$wepe,$weps,$wepsk,$wepnp;
		$wep = &$pdata['wep'];
		$wepk = &$pdata['wepk'];
		$wepe = &$pdata['wepe'];
		$weps = &$pdata['weps'];
		$wepsk = &$pdata['wepsk'];
		$wepnp = &$pdata['wepnp'];
		if($wep == $itm0 && $wepk == $itmk0 && $wepe == $itme0 && $wepsk == $itmsk0 && $wepnp == $itmnp0){
			$weps += $itms0;
			$log .= "与装备着的武器<span class=\"yellow\">$wep</span>合并了。";
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = $itmnp0 = 0;
			$mode = 'command';
			return;
		}else{
			for($i = 1;$i <= 6;$i++){
				${'itm'.$i} = &$pdata['itm'.$i];
				${'itmk'.$i} = &$pdata['itmk'.$i];
				${'itme'.$i} = &$pdata['itme'.$i];
				${'itms'.$i} = &$pdata['itms'.$i];
				${'itmsk'.$i} = &$pdata['itmsk'.$i];
				${'itmnp'.$i} = &$pdata['itmnp'.$i];
				if((${'itms'.$i})&&($itm0 == ${'itm'.$i})&&($itmk0 == ${'itmk'.$i})&&($itme0 == ${'itme'.$i})&&($itmsk0 == ${'itmsk'.$i})&&($itmnp0 == ${'itmnp'.$i})){
					${'itms'.$i} += $itms0;
					$log .= "与包裹里的<span class=\"yellow\">$itm0</span>合并了。";
					$itm0 = $itmk0 = $itmsk0 = '';
					$itme0 = $itms0 = $itmnp0 = 0;
					$mode = 'command';
					return;
				}
			}
		}
	} elseif(preg_match('/^H|^P/',$itmk0) && $itms0 !== $nosta){
		$sameitem = array();
		for($i = 1;$i <= 6;$i++){
			${'itm'.$i} = &$pdata['itm'.$i];
			${'itmk'.$i} = &$pdata['itmk'.$i];
			${'itme'.$i} = &$pdata['itme'.$i];
			${'itms'.$i} = &$pdata['itms'.$i];
			${'itmsk'.$i} = &$pdata['itmsk'.$i];
			${'itmnp'.$i} = &$pdata['itmnp'.$i];
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
	global $db,$tablepre,$log,$mode,$pdata,$nosta;
	$pls = $pdata['pls'];
	if($item == 'wep'){
		$itm = & $pdata['wep'];
		$itmk = & $pdata['wepk'];
		$itme = & $pdata['wepe'];
		$itms = & $pdata['weps'];
		$itmsk = & $pdata['wepsk'];
		$itmnp = & $pdata['wepnp'];
	} elseif(strpos($item,'ar') === 0) {
		$itmn = substr($item,2,1);
		$itm = & $pdata['ar'.$itmn];
		$itmk = & $pdata['ar'.$itmn.'k'];
		$itme = & $pdata['ar'.$itmn.'e'];
		$itms = & $pdata['ar'.$itmn.'s'];
		$itmsk = & $pdata['ar'.$itmn.'sk'];
		$itmnp = & $pdata['ar'.$itmn.'np'];

	} elseif(strpos($item,'itm') === 0) {
		$itmn = substr($item,3,1);
		$itm = & $pdata['itm'.$itmn];
		$itmk = & $pdata['itmk'.$itmn];
		$itme = & $pdata['itme'.$itmn];
		$itms = & $pdata['itms'.$itmn];
		$itmsk = & $pdata['itmsk'.$itmn];
		$itmnp = & $pdata['itmnp'.$itmn];
	}

	if(!$itms) {
		$log .= '此道具不存在，请重新选择。<br>';
		$mode = 'command';
		return;
	}
	$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,itmnp, pls) VALUES ('$itm', '$itmk', '$itme', '$itms', '$itmsk','$itmnp', '$pls')");
	$log .= "你丢弃了<span class=\"red\">$itm</span>。<br>";
	$mode = 'command';
	$itm = $itmk = $itmsk = '';
	$itme = $itms = $itmnp = 0;
//	if($item == 'wep'){
//	$itm = '拳头';
//	$itmsk = '';
//	$itmk = 'WN';
//	$itme = $itmnp = 0;
//	$itms = $nosta;
//	} else {
//	$itm = $itmk = $itmsk = '';
//	$itme = $itms = $itmnp = 0;
//	}
	return;
}

function itemoff($item){
	global $log,$mode,$cmd,$nosta,$pdata;
	$itm0 = & $pdata['itm0'];
	$itmk0 = & $pdata['itmk0'];
	$itme0 = & $pdata['itme0'];
	$itms0 = & $pdata['itms0'];
	$itmsk0 = & $pdata['itmsk0'];
	$itmnp0 = & $pdata['itmnp0'];
	if($item == 'wep'){
		$itm = & $pdata['wep'];
		$itmk = & $pdata['wepk'];
		$itme = & $pdata['wepe'];
		$itms = & $pdata['weps'];
		$itmsk = & $pdata['wepsk'];
		$itmnp = & $pdata['wepnp'];
	} elseif(strpos($item,'ar') === 0) {
		$itmn = substr($item,2,1);
		
		$itm = & $pdata['ar'.$itmn];
		$itmk = & $pdata['ar'.$itmn.'k'];
		$itme = & $pdata['ar'.$itmn.'e'];
		$itms = & $pdata['ar'.$itmn.'s'];
		$itmsk = & $pdata['ar'.$itmn.'sk'];
		$itmnp = & $pdata['ar'.$itmn.'np'];
	}
	
	if($itmk == 'N' || $itmk == 'WN' ||  $itmk == 'DN'){
		$log .= "<span class=\"yellow\">{$itm}无法被卸下！</span><br>";
		return;
	}
	
	$log .= "你卸下了装备<span class=\"yellow\">$itm</span>。<br>";

	$itm0 = $itm;
	$itmk0 = $itmk;
	$itme0 = $itme;
	$itms0 = $itms;
	$itmsk0 = $itmsk;
	$itmnp0 = $itmnp;
	$itm = $itmk = $itmsk = '';
	$itme = $itms = $itmnp = 0;
//	if($item == 'wep'){
//	$itm = '拳头';
//	$itmsk = '';
//	$itmk = 'WN';
//	$itme = $itmnp = 0;
//	$itms = $nosta;
//	} else {
//	$itm = $itmk = $itmsk = '';
//	$itme = $itms = $itmnp = 0;
//	}
	itemget();
	return;
}

function itemadd(){
	global $log,$mode,$cmd,$pdata;
	$itm0 = & $pdata['itm0'];
	$itmk0 = & $pdata['itmk0'];
	$itme0 = & $pdata['itme0'];
	$itms0 = & $pdata['itms0'];
	$itmsk0 = & $pdata['itmsk0'];
	$itmnp0 = & $pdata['itmnp0'];
	if(!$itms0){
		$log .= '你没有捡取物品。<br>';
		$mode = 'command';
		return;
	}
	for($i = 1;$i <= 6;$i++){
		$itmi = & $pdata['itm'.$i];
		$itmki = & $pdata['itmk'.$i];
		$itmei = & $pdata['itme'.$i];
		$itmsi = & $pdata['itms'.$i];
		$itmski = & $pdata['itmsk'.$i];
		$itmnpi = & $pdata['itmnp'.$i];
		if(!$itmsi){
			$log .= "将<span class=\"yellow\">$itm0</span>放入包裹。<br>";
			$itmi = $itm0;
			$itmki = $itmk0;
			$itmei = $itme0;
			$itmsi = $itms0;
			$itmski = $itmsk0;
			$itmnpi = $itmnp0;
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = $itmnp0 = 0;
			$mode = 'command';
			return;
		}
	}
	$mval = 'itemmain';$cval = 'swap';$dlist = Array('itm0','itm1','itm2','itm3','itm4','itm5','itm6');$inotice = '你的包裹已经满了。想要丢掉哪个物品？';
	ob_start();
	include template('itemmenu');
	$cmd = ob_get_contents();
	ob_end_clean();
	return;
}

function itemmerge($itn1,$itn2){
	global $log,$mode,$nosta,$pdata;
	
	if($itn1 == $itn2) {
		$log .= '需要选择两个物品才能进行合并！';
		$mode = 'itemmerge';
		return;
	}
	
	$it1 = & $pdata['itm'.$itn1];
	$itk1 = & $pdata['itmk'.$itn1];
	$ite1 = & $pdata['itme'.$itn1];
	$its1 = & $pdata['itms'.$itn1];
	$itsk1 = & $pdata['itmsk'.$itn1];
	$itnp1 = & $pdata['itmnp'.$itn1];
	$it2 = & $pdata['itm'.$itn2];
	$itk2 = & $pdata['itmk'.$itn2];
	$ite2 = & $pdata['itme'.$itn2];
	$its2 = & $pdata['itms'.$itn2];
	$itsk2 = & $pdata['itmsk'.$itn2];
	$itnp2 = & $pdata['itmnp'.$itn2];

	
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
		if(($itk1==$itk2)&&($itsk1==$itsk2)&&($itnp1==$itnp2)&&preg_match('/^(WC|WD|WF|Y|C|TN|GB|V|M)/',$itk1)) {
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = $itnp1 = 0;
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
				if($itnp1 !== ''){
					$itnp2=$itnp1;
					}
			}
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = $itnp1 = 0;
			
			$log .= "你合并了 <span class=\"yellow\">$it2</span>。";
			$mode = 'command';
			return;
		} elseif($itk1!=$itk2||$itsk1!=$itsk2||$itnp1!=$itnp2) {
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>不是同类型同属性物品，不能合并！";
			$mode = 'itemmerge';
		} else{
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>属于不可合并的物品！";
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
	global $now,$log,$mode,$gamecfg,$nosta,$pdata;
	${'itm'.$m1} = $pdata['itm'.$m1];
	${'itm'.$m2} = $pdata['itm'.$m2];
	${'itm'.$m3} = $pdata['itm'.$m3];
	
	$name = $pdata['name'];
	$club = $pdata['club'];
	$wd = & $pdata['wd'];

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
		
		$itm0 = & $pdata['itm0'];
		$itmk0 = & $pdata['itmk0'];
		$itme0 = & $pdata['itme0'];
		$itms0 = & $pdata['itms0'];
		$itmsk0 = & $pdata['itmsk0'];
		$itmnp0 = & $pdata['itmnp0'];
		list($itm0,$itmk0,$itme0,$itms0,$itmsk0,$itmnp0) = $minfo['result'];
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
	global $log,$pdata;
	if(strpos($item,'itm') === 0) {
		$itmn = substr($item,3,1);
		$itm = & $pdata['itm'.$itmn];
		$itmk = & $pdata['itmk'.$itmn];
		$itme = & $pdata['itme'.$itmn];
		$itms = & $pdata['itms'.$itmn];
		$itmsk = & $pdata['itmsk'.$itmn];
		$itmnp = & $pdata['itmnp'.$itmn];
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
		$itme = $itms = $itmnp = 0;
	}
	return;
}


function itembuy($item,$shop,$bnum=1) {
	global $db,$tablepre,$mode,$log,$pdata,$now,$areanum,$areaadd;
	$name = $pdata['name'];$money = & $pdata['money'];$pls = $pdata['pls'];$club= $pdata['club'];
	$itm0 = & $pdata['itm0'];
	$itmk0 = & $pdata['itmk0'];
	$itme0 = & $pdata['itme0'];
	$itms0 = & $pdata['itms0'];
	$itmsk0 = & $pdata['itmsk0'];
	$itmnp0 = & $pdata['itmnp0'];	
	$result=$db->query("SELECT * FROM {$tablepre}shopitem WHERE sid = '$item'");
	$iteminfo = $db->fetch_array($result);
	$price = $club == 11 ? round($iteminfo['price']*0.75) : $iteminfo['price'];
	if(!$iteminfo) {
		$log .= '要购买的道具不存在！<br>';
		$mode = 'command';
		return;
	}

	$bnum = (int)$bnum;
	if($iteminfo['num'] <= 0) {
		$log .= '此物品已经售空！<br>';
		$mode = 'command';
		return;
	} elseif($bnum<=0) {
		$log .= '购买数量必须为大于0的整数。<br>';
		$mode = 'command';
		return;
	} elseif($bnum>$iteminfo['num']) {
		$log .= '购买数量必须小于存货数量。<br>';
		$mode = 'command';
		return;
	} elseif($money < $price*$bnum) {
		$log .= '你的钱不够，不能购买此物品！<br>';
		$mode = 'command';
		return;
	} elseif(!preg_match('/^(WC|WD|WF|Y|C|TN|GB|H|V|M)/',$iteminfo['itmk'])&&$bnum>1) {
		$log .= '此物品一次只能购买一个。<br>';
		$mode = 'command';
		return;
	}elseif($iteminfo['area']> $areanum/$areaadd){
		$log .= '此物品尚未开放出售！<br>';
		$mode = 'command';
		return;
	}
	$inum = $iteminfo['num']-$bnum;
	$sid = $iteminfo['sid'];
	$db->query("UPDATE {$tablepre}shopitem SET num = '$inum' WHERE sid = '$sid'");
	$money -= $price*$bnum;
	naddnews($now,'itembuy',$name,$iteminfo['item']);
	$log .= "购买成功。";
	$itm0 = $iteminfo['item'];
	$itmk0 = $iteminfo['itmk'];
	$itme0 = $iteminfo['itme'];
	$itms0 = $iteminfo['itms']*$bnum;
	$itmsk0 = $iteminfo['itmsk'];
	$itmnp0 = $iteminfo['itmnp'];

	itemget();
	return;
}





function getcorpse($item){
	global $db,$tablepre,$pdata,$log,$mode;
	$itm0 = & $pdata['itm0'];
	$itmk0 = & $pdata['itmk0'];
	$itme0 = & $pdata['itme0'];
	$itms0 = & $pdata['itms0'];
	$itmsk0 = & $pdata['itmsk0'];
	$itmnp0 = & $pdata['itmnp0'];	
	$money = & $pdata['money'];	
	$pls = $pdata['pls'];
	$bid = & $pdata['bid'];	
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
		$itmnp0 = $edata['wepnp'];
		$edata['wep'] = $edata['wepk'] = $edata['wepsk'] = '';
		$edata['wepe'] = $edata['weps'] = $edata['wepnp'] = 0;  
	} elseif(strpos($item,'ar') === 0) {
		$itm0 = $edata[$item];
		$itmk0 = $edata[$item.'k'];
		$itme0 = $edata[$item.'e'];
		$itms0 = $edata[$item.'s'];
		$itmsk0 = $edata[$item.'sk'];
		$itmnp0 = $edata[$item.'np'];
		$edata[$item] = $edata[$item.'k'] = $edata[$item.'sk'] = '';
		$edata[$item.'e'] = $edata[$item.'s'] = $edata[$item.'np'] = 0;  
	} elseif(strpos($item,'itm') === 0) {
		$itmn = substr($item,3,1);
		$itm0 = $edata['itm'.$itmn];
		$itmk0 = $edata['itmk'.$itmn];
		$itme0 = $edata['itme'.$itmn];
		$itms0 = $edata['itms'.$itmn];
		$itmsk0 = $edata['itmsk'.$itmn];
		$itmnp0 = $edata['itmnp'.$itmn];
		$edata['itm'.$itmn] = $edata['itmk'.$itmn] = $edata['itmsk'.$itmn] = '';
		$edata['itme'.$itmn] = $edata['itms'.$itmn] = $edata['itmnp'.$itmn] = 0;  
	} elseif($item == 'money') {
		$money += $edata['money'];
		$log .= '获得了金钱 <span class="yellow">'.$edata['money'].'</span>。<br>';
		$edata['money'] = 0;
		player_save($edata);
		$bid = 0;
		$mode = 'command';
		return;
	} else {
		$bid = 0;
		return;
	}

	player_save($edata);

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