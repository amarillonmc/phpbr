<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<span>账户基本资料</span>
<table>
<? if($ustate == 'register') { ?>
<tr>
<td>账号</td>
<td><input type="text" name="username" size="15" maxlength="15" value=""></td>
<td>最长15个英文字符或者汉字，不能出现半角符号</td>
</tr>
<tr>
<td>新密码</td>
<td><input type="password" name="npass" size="24" maxlength="24" value=""></td>
<td>最长24个字符</td>
</tr>
<tr>
<td>重复新密码</td>
<td><input type="password" name="rnpass" size="24" maxlength="24" value=""></td>
<td>最长24个字符</td>
</tr>
<? } elseif($ustate == 'edit') { ?>
<tr>
<td>账号</td>
<td><?=$username?></td>
<td> </td>
</tr>
<tr>
<td>原密码</td>
<td><input type="password" name="opass" size="24" maxlength="24" value=""></td>
<td> </td>
</tr>
<tr>
<td>新密码</td>
<td><input type="password" name="npass" size="24" maxlength="24" value=""></td>
<td>最长24个字符</td>
</tr>
<tr>
<td>重复新密码</td>
<td><input type="password" name="rnpass" size="24" maxlength="24" value=""></td>
<td>最长24个字符</td>
</tr>
<? } else { ?>
<tr>
<td>账号</td>
<td><?=$username?></td>
<td> </td>
</tr>
<tr>
<td>密码</td>
<td>修改密码点此（未完成）</td>
<td> </td>
</tr>
<? } ?>
</table>