<?php

define('CURSCRIPT', 'chat');

require './include/common.inc.php';
//require_once GAME_ROOT.'./include/JSON.php';

if(!$cuser || !defined('IN_GAME')) {
	exit('Not in game.');
}

$result = $db->query("SELECT teamID,pls FROM {$tablepre}players WHERE name = '$cuser' AND type = '0'");
$rst = $db->fetch_array($result);
$cteam = $rst['teamID'];
$cpls = $rst['pls'];
unset($rst);

if(($sendmode == 'send')&&$chatmsg) {
	if(strpos($chatmsg,'/') === 0) {
		//$chatdata = array('lastcid' => $lastcid, 'msg' => Array($lastcid => '<span class="red">聊天信息不能用 / 开头。</span><br>'));
		$result = $db->query("SELECT groupid FROM {$tablepre}users WHERE username='$cuser'");
		$groupid = $db->result($result);
		if($groupid > 1 || $cuser == $gamefounder) {
			if(strpos($chatmsg,'/post') === 0) {
				$chatmsg = substr($chatmsg,6);
				if($chatmsg){
					$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('4','$now','$cuser','$chatmsg')");
				}
			} else {
				$chatdata = array('lastcid' => $lastcid, 'msg' => Array($lastcid => '<span class="red">指令错误。</span><br>'));
			}
		} else {
			$chatdata = array('lastcid' => $lastcid, 'msg' => Array($lastcid => '<span class="red">聊天信息不能用 / 开头。</span><br>'));
		}
	} else { 
		if($chattype == 0) {
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('0','$now','$cuser','$chatmsg')");
		} elseif($chattype == 1) {
			if(!empty($cteam)){
				$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('1','$now','$cuser','$cteam','$chatmsg')");
			}else{
				$chatdata = array('lastcid' => $lastcid, 'msg' => Array($lastcid => '<span class="red">你没加入队伍。</span><br>'));
			}			
		} elseif($chattype == 2) {
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('2','$now','$cuser','$cpls','$chatmsg')");
		} elseif($chattype == 4) {
			$result = $db->query("SELECT groupid FROM {$tablepre}users WHERE username='$cuser'");
			$groupid = $db->result($result);
			if($groupid > 1 || $cuser == $gamefounder) {
				$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('4','$now','$cuser','$chatmsg')");
			} else {
				$chatdata = array('lastcid' => $lastcid, 'msg' => Array($lastcid => '<span class="red">你不是管理员，不能发布公告。</span><br>'));
			}
		}
//		elseif($chattype == 4) {
//			$result = $db->query("SELECT groupid FROM {$tablepre}users WHERE username='$cuser'");
//			$groupid = $db->result($result);
//			if($groupid > 1 || $cuser == $gamefounder) {
//				$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('4','$now','$cuser','$chatmsg')");
//			}else{
//				$chatdata = array('lastcid' => $lastcid, 'msg' => Array($lastcid => '<span class="red">你不是管理员，不能发布公告。</span><br>'));
//			}			
//		}
	}
}
if(!$chatdata) {
	//$chatdata = getchat($lastcid,$cteam,$cpls);
	$chatdata = getchat(0,$cteam,$cpls);//lastcid弃之不用
}
ob_clean();
//$json = new Services_JSON();
//$jgamedata = $json->encode($chatdata);
$jgamedata = compatible_json_encode($chatdata);
echo $jgamedata;
ob_end_flush();


?>