<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" >申请手续</div><br /><br /><img border="0" src="img/Daichan.gif" width="200" height="200"><br /><br />“第N次常磐ACG祭注意事项：<br />本次ACG祭不同于以往，各项活动将采用虚拟现实系统进行。<br />请参加者如实填写身份资料，以便领取神经接入装置。<br />身份资料请填写在下面。”<br /><form method="post"  action="valid.php" name="valid"><input type="hidden" name="mode" value="enter"> 姓名 : <?=$username?> <br /><div id="iconImg" class="iconImg" ><img src="img/
<? if($gender != 2) { ?>
m
<? } else { ?>
f
<? } ?>
_<?=$icon?>.gif" alt="<?=$icon?>"></div>性别 : <input type="radio" id="gender" name="gender" onchange="iconMover()" value="m" 
<? if($gender != 2) { ?>
checked
<? } ?>
 >NOD编队  <input type="radio" name="gender" onchange="iconMover()" value="f" 
<? if($gender == 2) { ?>
checked
<? } ?>
>FARGO编队<br />头像 : <select id="icon" name="icon" onchange="iconMover()">
<? if(is_array($iconarray)) { foreach($iconarray as $icon) { ?>
	<?=$icon?>
<? } } ?>
</select>（0为随机）<br /><br /><div>座右铭 : <input size="30" type="text" name="motto" id="motto" maxlength="60" value="<?=$motto?>"></div><div>写下代表你性格的一句话，30个字以内。<div>留  言 : <input size="30" type="text" name="killmsg" id="killmsg" maxlength="60" value="<?=$killmsg?>"></div><div>写下你杀死对手的留言，30个字以内。<div>遗  言 : <input size="30" type="text" name="lastword" id="lastword" maxlength="60" value="<?=$lastword?>"></div><div>写下你不幸被害时的台词，30个字以内。<br /><br /><font color="yellow" size="2"><b>死亡笔记：非卖品<br />复活机会：非卖品<br /></b></font><br /><br /><font color="yellow" size="2"><b><u>同一玩家3个以上角色登录完全禁止；<br />其它网站盗连本站创意完全禁止；<br />玩家名字及游戏内发言不雅字眼完全禁止；<br />如发现破坏以上守则的玩家，<br />管理员可能将会强制删除游戏人物及封IP，<br />将请遵守这个小小的游戏守则，谢谢。<br /></u></b></font><br /><br /><input type="submit" name="enter" value="提交"> 　<input type="reset" name="reset" value="重设"><br></form><br /><br />
<? include template('footer'); ?>
