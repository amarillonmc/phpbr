<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}
function get_event($kind){
	global $mode,$log,$pdata,$infdata;
	$pls = $pdata['pls'];
	$hp = & $pdata['hp'];$sp = & $pdata['sp'];$inf = & $pdata['inf'];$rage = & $pdata['rage'];
	
	if(!$kind){return false;}
	$dice1 = rand(0,5);
	$dice2 = rand(20,40);
	$flag = false;
	if($kind == 'waterdrop'){
		$flag = true;
		$log .= "糟糕，脚下滑了一下！<BR>";
		if($dice1 <= 3){
			if($sp <= $dice2){
				$dice2 = $sp-1;
			}
			$sp-=$dice2;
			$rage += 1;
			$log .= "你摔进了池里！<BR>从水池里爬出来<span class=\"red\">消耗了{$dice2}点体力</span>。<BR>";
		}else{
			$log .= "万幸，你没跌进池中。<BR>";
		}
	} elseif ($kind == 'nail'){
		$flag = true;
		$log .= "前面的地上竟然有一排钉子！<BR>";
		if($dice1 <= 2){
			$hp-=$dice2;
			$rage += 1;
			$log .= "你踩中了钉子！<BR><span class=\"red\">受到了{$dice2}点伤害</span>。<BR>";
		}elseif($dice1 <= 4){
			$inf = str_replace('f','',$inf);
			$inf .= 'f';
			$rage += 1;
			$log .= "你踩中了钉子！<BR><span class=\"red\">足部受伤了！</span>。<BR>";
		}else{
			$log .= "幸好，你敏捷地避开了所有钉子。<BR>";
		}
	} elseif ($kind == 'clow'){
		$flag = true;
		$log .= "突然，一大群乌鸦向你扑来！<BR>";
		if($dice1 <= 2){
			$hp-=$dice2;
			$rage += 1;
			$log .= "你被乌鸦袭击！<BR><span class=\"red\">受到了{$dice2}点伤害</span>。<BR>";
		}elseif($dice1 <= 3){
			$inf = str_replace('h','',$inf);
			$inf .= 'h';
			$rage += 1;
			$log .= "你被乌鸦袭击！<BR><span class=\"red\">头部受伤了！</span>。<BR>";
		}else{
			$log .= "不过，你击退了乌鸦群。<BR>";
		}
	} elseif ($kind == 'gradius'){
		$flag = true;
		global $gamestate;
		if($gamestate < 50){
			$log .= "隶属于时空部门G的特殊部队『天使』正在实弹演习！<BR>你被卷入了弹幕中！<BR>";
			if($dice1 <= 1){
				$log .="在弹幕的狂风中，你有惊无险地回避着弹幕，总算擦弹成功了。<BR>";
				if($dice2 == 40){
					$log .= "咦，头顶上……好像有一名少女被弹幕击中了……？<BR>“对不起、对不起！”伴随着焦急的道歉声，少女以及她乘坐的机体向你笔直坠落下来。<br>你还来不及反应，重达数十吨的机体便直接落在了你的头上。<br>";
					set_death($GLOBALS['pdata'],'gradius');
					return;
				}
			}
			else{
				$log = ($log . "在弹幕的狂风中，你徒劳地试图回避弹幕……<BR>擦弹什么的根本做不到啊！<BR>你被少女们打成了筛子！<BR>");
				global $infwords;
				$infcache = '';
				foreach(Array('h','b','a','f') as $value){
					$dice3=rand(0,10);
					if($dice3<=6){
						$inf = str_replace($value,'',$inf);
						$infcache .= $value;
						$log .= "<span class=\"red\">弹幕造成你{$infdata[$value]['name']}了！</span><br />";
					}
				}
				if(empty($infcache)){
					$inf = str_replace('b','',$inf);
					$inf .= 'b';
					$log .= "<span class=\"red\">弹幕造成你胸部受伤了！</span><br />";
				} else {$inf .= $infcache;}
	//			$inf = str_replace('h','',$inf);
	//			$inf = str_replace('b','',$inf);
	//			$inf = str_replace('a','',$inf);
	//			$inf = str_replace('f','',$inf);
	//			$inf = ($inf . 'hbaf');
				if($dice2 >= 39){
					$log .= "并且，少女们的弹幕击中了要害！<BR><span class=\"red\">你感觉小命差点就交代在这里了</span>。<BR>";
					$hp = 1;
				}
				elseif($dice2 >= 36){
					$log .="并且，黑洞激光造成你<span class=\"clan\">冻结</span>了！<BR>";
					$inf = str_replace('i','',$inf);
					$inf .= 'i';
				}
				elseif($dice2 >= 32){
					$log .= "并且，环形激光导致你<span class=\"red\">烧伤</span>了！<BR>";
					$inf = str_replace('u','',$inf);
					$inf .= 'u';
				}
				elseif($dice2 >= 27){
					$log .= "并且，精神震荡弹导致你<span class=\"yellow\">全身麻痹</span>了！<BR>";
					$inf = str_replace('e','',$inf);
					$inf .= 'e';
				}
				elseif($dice2 >= 23){
					$log .= "并且，音波装备导致你<span class=\"grey\">混乱</span>了！<BR>";
					$inf = str_replace('w','',$inf);
					$inf .= 'w';
				}
				else{
					$log .= "并且，干扰用强袭装备导致你<span class=\"purple\">中毒</span>了！<BR>";
					$inf = str_replace('p','',$inf);
					$inf .= 'p';
				}
				$log .= "你遍体鳞伤、连滚带爬地逃走了。<BR>";
			}
		} else {
			$log .= "特殊部队『天使』的少女们不知道去了哪里。<BR>";
		}
	}
	
	if($hp<=0){
		//include_once GAME_ROOT . './include/state.func.php';
		set_death($GLOBALS['pdata'],'event');
	}
	return $flag;
}

?>