<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 7){
	exit($_ERROR['no_power']);
}

include './gamedata/adminmsg.php' ;

if($command == 'edit') {
//	$adminmsg = setconfig($_POST['adminmsg']);
//	$startmode = (int)$_POST['startmode'];
//	$starthour = (int)$_POST['starthour'];
//	$startmin = (int)$_POST['startmin'];
//	$iplimit = (int)$_POST['iplimit'];
//	$newslimit = (int)$_POST['newslimit'];
//	$alivelimit = (int)$_POST['alivelimit'];
//	$winlimit = (int)$_POST['winlimit'];
//	$noiselimit = (int)$_POST['noiselimit'];
//	$chatlimit = (int)$_POST['chatlimit'];
//	$chatrefresh = (int)$_POST['chatrefresh'];
//	$chatinnews = (int)$_POST['chatinnews'];
	
	$ednum = 0;
	$edfmt = Array(
		'adminmsg'=>'',
		'startmode'=>'int',
		'starthour'=>'int',
		'startmin'=>'int',
		'iplimit'=>'int',
		'newslimit'=>'int',
		'alivelimit'=>'int',
		'winlimit'=>'int',
		'noiselimit'=>'int',
		'chatlimit'=>'int',
		'chatrefresh'=>'int',
		'chatinnews'=>'int'
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
		if(in_array('adminmsg',array_keys($edlist))){
			$adminmsgfile = file_get_contents('./gamedata/adminmsg.php');
			$nadminmsg = $edlist['adminmsg'];
			$adminmsgfile = preg_replace("/[$]adminmsg\s*\=\s*[\"'].*?[\"'];/is", "\$adminmsg = '$nadminmsg';", $adminmsgfile);
			file_put_contents('./gamedata/adminmsg.php',$adminmsgfile);
		}
		$adminlog = '';
		//$gamecfg_file = config('gamecfg',$gamecfg);
		$systemfile = file_get_contents('./gamedata/system.php');
		foreach($edlist as $key => $val){
			if($key != 'adminmsg'){
				if($edfmt[$key] == 'int'){
					$systemfile = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $systemfile);
				}else{
					$systemfile = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $systemfile);
				}
			}
			$adminlog .= setadminlog('systemcfgmng',$key,$val);
		}
		file_put_contents('./gamedata/system.php',$systemfile);
		putadminlog($adminlog);
		echo '游戏参数已修改';
	}
	
	
	
//	$antiAFKertime = (int)$_POST['antiAFKertime'];
//	$corpseprotect = (int)$_POST['corpseprotect'];
//	$coldtimeon = (int)$_POST['coldtimeon'];
//	$showcoldtimer = (int)$_POST['showcoldtimer'];
	
//	$adminmsgfile = file_get_contents('./gamedata/adminmsg.php');
//	$adminmsgfile = preg_replace("/[$]adminmsg\s*\=\s*[\"'].*?[\"'];/is", "\$adminmsg = '$adminmsg';", $adminmsgfile);
//	file_put_contents('./gamedata/adminmsg.php',$adminmsgfile);
	
//	$systemfile = file_get_contents('./gamedata/system.php');
//	$fp = fopen('./gamedata/system.php', 'r');
//	$systemfile = fread($fp, filesize('./gamedata/system.php'));
//	fclose($fp);

	//$systemfile = preg_replace("/[$]adminmsg\s*\=\s*[\"'].*?[\"'];/is", "\$adminmsg = '$adminmsg';", $systemfile);
