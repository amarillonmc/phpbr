<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 6){
	exit($_ERROR['no_power']);
}


if($command == 'check') {
	$urorder = $urorder ? $urorder : 'uid';
	$start = getstart($start,$pagemode);
	$result = $db->query("SELECT * FROM {$tablepre}users ORDER BY $urorder DESC, uid DESC LIMIT $start,$showlimit");
	if(!$db->num_rows($result)) { echo '没有符合条件的用户！'; }
	else {
		while($ur = $db->fetch_array($result)) {
			$urdata[] = $ur;
		}
		foreach($urdata as $n => $ur) {
			$urlisthtm .= "<tr><td><input type=\"checkbox\" name=\"user_$n\" value=\"{$ur['username']}\"></td><td>{$ur['username']}</td><td>{$urgroup[$ur['groupid']]}</td><td>第{$ur['lastgame']}局</td><td>{$ur['ip']}</td><td>{$ur['credits']}</td><td>{$ursex[$ur['gender']]}</td><td>{$ur['icon']}</td><td>{$clubinfo[$ur['club']]}</td><td>{$ur['motto']}</td><td>{$ur['killmsg']}</td><td>{$ur['lastword']}</td></tr>";
		}
		echo count($urdata).'条结果。<br>';
		$cmd = '<input type="hidden" name="command" value="check"><input type="hidden" name="urorder" value="'.$urorder.'">';
		urlist($urlisthtm,$cmd,$start);
	}
} elseif($command == 'find') {
	$start = getstart($start,$pagemode);
	if($checkmode == 'ip') {
		$result = $db->query("SELECT * FROM {$tablepre}users WHERE ip LIKE '%{$checkinfo}%' ORDER BY uid DESC LIMIT $start,$showlimit");
	} else {
		$result = $db->query("SELECT * FROM {$tablepre}users WHERE username LIKE '%{$checkinfo}%' ORDER BY uid DESC LIMIT $start,$showlimit");
	}
	if(!$db->num_rows($result)) { echo '没有符合条件的用户！'; }
	else {
		while($ur = $db->fetch_array($result)) {
			$urdata[] = $ur;
		}
		foreach($urdata as $n => $ur) {
			$urlisthtm .= "<tr><td><input type=\"checkbox\" name=\"user_$n\" value=\"{$ur['username']}\"></td><td>{$ur['username']}</td><td>{$urgroup[$ur['groupid']]}</td><td>第{$ur['lastgame']}局</td><td>{$ur['ip']}</td><td>{$ur['credits']}</td><td>{$ursex[$ur['gender']]}</td><td>{$ur['icon']}</td><td>{$clubinfo[$ur['club']]}</td><td>{$ur['motto']}</td><td>{$ur['killmsg']}</td><td>{$ur['lastword']}</td></tr>";
		}
		echo count($urdata).'条结果。<br>';
		$cmd = '<input type="hidden" name="command" value="find"><input type="hidden" name="checkinfo" value="'.$checkinfo.'"><input type="hidden" name="checkmode" value="'.$checkmode.'">';
		urlist($urlisthtm,$cmd,$start);
	}
} elseif($command == 'ban') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'user_'.$i})) {
			$urlist[] = ${'user_'.$i};
		}
	}
	foreach($urlist as $n => $name) {
		$db->query("UPDATE {$tablepre}users SET groupid='0' WHERE username='$name' AND groupid<='$mygroup'");
		if($db->affected_rows()){
			adminlog('banur',$name);
			echo " 用户 $name 被封停。<br>";
		} else {
			echo "无法封停用户 $name 。";
		}
	}
} elseif($command == 'unban') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'user_'.$i})) {
			$urlist[] = ${'user_'.$i};
		}
	}
	foreach($urlist as $n => $name) {
		$db->query("UPDATE {$tablepre}users SET groupid='1' WHERE username='$name' AND groupid<='$mygroup'");
		if($db->affected_rows()){
			adminlog('unbanur',$name);
			echo " 用户 $name 已解封。<br>";
		} else {
			echo "无法解封用户 $name 。";
		}
	}
} elseif($command == 'del') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'user_'.$i})) {
			$urlist[] = ${'user_'.$i};
		}
	}
	foreach($urlist as $n => $name) {
		$db->query("DELETE FROM {$tablepre}users WHERE username='$name' AND groupid<='$mygroup'");
		if($db->affected_rows()){
			adminlog('delur',$name);
			echo " 用户 $name 被删除。<br>";
		} else {
			echo "无法删除用户 $name 。";
		}
	}
}  elseif($command == 'del2') {
	$result = $db->query("SELECT username,uid FROM {$tablepre}users WHERE lastgame = 0 AND groupid<='$mygroup' LIMIT 1000");
	while($ddata = $db->fetch_array($result)){
		$n = $ddata['username'];$u = $ddata['uid'];
		adminlog('delur',$n);
		echo " 用户 $n 被删除。<br>";
		$db->query("DELETE FROM {$tablepre}users WHERE uid='$u'");
		
	}
//	$i = $db->affected_rows();
//	adminlog('delng');
//	echo "$i名用户被删除。<br>";
//	for($i=0;$i<$showlimit;$i++){
//		if(isset(${'user_'.$i})) {
//			$urlist[] = ${'user_'.$i};
//		}
//	}
//	foreach($urlist as $n => $name) {
//		$db->query("DELETE FROM {$tablepre}users WHERE username='$name' AND groupid<='$mygroup'");
//		if($db->affected_rows()){
//			adminlog('delur',$name);
//			echo " 用户 $name 被删除。<br>";
//		} else {
//			echo "无法删除用户 $name 。";
//		}
//	}
}elseif($command == 'edit') {
	echo '此功能尚未开放！';
} else {

echo <<<EOT
<form method="post" name="urlist" onsubmit="admin.php">
$gmlist
<input type="hidden" name="mode" value="urlist">
<input type="radio" name="command" id="find" value="find" checked><a onclick=sl('find'); href="javascript:void(0);" >按<select name="checkmode">
	<option value="username" selected>用户名<br />
	<option value="ip">用户IP<br />
</select>查找用户</a><input size="30" type="text" name="checkinfo" id="checkinfo" maxlength="30" /><br><br>
<input type="radio" name="command" id="check" value="check"><a onclick=sl('check'); href="javascript:void(0);" >按
	<select name="urorder">
	<option value="groupid" selected>用户所属组<br />
	<option value="lastgame">最新游戏<br />
	<option value="uid">用户编号<br />
</select>查看用户列表</a><br>
<input type="radio" name="command" id="del2" value="del2"><a onclick=sl('del2'); href="javascript:void(0);" >删除未使用账户（每次1000个）</a><br>
<input type="submit" name="submit" value="提交">
</form>
EOT;
}



