<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table align="center">
<tr>
<td>游戏签名</td>
<td><textarea cols="35" rows="3" style="overflow:auto" name="motto" value=""><?=$motto?></textarea><br />写下彰显个性的台词，120个字以内。<br />可用BBcode标签：[br]	[b]	[i]	[u]	[strike]	[red]	[yellow]	[lime]	[green]	[blue]	[aqua]</td>
</tr>
<tr>
<td>必杀台词</td>
<td><input size="35" type="text" name="criticalmsg" maxlength="60" value="<?=$criticalmsg?>"><br />写下你使出重击或必杀技时的台词，60个字以内</td>
</tr>
<tr>
<tr>
<td>杀人宣言</td>
<td><input size="35" type="text" name="killmsg" maxlength="60" value="<?=$killmsg?>"><br />写下你杀死对手的留言，60个字以内</td>
</tr>
<tr>
<td>遗言</td>
<td><input size="35" type="text" name="lastword" maxlength="60" value="<?=$lastword?>"><br />写下你不幸被害时的台词，60个字以内</td>
</tr>
</table> 