//	$systemfile = preg_replace("/[$]startmode\s*\=\s*[0-9]+;/is", "\$startmode = $startmode;", $systemfile);
//	$systemfile = preg_replace("/[$]starthour\s*\=\s*[0-9]+;/is", "\$starthour = $starthour;", $systemfile);
//	$systemfile = preg_replace("/[$]startmin\s*\=\s*[0-9]+;/is", "\$startmin = $startmin;", $systemfile);
//	$systemfile = preg_replace("/[$]iplimit\s*\=\s*[0-9]+;/is", "\$iplimit = $iplimit;", $systemfile);
//	$systemfile = preg_replace("/[$]newslimit\s*\=\s*[0-9]+;/is", "\$newslimit = $newslimit;", $systemfile);
//	$systemfile = preg_replace("/[$]alivelimit\s*\=\s*[0-9]+;/is", "\$alivelimit = $alivelimit;", $systemfile);
//	$systemfile = preg_replace("/[$]winlimit\s*\=\s*[0-9]+;/is", "\$winlimit = $winlimit;", $systemfile);
//	$systemfile = preg_replace("/[$]noiselimit\s*\=\s*[0-9]+;/is", "\$noiselimit = $noiselimit;", $systemfile);
//	$systemfile = preg_replace("/[$]chatlimit\s*\=\s*[0-9]+;/is", "\$chatlimit = $chatlimit;", $systemfile);
//	$systemfile = preg_replace("/[$]chatrefresh\s*\=\s*[0-9]+;/is", "\$chatrefresh = $chatrefresh;", $systemfile);
//	$systemfile = preg_replace("/[$]chatinnews\s*\=\s*[0-9]+;/is", "\$chatinnews = $chatinnews;", $systemfile);
//	$systemfile = preg_replace("/[$]antiAFKertime\s*\=\s*[0-9]+;/is", "\$antiAFKertime = $antiAFKertime;", $systemfile);
//	$systemfile = preg_replace("/[$]corpseprotect\s*\=\s*[0-9]+;/is", "\$corpseprotect = $corpseprotect;", $systemfile);
//	$systemfile = preg_replace("/[$]coldtimeon\s*\=\s*[0-9]+;/is", "\$coldtimeon = $coldtimeon;", $systemfile);
//	$systemfile = preg_replace("/[$]showcoldtimer\s*\=\s*[0-9]+;/is", "\$showcoldtimer = $showcoldtimer;", $systemfile);

//	file_put_contents('./gamedata/system.php',$systemfile);
//	$fp = fopen('./gamedata/system.php', 'w');
//	fwrite($fp, trim($systemfile));
//	fclose($fp);

//	adminlog('systemmng');
//	echo '游戏参数修改完毕。';
}
$startmode_input = '';
for($i=0;$i<=3;$i++){
	if($i==$startmode){
		$startmode_input .= "<input type=\"radio\" name=\"startmode\" value=\"$i\" checked>".$lang['startmode_'.$i].'<br>';
	} else {
		$startmode_input .= "<input type=\"radio\" name=\"startmode\" value=\"$i\">".$lang['startmode_'.$i].'<br>';
	}
}

?>
<form method="post" name="systemmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="systemmng">
<input type="hidden" name="command" value="">

<table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
	<tr bgcolor="#3A4273">
		<td align="center" style="color: #FFFFFF"><?=$lang['variable']?></td>
		<td align="center" style="color: #FFFFFF"><?=$lang['value']?></td>
		<td align="center" style="color: #FFFFFF"><?=$lang['comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['adminmsg']?></td>
		<td bgcolor="#EEEEF6" width="30%"><textarea cols="30" rows="4" style="overflow:auto" name="adminmsg" value=""><?=$adminmsg?></textarea></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['adminmsg_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['startmode']?></td>
		<td bgcolor="#EEEEF6" width="30%"><?=$startmode_input?></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['startmode_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['starthour']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="starthour" value="<?=$starthour?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['starthour_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['startmin']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="startmin" value="<?=$startmin?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['startmin_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['iplimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="iplimit" value="<?=$iplimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['iplimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['newslimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="newslimit" value="<?=$newslimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['newslimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['alivelimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="alivelimit" value="<?=$alivelimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['alivelimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['winlimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="winlimit" value="<?=$winlimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['winlimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['noiselimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="noiselimit" value="<?=$noiselimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['noiselimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['chatlimit']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="chatlimit" value="<?=$chatlimit?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['chatlimit_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['chatrefresh']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="chatrefresh" value="<?=$chatrefresh?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['chatrefresh_comment']?></td>
	</tr>
	<tr style="color: #3A4273">
		<td bgcolor="#E3E3EA" width="20%">&nbsp;<?=$lang['chatinnews']?></td>
		<td bgcolor="#EEEEF6" width="30%"><input type="text" name="chatinnews" value="<?=$chatinnews?>" size="30"></td>
		<td bgcolor="#E3E3EA">&nbsp;<?=$lang['chatinnews_comment']?></td>
	</tr>
</table>
<input type="button" value="修改" onclick="javascript:document.systemmng.command.value='edit';document.systemmng.submit();">
<input type="button" value="返回" onclick="javascript:document.systemmng.mode.value='';document.systemmng.submit();">
</form>