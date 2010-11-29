<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div id="notice"></div>


<table align="center">
<tr><td>
<span id="main">
<? include template('profile'); ?>
<span>
</td>
<td rowspan="2">
<table border="1" width="250" height="550" cellspacing="0" cellpadding="0" >
<tr height="1">
<td height="20" class="b1"><b><span class="yellow" id="pls"><?=$plsinfo[$pls]?></span>【<span class="red">剩余：<span id="anum"><?=$alivenum?></span>人</span>】</b></td>
</tr>
<tr><td valign="top"class="b3" style="text-align: left">
<div id="log">
<?=$log?>
</div>
<form method="post" id="cmd" name="cmd" style="margin: 0px" >
<? if($state >=1 && $state <= 3) { include template('rest'); } elseif($itms0) { include template('itemfind'); } else { include template('command'); } ?>
<br /><br />
<input type="button" id="submit" style="width: 135px; height: 50px" onClick="postCommand();return false;" value="提交">
</form>
</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table id="chat" border="1" width="550" height="200" cellspacing="0" cellpadding="0">
<tr height="1"><td height="1" width="550" class="b1"><b>消息</b></td></tr>
<tr ><td valign="top" class="b3" style="text-align: left" height="1px">
<div id="chatlist" class="chatlist" >
<? if(is_array($chatdata['msg'])) { foreach($chatdata['msg'] as $msg) { ?>
<?=$msg?>
<? } } ?>
</div>
<form type="post" id="sendchat" name="sendchat" action="chat.php" onsubmit="return false;" >
<input type="hidden" id="lastcid" name="lastcid" value="<?=$chatdata['lastcid']?>">
<input type="hidden" id="team" name="team" value="<?=$teamID?>">
<input type="hidden" id="sendmode" name="sendmode" value="ref">
<span id="chattype">
<select name="chattype" value="2">
<option value="0" selected><?=$chatinfo['0']?>
<? if($teamID) { ?>
<option value="1" ><?=$chatinfo['1']?>
<? } ?>
</select>
</span>
<input type="text" id="chatmsg" name="chatmsg" maxlength="60" >
<input type="button" id="send" onClick="document['sendchat']['sendmode'].value='send';chat('send',<?=$chatrefresh?>);return false;" value="喊出去">
<input type="button" id="ref" onClick="document['sendchat']['sendmode'].value='ref';chat('ref',<?=$chatrefresh?>);return false;" value="仔细听">
</form>
<script type="text/javascript">chat('ref',<?=$chatrefresh?>);</script>
</td>
</tr>
</table>
</td>
</table>
<? include template('footer'); ?>