function urlist($htm,$cmd='',$start=0) {
	echo <<<EOT
<form method="post" name="urpage" onsubmit="admin.php">
<input type="hidden" name="mode" value="urlist">
<input type="hidden" name="start" value="$start">
<input type="hidden" name="pagemode" value="down">
$cmd
<input type="button" value="上一页" onclick="javascript:document.urpage.pagemode.value='up';document.urpage.submit();">
<input type="button" value="下一页" onclick="javascript:document.urpage.pagemode.value='down';document.urpage.submit();">
</form>
<form method="post" name="urlist" onsubmit="admin.php">
<table class="admin"><tr><td>选</td><td>账号</td><td>所属组</td><td>最新游戏</td><td>ip</td><td>分数</td><td>性别</td><td>头像</td><td>社团</td><td>口头禅</td><td>杀人留言</td><td>遗言</td></tr>
$htm
</table>
<input type="hidden" name="mode" value="urlist">
<input type="radio" name="command" id="ban" value="ban"><a onclick=sl('ban'); href="javascript:void(0);" >封停用户</a>
<input type="radio" name="command" id="unban" value="unban"><a onclick=sl('unban'); href="javascript:void(0);" >解封用户</a>
<input type="radio" name="command" id="del" value="del"><a onclick=sl('del'); href="javascript:void(0);" >删除用户</a>
<input type="radio" name="command" id="edit" value="edit"><a onclick=sl('edit'); href="javascript:void(0);" >修改用户资料</a><br>
<input type="submit" name="submit" value="提交">
</form>
EOT;
	
	return $urhtm;
}

?>

