
<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}
global $gamecfg;
include_once config('combatcfg',$gamecfg);

/*
$poseinfo = Array('通常','攻击姿态','防守姿态','探索姿态','隐藏姿态','治疗姿态');
$tacinfo = Array('通常','','重视防御','重视反击','重视躲避',);

*/

//发现率修正 find_r,越大越容易发现目标
function get_find_r($weather = 0,$pls = 0,$pose = 0,$tactic = 0,$club = 0,$inf = ''){
	global $mapdata,$wthdata;
	$_FIND = Array
		(
		//'weather' => array(10,20,0,-2,-3,-7,-10,-5,10,0,0,-7,-5,-30),
		//'pls' => array(10,0,0,10,-10,10,0,10,-10,0,10,0,0,-10,0,-10,-10,-10,0,10,0,10),
		'pose' => array(0,0,0,20,-10,-20,-5),
		'tactic' => array(),
		);
	$find_r = 0;
	$find_r += $_FIND['pose'][$pose];
	$find_r += $wthdata[$weather]['findr'];
	$find_r += $mapdata[$pls]['findr'];
//	if($find_r>100){$find_r = 100;}
//	elseif($find_r < 10){$find_r = 10;}
	//echo '<br>天气发现率:' . $wthdata[$weather]['findr'] . ' 实际发现率：' . $find_r.'<br>';
	return $find_r;
}
                                                            

//躲避率修正 hide_r,越大越不容易被发现
function get_hide_r($weather = 0,$pls = 0,$pose = 0,$tactic = 0,$club = 0,$inf = ''){
	global $mapdata,$wthdata;
	$_HIDE = Array
		(
		//'weather' => array(),
		//'pls' => array(),
		'pose' => array(),
		'tactic' => array(0,-5,5,0,20,-10,0),
		);
	$hide_r = 0;
	$hide_r += $_HIDE['tactic'][$tactic];
	$hide_r += $wthdata[$weather]['hider'];
	$hide_r += $mapdata[$pls]['hider'];
//	if($hide_r>100){$hide_r = 100;}
//	elseif($hide_r < 10){$hide_r = 10;}
	//echo '<br>天气躲避率:' . $wthdata[$weather]['hider'] . ' 实际躲避率：' . $hide_r.'<br>';
	return $hide_r;
}
//先攻几率修正，越大越容易先攻                                                            
function get_active_r($wkind = 'N',$weather = 0,$pls = 0,$pose = 0,$tactic = 0,$club = 0,$inf = '',$w_inf=''){
	global $active_obbs,$inf_active_p,$mapdata,$wthdata;
	$_ACTIVE = Array
		(
		//'weather' => array(20,10,0,-3,-5,-5,-7,10,-10,-10,-10,-5,0,-5),
		//'pls' => array(),
		'pose' => array(0,0,-10,10,20,-20,-5),
		'tactic' => array(),
		);
		
	$active_r = $active_obbs[$wkind];
	$active_r += $wthdata[$weather]['activer'];
	$active_r += $_ACTIVE['pose'][$pose];
	$active_r += $mapdata[$pls]['activer'];
	foreach ($inf_active_p as $inf_ky => $value) {
		if(strpos($inf, $inf_ky)!==false){$active_r *= $value;}
		if(strpos($w_inf, $inf_ky)!==false){
			if($value == 0){
				$active_r = 100;
			}else{
				$active_r /= $value;
			}
		}
	}	
	
	if($active_r>100){$active_r = 100;}
	//echo '实际先攻率：' . $active_r.'<br>';
	return $active_r;
}
//命中率修正
function get_hitrate($wkind = 'N',$skill = 0,$inf = '',$pls = 0){
	global $hitrate_obbs,$hitrate_max_obbs,$hitrate_r,$weather,$inf_htr_p,$mapdata,$wthdata;
	$hitrate = $hitrate_obbs[$wkind];
	$hitrate += round($skill * $hitrate_r[$wkind]); 
	$hitrate += $wthdata[$weather]['hitr'];
	$hitrate += $mapdata[$pls]['hitr'];
	if($hitrate > $hitrate_max_obbs[$wkind]) {$hitrate = $hitrate_max_obbs[$wkind];}
	foreach ($inf_htr_p as $inf_ky => $value) {
		if(strpos($inf, $inf_ky)!==false){$hitrate *= $value;}
	}	
	//if($weather == 12){$hitrate += 20;}
	////echo 'hitrate:'.$hitrate.' ';
//	if($hitrate>100){$hitrate = 100;}
//	elseif($hitrate < 10){$hitrate = 10;}
	//echo '<br>天气命中率:' . $wthdata[$weather]['hitr'] . ' 实际命中率：' . $hitrate.'<br>';
	return $hitrate;
}

