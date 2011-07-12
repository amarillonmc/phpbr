<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table>
<tr>
<td>
<div id="profile" 
<? if($promap == 'map') { ?>
style="display:none"
<? } ?>
> 
<? include template('profile'); ?>
</div>
</td>
<td class="td2">
<div class="cmdbtn" onclick="$('command').value='promap';postCommand();">切换</div>
</td>
<td>
<div id="gamemap" 
<? if($promap == 'pro') { ?>
style="display:none"
<? } ?>
> 
<? include template('gamemap'); ?>
</div>
</td>
</tr>
</table> 