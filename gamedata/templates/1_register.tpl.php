<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" align="center">账号注册</div>

<p align="center">
<span id="notice"></span>
<span id="info" class="yellow"></span>
</p>

<form method="post" id="reg" name="reg">
<input type="hidden" name="cmd" value="post_register">
<div id="userbasicdata">
<? include template('userbasicdata'); ?>
</div>
<br />
<div id="useradvdata">
<? include template('useradvdata'); ?>
</div>
<br />
<div id="postreg">
<input type="button" id="post" onClick="postRegCommand();return false;" value="提交">
<input type="reset" id="reset" name="reset" value="重设">
</div>
</form>
<br />
<? include template('footer'); ?>
