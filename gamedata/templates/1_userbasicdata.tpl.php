<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table align="center">
<? if($ustate == 'register') { ?>
<tr>
<td>账号</td>
<td><input type="text" name="username" size="15" maxlength="15" value=""></td>
<td>最长15个英文字符或者汉字，不能出现半角符号</td>
</tr>
<tr>
<td>新密码</td>
<td><input type="password" id="npass" name="npass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<tr>
<td>重复新密码</td>
<td><input type="password" id="rnpass" name="rnpass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<? } elseif($ustate == 'edit') { ?>
<tr>
<td>账号</td>
<td><?=$username?></td>
<td> </td>
</tr>
<tr>
<td>原密码</td>
<td><input type="password" id="opass" name="opass" size="24" maxlength="24" value=""></td>
<td> </td>
</tr>
<tr>
<td>新密码</td>
<td><input type="password" id="npass" name="npass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<tr>
<td>重复新密码</td>
<td><input type="password" id="rnpass" name="rnpass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<? } else { ?>
<tr>
<td>账号</td>
<td><?=$username?></td>
<td> </td>
</tr>
<tr>
<td>密码</td>
<td>此处无法修改</td>
<td> </td>
</tr>
<? } ?>
</table>