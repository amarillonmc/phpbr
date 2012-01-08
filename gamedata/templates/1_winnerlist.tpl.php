<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<br>

<form method="post" name="info" onsubmit="winner.php">
<input type="hidden" name="command" value="info">
<input type="button" value="查看选择角色的详细信息" onclick="javascript:document.info.command.value='info';document.info.submit();">
<input type="button" value="查看选择回数的进行状况" onclick="javascript:document.info.command.value='news';document.info.submit();">
<center>
<TABLE border="1" cellspacing="0" cellpadding="0">
<TR height="20"><TD class="b1"><DIV class=nttx>&nbsp;</div></TD><TD class="b1"><DIV class=nttx>回</div></TD><TD class="b1"><DIV class=nttx>优胜者名</div></TD><TD class="b1"><DIV class=nttx>胜利方式</div></TD><TD class="b1"><DIV class=nttx>游戏结束时间</div></TD><TD class="b1"><DIV class=nttx>胜利者留言</div></TD><TD class="b1"><DIV class=nttx>使用武器</div></TD></TR>
<? if(is_array($winfo)) { foreach($winfo as $info) { if($info['wmode'] && $info['wmode'] != 4) { ?>
<TR height="20">
<TD class="b2"><DIV class=nttx><INPUT type="radio" name="gnum" value="<?=$info['gid']?>" ></div></TD>
<TD class="b2"><DIV class=nttx><?=$info['gid']?></div></TD>
<TD class="b3" style="white-space: nowrap;"><DIV class=nttx>
<? if($info['name']) { ?>
<b><?=$info['name']?></b>
<? } else { ?>
无
<? } ?>
</div></TD>
<TD class="b3"><DIV class=nttx><?=$gwin[$info['wmode']]?></div></TD>
<TD class="b3"><DIV class=nttx><?=$info['date']?></div></TD>
<TD class="b3"><DIV class=nttx>
<? if($info['motto']) { ?>
<?=$info['motto']?>
<? } else { ?>
无
<? } ?>
</div></TD>
<TD class="b3"><DIV class=nttx>
<? if($info['wep']) { ?>
<?=$info['wep']?>
<? } else { ?>
无
<? } ?>
</div></TD>
</TR>
<? } else { ?>
<TR height="20">
<TD class="b2"></TD>
<TD class="b2"><DIV class=nttx><?=$info['gid']?></div></TD>
<TD class="b3" style="white-space: nowrap;"><DIV class=nttx>无</div></TD>
<TD class="b3"><DIV class=nttx><?=$gwin[$info['wmode']]?></div></TD>
<TD class="b3"><DIV class=nttx><?=$info['date']?></div></TD>
<TD class="b3"><DIV class=nttx>无</div></TD>
<TD class="b3"><DIV class=nttx>无</div></TD>
</TR>
<? } } } ?>
</TABLE>
</center>
<input type="button" value="查看选择角色的详细信息" onclick="javascript:document.info.command.value='info';document.info.submit();">
<input type="button" value="查看选择回数的进行状况" onclick="javascript:document.info.command.value='news';document.info.submit();">
</form>

<form method="post" name="list" action="winner.php">
<input type="hidden" name="command" value="list">
<input type="hidden" name="start" value="<?=$gamenum?>">
<input style='width: 120px;' type="button" value="最近 <?=$winlimit?> 回" onClick="document['list'].submit();">


<br>
<?=$listinfo?>
</form>
