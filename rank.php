<?php

define('CURSCRIPT', 'list');

require './include/common.inc.php';
require './include/display.func.php';

$result = $db->query("SELECT COUNT(*) FROM {$tablepre}users");
$count = $db->result($result,0);
if($ranklimit < 1){$ranklimit = 1;}
if(!isset($command) || !isset($start) || $start < 0) {
	$start = $ostart = 0;
}elseif($command == 'last'){
	$ostart = $start;
//	if($start - $ranklimit < 0){
//		$start = 0;
//	}else{
//		$start -= $ranklimit;
//	}
	$start -= $ranklimit;
}elseif($command == 'next'){
	$ostart = $start;
//	if($start + $ranklimit > $count){
//		$start = $count - $ranklimit >= 0 ? $count - $ranklimit : 0;
//	}else{
//		$start += $ranklimit;
//	}
	$start += $ranklimit;
}


if($count == 0){gexit('No data!');}
if($start < 0){$start = 0;}
elseif($start + $ranklimit > $count){
	if($count - $ranklimit >= 0){
		$start = $count - $ranklimit;
	}else{
		$start = 0;
	}
}
//elseif($start + $ranklimit > $count){$ranklimit = $count - $start;}

$startnum = $start + 1;
if($start + $ranklimit > $count){
	$endnum = $count;
}else{
	$endnum = $start + $ranklimit;
}
if(!isset($command) || $start != $ostart){
	$result = $db->query("SELECT * FROM {$tablepre}users ORDER BY credits DESC, validtimes DESC, uid LIMIT $start,$ranklimit");
	$rankdata = Array();
	
	while($data = $db->fetch_array($result)){
		$data['img'] = $data['gender'] == 'm' ? 'm_'.$data['icon'].'.gif' : 'f_'.$data['icon'].'.gif';
		$data['motto'] = $data['motto'] ? rep_label($data['motto']) : '无';
		$data['honour'] = $data['honour'] ? init_honourwords($data['honour']) : '无';
		$rankdata[] = $data;
	}
		
	if(isset($command)){
		include template('rankinfo');
		$showdata['innerHTML']['rank'] = ob_get_contents();
		ob_clean();
		$showdata['innerHTML']['pageinfo'] = "第<span class=\"yellow\">$startnum</span>条至第<span class=\"yellow\">$endnum</span>条";;
		$showdata['value']['start'] = $start;
		$jgamedata = compatible_json_encode($showdata);
		echo $jgamedata;
		ob_end_flush();
	}else{
		include template('rank');
	}
}
//else{
//	$showdata['innerHTML']['notice'] = '不需要！';
//	ob_clean();
//	$jgamedata = compatible_json_encode($showdata);
//	echo $jgamedata;
//	ob_end_flush();
//}




?>