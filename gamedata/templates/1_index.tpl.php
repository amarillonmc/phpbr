<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<table border="0" align="center">
<tr><td><font class="yellow"><B>官方网站</B></font></td><td>：</td><td><a href="<?=$homepage?>" class="clan" target="_blank"><?=$homepage?></a> MODDED By <u>冴月麟</u>　　</td></tr>
<tr><td><font class="yellow"><B>当前时刻</B></font></td><td>：</td><td><font class="lime"><?=$month?>月 <?=$day?>日 星期<?=$week["$wday"]?> <?=$hour?>:<?=$min?></font></td></tr>
<tr><td><font class="yellow"><B>站长留言</B></font></td><td>：</td><td><?=$adminmsg?></td></tr>
<tr><td><font class="yellow"><B>游戏情报</B></font></td><td>：</td><td><span class="clit"><B>第 <?=$gamenum?> 回游戏  <?=$gstate[$gamestate]?></span><br></td></tr>
</table>
<div >
<? if($gamestate > 10 ) { ?>
本局游戏已经进行  <span id="timing"></span><script type="text/javascript">updateTime(<?=$timing?>,1);</script><br> 
<? if($hplayer) { ?>
当前最高伤害 <?=$hplayer?> (<?=$hdamage?>)<br>
<? } } else { if($starttime > $now) { ?>
下局游戏开始于  <span id="timing"></span><script type="text/javascript">updateTime(<?=$timing?>,0);</script>   后<br>
<? } else { ?>
下局游戏开始时间  <span id="timing"></span>未定<br>
<? } if($hplayer) { ?>
上局最高伤害 <?=$hplayer?> (<?=$hdamage?>)<br>
<? } } ?>
</div>
<div>
上局结果：<span id="lastwin"><?=$gwin[$winmode]?></span>
<? if($winner) { ?>
，优胜者：<span id="lastwinner"><?=$winner?></span>
<? } ?>
</div>

禁区间隔时间： <?=$areahour?> 小时 ， <?=$arealimit?> 禁后停止激活<br>
每次增加禁区： <?=$areaadd?> 个 ， 当前禁区数： <?=$areanum?><br>
自动逃避禁区功能：
<? if($areaesc && $gamestate < 40) { ?>
<span class="yellow">开放</span>
<? } else { ?>
<span class="red">关闭</span>
<? } ?>
<br>
<span class="red">注意：进入连斗后，自动逃避功能自动关闭。</span><br><br>


激活人数：<span id="alivenum"><?=$validnum?></span>
生存人数：<span id="alivenum"><?=$alivenum?></span>
死亡总数：<span id="alivenum"><?=$deathnum?></span>
<br />
<? if($cuser) { ?>
<form method="post" name="togame" action="game.php">
<input type="hidden" name="mode" value="main">
欢迎你， <?=$cuser?> ！
<input type="submit" name="enter" value="进入游戏">
</form>
<? } else { ?>
<form method="post" name="login" action="login.php">
<input type="hidden" name="mode" value="main">
账号<input type="text" name="username" size="20" maxlength="20" value="<?=$cuser?>">
密码<input type="password" name="password" size="20" maxlength="20" value="<?=$cpass?>">
<input type="submit" name="enter" value="登录">
</form>
<? } ?>
<span class="yellow" style="font-size:24px;">第一次玩的，请先看 <a href="help.php">游戏帮助</a> !!!</span><br>
<? include template('footer'); ?>
