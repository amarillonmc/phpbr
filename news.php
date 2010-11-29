<?php

define('CURSCRIPT', 'news');

require_once './include/common.inc.php';
require_once GAME_ROOT.'./include/JSON.php';
require_once  GAME_ROOT.'./include/news.func.php';

$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
$newshtm = GAME_ROOT.TPLDIR.'/newsinfo.htm';
$lnewshtm = GAME_ROOT.TPLDIR.'/lastnews.htm';

if(filemtime($newsfile) > filemtime($lnewshtm)) {
	$lnewsinfo = parse_news(1,$newslimit);
	writeover($lnewshtm,$lnewsinfo);
}

if($newsmode == 'last') {
	include_once template('lastnews');
	$newsdata = ob_get_contents();
	ob_clean();
	$json = new Services_JSON();
	$jgamedata = $json->encode($newsdata);
	echo $jgamedata;
	ob_end_flush();
} elseif($newsmode == 'all') {
	if(filemtime($newsfile) > filemtime($newshtm)) {
		$newsinfo = parse_news();
		writeover($newshtm,$newsinfo);
	}
	include_once template('newsinfo');
	$newsdata = ob_get_contents();
	ob_clean();
	$json = new Services_JSON();
	$jgamedata = $json->encode($newsdata);
	echo $jgamedata;
	ob_end_flush();
} elseif($newsmode == 'chat') {
	$newsdata = getchat(0,'',$chatinnews);
	ob_clean();
	$json = new Services_JSON();
	$jgamedata = $json->encode($newsdata);
	echo $jgamedata;
	ob_end_flush();
} else {
	include_once template('news');
}


?>	
