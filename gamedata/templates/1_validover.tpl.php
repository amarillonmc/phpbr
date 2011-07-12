<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" >入场表格填写完成</div>
<!--<p><img border="0" src="img/story_0.jpg" style="align:center"></p>-->
<table border="0" cellspacing="0" align="center">
  <tbody>
<tr>
<td class="b1"><DIV class=nttx>姓名</div></td>
<td class="b3"><DIV class=nttx><?=$cuser?></div></td>
<td rowspan="3" colspan="2" class="b3"><DIV class=nttx><img src="./img/<?=$pl['gd']?>_<?=$pl['icon']?>.gif" border="0" /></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>学号</div></td>
<td class="b3"><DIV class=nttx><?=$sexinfo[$pl['gd']]?><?=$pl['sNo']?>号</div></td>

</tr>
<tr>
<td class="b1"><DIV class=nttx>内定称号</div></td>
<td class="b3"><DIV class=nttx><?=$clubinfo[$pl['club']]?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>生命</div></td>
<td class="b3"><DIV class=nttx><?=$pl['hp']?> / <?=$pl['mhp']?></div></td>
<td class="b1"><DIV class=nttx>体力</div></td>
<td class="b3"><DIV class=nttx><?=$pl['sp']?> / <?=$pl['msp']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>攻击力</div></td>
<td class="b3"><DIV class=nttx><?=$pl['att']?></div></td>
<td class="b1"><DIV class=nttx>防御力</div></td>
<td class="b3"><DIV class=nttx><?=$pl['def']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>武器</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$pl['wep']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>随机道具1</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$pl['itm3']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>随机道具2</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$pl['itm4']?></div></td>
</tr>
<? if($companysystem) { ?>
<tr></tr>
<tr>
<td class="b1"><DIV class=nttx>同伴</div></td>
<td class="b3"><DIV class=nttx><?=$cp['name']?></div></td>
<td rowspan="3" colspan="2" class="b3"><DIV class=nttx><img src="./img/n_<?=$cp['icon']?>.gif" border="0" /></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>学号</div></td>
<td class="b3"><DIV class=nttx><?=$sexinfo[$cp['gd']]?></div></td>

</tr>
<tr>
<td class="b1"><DIV class=nttx>内定称号</div></td>
<td class="b3"><DIV class=nttx><?=$clubinfo[$cp['club']]?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>生命</div></td>
<td class="b3"><DIV class=nttx><?=$cp['hp']?> / <?=$cp['mhp']?></div></td>
<td class="b1"><DIV class=nttx>体力</div></td>
<td class="b3"><DIV class=nttx><?=$cp['sp']?> / <?=$cp['msp']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>攻击力</div></td>
<td class="b3"><DIV class=nttx><?=$cp['att']?></div></td>
<td class="b1"><DIV class=nttx>防御力</div></td>
<td class="b3"><DIV class=nttx><?=$cp['def']?></div></td>
</tr>
<tr>
<td class="b1"><DIV class=nttx>武器</div></td>
<td class="b3" colspan="3"><DIV class=nttx><?=$cp['wep']?></div></td>
</tr>
<? } ?>
  </tbody>
</table>
<p align="center">“<?=$cuser?>，您好，正在为您重置虚拟身份……<br>
“完成了！您可以凭这个身份参加我们的战术训练了。
<br>
“基地入口就在前面，战术训练已经开始，请您尽快入场。”<br><br>

<form method="post"  action="valid.php" style="margin: 0px">
<input type="hidden" name="mode" value="notice">
<input type="submit" name="enter" value="进入会场">
</form>
</p>
<? include template('footer'); ?>
