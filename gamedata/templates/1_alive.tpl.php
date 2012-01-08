<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div id="notice"></div>
<div id="aliveinfo">
<center>
<span class="subtitle">幸存者一览</span>
<form method="post" name="backindex" onSubmit="return false;">
<input type="button" name="enter" value="显示前<?=$alivelimit?>名幸存者" onClick="showAlive('last');">
<input type="button" name="enter" value="显示全部幸存者" onClick="showAlive('all');">
</form>
<div id="alivelist">
<? include template('alivelist'); ?>
</div>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
</center>
</div>
<? include template('footer'); ?>
