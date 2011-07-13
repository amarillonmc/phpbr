<?php

define('CURSCRIPT', 'game');

require './include/common.inc.php';
//$t_s=getmicrotime();
//require_once GAME_ROOT.'./include/JSON.php';
require './include/game.func.php';
require './include/display.func.php';
require config('combatcfg',$gamecfg);

active_AI();
//判断是否进入游戏
if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 

$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");

if(!$db->num_rows($result)) { header("Location: valid.php");exit(); }

$pldata = $db->fetch_array($result);

//判断是否密码错误
if($pldata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$tablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	if($password == $cpass) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}

//判断玩家是否已经死亡
if(($pldata['hp'] <= 0)||($gamestate === 0)) {
	$gamedata['url'] = 'end.php';
	ob_clean();
	$jgamedata = compatible_json_encode($gamedata);
//	$json = new Services_JSON();
//	$jgamedata = $json->encode($gamedata);
	echo $jgamedata;
	ob_end_flush();
	exit();
}
//初始化同伴
//if($pldata['company'] > 0 && $companysystem){ 
//	$company = $pldata['company'];
//	$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid = '$company' AND hp > 0 AND type = 100");
//	if(!$db->num_rows($result)) {
//		$pldata['company'] = 0;
//	}
//	else{
//		$cpdata = $db->fetch_array($result);
//		if($cpdata['company'] != $pldata['pid']){
//			unset($cpdata);
//			$pldata['company'] = 0;
//		}
//	}
//}
if($companysystem && $pldata['company'] > 0){ 
	$company = $pldata['company'];
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid = '$company' AND type = 100");
	if(!$db->num_rows($result)) {
		$pldata['company'] = 0;
	}
	else{
		$cpdata = $db->fetch_array($result);
		if($cpdata['company'] != $pldata['pid']){
			unset($cpdata);
			$pldata['company'] = 0;
		}
	}
	if($pldata['company'] != 0){
		if($ctrl == 'cp' && $cpdata['hp']>0){
			$pdata = $cpdata;$cdata = $pldata;
		}elseif($ctrl == 'cp' && $cpdata['hp'] == 0){
			gsetcookie('ctrl','pl');
			$pdata = $pldata;$cdata = $cpdata;
		}else{
			$pdata = $pldata;$cdata = $cpdata;
		}
	}else{
		$pdata = $pldata;
	}
}else{
	$pdata = $pldata;
}

if($command == 'switch'){//交换角色
	if($companysystem){
		if($pdata['company'] && $cdata['hp'] > 0){
			if($ctrl == 'pl'){
				$ctrl = 'cp';
				gsetcookie('ctrl','cp');
			}else{
				$ctrl = 'pl';
				gsetcookie('ctrl','pl');
			}
			$temp = $pdata;
			$pdata = $cdata;
			$cdata = $temp;
			unset($temp);			
		}else{
			$log .= '指令错误、同伴不存在或者同伴已经死亡，无法交换角色！<br>';
		}
	}else{
		$log .= '指令错误，同伴系统未开启。<br>';
	}
}
//初始化各变量
//if($pldata['company'] && $companysystem){
//	if($ctrl == 'cp'){
//		$pdata = $cpdata; $cdata = $pldata;
//	}else{
//		$pdata = $pldata; $cdata = $cpdata;
//	}
//}else{
//	$pdata = $pldata;
//}
$pid = $pdata['pid'];
//extract($pdata,EXTR_REFS);
$log = $main = '';
//$gamedata = array();

