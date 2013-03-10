<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div id="notice"></div>
<div id="aliveinfo">
<center>
<span class="subtitle">幸存者一览</span>
<form method="post" name="alive" onSubmit="return false;">
<input type="hidden" id="alivemode" name="alivemode" value="last">
<input type="button" name="enter" value="显示前<?php echo $alivelimit?>名幸存者" onClick="$('alivemode').value='last';postCmd('alive','alive.php');">
<input type="button" name="enter" value="显示全部幸存者" onClick="$('alivemode').value='all';postCmd('alive','alive.php');">
</form>
<div id="alivelist">
<?php include template('alivelist'); ?>
</div>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
</center>
</div>
<?php include template('footer'); ?>
