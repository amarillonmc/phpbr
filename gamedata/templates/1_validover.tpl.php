<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" >入场表格填写完成</div>
<p><img border="0" src="img/story_0.gif" style="align:center"></p>
<table border="0" cellspacing="0" align="center">
  <tbody>
<tr>
<td class="b1"><DIV class=nttx>姓名</div></td>
<td class="b3"><DIV class=nttx><?=$cuser?></div></td>
<td rowspan="3" colspan="2" class="b3"><DIV class=nttx><img src="./img/<?=$gd?>_<?=$icon?>.gif" border="0" /></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>学号</div></td>
<td class="b3"><DIV class=nttx><?=$sexinfo[$gd]?><?=$sNo?>号</div></td>

</tr>
<tr>
<td class="b1"><DIV class=nttx>内定称号</div></td>
<td class="b3"><DIV class=nttx><?=$clubinfo[$club]?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>生命</div></td>
<td class="b3"><DIV class=nttx><?=$hp?> / <?=$mhp?></div></td>
<td class="b1"><DIV class=nttx>体力</div></td>
<td class="b3"><DIV class=nttx><?=$sp?> / <?=$msp?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>攻击力</div></td>
<td class="b3"><DIV class=nttx><?=$att?></div></td>
<td class="b1"><DIV class=nttx>防御力</div></td>
<td class="b3"><DIV class=nttx><?=$def?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>武器</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$wep?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>随机道具1</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$itm['3']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>随机道具2</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$itm['4']?></div></td>
</tr>
  </tbody>
</table>
<p align="center">“<?=$cuser?>，对吧？正在为您创建虚拟身份……<br>
“创建完成！您可以凭这个身份参加我们的特别活动了。
<br>
“会场入口就在前面。动漫祭的开幕仪式就要开始了，请您尽快入场。”<br><br>

<form method="post"  action="valid.php" style="margin: 0px">
<input type="hidden" name="mode" value="notice">
<input type="submit" name="enter" value="进入会场">
</form>
</p>
<? include template('footer'); ?>
