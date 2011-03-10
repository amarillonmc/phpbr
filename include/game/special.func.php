<?php


if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function getword(){
	global $db,$tablepre,$name,$motto,$lastword,$killmsg;
	
	$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$name'");
	$userinfo = $db->fetch_array($result);
	$motto = $userinfo['motto'];
	$lastword = $userinfo['lastword'];
	$killmsg = $userinfo['killmsg'];
	
}

function chgword($nmotto,$nlastword,$nkillmsg) {
	global $db,$tablepre,$name,$log;
	
	$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$name'");
	$userinfo = $db->fetch_array($result);

//	foreach ( Array('<','>',';',',','\\\'','\\"') as $value ) {
//		if(strpos($nmotto,$value)!==false){
//			$nmotto = str_replace ( $value, '', $nmotto );
//		}
//		if(strpos($nlastword,$value)!==false){
//			$nlastword = str_replace ( $value, '', $nlastword );
//		}
//		if(strpos($nkillmsg,$value)!==false){
//			$nkillmsg = str_replace ( $value, '', $nkillmsg );
//		}
//	}

	
	if($nmotto != $userinfo['motto']) {
		$log .= $nmotto == '' ? '口头禅已清空。' : '口头禅变更为<span class="yellow">'.$nmotto.'</span>。<br>';
	}
	if($nlastword != $userinfo['lastword']) {
		$log .= $nlastword == '' ? '遗言已清空。' : '遗言变更为<span class="yellow">'.$nlastword.'</span>。<br>';
	}
	if($nkillmsg != $userinfo['killmsg']) {
		$log .= $nkillmsg == '' ? '杀人留言已清空。' : '杀人留言变更为<span class="yellow">'.$nkillmsg.'</span>。<br>';
	}

	$db->query("UPDATE {$tablepre}users SET motto='$nmotto', lastword='$nlastword', killmsg='$nkillmsg' WHERE username='$name'");
	
	$mode = 'command';
	return;
}

function chgpassword($oldpswd,$newpswd,$newpswd2){
	global $db,$tablepre,$name,$log;
	
	if (!$oldpswd || !$newpswd || !$newpswd2){
		$log .= '放弃了修改密码。<br />';
		$mode = 'command';
		return;
	} elseif ($newpswd !== $newpswd2) {
		$log .= '<span class="red">两次输入的新密码不一致。</span><br />';
		$mode = 'command';
		return;
	}
	
	$oldpswd = md5($oldpswd);$newpswd = md5($newpswd);
	
	$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$name'");
	$userinfo = $db->fetch_array($result);
	
	if($oldpswd == $userinfo['password']){
		$db->query("UPDATE {$tablepre}users SET `password` ='$newpswd' WHERE username='$name'");
		$log .= '<span class="yellow">密码已修改！</span><br />';
		
		//include_once GAME_ROOT.'./include/global.func.php';
		
		gsetcookie('pass',$newpswd);
		$mode = 'command';
		return;
	}else{
		$log .= '<span class="red">原密码输入错误！</span><br />';
		$mode = 'command';
		return;
	}
}


