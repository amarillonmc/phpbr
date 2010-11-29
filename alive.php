<?php

define('CURSCRIPT', 'alive');

require_once './include/common.inc.php';

extract(gaddslashes($_GET));
if($alivemode == 'all') {
	$query = $db->query("SELECT name,gd,sNo,icon,lvl,exp,killnum,teamID FROM {$tablepre}players WHERE type=0 AND hp>0 order by killnum desc, lvl desc");
} else {
	$query = $db->query("SELECT name,gd,sNo,icon,lvl,exp,killnum,teamID FROM {$tablepre}players WHERE type=0 AND hp>0 order by killnum desc, lvl desc limit $alivelimit");
}
while($playerdata = $db->fetch_array($query)) {
	$playerdata['iconImg'] = "{$playerdata['gd']}_{$playerdata['icon']}.gif";
	$result = $db->query("SELECT motto FROM {$tablepre}users WHERE username = '".$playerdata['name']."'");
	$playerdata['motto'] = $db->result($result, 0);
	$alivedata[] = $playerdata;
}


include template('alive');



?>