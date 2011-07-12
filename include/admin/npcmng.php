<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 5){
	exit($_ERROR['no_power']);
}

if($subcmd == 'kill') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'npc_'.$i})) {
			$npclist[] = ${'npc_'.$i};
		}
	}
	foreach($npclist as $n => $name) {
		$db->query("UPDATE {$tablepre}players SET hp='0',state='15' WHERE pid='$name' AND type>'0' AND hp>'0' AND state<'10'");
		if($db->affected_rows()){
			adminlog('killnpc',$name,$adminmsg);
			echo " 角色 $name 被杀死。<br>";
			//addnews($now,'death15',$name);
			$deathnum++;
			save_gameinfo();
		} else {
			echo "无法杀死角色 $name 。";
		}
	}
} elseif($subcmd == 'live') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'npc_'.$i})) {
			$npclist[] = ${'npc_'.$i};
		}
	}
	foreach($npclist as $n => $name) {
		$db->query("UPDATE {$tablepre}players SET hp='100',state='0' WHERE pid='$name' AND type>'0' AND (hp<='0' OR state>='10')");
		if($db->affected_rows()){
			adminlog('livenpc',$name,$adminmsg);
			echo " 角色 $name 被复活。<br>";
			//addnews($now,'alive',$name);
			$deathnum--;
			save_gameinfo();
		} else {
			echo "无法复活角色 $name 。";
		}
	}
} elseif($subcmd == 'del') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'npc_'.$i})) {
			$npclist[] = ${'npc_'.$i};
		}
	}
	foreach($npclist as $n => $name) {
		$result = $db->query("SELECT hp,state FROM {$tablepre}players WHERE pid='$name' AND type>'0'");
		$npc = $db->fetch_array($result);
		if(!$npc) {
			echo "无法清除角色 $name 。";
		} elseif(($npc['hp']>0)&&($npc['state']<10)) {
//			$db->query("UPDATE {$tablepre}players SET hp='0',state='16'weps='0',arbs='0',arhs='0',aras='0',arfs='0',arts='0',itms0='0',itms1='0',itms2='0',itms3='0',itms4='0',itms5='0',itms6='0',money='0' WHERE pid='$name' AND type>'0'");
			$db->query("UPDATE {$tablepre}players SET hp=0,state=99 WHERE pid='$name' AND type>0");
			adminlog('delnpc',$name,$adminmsg);
			echo " 角色 $name 被清除了。<br>";
			//addnews($now,'death16',$name);
			$deathnum++;
			save_gameinfo();
		} else {
			$db->query("UPDATE {$tablepre}players SET state=99 WHERE pid='$name' AND type>0");
			adminlog('delncp',$name,$adminmsg);
			echo " 角色 $name 的尸体被清除了。<br>";
			//addnews($now,'delcp',$name);
		}
	}
} elseif($subcmd == 'edit') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'npc_'.$i})) {
			$name = ${'npc_'.$i};
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$name'");
			$npc = $db->fetch_array($result);
			if(!$npc) {
				echo "找不到角色 $name 。";
			} else {
				echo <<<EOT
<form method="post" name="npcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="hidden" name="command" value="npcmng">
<input type="hidden" name="type" value="{$npc['type']}">
<table class="admin">
<tr>
	<td>姓名</td><td><input type="hidden" name="name" value="{$npc['pid']}">{$npc['name']}</td>
	<td>性别</td><td><input size="15" type="text" name="gd" value="{$npc['gd']}" maxlength="20"></td>
	<td>学号</td><td><input size="15" type="text" name="sNo" value="{$npc['sNo']}" maxlength="20"></td>
	<td>头像</td><td><input size="15" type="text" name="icon" value="{$npc['icon']}" maxlength="20"></td>
	<td>社团</td><td><input size="15" type="text" name="club" value="{$npc['club']}" maxlength="20"></td>
	<td>状态</td><td><input size="15" type="text" name="state" value="{$npc['state']}" maxlength="20"></td>
</tr>
<tr>
	<td>等级</td><td><input size="15" type="text" name="lvl" value="{$npc['lvl']}" maxlength="20"></td>
	<td>经验</td><td><input size="15" type="text" name="exp" value="{$npc['exp']}" maxlength="20"></td>
	<td>金钱</td><td><input size="15" type="text" name="money" value="{$npc['money']}" maxlength="20"></td>
	<td>位置</td><td><input size="15" type="text" name="pls" value="{$npc['pls']}" maxlength="20"></td>
	<td>基础姿态</td><td><input size="15" type="text" name="pose" value="{$npc['pose']}" maxlength="20"></td>
	<td>应战策略</td><td><input size="15" type="text" name="tactic" value="{$npc['tactic']}" maxlength="20"></td>
</tr>
<tr>
	<td>基础攻击</td><td><input size="15" type="text" name="att" value="{$npc['att']}" maxlength="20"></td>
	<td>基础防御</td><td><input size="15" type="text" name="def" value="{$npc['def']}" maxlength="20"></td>
	<td>生命</td><td><input size="15" type="text" name="hp" value="{$npc['hp']}" maxlength="20"></td>
	<td>最大生命</td><td><input size="15" type="text" name="mhp" value="{$npc['mhp']}" maxlength="20"></td>
	<td>体力</td><td><input size="15" type="text" name="sp" value="{$npc['sp']}" maxlength="20"></td>
	<td>最大体力</td><td><input size="15" type="text" name="msp" value="{$npc['msp']}" maxlength="20"></td>
</tr>
<tr>
	
	<td>对手</td><td><input size="15" type="text" name="bid" value="{$npc['bid']}" maxlength="20"></td>
	<td>受伤</td><td><input size="15" type="text" name="inf" value="{$npc['inf']}" maxlength="20"></td>
	<td>怒气</td><td><input size="15" type="text" name="rage" value="{$npc['rage']}" maxlength="20"></td>
	<td>杀人数</td><td><input size="15" type="text" name="killnum" value="{$npc['killnum']}" maxlength="20"></td>
	<td>队伍名称</td><td><input size="15" type="text" name="teamID" value="{$npc['teamID']}" maxlength="20"></td>
	<td>队伍密码</td><td><input size="15" type="text" name="teamPass" value="{$npc['teamPass']}" maxlength="20"></td>
</tr>
<tr>
	<td>殴熟</td><td><input size="15" type="text" name="wp" value="{$npc['wp']}" maxlength="20"></td>
	<td>斩熟</td><td><input size="15" type="text" name="wk" value="{$npc['wk']}" maxlength="20"></td>
	<td>枪熟</td><td><input size="15" type="text" name="wg" value="{$npc['wg']}" maxlength="20"></td>
	<td>投熟</td><td><input size="15" type="text" name="wc" value="{$npc['wc']}" maxlength="20"></td>
	<td>爆熟</td><td><input size="15" type="text" name="wd" value="{$npc['wd']}" maxlength="20"></td>
	<td>灵熟</td><td><input size="15" type="text" name="wf" value="{$npc['wf']}" maxlength="20"></td>
</tr>
</table>
<table class="admin">
<tr>
	<td>武器</td><td><input size="15" type="text" name="wep" value="{$npc['wep']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="wepk" value="{$npc['wepk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="wepe" value="{$npc['wepe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="weps" value="{$npc['weps']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="wepsk" value="{$npc['wepsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="wepnp" value="{$npc['wepnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>身体防具</td><td><input size="15" type="text" name="arb" value="{$npc['arb']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arbk" value="{$npc['arbk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arbe" value="{$npc['arbe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arbs" value="{$npc['arbs']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arbsk" value="{$npc['arbsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="arbnp" value="{$npc['arbnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>头部防具</td><td><input size="15" type="text" name="arh" value="{$npc['arh']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arhk" value="{$npc['arhk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arhe" value="{$npc['arhe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arhs" value="{$npc['arhs']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arhsk" value="{$npc['arhsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="arhnp" value="{$npc['arhnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>腕部防具</td><td><input size="15" type="text" name="ara" value="{$npc['ara']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arak" value="{$npc['arak']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arae" value="{$npc['arae']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="aras" value="{$npc['aras']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arask" value="{$npc['arask']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="aranp" value="{$npc['aranp']}" maxlength="20"></td>
</tr>
<tr>
	<td>足部防具</td><td><input size="15" type="text" name="arf" value="{$npc['arf']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arfk" value="{$npc['arfk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arfe" value="{$npc['arfe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arfs" value="{$npc['arfs']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arfsk" value="{$npc['arfsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="arfnp" value="{$npc['arfnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>饰物</td><td><input size="15" type="text" name="art" value="{$npc['art']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="artk" value="{$npc['artk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arte" value="{$npc['arte']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arts" value="{$npc['arts']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="artsk" value="{$npc['artsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="artnp" value="{$npc['artnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>拾取道具</td><td><input size="15" type="text" name="itm0" value="{$npc['itm0']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk0" value="{$npc['itmk0']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme0" value="{$npc['itme0']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms0" value="{$npc['itms0']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk0" value="{$npc['itmsk0']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp0" value="{$npc['itmnp0']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹1</td><td><input size="15" type="text" name="itm1" value="{$npc['itm1']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk1" value="{$npc['itmk1']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme1" value="{$npc['itme1']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms1" value="{$npc['itms1']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk1" value="{$npc['itmsk1']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp1" value="{$npc['itmnp1']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹2</td><td><input size="15" type="text" name="itm2" value="{$npc['itm2']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk2" value="{$npc['itmk2']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme2" value="{$npc['itme2']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms2" value="{$npc['itms2']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk2" value="{$npc['itmsk2']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp2" value="{$npc['itmnp2']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹3</td><td><input size="15" type="text" name="itm3" value="{$npc['itm3']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk3" value="{$npc['itmk3']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme3" value="{$npc['itme3']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms3" value="{$npc['itms3']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk3" value="{$npc['itmsk3']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp3" value="{$npc['itmnp3']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹4</td><td><input size="15" type="text" name="itm4" value="{$npc['itm4']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk4" value="{$npc['itmk4']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme4" value="{$npc['itme4']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms4" value="{$npc['itms4']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk4" value="{$npc['itmsk4']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp4" value="{$npc['itmnp4']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹5</td><td><input size="15" type="text" name="itm5" value="{$npc['itm5']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk5" value="{$npc['itmk5']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme5" value="{$npc['itme5']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms5" value="{$npc['itms5']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk5" value="{$npc['itmsk5']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp5" value="{$npc['itmnp5']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹6</td><td><input size="15" type="text" name="itm6" value="{$npc['itm6']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk6" value="{$npc['itmk6']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme6" value="{$npc['itme6']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms6" value="{$npc['itms6']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk6" value="{$npc['itmsk6']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp6" value="{$npc['itmnp6']}" maxlength="20"></td>
</tr>
</table>
<input type="hidden" name="subcmd" value="back">
<input type="button" value="修改属性" onclick="javascript:document.npcmng.subcmd.value = 'edit2';document.npcmng.submit();">
<input type="button" value="返回" onclick="javascript:document.npcmng.submit();">
</form>
EOT;
			}
		break;
		}
	}
} elseif($subcmd == 'edit2') {
	$db->query("UPDATE {$tablepre}players SET gd='$gd',icon='$icon',club='$club',state='$state',sNo='$sNo',hp='$hp',mhp='$mhp',sp='$sp',msp='$msp',att='$att',def='$def',pls='$pls',lvl='$lvl',exp='$exp',money='$money',bid='$bid',inf='$inf',rage='$rage',pose='$pose',tactic='$tactic',killnum='$killnum',wp='$wp',wk='$wk',wg='$wg',wc='$wc',wd='$wd',wf='$wf',teamID='$teamID',teamPass='$teamPass',wep='$wep',wepk='$wepk',wepe='$wepe',weps='$weps',wepsk='$wepsk',arb='$arb',arbk='$arbk',arbe='$arbe',arbs='$arbs',arbsk='$arbsk',arh='$arh',arhk='$arhk',arhe='$arhe',arhs='$arhs',arhsk='$arhsk',ara='$ara',arak='$arak',arae='$arae',aras='$aras',arask='$arask',arf='$arf',arfk='$arfk',arfe='$arfe',arfs='$arfs',arfsk='$arfsk',art='$art',artk='$artk',arte='$arte',arts='$arts',artsk='$artsk',itm0='$itm0',itmk0='$itmk0',itme0='$itme0',itms0='$itms0',itmsk0='$itmsk0',itm1='$itm1',itmk1='$itmk1',itme1='$itme1',itms1='$itms1',itmsk1='$itmsk1',itm2='$itm2',itmk2='$itmk2',itme2='$itme2',itms2='$itms2',itmsk2='$itmsk2',itm3='$itm3',itmk3='$itmk3',itme3='$itme3',itms3='$itms3',itmsk3='$itmsk3',itm4='$itm4',itmk4='$itmk4',itme4='$itme4',itms4='$itms4',itmsk4='$itmsk4',itm5='$itm5',itmk5='$itmk5',itme5='$itme5',itms5='$itms5',itmsk5='$itmsk5',itm6='$itm6',itmk6='$itmk6',itme6='$itme6',itms6='$itms6',itmsk6='$itmsk6',wepnp = '$wepnp',arbnp = '$arbnp',arhnp = '$arhnp',aranp = '$aranp',arfnp = '$arfnp',artnp = '$artnp',itmnp0 = '$itmnp0',itmnp1 = '$itmnp1',itmnp2 = '$itmnp2',itmnp3 = '$itmnp3',itmnp4 = '$itmnp4',itmnp5 = '$itmnp5',itmnp6 = '$itmnp6' where pid='$name'");
	//$db->query("UPDATE {$tablepre}players SET gd='$gd',icon='$icon',club='$club',sNo='$sNo',hp='$hp',mhp='$mhp',sp='$sp',msp='$msp',att='$att',def='$def',pls='$pls',lvl='$lvl',exp='$exp',money='$money',bid='$bid',inf='$inf',rage='$rage',pose='$pose',tactic='$tactic',killnum='$killnum',wp='$wp',wk='$wk',wg='$wg',wc='$wc',wd='$wd',wf='$wf',teamID='$teamID',teamPass='$teamPass',wep='$wep',wepk='$wepk',wepe='$wepe',weps='$weps',wepsk='$wepsk',arb='$arb',arbk='$arbk',arbe='$arbe',arbs='$arbs',arbsk='$arbsk',arh='$arh',arhk='$arhk',arhe='$arhe',arhs='$arhs',arhsk='$arhsk',ara='$ara',arak='$arak',arae='$arae',aras='$aras',arask='$arask',arf='$arf',arfk='$arfk',arfe='$arfe',arfs='$arfs',arfsk='$arfsk',art='$art',artk='$artk',arte='$arte',arts='$arts',artsk='$artsk',itm0='$itm0',itmk0='$itmk0',itme0='$itme0',itms0='$itms0',itmsk0='$itmsk0',itm1='$itm1',itmk1='$itmk1',itme1='$itme1',itms1='$itms1',itmsk1='$itmsk1',itm2='$itm2',itmk2='$itmk2',itme2='$itme2',itms2='$itms2',itmsk2='$itmsk2',itm3='$itm3',itmk3='$itmk3',itme3='$itme3',itms3='$itms3',itmsk3='$itmsk3',itm4='$itm4',itmk4='$itmk4',itme4='$itme4',itms4='$itms4',itmsk4='$itmsk4',itm5='$itm5',itmk5='$itmk5',itme5='$itme5',itms5='$itms5',itmsk5='$itmsk5',itm6='$itm6',itmk6='$itmk6',itme6='$itme6',itms6='$itms6',itmsk6='$itmsk6' where pid='$name'");
	if(!$db->affected_rows()){
		echo "无法修改角色 $name";
	} else {
		adminlog('editnpc',$name);
		////addnews($now,'editpc',$name);
		echo "角色 $name 的属性被修改了";
	}
} else {
	$start = getstart($start,$pagemode);
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE type>'0' ORDER BY pid LIMIT $start,$showlimit");
	if(!$db->num_rows($result)) {
		$notice = '没有符合条件的角色。';
	} else {
		while($npc = $db->fetch_array($result)) {
			$npcdata[] = $npc;
		}
		foreach($npcdata as $n => $npc) {
			if($npc['state']==98 || $npc['state']==99){$namecolor = 'style="color:black;"';}
			elseif( $npc['hp']<=0 ) {$namecolor = 'style="color:red;"';}
			else {$namecolor = '';}
			$listhtm .= "<tr><td><input type=\"checkbox\" name=\"npc_$n\" value=\"{$npc['pid']}\"></td><td $namecolor>{$npc['name']}</td><td>{$typeinfo[$npc['type']]}</td><td>{$sexinfo[$npc['gd']]}</td><td>{$npc['sNo']}</td><td>{$npc['lvl']}</td><td>{$npc['teamID']}</td><td>{$npc['state']}</td><td>{$npc['sp']}/{$npc['msp']}</td><td>{$npc['hp']}/{$npc['mhp']}</td><td>{$clubinfo[$npc['club']]}</td><td>{$npc['killnum']}</td><td>{$npc['money']}</td><td>{$npc['wp']}/{$npc['wk']}/{$npc['wg']}/{$npc['wc']}/{$npc['wd']}</td><td>{$npc['wep']}/{$npc['wepe']}/{$npc['weps']}</td><td>{$npc['arb']}/{$npc['arbe']}/{$npc['arbs']}</td><td>{$npc['att']}</td><td>{$npc['def']}</td></tr>";
		}
	}
	pclist($listhtm,$notice,$start);
}


function pclist($npclist='',$notice='',$start=0) {
	global $showlimit;
	$st0=$start;
	$st1=$start+$showlimit;
	echo <<<EOT
<form method="post" name="npcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="hidden" name="command" value="npcmng">
<input type="hidden" name="start" value="$start">
<input type="hidden" name="pagemode" value="down">
<br><br>
$notice
<br>
<input type="button" value="上一页" onclick="javascript:document.npcmng.pagemode.value='up';document.npcmng.submit();">
<span>第{$st0}至{$st1}条结果</span>
<input type="button" value="下一页" onclick="javascript:document.npcmng.pagemode.value='down';document.npcmng.submit();">
<table class="admin"><tr><td>选</td><td>姓名</td><td>类型</td><td>性别</td><td>学号</td><td>等级</td><td>队伍</td><td>状态</td><td>体力</td><td>生命</td><td>社团</td><td>杀人数</td><td>金钱</td><td>熟练</td><td>武器</td><td>防具</td><td>攻击力</td><td>防御力</td></tr>
$npclist
</table>
<input type="radio" name="subcmd" id="edit" value="edit"><a onclick=sl('edit'); href="javascript:void(0);" >查看/修改</a>
<input type="radio" name="subcmd" id="live" value="live"><a onclick=sl('live'); href="javascript:void(0);" >复活</a>
<input type="radio" name="subcmd" id="kill" value="kill"><a onclick=sl('kill'); href="javascript:void(0);" >杀死</a>
<input type="radio" name="subcmd" id="del" value="del"><a onclick=sl('del'); href="javascript:void(0);" >清除</a><br>
<input type="submit" value="提交">
</form>
EOT;
}



?>