function chginf($infpos){
	global $log,$mode,$inf,$inf_sp,$sp,$infinfo;

	if(!$infpos){$mode = 'command';return;}
	if(strpos($inf,$infpos) !== false) {
		if($sp <= $inf_sp) {
			$log .= '体力不足，无法包扎伤口，先休息一下吧！';
			$mode = 'command';
			return;
		} else {
			$inf = str_replace($infpos,'',$inf);
			$sp -= $inf_sp;
			$log .= $infinfo[$infpos].'<span class="red">部</span>的伤口已经包扎好了！';
			$mode = 'command';
			return;
		}
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
	global $gamecfg,$mode,$itemdata,$areanum,$areaadd,$iteminfo,$itemspkinfo;
	global $db,$tablepre;
	$arean = floor($areanum / $areaadd); 
	$result=$db->query("SELECT * FROM {$tablepre}shopitem WHERE kind = '$sn' AND area <= '$arean' AND num > '0' AND price > '0'");
	$shopnum = $db->num_rows($result);
	for($i=0;$i< $shopnum;$i++){
		$itemlist = $db->fetch_array($result);
		$itemdata[$i]['sid']=$itemlist['sid'];
		$itemdata[$i]['kind']=$itemlist['kind'];
		$itemdata[$i]['num']=$itemlist['num'];
		$itemdata[$i]['price']=$itemlist['price'];
		$itemdata[$i]['area']=$itemlist['area'];
		$itemdata[$i]['item']=$itemlist['item'];
		$itemdata[$i]['itme']=$itemlist['itme'];
		$itemdata[$i]['itms']=$itemlist['itms'];
		//list($sid,$kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk)=explode(',',$itemlist);
		foreach($iteminfo as $info_key => $info_value){
			if(strpos($itemlist['itmk'],$info_key)===0){
				$itemdata[$i]['itmk_words'] = $info_value;
				break;
			}
		}
		$itemdata[$i]['itmsk_words'] = '';
		if($itemlist['itmsk'] && ! is_numeric($itemlist['itmsk'])){
			for ($j = 0; $j < strlen($itemlist['itmsk']); $j++) {
				$itemdata[$i]['itmsk_words'] .= $itemspkinfo[substr($itemlist['itmsk'],$j,1)];
			}
		}
		//$itemdata[$i] = array('sid' => $sid, 'kind' => $kind,'num' => $num, 'price' => $price, 'area' => $area, 'item' => $item,'itmk_words' => $itmk_words,'itme' => $itme, 'itms' => $itms,'itmsk_words' => $itmsk_words);
	}
	
	$mode = 'shop';

	return;
	//$file = GAME_ROOT."./gamedata/shopitem/{$sn}shopitem.php";
	//$itemlist = openfile($file);
	//$in = count($itemlist);
//	for($i=1;$i<$in;$i++){
//		list($num,$price,$iname,$ikind,$ieff,$ista,$isk) = explode(',',$itemlist[$i]);
//		if(($num<=0)||($price<=0)){
//			$itemdata[$i] = '';
//		} elseif (strpos($ikind,'_') !== false) {
//			list($ik,$it) = explode('_',$ikind);
//			if($areanum < $it*$areaadd) {
//				$itemdata[$i] = '';
//			} else {
//				foreach($iteminfo as $info_key => $info_value){
//					if(strpos($ik,$info_key)===0){
//						$ikind_words = $info_value;
//						break;
//					}
//				}
//				$isk_words = '';
//				if($isk && ! is_numeric($isk)){
//					for ($j = 0; $j < strlen($isk); $j++) {
//						$isk_words .= $itemspkinfo[substr($isk,$j,1)];
//					}
//				}
//				$itemdata[$i] = array($i,$num,$price,$iname,$ikind_words,$ieff,$ista,$isk_words);
//			}
//		} else {
//			foreach($iteminfo as $info_key => $info_value){
//				if(strpos($ikind,$info_key)===0){
//					$ikind_words = $info_value;
//					break;
//				}
//				
//			}
//			$isk_words = '';
//			if($isk && ! is_numeric($isk)){
//				for ($j = 0; $j < strlen($isk); $j++) {
//					$isk_words .= $itemspkinfo[substr($isk,$j,1)];
//				}
//			}
//			$itemdata[$i] = array($i,$num,$price,$iname,$ikind_words,$ieff,$ista,$isk_words);
//		}
//	}
//	$mode = 'shop';
//
//	return;

}

//function shoplist($sn) {
//	global $gamecfg,$mode,$itemdata,$areanum,$areaadd,$iteminfo,$itemspkinfo;
//	$file = GAME_ROOT."./gamedata/shopitem/{$sn}shopitem.php";
//	$itemlist = openfile($file);
//	$in = count($itemlist);
//	for($i=1;$i<$in;$i++){
//		list($num,$price,$iname,$ikind,$ieff,$ista,$isk) = explode(',',$itemlist[$i]);
//		if(($num<=0)||($price<=0)){
//			$itemdata[$i] = '';
//		} elseif (strpos($ikind,'_') !== false) {
//			list($ik,$it) = explode('_',$ikind);
//			if($areanum < $it*$areaadd) {
//				$itemdata[$i] = '';
//			} else {
//				foreach($iteminfo as $info_key => $info_value){
//					if(strpos($ik,$info_key)===0){
//						$ikind_words = $info_value;
//						break;
//					}
//				}
//				$isk_words = '';
//				if($isk && ! is_numeric($isk)){
//					for ($j = 0; $j < strlen($isk); $j++) {
//						$isk_words .= $itemspkinfo[substr($isk,$j,1)];
//					}
//				}
//				$itemdata[$i] = array($i,$num,$price,$iname,$ikind_words,$ieff,$ista,$isk_words);
//			}
//		} else {
//			foreach($iteminfo as $info_key => $info_value){
//				if(strpos($ikind,$info_key)===0){
//					$ikind_words = $info_value;
//					break;
//				}
//				
//			}
//			$isk_words = '';
//			if($isk && ! is_numeric($isk)){
//				for ($j = 0; $j < strlen($isk); $j++) {
//					$isk_words .= $itemspkinfo[substr($isk,$j,1)];
//				}
//			}
//			$itemdata[$i] = array($i,$num,$price,$iname,$ikind_words,$ieff,$ista,$isk_words);
//		}
//	}
//	$mode = 'shop';
//
//	return;
//
//}

?>