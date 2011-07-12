<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?=$restinfo[$pdata['state']]?> 中。。。<br>
<input type="hidden" name="mode" value="rest"><br>
<input type="radio" name="command" id="rest" value="rest" checked><a onclick=sl("rest"); href="javascript:void(0);" ><?=$restinfo[$pdata['state']]?></a><br>
<input type="radio" name="command" id="back" value="back"><a onclick=sl("back"); href="javascript:void(0);" >返回</a>
