<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 9){
	exit($_ERROR['no_power']);
}
$result = $db->query("SELECT uid,username,groupid FROM {$tablepre}users WHERE groupid > 1");
while($gm = $db->fetch_array($result)) {
	$gmdata[] = $gm;
}

if($command == 'add') {
	for($i=2;$i<=$mygroup;$i++) {
		if($i==2){
			$grouplist .= "[<input type=\"radio\" name=\"addgroup\" value=\"$i\" checked>$i]";
		} else {
			$grouplist .= "[<input type=\"radio\" name=\"addgroup\" value=\"$i\">$i]";
		}
	}
echo <<<EOT
<form method="post" name="gmlist" onsubmit="admin.php">
<input type="hidden" name="mode" value="gmlist">
<input type="hidden" name="command" value="add2">
GM账号(必须为已存在账号)：<input size="15" type="text" name="addname" maxlength="15"><br>
GM权限：$grouplist<br>
<input type="submit" name="submit" value="提交">
</form>
EOT;
} elseif($command == 'add2') {
	if(!$addname) {
		echo '必须填写GM账号';
	} elseif ((int)$addgroup < 2 || (int)$addgruop > $mygroup) {
		echo '权限设置错误！';
	} else {
		$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$addname' AND groupid<=1");
		if(!$db->num_rows($result)) { 
			echo '此账号不存在或此账号已经是管理员。'; 
		} else {
			$db->query("UPDATE {$tablepre}users SET groupid='$addgroup' WHERE username='$addname'");
			adminlog('addgm',$addname,$addgroup);
			echo "管理员 {$addname} 添加成功，权限等级：{$addgroup}<br>";
		}
	}
} elseif($command == 'del') {
	foreach($gmdata as $n => $gm) {
		if(${'admin_'.$n} === $gm['uid']) {
			if($gm['groupid'] >= $mygroup){
				echo "权限不够，不能删除管理员 {$gm['username']}！<br>";
			} else {
				$db->query("UPDATE {$tablepre}users SET groupid=1 WHERE uid='{$gm['uid']}'");
				adminlog('delgm',$gm['username']);
				echo "管理员 {$gm['username']} 的管理权限被删除！<br>";
			}
		}
	}
} elseif($command == 'edit') {
	foreach($gmdata as $n => $gm) {
		if(${'admin_'.$n} === $gm['uid']) {
			for($i=2;$i<=$mygroup;$i++) {
				if($i==$gm['groupid']){
					$grouplist .= "[<input type=\"radio\" name=\"editgroup\" value=\"$i\" checked>$i]";
				} else {
					$grouplist .= "[<input type=\"radio\" name=\"editgroup\" value=\"$i\">$i]";
				}
			}
echo <<<EOT
<form method="post" name="gmlist" onsubmit="admin.php">
<input type="hidden" name="mode" value="gmlist">
<input type="hidden" name="command" value="edit2">
<input type="hidden" name="editid" value="{$gm['uid']}">
<input type="hidden" name="editgid" value="{$gm['groupid']}">
<input type="hidden" name="editname" value="{$gm['username']}">
GM账号：{$gm['username']}<br>
GM权限：$grouplist<br>
<input type="submit" name="submit" value="提交">
</form>
EOT;
		break;
		}
	}
} elseif($command == 'edit2') {
	$editgroup = (int)$editgroup;
	if ( $editgroup < 2 || $editgroup >= $mygroup) {
		echo '权限设置错误！<br>';
	} elseif(($editgid >= $mygroup)&&($editname!=$cuser)) {
		echo "权限不够，不能编辑管理员 {$editname} ！<br>";
	} else {
		$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$editname'");
		if(!$db->num_rows($result)) { 
			echo '此账号不存在。<br>'; 
		} else {
			$db->query("UPDATE {$tablepre}users SET groupid='$editgroup' WHERE username='$editname'");
			adminlog('editgm',$editname,$editgroup);
			echo "管理员 {$editname} 权限修改成功，权限等级：{$editgroup}<br>";
		}
	}
} else {
	foreach($gmdata as $n => $gm) {
	$gmlisthtm .= "<tr><td><input type=\"checkbox\" name=\"admin_$n\" value=\"{$gm['uid']}\"></td><td>{$gm['username']}</td><td>{$gm['groupid']}</td></tr>";
}

echo <<<EOT
<form method="post" name="gmlist" onsubmit="admin.php">
<table class="admin"><tr><td>选</td><td>账号</td><td>权限等级</td></tr>
$gmlisthtm
</table>
<input type="hidden" name="mode" value="gmlist">
<input type="radio" name="command" id="add" value="add"><a onclick=sl('add'); href="javascript:void(0);" >增加GM</a>
<input type="radio" name="command" id="del" value="del"><a onclick=sl('del'); href="javascript:void(0);" >删除GM</a>
<input type="radio" name="command" id="edit" value="edit"><a onclick=sl('edit'); href="javascript:void(0);" >编辑GM</a><br>
<input type="submit" name="submit" value="提交">
EOT;
}



?>