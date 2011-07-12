<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div id="notice"></div>
<table border="0" cellspacing="10" cellpadding="0" align="center">
<tr valign=top><td>
<div id="main">
<? include template('main'); ?>
</div>
</td>
<td rowspan="2">
<table border="0" width="280" cellspacing="0" cellpadding="0" >
<tr>
<td height="20" class="b1"><div class=nttx><span class="yellow" id="pls"><?=$mapdata[$pdata['pls']]['name']?></span>【<span class="red">剩余：<span id="anum"><?=$alivenum?></span>人</span>】</div></td>
</tr>
<tr><td valign="top" class="b3" style="text-align: left">
<div id="log" class=nttx>
<?=$log?>
</div>
<div class=nttx>
<form method="post" id="cmd" name="cmd" style="margin: 0px" >
<? if($pdata['state'] >=1 && $pdata['state'] <= 3) { include template('rest'); } elseif($pdata['itms0']) { include template('itemfind'); } else { include template('command'); } ?>
<br /><br />
</form>
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table id="chat" border="0" width="640" cellspacing="0" cellpadding="0" background="img/chatting.gif" style="background-position:0px 80px;background-repeat:no-repeat;valign:top">
<tr><td height="20px" width="635" class="b1"><div class=nttx>消息</div></td></tr>
<tr><td height="140px" valign="top" class="b3" style="text-align: left" height="1px">
<div id="chatlist" class="chatlist" >
<? if(is_array($chatdata['msg'])) { foreach($chatdata['msg'] as $msg) { ?>
<?=$msg?>
<? } } ?>
</div></td></tr>
<tr><td class="b3" height="5"></td></tr>
<tr><td class="b3" height="35">
<div class=nttx>
<form type="post" id="sendchat" name="sendchat" action="chat.php" onsubmit="return false;" >
<input type="hidden" id="lastcid" name="lastcid" value="<?=$chatdata['lastcid']?>">
<!--<input type="hidden" id="cteam" name="cteam" value="<?=$teamID?>">
<input type="hidden" id="cpls" name="cpls" value="<?=$pls?>">-->
<input type="hidden" id="sendmode" name="sendmode" value="ref">
<span id="chattype">
<select name="chattype" value="4">
<option value="0" selected><?=$chatinfo['0']?>
<option value="1" ><?=$chatinfo['1']?>
<option value="2" ><?=$chatinfo['2']?>
<option value="4" ><?=$chatinfo['4']?>
</select>
</span>
<input type="text" id="chatmsg" name="chatmsg" maxlength="60" >
<input type="button" id="send" onClick="document['sendchat']['sendmode'].value='send';chat('send',<?=$chatrefresh?>);return false;" value="发送">
<input type="button" id="ref" onClick="document['sendchat']['sendmode'].value='ref';chat('ref',<?=$chatrefresh?>);return false;" value="刷新">
</form>
<script type="text/javascript">chat('ref',<?=$chatrefresh?>);</script>
</div>
</td>
</tr>
</table>
</td>
</table>
<? include template('footer'); ?>
