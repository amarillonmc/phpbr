<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 2){
	exit($_ERROR['no_power']);
}



if($command == 'pcmng') {
	include_once GAME_ROOT.'./include/admin/pcmng.php';
} elseif($command == 'npcmng') {
	include_once GAME_ROOT.'./include/admin/npcmng.php';
} elseif($command == 'shopmng') {
} elseif($command == 'mapmng') {
} elseif($command == 'chatmng') {
} elseif($command == 'newsmng') {
} elseif($command == 'infomng') {
	include_once GAME_ROOT.'./include/admin/infomng.php';
} elseif($command == 'antiAFKmng') {
	include_once GAME_ROOT.'./include/admin/antiAFKmng.php';
} elseif($command == 'sttimemng') {
	include_once GAME_ROOT.'./include/admin/sttimemng.php';
} else {
echo <<<EOT
<form method="post" name="gamemng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="radio" name="command" id="pcmng" value="pcmng"><a onclick=sl('pcmng'); href="javascript:void(0);" >角色管理</a><br>
<input type="radio" name="command" id="npcmng" value="npcmng"><a onclick=sl('npcmng'); href="javascript:void(0);" >NPC管理</a><br>
<!--<input type="radio" name="command" id="shopmng" value="shopmng"><a onclick=sl('shopmng'); href="javascript:void(0);" >商店管理</a><br>-->
<!--<input type="radio" name="command" id="mapmng" value="mapmng"><a onclick=sl('mapmng'); href="javascript:void(0);" >地图管理</a><br>-->
<!--<input type="radio" name="command" id="chatmng" value="chatmng"><a onclick=sl('chatmng'); href="javascript:void(0);" >聊天管理</a><br>-->
<!--<input type="radio" name="command" id="newsmng" value="newsmng"><a onclick=sl('newsmng'); href="javascript:void(0);" >新闻管理</a><br>-->
<input type="radio" name="command" id="infomng" value="infomng"><a onclick=sl('infomng'); href="javascript:void(0);" >游戏状态同步（用于激活，生存或死亡人数与实际不符时）</a><br>
<input type="radio" name="command" id="antiAFKmng" value="antiAFKmng"><a onclick=sl('antiAFKmng'); href="javascript:void(0);" >启动反挂机功能</a><br>
<input type="radio" name="command" id="sttimemng" value="sttimemng"><a onclick=sl('sttimemng'); href="javascript:void(0);" >设置下局时间</a><br>
<input type="submit" name="submit" value="提交">
</form>
EOT;
}





?>