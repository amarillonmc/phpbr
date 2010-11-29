<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
你想卸下什么？<br><br>
<input type="hidden" name="mode" value="itemmain">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<? if($weps && $wepe) { ?>
<input type="radio" name="command" id="wep" value="offwep"><a onclick=sl('wep'); href="javascript:void(0);" ><?=$wep?>/<?=$wepe?>/<?=$weps?> </a><br>
<? } if($arbs && $arbe) { ?>
<input type="radio" name="command" id="arb" value="offarb"><a onclick=sl('arb'); href="javascript:void(0);" ><?=$arb?>/<?=$arbe?>/<?=$arbs?> </a><br>
<? } if($arhs) { ?>
<input type="radio" name="command" id="arh" value="offarh"><a onclick=sl('arh'); href="javascript:void(0);" ><?=$arh?>/<?=$arhe?>/<?=$arhs?> </a><br>
<? } if($aras) { ?>
<input type="radio" name="command" id="ara" value="offara"><a onclick=sl('ara'); href="javascript:void(0);" ><?=$ara?>/<?=$arae?>/<?=$aras?> </a><br>
<? } if($arfs) { ?>
<input type="radio" name="command" id="arf" value="offarf"><a onclick=sl('arf'); href="javascript:void(0);" ><?=$arf?>/<?=$arfe?>/<?=$arfs?> </a><br>
<? } if($arts) { ?>
<input type="radio" name="command" id="art" value="offart"><a onclick=sl('art'); href="javascript:void(0);" ><?=$art?>/<?=$arte?>/<?=$arts?> </a><br>
<? } ?>
