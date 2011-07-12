<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 7){
	exit($_ERROR['no_power']);
}

if($command == 'edit') {
	$ednum = 0;
	$edfmt = Array(
		'areahour'=>'int',
		'areaadd'=>'int',
		'arealimit'=>'int',
		'areaesc'=>'int',
		'validlimit'=>'int',
		'combolimit'=>'int',
		'deathlimit'=>'int',
		'splimit'=>'int',
		'hplimit'=>'int',
		'sleep_time'=>'int',
		'heal_time'=>'int',
		'teamlimit'=>'int',
		'antiAFKertime'=>'int',
		'corpseprotect'=>'int',
		'coldtimeon'=>'int',
		'showcoldtimer'=>'int',
		'npcchaton'=>'int'
	);
	$edlist = Array();
	foreach($edfmt as $key => $val){
		if(isset($_POST[$key])){
			${'o_'.$key} = ${$key};
			if($val == 'int'){
				${$key} = intval($_POST[$key]);
			}else{
				${$key} = $_POST[$key];
			}
			if(${$key} != ${'o_'.$key}){
				$ednum ++;
				if(${$key}===''){
					echo "$lang[$key] 已清空<br>";
				}else{
					echo "$lang[$key] 修改为 ${$key} <br>";
				}
				$edlist[$key] = ${$key};
			}
		}
	}
	
	echo "提交的修改请求数量： $ednum <br>";
	
	if($ednum){
		$adminlog = '';
		$gamecfg_file = config('gamecfg',$gamecfg);
		$systemfile = file_get_contents($gamecfg_file);
		foreach($edlist as $key => $val){
			if($edfmt[$key] == 'int'){
				$systemfile = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $systemfile);
			}else{
				$systemfile = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $systemfile);
			}
			
			$adminlog .= setadminlog('gamecfgmng',$key,$val,$gamecfg);
		}
		file_put_contents($gamecfg_file,$systemfile);
		putadminlog($adminlog);
		echo '游戏参数已修改';
	}

}
foreach(Array('areaesc','coldtimeon','showcoldtimer','npcchaton') as $val){
	if(${$val}){
		${$val.'_input'} = "<input type=\"radio\" name=\"$val\" value=\"1\" checked>".$lang['on']."&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"$val\" value=\"0\">".$lang['off'];
	}else{
		${$val.'_input'} = "<input type=\"radio\" name=\"$val\" value=\"1\">".$lang['on']."&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"$val\" value=\"0\" checked>".$lang['off'];
	}
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
		<td bgcolor="#EEEEF6" width="30%"><?=$areaesc_input?></td>
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
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['antiAFKertime']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="antiAFKertime" value="<?=$antiAFKertime?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['antiAFKertime_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['corpseprotect']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="corpseprotect" value="<?=$corpseprotect?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['corpseprotect_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['coldtimeon']?></td>
		<td bgcolor="#EEEEF6" width="30%"><?=$coldtimeon_input?></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['coldtimeon_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['showcoldtimer']?></td>
		<td bgcolor="#EEEEF6" width="30%"><?=$showcoldtimer_input?></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['showcoldtimer_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['npcchaton']?></td>
		<td bgcolor="#EEEEF6" width="30%"><?=$npcchaton_input?></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['npcchaton_comment']?></td>
	</tr>
</table>
<input type="button" value="修改" onclick="javascript:document.gamecfgmng.command.value='edit';document.gamecfgmng.submit();">
<input type="button" value="返回" onclick="javascript:document.gamecfgmng.mode.value='';document.gamecfgmng.submit();">
</form>