//获取反击几率
function get_counter($pls,$wkind = 'N',$tactic = 0,$club = 0,$inf = ''){
	global $counter_obbs,$inf_counter_p,$mapdata,$wthdata,$weather;
	$counter = $counter_obbs[$wkind];
	$counter += $wthdata[$weather]['counterr'];
	$counter += $mapdata[$pls]['counterr'];
	if($tactic == 4) {
		$counter = 0;
	} elseif($tactic == 3) {
		$counter += 30;
	}
	foreach ($inf_counter_p as $inf_ky => $value) {
		if(strpos($inf, $inf_ky)!==false){$counter *= $value;}
	}	
	if($counter>100){$counter = 100;}
//	elseif($counter < 10){$counter = 10;}
	//echo '实际反击率：' . $counter.'<br>';
	return $counter;
}
//攻击力修正，百分比增加
function get_attack_p($weather = 0,$pls = 0,$pose = 0,$tactic = 0,$club = 0,$inf = '',$active = 1){
	global $inf_att_p,$mapdata,$wthdata;
	$_ATTACK = Array
		(
		//'weather' => array(20,20,0,-20,-5,-7,-7,-10,0,5,20,-7,-20,-5),
		//'pls' => array(0,0,0,0,0,0,10,0,0,-10,0,0,0,0,-10,0,0,0,10,0,0,0),
		'pose' => array(0,20,-20,-5,-20,-30,0),
		'tactic' => array(0,20,-20,5,-10,-30),
		);
	
	$attack = 100;
	$attack += $wthdata[$weather]['attp'];
	//$attack += $_ATTACK['pls'][$pls];
	$attack += $mapdata[$pls]['attp'];
	if($active){$attack += $_ATTACK['pose'][$pose];}
	else{$attack += $_ATTACK['tactic'][$tactic];}
	foreach ($inf_att_p as $inf_ky => $value) {
		if(strpos($inf, $inf_ky)!==false){$attack *= $value;}
	}	
//	if($battle_cmd != 'natk'){
//		$b_cmd_fac = $techniqueinfo['active'][$battle_cmd];
//		if(isset($b_cmd_fac['att'])){
//			$attack += $b_cmd_fac['att'];
//		}
//	}
	$attack = $attack > 0 ? $attack : 1;
//	echo '实际攻击加成：' . $attack.'<br>';
	return $attack/100;
}
//防御力修正，百分比
function get_defend_p($weather = 0,$pls = 0,$pose = 0,$tactic = 0,$club = 0,$inf = '',$active = 1){
	global $inf_def_p,$mapdata,$wthdata;
	$_DEFEND = Array
		(
		//'weather' => array(30,10,0,-3,-3,-5,-10,-15,-20,-30,-50,-5,-20,-3),
		//'pls' => array(0,-10,10,0,0,0,0,0,0,0,0,-10,10,0,0,0,0,0,0,0,10,0),
		'pose' => array(0,-20,20,-10,-5,-15,0),
		'tactic' => array(0,-20,20,-10,10,-5),
		);

	$defend = 100;
	$defend += $wthdata[$weather]['defp'];
	//$defend += $_DEFEND['pls'][$pls];
	$defend += $mapdata[$pls]['defp'];
	if($active){$defend += $_DEFEND['pose'][$pose];}
	else{$defend += $_DEFEND['tactic'][$tactic];}
	foreach ($inf_def_p as $inf_ky => $value) {
		if(strpos($inf, $inf_ky)!==false){$defend *= $value;}
	}	
//	if($battle_cmd != 'natk'){
//		$b_cmd_fac = $techniqueinfo['active'][$battle_cmd];
//		if(isset($b_cmd_fac['def'])){
//			$defend += $b_cmd_fac['def'];
//		}
//	}
	$defend = $defend > 0 ? $defend : 1;
//	echo '实际防御加成：' . $defend.'<br>';
	return $defend/100;
}


?>