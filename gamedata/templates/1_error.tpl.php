<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" ><?=$message?>
</div>
<? if($errorinfo) { ?>
file=<?=$file?><br \>line=<?=$line?>
<? } ?>
<br>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
<? include template('footer'); ?>
