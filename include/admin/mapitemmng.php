<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 9){
	exit($_ERROR['no_power']);
}

global $db,$tablepre;
for($i=0;$i<30;$i++){
	$result = $db->query("SELECT iid FROM {$tablepre}{$i}mapitem ORDER BY iid DESC LIMIT 1");
	$num=$db->fetch_array($result);
	echo $num['iid'].'<br>';
}

?>