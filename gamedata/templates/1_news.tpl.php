<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" >进行状况</div>

<div align="left">
<div class="clearfix">
<span style="float:left;" ><img border="0" src="img/n_1.gif" width="70" height="70"></span>
<span>『大家都还活得很精神嘛！<br />以下是到现在为止的游戏状况。<br />今天也要好好干喔！』</span>
</div>
<br>
<? if($hack) { ?>
<span class="yellow"><b>（禁区已解除）</b></span>
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
