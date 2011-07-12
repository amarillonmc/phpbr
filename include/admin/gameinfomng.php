<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 2){
	exit($_ERROR['no_power']);
}

if($subcmd == 'infosync'){
	if($mygroup < 2){exit($_ERROR['no_power']);}
	//require_once GAME_ROOT.'./include/system.func.php';

	$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0");
	$validnum = $db->num_rows($result);
	
	$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type=0");
	$alivenum = $db->num_rows($result);
	
	$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp<=0 OR state>=10");
	$deathnum = $db->num_rows($result);
	
	save_gameinfo();
	adminlog('infomng');
	echo "状态更新：激活人数 {$validnum},生存人数 {$alivenum},死亡人数 {$deathnum}<br>";
}elseif($subcmd == 'infoedit'){
	if($mygroup < 8){exit($_ERROR['no_power']);}
	$ednum=0;

	foreach(Array('arealist','areanum','weather','hack') as $value){
		if(isset(${'i'.$value}) && ${'i'.$value} != ${$value}){
			${$value} = ${'i'.$value};
			echo "$value 修改为 ${$value}<br>";
			$ednum+=1;
		}
	}
	save_gameinfo();
	echo "做出了 $ednum 项修改<br>";
	adminlog('infoedit');
}elseif($subcmd == 'gameover'){
	if($mygroup < 8){exit($_ERROR['no_power']);}
	include_once GAME_ROOT.'./include/system.func.php';
	gameover($now,6);
	save_gameinfo();
	echo "第 $gamenum 局大逃杀紧急中止<br>";
	adminlog('gameover');
}

?>
<form method="post" name="gameinfomng" onsubmit="admin.php">
	<input type="hidden" name="mode" value="gamemng">
	<input type="hidden" name="command" value="gameinfomng">
	
	<table class="admin">
		<tr>
			<td>游戏局数</td><td><?=$gamenum?></td>
		</tr>
		<tr>
			<td>游戏状态</td><td>
				<?php
				if($gamestate == 0){echo '游戏结束';}
				elseif($gamestate == 10){echo '即将开始';}
				elseif($gamestate == 20){echo '开放激活';}
				elseif($gamestate == 30){echo '停止激活';}
				elseif($gamestate == 40){echo '连斗中';}
				elseif($gamestate == 50){echo '死斗中';}
				else{echo '参数错误';}
				?>
				<!--<input type="radio" name="igamestate" value="0" <? if ($gamestate == 0) {echo 'checked';}?> >游戏结束<br>
				<input type="radio" name="igamestate" value="10" <? if ($gamestate == 10) {echo 'checked';}?> >即将开始<br>
				<input type="radio" name="igamestate" value="20" <? if ($gamestate == 20) {echo 'checked';}?> >开放激活<br>
				<input type="radio" name="igamestate" value="30" <? if ($gamestate == 30) {echo 'checked';}?> >停止激活<br>
				<input type="radio" name="igamestate" value="40" <? if ($gamestate == 40) {echo 'checked';}?> >连斗中<br>
				<input type="radio" name="igamestate" value="50" <? if ($gamestate == 50) {echo 'checked';}?> >死斗中-->
			</td>
		</tr>
		<tr>
			<td>游戏开始时间</td><td><? echo $starttime ? date("Y 年 m 月 d 日 H 时 i 分 s 秒",$starttime) : '未定义' ?></td>
		</tr>
		<tr>
			<td>禁区列表</td><td>工事中</td>
		</tr>
		<tr>
			<td>已有禁区数目</td><td><input size="10" type="text" name="iareanum" value="<?=$areanum?>" maxlength="10"></td>
		</tr>
		<tr>
			<td>下次禁区时间</td><td>工事中<!--<input size="20" type="text" name="iareatime" value="<? echo date("Y-m-d-H-i-s",$areatime) ?>" maxlength="20">--></td>
		</tr>
		<tr>
			<td>当前天气</td><td><input size="10" type="text" name="iweather" value="<?=$weather?>" maxlength="10"></td>
		</tr>
		<tr>
			<td>禁区解除</td><td><input type="radio" name="ihack" value="1" <? if ($hack) {echo 'checked';}?> >是<br><input type="radio" name="ihack" value="0" <? if (!$hack) {echo 'checked';}?> >否</td>
		</tr>
		<tr>
			<td>当前激活人数</td><td><?=$validnum?></td>
		</tr>
		<tr>
			<td>当前生存人数</td><td><?=$alivenum?></td>
		</tr>
		<tr>
			<td>当前死亡人数</td><td><?=$deathnum?></td>
		</tr>
	</table>
	
	<input type="radio" name="subcmd" id="infosync" value="infosync" checked><a onclick=sl('infosync'); href="javascript:void(0);" >游戏人数同步（用于修正游戏激活、存活、死亡人数错误的情况）</a><br>
	<input type="radio" name="subcmd" id="infoedit" value="infoedit"><a onclick=sl('infoedit'); href="javascript:void(0);" >游戏状态修改</a><br>
	<input type="radio" name="subcmd" id="gameover" value="gameover"><a onclick=sl('gameover'); href="javascript:void(0);" >终止当前游戏</a><br>
	<input type="submit" value="提交">
</form>
