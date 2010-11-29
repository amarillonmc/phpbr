<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" >申请表格填写完成</div>

<table border="1" cellspacing="0" align="center">
  <tbody>
<tr><td class="b1">姓名</td><td colspan="3" class="b3"><?=$cuser?></td></tr>
<tr><td class="b1">学号</td><td colspan="3" class="b3"><?=$sexinfo[$gd]?> <?=$sNo?> 号</td></tr>
<tr><td class="b1">内定称号</td><td colspan="3" class="b3"><?=$clubinfo[$club]?></td></tr>
<tr><td class="b1">生命</td><td class="b3">100/100</td><td width="39" class="b1">体力</td><td class="b3">300</td></tr>
<tr><td class="b1">攻击力</td><td class="b3">95</td><td class="b1">武器</td><td class="b3"><?=$wep?></td></tr>
<tr><td class="b1">防御力</td><td class="b3">105</td><td class="b1">防具</td><td class="b3"><?=$arb?></td></tr>
  </tbody>
</table>
<p align="center">开始为 <?=$cuser?> 建立档案中……<br>
<img border="0" src="./img/i_inada.gif" width="70" height="70"><br><br>
“您好！我是本次ACG祭的工作人员，现在为您创建您的虚拟身份。”<br>
“创建完成！您可以凭这个身份进行COSPLAY了。”<br>
“会场入口在那个方向，ACG祭的开幕仪式已经开始了，请您尽快入场。”<br>
<br><br>

<form method="post"  action="valid.php" style="margin: 0px">
<input type="hidden" name="mode" value="notice">
<input type="submit" name="enter" value="试炼开始">
</form>
</p>
<? include template('footer'); ?>
