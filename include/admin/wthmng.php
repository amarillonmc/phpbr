<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 3){
	exit($_ERROR['no_power']);
}
global $wth,$now;

if(isset($wth)){
	echo '当前天气修改为：'.$wthdata[$wth]['name'];
	$weather = $wth;
	save_gameinfo();
	adminlog('wthchg',$wth);
	naddnews($now,'syswthchg',$wth);
}else{echo "当前天气：{$wthdata[$weather]['name']}<br />";}


$wthlog = '';
foreach($wthdata as $key => $value){
	$wthlog .= "<input type=\"radio\" name=\"wth\" id=\"$key\" value=\"$key\"><a onclick=sl('$key'); href=\"javascript:void(0);\" >{$value['name']}</a>&nbsp;";
}


echo <<<EOT
<form method="post" name="wthmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="hidden" name="command" value="wthmng">
$wthlog <br />
<input type="submit" name="submit" value="修改当前天气"></form>
EOT;
?>

