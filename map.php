<?php

define('CURSCRIPT', 'map');

require_once './include/common.inc.php';

for($i=0;$i<=count($plsinfo);$i++){
	if($hack || array_search($i,$arealist) > ($areanum + $areaadd)){
		$plscolor[$i] = 'lime';
	} elseif(array_search($i,$arealist) <= $areanum) {
		$plscolor[$i] = 'red';
	} else {
		$plscolor[$i] = 'yellow';
	}
}



include template('map');

?>

