<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 7){
	exit($_ERROR['no_power']);
}


if($command == 'edit') {
	$areahour = (int)$_POST['areahour'];
	$areaadd = (int)$_POST['areaadd'];
	$arealimit = (int)$_POST['arealimit'];
	$areaesc = (int)$_POST['areaesc'];
	$validlimit = (int)$_POST['validlimit'];
	$combolimit = (int)$_POST['combolimit'];
	$deathlimit = (int)$_POST['deathlimit'];
	$splimit = (int)$_POST['splimit'];
	$hplimit = (int)$_POST['hplimit'];
	$sleep_time = (int)$_POST['sleep_time'];
	$heal_time = (int)$_POST['heal_time'];
	$teamlimit = (int)$_POST['teamlimit'];

	$gamecfg_file = config('gamecfg',$gamecfg);
	$fp = fopen($gamecfg_file, 'r');
	$systemfile = fread($fp, filesize($gamecfg_file));
	fclose($fp);


	$systemfile = preg_replace("/[$]areahour\s*\=\s*[0-9]+;/is", "\$areahour = $areahour;", $systemfile);
	$systemfile = preg_replace("/[$]areaadd\s*\=\s*[0-9]+;/is", "\$areaadd = $areaadd;", $systemfile);
	$systemfile = preg_replace("/[$]arealimit\s*\=\s*[0-9]+;/is", "\$arealimit = $arealimit;", $systemfile);
	$systemfile = preg_replace("/[$]areaesc\s*\=\s*[0-9]+;/is", "\$areaesc = $areaesc;", $systemfile);
	$systemfile = preg_replace("/[$]validlimit\s*\=\s*[0-9]+;/is", "\$validlimit = $validlimit;", $systemfile);
	$systemfile = preg_replace("/[$]combolimit\s*\=\s*[0-9]+;/is", "\$combolimit = $combolimit;", $systemfile);
	$systemfile = preg_replace("/[$]deathlimit\s*\=\s*[0-9]+;/is", "\$deathlimit = $deathlimit;", $systemfile);
	$systemfile = preg_replace("/[$]splimit\s*\=\s*[0-9]+;/is", "\$splimit = $splimit;", $systemfile);
	$systemfile = preg_replace("/[$]hplimit\s*\=\s*[0-9]+;/is", "\$hplimit = $hplimit;", $systemfile);
	$systemfile = preg_replace("/[$]sleep_time\s*\=\s*[0-9]+;/is", "\$sleep_time = $sleep_time;", $systemfile);
	$systemfile = preg_replace("/[$]heal_time\s*\=\s*[0-9]+;/is", "\$heal_time = $heal_time;", $systemfile);
	$systemfile = preg_replace("/[$]teamlimit\s*\=\s*[0-9]+;/is", "\$teamlimit = $teamlimit;", $systemfile);


	$fp = fopen($gamecfg_file, 'w');
	fwrite($fp, trim($systemfile));
	fclose($fp);

	adminlog('gamecfgmng',$gamecfg);
	echo '游戏数据修改完毕。';

}

?>
<form method="post" name="gamecfgmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamecfgmng">
<input type="hidden" name="command" value="">
<table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
	<tr bgcolor="#3A4273">
		<td align="center" style="color: #FFFFFF"><?=$lang['variable']?></td>
		<td align="center" style="color: #FFFFFF"><?=$lang['value']?></td>
		<td align="center" style="color: #FFFFFF"><?=$lang['comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['areahour']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="areahour" value="<?=$areahour?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['areahour_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['areaadd']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="areaadd" value="<?=$areaadd?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['areaadd_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['arealimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="arealimit" value="<?=$arealimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['arealimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['areaesc']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="areaesc" value="<?=$areaesc?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['areaesc_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['validlimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="validlimit" value="<?=$validlimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['validlimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['combolimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="combolimit" value="<?=$combolimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['combolimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['deathlimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="deathlimit" value="<?=$deathlimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['deathlimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['splimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="splimit" value="<?=$splimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['splimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['hplimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="hplimit" value="<?=$hplimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['hplimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['sleep_time']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="sleep_time" value="<?=$sleep_time?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['sleep_time_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['heal_time']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="heal_time" value="<?=$heal_time?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['heal_time_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['teamlimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="teamlimit" value="<?=$teamlimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['teamlimit_comment']?></td>
	</tr>
</table>
<input type="button" value="修改" onclick="javascript:document.gamecfgmng.command.value='edit';document.gamecfgmng.submit();">
<input type="button" value="返回" onclick="javascript:document.gamecfgmng.mode.value='';document.gamecfgmng.submit();">
</form>