//显示枪声信息
$noise = get_noise($pid);
//读取玩家互动信息
$result = $db->query("SELECT time,log FROM {$tablepre}log WHERE toid = '$pid' ORDER BY time,lid");
while($logtemp = $db->fetch_array($result)){
	$log .= date("H:i:s",$logtemp['time']).'，'.$logtemp['log'].'<br />';
}
$db->query("DELETE FROM {$tablepre}log WHERE toid = '$pid'");
init_battlefield();
$pdata['mapprop'] = player_property($pdata);
//HP和SP回复道具的判断
if(isset($pdata['mapprop']['HH']) || isset($pdata['mapprop']['HS'])){
	if($hp < $mhp && isset($pdata['mapprop']['HH'])){
		$pmh = $pdata['mapprop']['HH'];
		$hhitm = $pdata[$pmh[0]];$hhitmnp = $pdata[$pmh[0].'np'];
		if($hhitmnp <=0){$hhitmnp = 1;}
		$log .= "在<span class=\"yellow\">$hhitm</span>的作用下，".set_rest(2,$pdata,1,$hhitmnp);
	}
	if($sp < $msp && isset($pdata['mapprop']['HS'])){
		$pms = $pdata['mapprop']['HS'];
		$hsitm = $pdata[$pms[0]];$hsitmnp = $pdata[$pms[0].'np'];
		if($hsitmnp <=0){$hsitmnp = 1;}		
		$log .= "在<span class=\"yellow\">$hsitm</span>的作用下，".set_rest(1,$pdata,1,$hsitmnp);
	}
}

