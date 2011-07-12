<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table align="center">
<tr>
<td>性别</td>
<td>
<input type="radio" id="male" name="gender" onclick="userIconMover()" value="m" 
<? if($gender != "f") { ?>
checked
<? } ?>
 ><?=$sexinfo['m']?><br />
<input type="radio" name="gender" onclick="userIconMover()" value="f" 
<? if($gender == "f") { ?>
checked
<? } ?>
><?=$sexinfo['f']?>
</td>
<td> </td>
</tr>
<tr>
<td>头像</td>
<td>
<select id="icon" name="icon" onchange="userIconMover()">
<? if(is_array($iconarray)) { foreach($iconarray as $icon) { ?>
<?=$icon?>
<? } } ?>
</select>（0为随机）
</td>
<td>
<div id="userIconImg" class="iconImg" >
<img src="img/
<? if($gender != 'f') { ?>
m
<? } else { ?>
f
<? } ?>
_<?=$select_icon?>.gif" alt="<?=$select_icon?>">
</div>
</td>
</tr>
</table>