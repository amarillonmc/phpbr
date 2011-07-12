<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 7){
	exit($_ERROR['no_power']);
}


if($command == 'edit') {

	$ednum = 0;
	$edfmt = Array('authkey'=>'','bbsurl'=>'','gameurl'=>'','moveut'=>'int','moveutmin'=>'int');
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
		$configfile = file_get_contents('./config.inc.php');
		foreach($edlist as $key => $val){
			if($edfmt[$key] == 'int'){
				$configfile = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $configfile);
			}else{
				$configfile = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $configfile);
			}
			
			$adminlog .= setadminlog('configmng',$key,$val);
		}
		file_put_contents('./config.inc.php',$configfile);
		putadminlog($adminlog);
		//adminlog('configmng');
		echo '系统参数已修改';
	}

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
	  <td bgcolor="#EEEEF6" align="center"><input type="text" name="moveut" value="<?=$moveut?>" size="5"><?=$lang['moveuthours']?><input type="text" name="moveutmin" value="<?=$moveutmin?>" size="5"><?=$lang['moveutmins']?></td>
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