<? if(!defined('IN_GAME')) exit('Access Denied'); ?>

<table align="center">
<tr>
<td>游戏积分</td>
<td><span class="yellow"><?=$credits?></span></td>
</tr>
<tr>
<td>游戏次数</td>
<td><span class="yellow"><?=$validtimes?></span></td>
</tr>
<!--	<tr>
<td>击杀数</td>
<td><span class="yellow"><?=$killnum?></span></td>
</tr>-->
<? if($ustate == 'valid') { ?>
<tr>
<td>选择游戏称号</td>
<td>
<input type="radio" name="gamehonour" id="nohonour" value="" checked><a onclick=sl('nohonour'); href="javascript:void(0);" >无称号</a><br />
<? if(!empty($select_honour)) { ?>
<br />
<? if(is_array($select_honour)) { foreach($select_honour as $hstr => $hinfo) { ?>
<input type="radio" name="gamehonour" id="<?=$hstr?>" value="<?=$hstr?>"><a onclick=sl('<?=$hstr?>'); href="javascript:void(0);" ><?=$hinfo?></a><br />
<? } } } ?>
</td>
</tr>
<? } else { ?>
<tr>
<td>称号</td>
<td><?=$honourwords?></td>
</tr>
<? } ?>
</table> 