if($command != 'switch'){
	//判断冷却时间是否过去
	if($coldtimeon){
		$cdover = $pdata['lastcmd']*1000 + $pdata['cdmsec'] + $pdata['cdtime'];
		$nowmtime = floor(getmicrotime()*1000);
		$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
	}
	$cannot_cmd = false;
	if($coldtimeon && $rmcdtime > 0 && (strpos($command,'move')===0 || strpos($command,'search')===0 || strpos($command,'itm')===0)){
		$log .= '<span class="yellow">冷却时间尚未结束！</span><br>';
		$cannot_cmd = true;
	}
	if($pdata['inf']){
		$cannot_cmd = check_cannot_cmd($pdata,1);
	}
	if($cannot_cmd){
		$mode = 'command';
	}else{
		
		//进入指令判断
		if($mode !== 'combat' && $mode !== 'corpse' && $mode !== 'senditem'){
			$pdata['bid'] = 0;
		}
		
		if($command == 'menu') {
			$mode = 'command';
		} elseif($mode == 'command') {
			if($command == 'move') {
				include_once GAME_ROOT.'./include/game/search.func.php';
				move($moveto);
				if($coldtimeon){$cmdcdtime=$movecoldtime;}
			} elseif($command == 'search') {
				include_once GAME_ROOT.'./include/game/search.func.php';
				search();
				if($coldtimeon){$cmdcdtime=$searchcoldtime;}
			} elseif(strpos($command,'itm') === 0) {
				include_once GAME_ROOT.'./include/game/item.func.php';
				$item = substr($command,3);
				itemuse($item);
				if($coldtimeon){$cmdcdtime=$itemusecoldtime;}
			} elseif(strpos($command,'rest') === 0) {
				if($command=='rest3' && !in_array('HOSPITAL',$mapdata[$pdata['pls']]['function'])){
					$log .= '<span class="yellow">你所在的位置并非医院，不能静养！</span><br>';
				}else{
					$pdata['state'] = substr($command,4,1);
					$mode = 'rest';
				}
			} elseif($command == 'itemmain') {
				$mode = $itemcmd;
				$mval = 'itemmain';
				$cval = substr($itemcmd,4);
				if($itemcmd == 'itemoff'){
					$dlist = Array('wep','arb','arh','arb','ara','arf','art');
					$inotice = '你想卸下什么？';
				}elseif($itemcmd == 'itemdrop'){
					$dlist = Array('wep','arb','arh','arb','ara','arf','art','itm1','itm2','itm3','itm4','itm5','itm6');
					$inotice = '你想丢弃什么？';
				}
			} elseif($command == 'special') {
				if($sp_cmd == 'sp_adtsk')
				{
					include_once GAME_ROOT.'./include/game/special.func.php';
					adtsk();
					$mode = 'command';
				}elseif($sp_cmd == 'sp_tech'){
					$newtech = show_new_tech($pdata,1);
					$mode = $sp_cmd;
				}elseif($sp_cmd == 'sp_poison'){
					$mval = 'special';
					$mode = $sp_cmd;
				}else{
					$mode = $sp_cmd;
				}
				
			} elseif($command == 'team') {
				if($teamcmd == 'teamquit') {
					include_once GAME_ROOT.'./include/game/team.func.php';
					teamquit();
				} elseif($teamcmd !== 'main') {
					$mode = 'team';
				}
			} elseif($command == 'company') {
				if($cp_cmd == 'senditem'){
					$inotice = '你想递送什么物品给同伴？';
					$mode = 'senditem';
					$mval = 'cpitem';
					$cval = '';
					$dlist = Array('itm1','itm2','itm3','itm4','itm5','itm6');
				}				
			}
		} elseif($mode == 'item') {
			include_once GAME_ROOT.'./include/game/item2.func.php';
			$item = substr($command,3);
			$usemode($item);
		} elseif($mode == 'itemmain') {
			include_once GAME_ROOT.'./include/game/itemmain.func.php';
			if($command == 'itemget') {
				itemget();
			} elseif($command == 'itemadd') {
				itemadd();
			} elseif($command == 'itemmerge') {
				if($merge2 == 'n'){itemadd();}
				else{itemmerge($merge1,$merge2);}
			} elseif(strpos($command,'drop') === 0) {
				$drop_item = substr($command,4);
				itemdrop($drop_item);
			} elseif(strpos($command,'off') === 0) {
				$off_item = substr($command,3);
				itemoff($off_item);
				//itemadd();
			} elseif(strpos($command,'swap') === 0) {
				$swap_item = substr($command,4);
				itemdrop($swap_item);
				itemadd();
			} elseif($command == 'itemmix') {
				itemmix($mix1,$mix2,$mix3);
			}
		} elseif($mode == 'special') {
			include_once GAME_ROOT.'./include/game/special.func.php';
			if(strpos($command,'pose') === 0) {
				$npose = substr($command,4,1);
				if($npose == '6'){
					if($companysystem){
						if($pdata['pls'] != $cdata['pls']){
		//					$canmoveto = Array_keys(get_neighbor_map($cdata['pls']);
		//					if(in_array($pdata['pls']),$canmoveto){
		//						$cdata['pls'] = $pdata['pls']; 
		//					}
							$log .= "同伴不在同一地图，无法做出<span class=\"yellow\">{$poseinfo[$npose]}</span>。<br> ";
							
						}else{
							$pdata['pose'] = $npose;
							$log .= "基础姿态变为<span class=\"yellow\">{$poseinfo[$npose]}</span>。<br> ";
						}		
					}else{
						$log .= "指令错误，同伴系统未开启。<br> ";
					}
				}else{
					$pdata['pose'] = $npose;
					$log .= "基础姿态变为<span class=\"yellow\">{$poseinfo[$npose]}</span>。<br> ";
				}
				
				$mode = 'command';
				

			} elseif(strpos($command,'tac') === 0) {
				$pdata['tactic'] = substr($command,3,1);
				$log .= "应战策略变为<span class=\"yellow\">{$tacinfo[$pdata['tactic']]}</span>。<br> ";
				$mode = 'command';
			} elseif(strpos($command,'inf') === 0) {
				$infpos = substr($command,3,1);
				chginf($infpos);
			} elseif(strpos($command,'chkp') === 0) {
				$itmn = substr($command,4,1);
				chkpoison($itmn);
			} elseif(strpos($command,'shop') === 0) {
				$shop = substr($command,4,2);
				shoplist($shop);
			} elseif(strpos($command,'tech') === 0) {
				$learntech = substr($command,4,3);
				learn_tech($learntech);
			}
		} elseif(strpos($mode,'senditem') === 0 || strpos($mode,'cpitem') === 0) {
			include_once GAME_ROOT.'./include/game/battle.func.php';
			if($mode=='cpitem'){
				senditem('c');
			}else{
				senditem('t');
			}
			
		} elseif($mode == 'combat') {
			include_once GAME_ROOT.'./include/game/combat.func.php';
			combat(1,$command);
		} elseif($mode == 'rest') {
			$log .= set_rest($command,$pdata,1);//set_rest($command,$pdata,1);
		} elseif($mode == 'corpse' && strpos($command,'corpse')===0) {
			$itmpos = substr($command,6);
			include_once GAME_ROOT.'./include/game/itemmain.func.php';
			getcorpse($itmpos);
		} elseif($mode == 'team') {
			include_once GAME_ROOT.'./include/game/team.func.php';
			$command($nteamID,$nteamPass);
		} elseif($mode == 'shop') {
			if(in_array('SHOP',$mapdata[$pdata['pls']]['function'])){
				if($command == 'shop') {
					$mode = 'sp_shop';
				} else {
					include_once GAME_ROOT.'./include/game/itemmain.func.php';
					itembuy($command,$shoptype,$buynum);
				}
			}else{
				$log .= '<span class="yellow">你所在的地区没有商店。</span><br />';
				$mode = 'command';
			}
		} elseif($mode == 'deathnote') {
			if($dnname){
				include_once GAME_ROOT.'./include/game/item2.func.php';
				deathnote($item,$dnname,$dndeath,$pdata['name']);
			} else {
				$log .= '嗯，暂时还不想杀人。<br>你合上了■DeathNote■。<br>';
				$mode = 'command';
			}
		} elseif($mode == 'hack'){
			if($command !== 'back'){
				include_once GAME_ROOT.'./include/game/item2.func.php';
				hack($item,$command);
			}else{
				$log .= '你决定暂不hack禁区。<br>';
				$mode = 'command';
			}
		} else {
			$mode = 'command';
		}
		
		//指令执行完毕，更新冷却时间
		if($coldtimeon && isset($cmdcdtime)){	
			$nowmtime = floor(getmicrotime()*1000);
			$pdata['lastcmd'] = floor($nowmtime/1000);	
			$pdata['cdmsec'] = $nowmtime % 1000;
			$pdata['cdtime'] = $cmdcdtime;
			$rmcdtime = $cmdcdtime;
		}else{
			$pdata['lastcmd'] = $now;
		}
	}
	
	//显示指令执行结果
	$gamedata['notice'] = ob_get_contents();
	if($coldtimeon && $showcoldtimer && $rmcdtime){
		$gamedata['timer'] = $rmcdtime;
	}
	if($hp > 0 && $coldtimeon && $showcoldtimer && $rmcdtime){
		$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow\"></span>秒<br>";
	}
}


