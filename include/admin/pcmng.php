<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 5){
	exit($_ERROR['no_power']);
}

if($subcmd == 'kill') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'pc_'.$i})) {
			$pclist[] = ${'pc_'.$i};
		}
	}
	foreach($pclist as $n => $name) {
		$db->query("UPDATE {$tablepre}players SET hp='0',state='15' WHERE name='$name'AND type='0' AND hp>'0' AND state<'10'");
		if($db->affected_rows()){
			adminlog('killpc',$name,$adminmsg);
			echo " 角色 $name 被杀死。<br>";
			naddnews($now,'death15',$name);
			$alivenum--;
			$deathnum++;
			save_gameinfo();
		} else {
			echo "无法杀死角色 $name 。";
		}
	}
} elseif($subcmd == 'live') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'pc_'.$i})) {
			$pclist[] = ${'pc_'.$i};
		}
	}
	foreach($pclist as $n => $name) {
		$db->query("UPDATE {$tablepre}players SET hp='100',state='0' WHERE name='$name'AND type='0' AND (hp<='0' OR state>='10')");
		if($db->affected_rows()){
			adminlog('livepc',$name,$adminmsg);
			echo " 角色 $name 被复活。<br>";
			naddnews($now,'alive',$name);
			$alivenum++;
			$deathnum--;
			save_gameinfo();
		} else {
			echo "无法复活角色 $name 。";
		}
	}
} elseif($subcmd == 'del') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'pc_'.$i})) {
			$pclist[] = ${'pc_'.$i};
		}
	}
	foreach($pclist as $n => $name) {
		$result = $db->query("SELECT hp,state FROM {$tablepre}players WHERE name='$name'AND type='0'");
		$pc = $db->fetch_array($result);
		if(!$pc) {
			echo "无法清除角色 $name 。";
		} elseif(($pc['hp']>0)&&($pc['state']<10)) {
			$db->query("UPDATE {$tablepre}players SET hp=0,state=99 WHERE name='$name'AND type=0");
			adminlog('delpc',$name,$adminmsg);
			echo " 角色 $name 被清除了。<br>";
			naddnews($now,'death16',$name);
			$alivenum--;
			$deathnum++;
			save_gameinfo();
		} else {
			$db->query("UPDATE {$tablepre}players SET state=99 WHERE name='$name'AND type=0");
			adminlog('delcp',$name,$adminmsg);
			echo " 角色 $name 的尸体被清除了。<br>";
			naddnews($now,'delcp',$name);
		}
	}
} elseif($subcmd == 'edit') {
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'pc_'.$i})) {
			$name = ${'pc_'.$i};
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$name'AND type='0'");
			$pc = $db->fetch_array($result);
			if(!$pc) {
				echo "找不到角色 $name 。";
			} else {
				echo <<<EOT
<form method="post" name="pcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="hidden" name="command" value="pcmng">
<table class="admin">
<tr>
	<td>姓名</td><td><input type="hidden" name="name" value="{$pc['name']}">{$pc['name']}</td>
	<td>性别</td><td><input size="15" type="text" name="gd" value="{$pc['gd']}" maxlength="20"></td>
	<td>学号</td><td><input size="15" type="text" name="sNo" value="{$pc['sNo']}" maxlength="20"></td>
	<td>头像</td><td><input size="15" type="text" name="icon" value="{$pc['icon']}" maxlength="20"></td>
	<td>社团</td><td><input size="15" type="text" name="club" value="{$pc['club']}" maxlength="20"></td>
	<td>状态</td><td><input size="15" type="text" name="state" value="{$pc['state']}" maxlength="20"></td>
</tr>
<tr>
	<td>等级</td><td><input size="15" type="text" name="lvl" value="{$pc['lvl']}" maxlength="20"></td>
	<td>经验</td><td><input size="15" type="text" name="exp" value="{$pc['exp']}" maxlength="20"></td>
	<td>金钱</td><td><input size="15" type="text" name="money" value="{$pc['money']}" maxlength="20"></td>
	<td>位置</td><td><input size="15" type="text" name="pls" value="{$pc['pls']}" maxlength="20"></td>
	<td>基础姿态</td><td><input size="15" type="text" name="pose" value="{$pc['pose']}" maxlength="20"></td>
	<td>应战策略</td><td><input size="15" type="text" name="tactic" value="{$pc['tactic']}" maxlength="20"></td>
</tr>
<tr>
	<td>基础攻击</td><td><input size="15" type="text" name="att" value="{$pc['att']}" maxlength="20"></td>
	<td>基础防御</td><td><input size="15" type="text" name="def" value="{$pc['def']}" maxlength="20"></td>
	<td>生命</td><td><input size="15" type="text" name="hp" value="{$pc['hp']}" maxlength="20"></td>
	<td>最大生命</td><td><input size="15" type="text" name="mhp" value="{$pc['mhp']}" maxlength="20"></td>
	<td>体力</td><td><input size="15" type="text" name="sp" value="{$pc['sp']}" maxlength="20"></td>
	<td>最大体力</td><td><input size="15" type="text" name="msp" value="{$pc['msp']}" maxlength="20"></td>
</tr>
<tr>
	
	<td>对手</td><td><input size="15" type="text" name="bid" value="{$pc['bid']}" maxlength="20"></td>
	<td>受伤</td><td><input size="15" type="text" name="inf" value="{$pc['inf']}" maxlength="20"></td>
	<td>怒气</td><td><input size="15" type="text" name="rage" value="{$pc['rage']}" maxlength="20"></td>
	<td>杀人数</td><td><input size="15" type="text" name="killnum" value="{$pc['killnum']}" maxlength="20"></td>
	<td>队伍名称</td><td><input size="15" type="text" name="teamID" value="{$pc['teamID']}" maxlength="20"></td>
	<td>队伍密码</td><td><input size="15" type="text" name="teamPass" value="{$pc['teamPass']}" maxlength="20"></td>
</tr>
<tr>
	<td>殴熟</td><td><input size="15" type="text" name="wp" value="{$pc['wp']}" maxlength="20"></td>
	<td>斩熟</td><td><input size="15" type="text" name="wk" value="{$pc['wk']}" maxlength="20"></td>
	<td>枪熟</td><td><input size="15" type="text" name="wg" value="{$pc['wg']}" maxlength="20"></td>
	<td>投熟</td><td><input size="15" type="text" name="wc" value="{$pc['wc']}" maxlength="20"></td>
	<td>爆熟</td><td><input size="15" type="text" name="wd" value="{$pc['wd']}" maxlength="20"></td>
	<td>灵熟</td><td><input size="15" type="text" name="wf" value="{$pc['wf']}" maxlength="20"></td>
</tr>
</table>
<table class="admin">
<tr>
	<td>武器</td><td><input size="15" type="text" name="wep" value="{$pc['wep']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="wepk" value="{$pc['wepk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="wepe" value="{$pc['wepe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="weps" value="{$pc['weps']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="wepsk" value="{$pc['wepsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="wepnp" value="{$pc['wepnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>身体防具</td><td><input size="15" type="text" name="arb" value="{$pc['arb']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arbk" value="{$pc['arbk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arbe" value="{$pc['arbe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arbs" value="{$pc['arbs']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arbsk" value="{$pc['arbsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="arbnp" value="{$pc['arbnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>头部防具</td><td><input size="15" type="text" name="arh" value="{$pc['arh']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arhk" value="{$pc['arhk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arhe" value="{$pc['arhe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arhs" value="{$pc['arhs']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arhsk" value="{$pc['arhsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="arhnp" value="{$pc['arhnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>腕部防具</td><td><input size="15" type="text" name="ara" value="{$pc['ara']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arak" value="{$pc['arak']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arae" value="{$pc['arae']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="aras" value="{$pc['aras']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arask" value="{$pc['arask']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="aranp" value="{$pc['aranp']}" maxlength="20"></td>
</tr>
<tr>
	<td>足部防具</td><td><input size="15" type="text" name="arf" value="{$pc['arf']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="arfk" value="{$pc['arfk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arfe" value="{$pc['arfe']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arfs" value="{$pc['arfs']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="arfsk" value="{$pc['arfsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="arfnp" value="{$pc['arfnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>饰物</td><td><input size="15" type="text" name="art" value="{$pc['art']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="artk" value="{$pc['artk']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="arte" value="{$pc['arte']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="arts" value="{$pc['arts']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="artsk" value="{$pc['artsk']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="artnp" value="{$pc['artnp']}" maxlength="20"></td>
</tr>
<tr>
	<td>拾取道具</td><td><input size="15" type="text" name="itm0" value="{$pc['itm0']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk0" value="{$pc['itmk0']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme0" value="{$pc['itme0']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms0" value="{$pc['itms0']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk0" value="{$pc['itmsk0']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp0" value="{$pc['itmnp0']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹1</td><td><input size="15" type="text" name="itm1" value="{$pc['itm1']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk1" value="{$pc['itmk1']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme1" value="{$pc['itme1']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms1" value="{$pc['itms1']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk1" value="{$pc['itmsk1']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp1" value="{$pc['itmnp1']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹2</td><td><input size="15" type="text" name="itm2" value="{$pc['itm2']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk2" value="{$pc['itmk2']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme2" value="{$pc['itme2']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms2" value="{$pc['itms2']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk2" value="{$pc['itmsk2']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp2" value="{$pc['itmnp2']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹3</td><td><input size="15" type="text" name="itm3" value="{$pc['itm3']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk3" value="{$pc['itmk3']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme3" value="{$pc['itme3']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms3" value="{$pc['itms3']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk3" value="{$pc['itmsk3']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp3" value="{$pc['itmnp3']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹4</td><td><input size="15" type="text" name="itm4" value="{$pc['itm4']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk4" value="{$pc['itmk4']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme4" value="{$pc['itme4']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms4" value="{$pc['itms4']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk4" value="{$pc['itmsk4']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp4" value="{$pc['itmnp4']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹5</td><td><input size="15" type="text" name="itm5" value="{$pc['itm5']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk5" value="{$pc['itmk5']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme5" value="{$pc['itme5']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms5" value="{$pc['itms5']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk5" value="{$pc['itmsk5']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp5" value="{$pc['itmnp5']}" maxlength="20"></td>
</tr>
<tr>
	<td>包裹6</td><td><input size="15" type="text" name="itm6" value="{$pc['itm6']}" maxlength="30"></td>
	<td>类型</td><td><input size="15" type="text" name="itmk6" value="{$pc['itmk6']}" maxlength="20"></td>
	<td>效果</td><td><input size="15" type="text" name="itme6" value="{$pc['itme6']}" maxlength="20"></td>
	<td>耐久</td><td><input size="15" type="text" name="itms6" value="{$pc['itms6']}" maxlength="20"></td>
	<td>属性</td><td><input size="15" type="text" name="itmsk6" value="{$pc['itmsk6']}" maxlength="20"></td>
	<td>数字</td><td><input size="15" type="text" name="itmnp6" value="{$pc['itmnp6']}" maxlength="20"></td>
</tr>
</table>
<input type="hidden" name="subcmd" value="back">
<input type="button" value="修改属性" onclick="javascript:document.pcmng.subcmd.value = 'edit2';document.pcmng.submit();">
<input type="button" value="返回" onclick="javascript:document.pcmng.submit();">
</form>
EOT;
			}
		break;
		}
	}
} elseif($subcmd == 'edit2') {
	$db->query("UPDATE {$tablepre}players SET gd='$gd',icon='$icon',club='$club',state='$state',sNo='$sNo',hp='$hp',mhp='$mhp',sp='$sp',msp='$msp',att='$att',def='$def',pls='$pls',lvl='$lvl',exp='$exp',money='$money',bid='$bid',inf='$inf',rage='$rage',pose='$pose',tactic='$tactic',killnum='$killnum',wp='$wp',wk='$wk',wg='$wg',wc='$wc',wd='$wd',wf='$wf',teamID='$teamID',teamPass='$teamPass',wep='$wep',wepk='$wepk',wepe='$wepe',weps='$weps',wepsk='$wepsk',arb='$arb',arbk='$arbk',arbe='$arbe',arbs='$arbs',arbsk='$arbsk',arh='$arh',arhk='$arhk',arhe='$arhe',arhs='$arhs',arhsk='$arhsk',ara='$ara',arak='$arak',arae='$arae',aras='$aras',arask='$arask',arf='$arf',arfk='$arfk',arfe='$arfe',arfs='$arfs',arfsk='$arfsk',art='$art',artk='$artk',arte='$arte',arts='$arts',artsk='$artsk',itm0='$itm0',itmk0='$itmk0',itme0='$itme0',itms0='$itms0',itmsk0='$itmsk0',itm1='$itm1',itmk1='$itmk1',itme1='$itme1',itms1='$itms1',itmsk1='$itmsk1',itm2='$itm2',itmk2='$itmk2',itme2='$itme2',itms2='$itms2',itmsk2='$itmsk2',itm3='$itm3',itmk3='$itmk3',itme3='$itme3',itms3='$itms3',itmsk3='$itmsk3',itm4='$itm4',itmk4='$itmk4',itme4='$itme4',itms4='$itms4',itmsk4='$itmsk4',itm5='$itm5',itmk5='$itmk5',itme5='$itme5',itms5='$itms5',itmsk5='$itmsk5',itm6='$itm6',itmk6='$itmk6',itme6='$itme6',itms6='$itms6',itmsk6='$itmsk6',wepnp = '$wepnp',arbnp = '$arbnp',arhnp = '$arhnp',aranp = '$aranp',arfnp = '$arfnp',artnp = '$artnp',itmnp0 = '$itmnp0',itmnp1 = '$itmnp1',itmnp2 = '$itmnp2',itmnp3 = '$itmnp3',itmnp4 = '$itmnp4',itmnp5 = '$itmnp5',itmnp6 = '$itmnp6' where name='$name' and type='0'");
	if(!$db->affected_rows()){
		echo "无法修改角色 $name";
	} else {
		adminlog('editpc',$name);
		naddnews($now,'editpc',$name);
		echo "角色 $name 的属性被修改了";
	}
} else {
	$start = getstart($start,$pagemode);
	$limitstr = " LIMIT $start,$showlimit";
	if(($checkmode == 'name')&&($checkinfo)) {
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE name LIKE '%{$checkinfo}%' AND type='0'".$limitstr);
	} elseif($checkmode == 'teamID') {
		if($checkinfo){
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE teamID LIKE '%".$checkinfo."%' AND type='0' ORDER BY teamID".$limitstr);
		} else {
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='0' ORDER BY teamID DESC".$limitstr);
		}
	} elseif($checkmode == 'club') {
		if($checkinfo) {
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE club='$checkinfo' AND type='0'".$limitstr);
		} else {
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='0' ORDER BY club".$limitstr);
		}
	} else {
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='0'".$limitstr);
	}

	if(!$db->num_rows($result)) {
		$notice ='没有符合条件的角色。';
	} else {
		while($pc = $db->fetch_array($result)) {
			$pcdata[] = $pc;
		}
		foreach($pcdata as $n => $pc) {
			if($pc['state']==98 || $pc['state']==99){$namecolor = 'style="color:black;"';}
			elseif( $pc['hp']<=0) {$namecolor = 'style="color:red;"';}
			else {$namecolor = '';}
			$listhtm .= "<tr><td><input type=\"checkbox\" name=\"pc_$n\" value=\"{$pc['name']}\"></td><td $namecolor>{$pc['name']}</td><td>{$sexinfo[$pc['gd']]}</td><td>{$pc['sNo']}</td><td>{$pc['lvl']}</td><td>{$pc['teamID']}</td><td>{$pc['state']}</td><td>{$pc['sp']}/{$pc['msp']}</td><td>{$pc['hp']}/{$pc['mhp']}</td><td>{$clubinfo[$pc['club']]}</td><td>{$pc['killnum']}</td><td>{$pc['money']}</td><td>{$pc['wp']}/{$pc['wk']}/{$pc['wg']}/{$pc['wc']}/{$pc['wd']}</td><td>{$pc['wep']}/{$pc['wepe']}/{$pc['weps']}</td><td>{$pc['arb']}/{$pc['arbe']}/{$pc['arbs']}</td><td>{$pc['att']}</td><td>{$pc['def']}</td></tr>";
		}
	}
	pclist($listhtm,$notice,$start);
}


function pclist($pclist='',$notice='',$start=0){
	global $showlimit;
	$st0=$start;
	$st1=$start+$showlimit;
echo <<<EOT
<form method="post" name="pcmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="hidden" name="command" value="pcmng">
<input type="radio" name="subcmd" value="find">
<input type="hidden" name="start" value="$start">
<input type="hidden" name="pagemode" value="down">
<a onclick=sl('subcmd'); href="javascript:void(0);" >按<select name="checkmode">
	<option value="name" selected>用户名<br />
	<option value="state">用户状态<br />
	<option value="teamID">队伍名称<br />
	<option value="club">社团<br />
</select>查找用户<input size="30" type="text" name="checkinfo" id="checkinfo" maxlength="30"></a><input type="submit" value="提交" onclick="javascript:document.pcmng.pagemode.value='ref'">
<br><br>
$notice
<br>
<input type="button" value="上一页" onclick="javascript:document.pcmng.pagemode.value='up';document.pcmng.submit();">
<span>第{$st0}至{$st1}条结果</span>
<input type="button" value="下一页" onclick="javascript:document.pcmng.pagemode.value='down';document.pcmng.submit();">
<table class="admin"><tr><td>选</td><td>姓名</td><td>性别</td><td>学号</td><td>等级</td><td>队伍</td><td>状态</td><td>体力</td><td>生命</td><td>社团</td><td>杀人数</td><td>金钱</td><td>熟练</td><td>武器</td><td>防具</td><td>攻击力</td><td>防御力</td></tr>
$pclist
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