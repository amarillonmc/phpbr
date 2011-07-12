<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" align="center">入场手续</div>

<!--<p><img border="0" src="img/story_0.jpg" style="align:center"></p>-->

<p align="center" class="linen">“你好，这里是新式战术训练基地‘齿轮’。<br />
“我是基地操作人员。<br />
“‘齿轮’拥有全世界最先进的虚拟现实技术，能够在兵不血刃的情况下进行战术训练。<br />
“请您如实填写身份资料，以便领取神经接入装置。<br />
“身份资料请填写在下面。”<br /></p>
<form method="post"  action="valid.php" name="valid">
<input type="hidden" name="mode" value="enter">
<span class ="yellow">账户基本资料</span>
<? include template('userbasicdata'); ?>
<br />
<span class ="yellow">账户个性化资料</span>
<? include template('usergdicon'); ?>
<br />
<? include template('usercredits'); ?>
<br />
<? include template('userwords'); ?>
<br />
<div class="yellow">
<? if($iplimit) { ?>
同IP允许进入次数：<?=$iplimit?>；
<? } ?>
<br />请勿使用不文明词汇；
<br />禁止利用游戏BUG谋取利益；
<br />如有问题请联系GM，请遵守游戏规则，并祝您游戏愉快。</div>

<p><input type="submit" name="enter" value="提交"> 　<input type="reset" name="reset" value="重设"><br />
</p>
</form>
<br />
<? include template('footer'); ?>