init_displaydata($pdata);
init_profile($pdata);
init_itemwords($pdata);
init_techniquewords($pdata);
$nmap = get_neighbor_map($pdata['pls']);
$gst = $gstate[$gamestate];
if($pdata['hp'] <= 0) {
	gsetcookie('ctrl','pl');
	ob_start();
	include template('death');
	$gamedata['cmd'] = ob_get_contents();
	ob_end_clean();
} else{
	if(show_new_tech($pdata) && $mode != 'sp_tech'){
		$log .= '<span class="yellow">你能学习新的技能！</span>';
		$canlearntech = true;
	}else{
		$canlearntech = false;
	}
	
	if(!$cmd) {
		ob_start();
		if($mode == 'itemdrop' || $mode == 'itemoff' || $mode == 'senditem'){
			include template('itemmenu');
		}elseif($mode&&file_exists(GAME_ROOT.TPLDIR.'/'.$mode.'.htm')) {
			include template($mode);
		} else {
			include template('command');
		}
		$gamedata['cmd'] = ob_get_contents();
		ob_end_clean();
	} else {
		$gamedata['cmd'] = $cmd;
	}
}
player_save($pdata);

if($url){$gamedata['url'] = $url;}
$gamedata['pls'] = $mapdata[$pdata['pls']]['name'];
$gamedata['weather'] = $wthdata[$weather]['name'];
//$gamedata['cteam'] = $teamID;
//$gamedata['cpls'] = $pdata['pls'];
$gamedata['anum'] = $alivenum;
ob_start();
include template('profile');
$gamedata['profile'] = ob_get_contents();
ob_end_clean();
if(!$main){
	ob_start();
	include template('main');
	$gamedata['main'] = ob_get_contents();
	ob_end_clean();
}else{
	$gamedata['main'] = $main;
}
ob_start();
include template('eqp');
$gamedata['eqp'] = ob_get_contents();
ob_end_clean();
$gamedata['noise'] = $noise;
$gamedata['gst'] = $gst;
$gamedata['log'] = $log;

//foreach($gamedata as $k => $v){
//	$w .= "{ $k } => { $v };\n\r";
//}
//writeover('a.txt',$w);
ob_clean();
$jgamedata = compatible_json_encode($gamedata);
//$json = new Services_JSON();
//$jgamedata = $json->encode($gamedata);
echo $jgamedata;
ob_end_flush();
//$t_e=getmicrotime();
//putmicrotime($t_s,$t_e,'cmd_time','');

?>