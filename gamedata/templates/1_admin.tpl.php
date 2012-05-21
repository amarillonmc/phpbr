<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<center>
<div class="subtitle" >游戏管理</div>
<div><span class="yellow"><?=$lang['mygroup']?> <?=$mygroup?></span></div>
<div><span class="yellow"><?=$cmd_info?></span></div>
<? if($showdata) { ?>
<?=$showdata?>
<div>
<form method="post" name="goto_menu" action="admin.php">
<input type="submit" name="enter" value="<?=$lang['goto_menu']?>">
</form>
</div>
<? } else { include template('admin_menu'); } ?>
</center>
<? include template('footer'); ?>
