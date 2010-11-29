<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
发现了物品 <span class="yellow"><?=$itm0?>  <span class="clan"> 【<?=$iteminfo[$itmk0]?>】</span> 效：<?=$itme0?> 耐：<?=$itms0?></span>。
<br>
你想如何处理？
<br>
<input type="hidden" name="mode" value="itemmain">
<input type="radio" name="command" id="itemget" value="itemget" checked><a onclick=sl("itemget"); href="javascript:void(0);" >拾取</a>
<br>
<input type="radio" name="command" id="itm0" value="dropitm0"><a onclick=sl("itm0"); href="javascript:void(0);" >丢弃</a>
<br>