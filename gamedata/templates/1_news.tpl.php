<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div id="notice"></div>
<div class="subtitle" >进行状况</div>

<div align="left">
<div class="clearfix">
<? if($bossdeath) { ?>
<div style="float:left;"><img border="0" src="img/question.gif"></div><div class="linen">“………………”</div>
<? } else { ?>
<div style="float:left;"><img border="0" src="img/n_7.gif"></div><div class="linen">“以下是到现在为止的游戏状况。<br />各位参战者请加油哦！”</div>
<? } ?>
</div>
<br>
<span class="evergreen">当前时间：<?=$month?>月<?=$day?>日 星期<?=$week["$wday"]?> <?=$hour?>:<?=$min?></span><br />
<span class="evergreen">当前天气：<?=$wthdata[$weather]['name']?></span><br />
<? if($gamestate==40) { ?>
<span class="yellow">游戏已经进入连斗阶段！</span><br />
<? } if($gamestate==50) { ?>
<span class="red">游戏已经进入死斗模式！</span><br />
<? } if($hack) { ?>
<span class="evergreen">（禁区已解除）</span>
<? } include template('areainfo'); ?>
<br><br>
<button onClick="showNews('last');return false;">显示最新的<?=$newslimit?>条消息</button>
<button onClick="showNews('all');return false;">显示全部消息</button>
<button onClick="showNews('chat');return false;">显示最新聊天纪录</button>


<div id="newsinfo">
<? if($newsmode == 'all') { include template('newsinfo'); } else { include template('lastnews'); } ?>
</div>

</div>
<br>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
<? include template('footer'); ?>
