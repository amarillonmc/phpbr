<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<TABLE border="0" cellspacing="0" cellpadding="0">
<TR height="20">
<TD class="b1"><span>排名</span></TD>
<TD class="b1"><span>UID</span></TD>
<TD class="b1"><span>姓名</span></TD>
<TD class="b1"><span>性别</span></TD>
<TD class="b1"><span>头像</span></TD>
<TD class="b1" style="maxwidth:120"><span>口头禅</span></TD>
<TD class="b1"><span>积分</span></TD>
<TD class="b1"><span>游戏场数</span></TD>
<TD class="b1"><span>获胜场数</span></TD>
<TD class="b1"><span>胜率</span></TD>
<TD class="b1"><span>最后游戏</span></TD>
</TR>
<? if(is_array($rankdata)) { foreach($rankdata as $urdata) { ?>
<TR height="20">
<TD class="b2"><span>
<? if($urdata['number']==1) { ?>
<a title="触手！"><span class="red">榜首</span></a>
<? } elseif($urdata['number']<=10) { ?>
<span class="yellow"><?=$urdata['number']?></span>
<? } else { ?>
<?=$urdata['number']?>
<? } ?>
</span></TD>
<TD class="b3"><span><?=$urdata['uid']?></span></TD>
<TD class="b3"><span><?=$urdata['username']?></span></TD>
<TD class="b3"><span>
<? if($urdata['gender']) { ?>
<?=$sexinfo[$urdata['gender']]?>
<? } else { ?>
<?=$sexinfo['0']?>
<? } ?>
</span></TD>
<TD class="b3"><span><IMG src="img/<?=$urdata['img']?>" width="70" height="40" border="0" align="absmiddle"></span></TD>
<TD class="b3"><span><?=$urdata['motto']?></span></TD>
<TD class="b3"><span><span class="yellow"><?=$urdata['credits']?></span></span></TD>
<TD class="b3"><span><?=$urdata['validgames']?></span></TD>
<TD class="b3"><span><?=$urdata['wingames']?></span></TD>
<TD class="b3"><span><?=$urdata['winrate']?></span></TD>
<TD class="b3"><span><?=$urdata['lastgame']?></span></TD>
</TR>
<? } } ?>
</TABLE>
