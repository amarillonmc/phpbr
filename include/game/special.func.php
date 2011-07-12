<?php


if(!defined('IN_GAME')) {
	exit('Access Denied');
}
function learn_tech($ltech){
	global $log,$mode,$pdata,$techniqueinfo;
	$technique = &$pdata['technique']; $techlevel = &$pdata['techlevel'];
	
	$newtechlist = get_new_tech($pdata);
	if(!in_array($ltech,$newtechlist)){
		$log .= '<span class="red">指令错误，你不能学习此技能！</span><br />';
		$mode = 'command';
		return;
	}
	$technique .= $ltech;
	$tech = Array_merge($techniqueinfo['active']['combat'], $techniqueinfo['active']['map'], $techniqueinfo['passive']['combat'], $techniqueinfo['passive']['map']);
	$techlevel = $tech[$ltech]['lvl'];
	$log .= '<span class="yellow">已学会'.$tech[$ltech]['name'].'技能！</span><br />';
	$mode = 'command';
	return;
}

function adtsk(){
	global $log,$mode,$pdata;
	$club = $pdata['club'];
	$wep = & $pdata['wep'];
	$wepk = & $pdata['wepk'];
	$wepe = & $pdata['wepe'];
	$weps = & $pdata['weps'];
	$wepsk = & $pdata['wepsk'];
	$wepnp = & $pdata['wepnp'];
	if($wepk == 'WN' || !$wepe || !$weps){
		$log .= '<span class="red">你没有装备武器，无法改造！</span><br />';
		$mode = 'command';
		return;
	}
	if($club == 7){//电脑社，电气改造
		$position = 0;
		foreach(Array(1,2,3,4,5,6) as $imn){
			//global ${'itm'.$imn},${'itmk'.$imn},${'itme'.$imn},${'itms'.$imn},${'itmsk'.$imn};
			if(strpos($pdata['itm'.$imn],'电池')!==false && $pdata['itmk'.$imn] == 'Y' && $pdata['itme'.$imn] > 0 ){
				$position = $imn;
				break;
			}
		}
		if($position){
			if(strpos($wepsk,'e')!==false){
				$log .= '<span class="red">武器已经带有电击属性，不用改造！</span><br />';
				$mode = 'command';
				return;
			}elseif(strlen($wepsk)>=5){
				$log .= '<span class="red">武器属性数目达到上限，无法改造！</span><br />';
				$mode = 'command';
				return;
			}
			$wep = '电气'.$wep;
			$wepsk .= 'Ae';
			$log .= "<span class=\"yellow\">用电池改造了{$wep}，{$wep}增加了电击属性！</span><br />";
			$pdata['itms'.$position]-=1;
			$itm = $pdata['itm'.$position];
			if($pdata['itms'.$position] <= 0){
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				$pdata['itm'.$position] = $pdata['itmk'.$position] = $pdata['itmsk'.$position] = '';
				$pdata['itme'.$position] = $pdata['itms'.$position] = $pdata['itmnp'.$position] = 0;				
			}
			$mode = 'command';
			return;
		}else{
			$log .= '<span class="red">你没有电池，无法改造武器！</span><br />';
			$mode = 'command';
			return;
		}
	}elseif($club == 8){//带毒改造
		$position = 0;
		foreach(Array(1,2,3,4,5,6) as $imn){
			//global ${'itm'.$imn},${'itmk'.$imn},${'itme'.$imn},${'itms'.$imn},${'itmsk'.$imn};
			if($pdata['itm'.$imn] == '毒药' && $pdata['itmk'.$imn] == 'Y' && $pdata['itme'.$imn] > 0 ){
				$position = $imn;
				break;
			}
		}
		if($position){
			if(strpos($wepsk,'p')!==false){
				$log .= '<span class="red">武器已经带毒，不用改造！</span><br />';
				$mode = 'command';
				return;
			}elseif(strlen($wepsk)>=15){
				$log .= '<span class="red">武器属性数目达到上限，无法改造！</span><br />';
				$mode = 'command';
				return;
			}
			$wep = '毒性'.$wep;
			$wepsk .= 'Ap';
			$log .= "<span class=\"yellow\">用毒药为{$wep}淬毒了，{$wep}增加了带毒属性！</span><br />";
			$pdata['itms'.$position]-=1;
			$itm = $pdata['itm'.$position];
			if($pdata['itms'.$position] == 0){
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				$pdata['itm'.$position] = $pdata['itmk'.$position] = $pdata['itmsk'.$position] = '';
				$pdata['itme'.$position] =$pdata['itms'.$position] = $pdata['itmnp'.$position] = 0;				
			}
			$mode = 'command';
			return;
		}else{
			$log .= '<span class="red">你没有毒药，无法给武器淬毒！</span><br />';
			$mode = 'command';
			return;
		}
	}else{
		$log .= '<span class="red">你不懂得如何改造武器！</span><br />';
		$mode = 'command';
		return;
	}
}

