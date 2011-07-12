<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 6){
	exit($_ERROR['no_power']);
}
$dir = GAME_ROOT.'./gamedata/';

if($write){
	write_list($dir,$postnmlmt,$postiplmt);
	echo '新的屏蔽列表已经写入。<br>';
}

include_once $dir.'banlist.php';
$iplimit = str_replace('\\.','.',$iplimit);
//foreach(Array('nm','ip') as $ar_nm){
//	${$ar_nm.'lmtlist0'} = ${$ar_nm.'lmtlist'} = '';
//	foreach(${$ar_nm.'limit'} as $value){
//		${$ar_nm.'lmtlist'} .= $value.'|';
//	}
//	if(${$ar_nm.'lmtlist0'} != ${$ar_nm.'lmtlist'}){
//		${$ar_nm.'lmtlist'} = substr(${$ar_nm.'lmtlist'},0,-1);
//	}
//}

function write_list($dir,$nmlmtstr,$iplmtstr){
//	foreach(Array('nm','ip') as $ar_nm){
//		${$ar_nm.'lmtarray'} = explode('|',${$ar_nm.'lmtstr'});
//		${$ar_nm.'lmtlist0'} = ${$ar_nm.'lmtlist'} = '';
//		foreach(${$ar_nm.'lmtarray'} as $value){
//			${$ar_nm.'lmtlist'} .= "'$value',";
//		}
//		if(${$ar_nm.'lmtlist0'} != ${$ar_nm.'lmtlist'}){
//			${$ar_nm.'lmtlist'} = 'Array('.substr(${$ar_nm.'lmtlist'},0,-1).')';
//		}else{
//			${$ar_nm.'lmtlist'} = 'Array()';
//		}
//	}
	$iplmtstr = str_replace('.','\\.',$iplmtstr);
	$vldata = "<?php\n\n\$nmlimit = '$nmlmtstr';\n\$iplimit = '$iplmtstr';\n\n?>";
	if($fp = fopen("{$dir}banlist.php", 'w')) {
		if(flock($fp,LOCK_EX)) {
			fwrite($fp, $vldata);
		} else {
			exit("Couldn't save the game's info !");
		}
		fclose($fp);
	} else {
		gexit('Can not write to cache files, please check directory ./gamedata/ .', __file__, __line__);
	}
	return;
}
echo <<<EOT
<form method="post" name="banlist" onsubmit="admin.php">
<input type="hidden" name="mode" value="banlistmng">
<input type="hidden" name="command" value="banlistmng">
<input type="hidden" name="write" value="1">
<div>输入要屏蔽的用户名和IP段的正则表达式。修改之前请弄明白你正在做什么。</div>
<div>用户名屏蔽：<br><textarea name="postnmlmt" style="width:450;height:150">$nmlimit</textarea></div><br>
<div>IP段屏蔽：<br><textarea name="postiplmt" style="width:450;height:150">$iplimit</textarea></div>
<input type="submit" value="提交">
</form>
EOT;
?>