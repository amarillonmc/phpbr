<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
你想要合成什么？<br>

<input type="hidden" name="mode" value="itemmain">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<input type="radio" name="command" id="itemmix" value="itemmix"><a onclick=sl('itemmix'); href="javascript:void(0);">合成</a>
<br>
<select name="mix1" onclick=sl('itemmix'); href="javascript:void(0);">
<option value="0">■ 道具一 ■<br />
<? if($pdata['itms1']) { ?>
<option value="1"><?=$pdata['itm1']?>/<?=$pdata['itme1']?>/<?=$pdata['itms1']?><br />
<? } if($pdata['itms2']) { ?>
<option value="2"><?=$pdata['itm2']?>/<?=$pdata['itme2']?>/<?=$pdata['itms2']?><br />
<? } if($pdata['itms3']) { ?>
<option value="3"><?=$pdata['itm3']?>/<?=$pdata['itme3']?>/<?=$pdata['itms3']?><br />
<? } if($pdata['itms4']) { ?>
<option value="4"><?=$pdata['itm4']?>/<?=$pdata['itme4']?>/<?=$pdata['itms4']?><br />
<? } if($pdata['itms5']) { ?>
<option value="5"><?=$pdata['itm5']?>/<?=$pdata['itme5']?>/<?=$pdata['itms5']?><br />
<? } if($pdata['itms6']) { ?>
<option value="6"><?=$pdata['itm6']?>/<?=$pdata['itme6']?>/<?=$pdata['itms6']?><br />
<? } ?>
</select>
<br>
<br>
<select name="mix2" onclick=sl('itemmix'); href="javascript:void(0);">
<option value="0">■ 道具二 ■<br />
<? if($pdata['itms1']) { ?>
<option value="1"><?=$pdata['itm1']?>/<?=$pdata['itme1']?>/<?=$pdata['itms1']?><br />
<? } if($pdata['itms2']) { ?>
<option value="2"><?=$pdata['itm2']?>/<?=$pdata['itme2']?>/<?=$pdata['itms2']?><br />
<? } if($pdata['itms3']) { ?>
<option value="3"><?=$pdata['itm3']?>/<?=$pdata['itme3']?>/<?=$pdata['itms3']?><br />
<? } if($pdata['itms4']) { ?>
<option value="4"><?=$pdata['itm4']?>/<?=$pdata['itme4']?>/<?=$pdata['itms4']?><br />
<? } if($pdata['itms5']) { ?>
<option value="5"><?=$pdata['itm5']?>/<?=$pdata['itme5']?>/<?=$pdata['itms5']?><br />
<? } if($pdata['itms6']) { ?>
<option value="6"><?=$pdata['itm6']?>/<?=$pdata['itme6']?>/<?=$pdata['itms6']?><br />
<? } ?>
</select>
<br>
<br>
<select name="mix3" onclick=sl('itemmix'); href="javascript:void(0);">
<option value="0">■ 道具三 ■<br />
<? if($pdata['itms1']) { ?>
<option value="1"><?=$pdata['itm1']?>/<?=$pdata['itme1']?>/<?=$pdata['itms1']?><br />
<? } if($pdata['itms2']) { ?>
<option value="2"><?=$pdata['itm2']?>/<?=$pdata['itme2']?>/<?=$pdata['itms2']?><br />
<? } if($pdata['itms3']) { ?>
<option value="3"><?=$pdata['itm3']?>/<?=$pdata['itme3']?>/<?=$pdata['itms3']?><br />
<? } if($pdata['itms4']) { ?>
<option value="4"><?=$pdata['itm4']?>/<?=$pdata['itme4']?>/<?=$pdata['itms4']?><br />
<? } if($pdata['itms5']) { ?>
<option value="5"><?=$pdata['itm5']?>/<?=$pdata['itme5']?>/<?=$pdata['itms5']?><br />
<? } if($pdata['itms6']) { ?>
<option value="6"><?=$pdata['itm6']?>/<?=$pdata['itme6']?>/<?=$pdata['itms6']?><br />
<? } ?>
</select>
