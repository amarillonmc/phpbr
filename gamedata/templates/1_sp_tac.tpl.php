<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
想采取什么应战策略？<br>

<input type="hidden" name="mode" value="special">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<? if(is_array($tacinfo)) { foreach($tacinfo as $key => $value) { if($value) { ?>
<input type="radio" name="command" id="tac<?=$key?>" value="tac<?=$key?>"><a onclick=sl('tac<?=$key?>'); href="javascript:void(0);" ><?=$value?> </a><br>
<? } } } ?>
 

