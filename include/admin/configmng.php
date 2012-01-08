<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 7){
	exit($_ERROR['no_power']);
}


if($command == 'edit') {

	$authkey = setconfig($_POST['authkey']);
	$bbsurl = setconfig($_POST['bbsurl']);
	$gameurl = setconfig($_POST['gameurl']);
	$moveut = (int)$_POST['moveut'];
	$moveutmin = (int)$_POST['moveutmin'];

	$fp = fopen('./config.inc.php', 'r');
	$configfile = fread($fp, filesize('./config.inc.php'));
	fclose($fp);

	$configfile = preg_replace("/[$]authkey\s*\=\s*[\"'].*?[\"'];/is", "\$authkey = '$authkey';", $configfile);
	$configfile = preg_replace("/[$]bbsurl\s*\=\s*[\"'].*?[\"'];/is", "\$bbsurl = '$bbsurl';", $configfile);
	$configfile = preg_replace("/[$]gameurl\s*\=\s*[\"'].*?[\"'];/is", "\$gameurl = '$gameurl';", $configfile);
	$configfile = preg_replace("/[$]moveut\s*\=\s*-?[0-9]+;/is", "\$moveut = $moveut;", $configfile);
	$configfile = preg_replace("/[$]moveutmin\s*\=\s*-?[0-9]+;/is", "\$moveutmin = $moveutmin;", $configfile);

	$fp = fopen('./config.inc.php', 'w');
	fwrite($fp, trim($configfile));
	fclose($fp);

	adminlog('configmng');
	echo '系统参数修改完毕。';
}
$sysnow = time();
list($nowsec,$nowmin,$nowhour,$nowday,$nowmonth,$nowyear,$nowwday,$nowyday,$nowisdst) = localtime($sysnow);
$nowmonth++;
$nowyear += 1900;


?>
<form method="post" name="configmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="configmng">
<input type="hidden" name="command" value="">

<table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
	<tr bgcolor="#3A4273">
		<td align="center" style="color: #FFFFFF"><?=$lang['variable']?></td>
		<td align="center" style="color: #FFFFFF"><?=$lang['value']?></td>
		<td align="center" style="color: #FFFFFF"><?=$lang['comment']?></td>
	</tr>
	<tr style="color: #3A4273">
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['moveut']?></td>
	  <td bgcolor="#EEEEF6" align="center"><input type="text" name="moveut" value="<?=$moveut?>" size="5">小时<input type="text" name="moveutmin" value="<?=$moveutmin?>" size="5">分钟</td>
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['moveut_comment']?><br><?=$nowyear?><?=$lang['year']?><?=$nowmonth?><?=$lang['month']?><?=$nowday?><?=$lang['day']?><?=$nowhour?><?=$lang['hour']?><?=$nowmin?><?=$lang['min']?></td>
	</tr>
	<!--
	<tr  style="color: #3A4273">
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['authkey']?></td>
	  <td bgcolor="#EEEEF6" align="center"><input type="text" name="authkey" value="<?=$authkey?>" size="30"></td>
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['authkey_comment']?></td>
	</tr>
	-->
	<input type="hidden" name="authkey" value="<?=$authkey?>"> 
	<tr  style="color: #3A4273">
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['bbsurl']?></td>
	  <td bgcolor="#EEEEF6" align="center"><input type="text" name="bbsurl" value="<?=$bbsurl?>" size="30"></td>
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['bbsurl_comment']?></td>
	</tr>
	<tr  style="color: #3A4273">
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['gameurl']?></td>
	  <td bgcolor="#EEEEF6" align="center"><input type="text" name="gameurl" value="<?=$gameurl?>" size="30"></td>
	  <td bgcolor="#E3E3EA">&nbsp;<?=$lang['gameurl_comment']?></td>
	</tr>
</table>
<input type="button" value="修改" onclick="javascript:document.configmng.command.value='edit';document.configmng.submit();">
<input type="button" value="返回" onclick="javascript:document.configmng.mode.value='';document.configmng.submit();">
</form>