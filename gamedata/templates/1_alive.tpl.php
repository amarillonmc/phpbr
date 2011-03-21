<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<center>
<span class="subtitle">幸存者一览</span>
<form method="post" name="backindex" onSubmit="return false;">
<input type="button" name="enter" value="显示前<?=$alivelimit?>名幸存者" onClick="showAlive('last');">
<input type="button" name="enter" value="显示全部幸存者" onClick="showAlive('all');">
</form>

<TABLE border="1"><tr align="center" class="b1"><td class="b1"><div class=nttx>名字&编号</div></td><td width="140" class="b1"><div class=nttx>头像</div></td><td class="b1"><div class=nttx>等级</div></td><td class="b1"><div class=nttx>杀害者数</div></td><td class="b1"><div class=nttx>队伍名</div></td><td width="300" class="b1"><div class=nttx>口头禅</div></td></tr>
<? if(is_array($alivedata)) { foreach($alivedata as $alive) { ?>
<tr class="b3"><td align="center" class="b3"><div class=nttx><?=$alive['name']?><br><?=$sexinfo[$alive['gd']]?> <?=$alive['sNo']?> 号</div></td><td align="center" class="b3"><div class=nttx><IMG src="img/<?=$alive['iconImg']?>" width="140" height="80" border="0" align="absmiddle"></div></td><td class="b3"><div class=nttx><?=$alive['lvl']?></div></td><td class="b3"><div class=nttx><?=$alive['killnum']?></div></td><td class="b3"><div class=nttx>
<? if($alive['teamID']) { ?>
<?=$alive['teamID']?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><div class=nttx><?=$alive['motto']?></div></td></tr>
<? } } ?>
</table><BR>
【生存者数：<?=$alivenum?>人】</table><BR><BR>

<br>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
</center>
<? include template('footer'); ?>