function chginf($infpos){
	global $pdata,$log,$mode,$inf_sp,$inf_sp_2,$infdata;
	$club = $pdata['club'];
	$inf = & $pdata['inf']; $sp = & $pdata['sp'];
	$normalinf = Array('h','b','a','f');
	if(!$infpos){$mode = 'command';return;}
	if($infpos == 'A'){  //包扎全身伤口
		if($club == 16){
			$spdown = 0;
			foreach($normalinf as $value){
				if(strpos($inf,$value)!== false){
					$spdown += $inf_sp;
				}
			}
			if(!$spdown){
				$log .= '你并没有受伤！';
				$mode = 'command';
				return;
			}elseif($sp <= $spdown){
				$log .= "包扎全部伤口需要{$spdown}点体力，先回复体力吧！";
				$mode = 'command';
				return;
			}
			$inf = str_replace('h','',$inf);
			$inf = str_replace('b','',$inf);
			$inf = str_replace('a','',$inf);
			$inf = str_replace('f','',$inf);
			$sp -= $spdown;
			$log .= "消耗<span class=\"yellow\">$spdown</span>点体力，全身伤口都包扎好了！";
			$mode = 'command';
			return;
		}else{
			$log .= '你不懂得怎样快速包扎伤口！';
			$mode = 'command';
			return;
		}
	}elseif(in_array($infpos,$normalinf) && strpos($inf,$infpos) !== false){	//普通伤口
		if($sp <= $inf_sp) {
			$log .= "包扎伤口需要{$inf_sp}点体力，先回复体力吧！";
			$mode = 'command';
			return;
		} else {
			$inf = str_replace($infpos,'',$inf);
			$sp -= $inf_sp;
			$log .= "消耗<span class=\"yellow\">$inf_sp</span>点体力，{$infdata[$infpos]['short']}部的伤口已经包扎好了！";
			$mode = 'command';
			return;
		}
	}elseif(strpos($inf,$infpos) !== false){  //特殊状态
		if($club == 16){
			if($sp <= $inf_sp_2) {
				$log .= "处理异常状态需要{$inf_sp_2}点体力，先回复体力吧！";
				$mode = 'command';
				return;
			} else {
				$inf = str_replace($infpos,'',$inf);
				$sp -= $inf_sp_2;
				$log .= "消耗<span class=\"yellow\">$inf_sp_2</span>点体力，{$infdata[$infpos]['name']}状态已经完全治愈了！";
				$mode = 'command';
				return;
			}
		}else{
			$log .= '你不懂得怎样治疗异常状态！';
			$mode = 'command';
			return;
		}
	}else{
		$log .= '你不需要包扎这个伤口！';
		$mode = 'command';
		return;
	}
}

function chkpoison($itmn){
	global $log,$mode,$club;
	if($club != 8){
		$log .= '你不会查毒。';
		$mode = 'command';
		return;
	}

	if ( $itmn < 1 || $itmn > 5 ) {
		$log .= '此道具不存在，请重新选择。';
		$mode = 'command';
		return;
	}

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
	
	if(strpos($itmk,'P') === 0) {
		$log .= '<span class="red">'.$itm.'有毒！</span>';
	} else {
		$log .= '<span class="yellow">'.$itm.'是安全的。</span>';
	}
	$mode = 'command';
	return;
}

function shoplist($sn) {
	global $gamecfg,$mode,$itemdata,$areanum,$areaadd,$iteminfo,$itemspkinfo,$club;
	global $db,$tablepre;
	$arean = floor($areanum / $areaadd); 
	$result=$db->query("SELECT * FROM {$tablepre}shopitem WHERE kind = '$sn' AND area <= '$arean' AND num > '0' AND price > '0'");
	$shopnum = $db->num_rows($result);
	for($i=0;$i< $shopnum;$i++){
		$itemlist = $db->fetch_array($result);
		$itemdata[$i]['sid']=$itemlist['sid'];
		$itemdata[$i]['kind']=$itemlist['kind'];
		$itemdata[$i]['num']=$itemlist['num'];
		$itemdata[$i]['price']= $club == 11 ? round($itemlist['price']*0.75) : $itemlist['price'];
		$itemdata[$i]['area']=$itemlist['area'];
		$itemdata[$i]['item']=$itemlist['item'];
		$itemdata[$i]['itme']=$itemlist['itme'];
		$itemdata[$i]['itms']=$itemlist['itms'];
		//list($sid,$kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk)=explode(',',$itemlist);
		$itemdata[$i]['itmk_words'] = get_itmkwords($itemlist['itmk']);
		$itemdata[$i]['itmsk_words'] = get_itmskwords($itemlist['itmk'],$itemlist['itmsk']);
//		foreach($iteminfo as $info_key => $info_value){
//			if(strpos($itemlist['itmk'],$info_key)===0){
//				$itemdata[$i]['itmk_words'] = $info_value;
//				break;
//			}
//		}
//		$itemdata[$i]['itmsk_words'] = '';
//		if($itemlist['itmsk']){
//			for ($j = 0; $j < strlen($itemlist['itmsk']); $j++) {
//				$sub = substr($itemlist['itmsk'],$j,1);
//				if(!empty($sub)){
//					$itemdata[$i]['itmsk_words'] .= $itemspkinfo[$sub];
//				}
//			}
//		}
	}
	
	$mode = 'shop';

	return;
}

?>