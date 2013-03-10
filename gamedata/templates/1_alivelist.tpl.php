<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<TABLE border="1">
<tr align="center" class="b1">
<td class="b1"><span>名字&编号</span></td>
<td width="140" class="b1"><span>头像</span></td>
<td class="b1"><span>等级</span></td>
<td class="b1"><span>杀害者数</span></td>
<?php if($gamestate < 40 ) { ?>
<td class="b1"><span>队伍名</span></td>
<?php } ?>
<td width="300" class="b1"><span>口头禅</span></td>
</tr>
<?php if(is_array($alivedata)) { foreach($alivedata as $alive) { ?>
<tr class="b3">
<td align="center" class="b3"><span><?php echo $alive['name']?><br><?php echo $sexinfo[$alive['gd']]?> <?php echo $alive['sNo']?> 号</span></td>
<td align="center" class="b3"><span><IMG src="img/<?php echo $alive['iconImg']?>" width="140" height="80" border="0" align="absmiddle"></span></td>
<td class="b3"><span><?php echo $alive['lvl']?></span></td>
<td class="b3"><span><?php echo $alive['killnum']?></span></td>
<?php if($gamestate < 40 ) { ?>
<td class="b3"><span>
<?php if($alive['teamID']) { ?>
<?php echo $alive['teamID']?>
<?php } else { ?>
无
<?php } ?>
</span></td>
<?php } ?>
<td class="b3"><span><?php echo $alive['motto']?></span></td>
</tr>
<?php } } ?>
</table><BR>
【生存者数：<?php echo $alivenum?>人】