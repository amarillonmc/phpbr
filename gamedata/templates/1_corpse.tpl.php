<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
想要从尸体上拾取什么？<br><br>
<input type="hidden" name="mode" value="corpse">
<input type="hidden" name="wid" value="<?=$w_pid?>">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<? if($w_weps && $w_wepe) { ?>
<input type="radio" name="command" id="wep" value="wep"><a onclick=sl('wep'); href="javascript:void(0);" ><?=$w_wep?>/<?=$w_wepe?>/<?=$w_weps?> </a><br>
<? } if($w_arbs && $w_arbe) { ?>
<input type="radio" name="command" id="arb" value="arb"><a onclick=sl('arb'); href="javascript:void(0);" ><?=$w_arb?>/<?=$w_arbe?>/<?=$w_arbs?> </a><br>
<? } if($w_arhs) { ?>
<input type="radio" name="command" id="arh" value="arh"><a onclick=sl('arh'); href="javascript:void(0);" ><?=$w_arh?>/<?=$w_arhe?>/<?=$w_arhs?> </a><br>
<? } if($w_aras) { ?>
<input type="radio" name="command" id="ara" value="ara"><a onclick=sl('ara'); href="javascript:void(0);" ><?=$w_ara?>/<?=$w_arae?>/<?=$w_aras?> </a><br>
<? } if($w_arfs) { ?>
<input type="radio" name="command" id="arf" value="arf"><a onclick=sl('arf'); href="javascript:void(0);" ><?=$w_arf?>/<?=$w_arfe?>/<?=$w_arfs?> </a><br>
<? } if($w_arts) { ?>
<input type="radio" name="command" id="art" value="art"><a onclick=sl('art'); href="javascript:void(0);" ><?=$w_art?>/<?=$w_arte?>/<?=$w_arts?> </a><br>
<? } if($w_itms0) { ?>
<input type="radio" name="command" id="itm0" value="itm0"><a onclick=sl('itm0'); href="javascript:void(0);" ><?=$w_itm0?>/<?=$w_itme0?>/<?=$w_itms0?> </a><br>
<? } if($w_itms1) { ?>
<input type="radio" name="command" id="itm1" value="itm1"><a onclick=sl('itm1'); href="javascript:void(0);" ><?=$w_itm1?>/<?=$w_itme1?>/<?=$w_itms1?> </a><br>
<? } if($w_itms2) { ?>
<input type="radio" name="command" id="itm2" value="itm2"><a onclick=sl('itm2'); href="javascript:void(0);" ><?=$w_itm2?>/<?=$w_itme2?>/<?=$w_itms2?> </a><br>
<? } if($w_itms3) { ?>
<input type="radio" name="command" id="itm3" value="itm3"><a onclick=sl('itm3'); href="javascript:void(0);" ><?=$w_itm3?>/<?=$w_itme3?>/<?=$w_itms3?> </a><br>
<? } if($w_itms4) { ?>
<input type="radio" name="command" id="itm4" value="itm4"><a onclick=sl('itm4'); href="javascript:void(0);" ><?=$w_itm4?>/<?=$w_itme4?>/<?=$w_itms4?> </a><br>
<? } if($w_itms5) { ?>
<input type="radio" name="command" id="itm5" value="itm5"><a onclick=sl('itm5'); href="javascript:void(0);" ><?=$w_itm5?>/<?=$w_itme5?>/<?=$w_itms5?> </a><br>
<? } if($w_money) { ?>
<input type="radio" name="command" id="money" value="money"><a onclick=sl('money'); href="javascript:void(0);" ><?=$w_money?> 元 </a><br>
<? } ?>
