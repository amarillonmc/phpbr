<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}


function  nparse_news($start = 0, $range = 0  ){//$type = '') {
	global $week,$nowep,$db,$tablepre,$lwinfo,$mapdata,$wthdata,$typeinfo,$infdata,$newslimit;
	//$file = $file ? $file : $newsfile;	
	//$ninfo = openfile($file);
	$range = $range == 0 ? $newslimit : $range ;
	$result = $db->query("SELECT * FROM {$tablepre}newsinfo ORDER BY nid DESC LIMIT $start,$range");
	//$r = sizeof($ninfo) - 1;
//	$rnum=$db->num_rows($result);
//	if($range && ($range <= $rnum)) {
//		$nnum = $range;
//	} else{
//		$nnum = $rnum;
//	}
	$newsinfo = '<ul>';
	$nday = 0;
	//for($i = $start;$i <= $r;$i++) {
	//for($i = 0;$i < $nnum;$i++) {
	while($news0=$db->fetch_array($result)) {
		//$news0=$db->fetch_array($result);
		$time=$news0['time'];$news=$news0['news'];$a=$news0['a'];$b=$news0['b'];$c=$news0['c'];$d=$news0['d'];$e=$news0['e'];
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$time));
		if($day != $nday) {
			$newsinfo .= "<span class=\"evergreen\"><B>{$month}月{$day}日(星期$week[$wday])</B></span><br>";
			$nday = $day;
		}

		if($news == 'gameready') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">虚拟战场初始化完毕</span><br>\n";
		} elseif($news == 'newgame') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回ACFUN大逃杀开始了</span><br>\n";
		} elseif($news == 'gameover') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回ACFUN大逃杀结束了</span><br>\n";
		} elseif($news == 'newpc') {
			$ip = explode('.',$c);
			$ip = $ip[0].'.'.$ip[1].'.*.*';
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}({$b})进入了虚拟战场 (IP：{$ip})</span><br>\n";
		} elseif($news == 'teammake') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$b}创建了队伍{$a}</span><br>\n";
		} elseif($news == 'teamjoin') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$b}加入了队伍{$a}</span><br>\n";
		} elseif($news == 'teamquit') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$b}退出了队伍{$a}</span><br>\n";
		} elseif($news == 'senditem') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}将<span class=\"yellow\">$c</span>赠送给了{$b}</span><br>\n";
		} elseif($news == 'addarea') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，增加禁区：{$a}";
//			$alist = explode('_',$a);
//			foreach($alist as $ar) {
//				$newsinfo .= $mapdata[$ar]['name'].' ';
//			}
			$newsinfo .= "<span class=\"yellow\">【天气：{$wthdata[$b]['name']}】</span><br>\n";
		} elseif($news == 'hackclean') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}启动了hack程序，全部禁区解除！</span><br>\n";
		} elseif($news == 'hackadd') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}启动了hack程序，下一次禁区提前到来了！</span><br>\n";
		} elseif($news == 'combo') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏进入连斗阶段！</span><br>\n";
		} elseif($news == 'duel') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏进入死斗阶段！</span><br>\n";
		} elseif($news == 'end0') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏出现故障，意外结束</span><br>\n";
		} elseif($news == 'end1') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">参与者全部死亡！</span><br>\n";
		} elseif($news == 'end2') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">优胜者——{$a}！</span><br>\n";
		} elseif($news == 'end3') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}解除了精神锁定，游戏紧急中止</span><br>\n";
		} elseif($news == 'end4') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">无人参加，游戏自动结束</span><br>\n";
		} elseif($news == 'end5') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}引爆了核弹，毁坏了虚拟战场</span><br>\n";
		} elseif($news == 'end6') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">本局游戏被GM中止</span><br>\n";
		} elseif($news == 'end7') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}对圣杯许下了愿望，本局游戏的存在被重置了</span><br>\n";
		} elseif(strpos($news,'death') === 0) {
			if($news == 'death11') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因滞留在<span class=\"red\">禁区【{$mapdata[$c]['name']}】</span>死亡";
			} elseif($news == 'death12') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">毒发</span>死亡";
			} elseif($news == 'death13') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">意外事故</span>死亡";
			} elseif($news == 'death14') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">入侵禁区系统失败</span>死亡";
			} elseif($news == 'death15') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">时空特使强行消除</span>";
			} elseif($news == 'death16') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">由理直接拉入SSS团</span>";
			} elseif($news == 'death17') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">冰雹砸死</span>";
			} elseif($news == 'death18') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">烧伤发作</span>死亡";
			} elseif($news == 'death19') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被轨道空间站发动<span class=\"red\">精确打击</span>炸死";
			} elseif($news == 'death20') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span><span class=\"red\">空手击飞</span>";
			} elseif($news == 'death21') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>殴打致死";
			} elseif($news == 'death22') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>斩杀";
			} elseif($news == 'death23') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>射杀";
			} elseif($news == 'death24') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>投掷<span class=\"red\">$d</span>致死";
			} elseif($news == 'death25') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>埋设<span class=\"red\">$d</span>伏击炸死";
			} elseif($news == 'death26') {
				if($c) {
					$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因食用了<span class=\"yellow\">$c</span>下毒的<span class=\"red\">$d</span>被毒死";
				} else {
					$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因食用了有毒的<span class=\"red\">$d</span>被毒死";
				}
			} elseif($news == 'death27') {
				if($c){
					$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因触发了<span class=\"yellow\">$c</span>设置的陷阱<span class=\"red\">$d</span>被杀死";
				} else {
					$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因触发了陷阱<span class=\"red\">$d</span>被杀死";
				}
			} elseif($news == 'death28') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"yellow\">$d</span>意外身亡";
			}	elseif($news == 'death29') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>发动<span class=\"red\">$d</span>以灵力杀死";
			} elseif($news == 'death30') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因误触伪装成核弹按钮的蛋疼机关被炸死";
			} elseif($news == 'death31'){
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因L5发作自己挠破喉咙身亡！";
			} elseif($news == 'death32'){
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，躲藏于<span class=\"red\">{$mapdata[$c]['name']}</span>的<span class=\"yellow\">$a</span><span class=\"red\">挂机时间过长</span>，被在外等待的愤怒的玩家们私刑处死！";
			} elseif($news == 'death33'){
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因卷入特殊部队『天使』的实弹演习，被坠落的少女和机体“亲吻”而死";
			} elseif($news == 'death34'){
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，位于<span class=\"red\">{$mapdata[$c]['name']}</span>的<span class=\"yellow\">$a</span>因遭到{$d}的攻击而死亡。";
			} else {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">不明原因</span>死亡";
			}
//			$dname = $typeinfo[$b].' '.$a;
//			if(!$e){
//				if($b != 0) {
//					$lastword = '';//待处理，这里的$a已经加了头衔，无法作为判断；而且在这里调用数据库是很蛋疼的
////					$lwresult = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
////					$lastword = $db->result($lwresult, 0);
//				} else {
//					$lastword = is_array($lwinfo[$b]) ? $lwinfo[$b][$a] : $lwinfo[$b];
//				}				
//			}else{
//				$lastword = $e;
//			}
//			if($lastword){
//				$newsinfo .= "<span class=\"yellow\">【{$a}：“{$lastword}”】</span><br>\n";
//			}else{
//				$newsinfo .= "<span class=\"yellow\">【{$a} 什么都没说就死去了】</span><br>\n";
//			}
			if($e){
				$newsinfo .= "<span class=\"yellow\">【{$a}：“{$e}”】</span><br>\n";
			}else{
				$newsinfo .= "<span class=\"yellow\">【{$a} 什么都没说就死去了】</span><br>\n";
			}
		} elseif($news == 'revive') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}借助{$b}的力量而复活了！</span><br>\n";
		} elseif($news == 'poison') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"purple\">{$a}食用了{$b}下毒的{$c}</span><br>\n";
		} elseif($news == 'trap') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}中了{$b}设置的陷阱{$c}</span><br>\n";
		} elseif($news == 'trapmiss') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}回避了{$b}设置的陷阱{$c}</span><br>\n";
		} elseif($news == 'duelkey') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}使用了{$b}，启动了死斗程序！</span><br>\n";
		} elseif($news == 'corpseclear') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了凸眼鱼，{$b}具尸体被吸走了！</span><br>\n";
		} elseif($news == 'MAPWset') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，{$a}使用了{$b}，将在<span class=\"yellow\">{$c}</span>秒后对<span class=\"yellow\">{$mapdata[$d]['name']}</span>实施{$e}攻击！<br>\n";
		} elseif($news == 'MAPWexpl') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$mapdata[$a]['name']}遭到了{$b}的攻击！</span><br>\n";
		} elseif($news == 'wthchange') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了{$c}，天气变成了{$wthdata[$b]['name']}！</span><br>\n";
		} elseif($news == 'syswthchg') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">东风谷 早苗使奇迹降临了！天气变成了{$wthdata[$a]['name']}！</span><br>\n";
		} elseif($news == 'newwep') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用{$b}，改造了<span class=\"yellow\">$c</span>！</span><br>\n";
		} elseif($news == 'itemmix') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}合成了{$b}</span><br>\n";
			//$newsinfo .= '';
		} elseif($news == 'itembuy') {
			//$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}购买了{$b}</span><br>\n";
			$newsinfo .= '';
		} elseif($news == 'damage') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">$a</span><br>\n";
		} elseif($news == 'alive') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">鹿目 圆香许愿复活</span><br>\n";
		} elseif($news == 'delcp') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}的尸体被QB吞噬了</span><br>\n";
		} elseif($news == 'editpc') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}遭到了黑幕的生化改造！</span><br>\n";
		} elseif($news == 'suisidefail') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}注射了H173，却由于RP太高进入了发狂状态！！</span><br>\n";
		} elseif($news == 'inf') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}的攻击致使{$b}</span>{$infdata[$c]['name']}<span class=\"red\">了</span><br>\n";
		} elseif($news == 'trapinf') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$b}设置的陷阱{$c}致使{$a}</span>{$infdata[$d]['name']}<span class=\"red\">了</span><br>\n";
		} elseif($news == 'battlelock') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}被轨道空间站锁定了</span><br>\n";
		} elseif($news == 'mustdie') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}对{$b}使出了即死攻击</span><br>\n";
		} elseif($news == 'addnpc') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}进入了虚拟战场</span><br>\n";
		} elseif($news == 'addnpcs') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$b}名{$a}进入了虚拟战场</span><br>\n";
		} elseif($news == 'honour') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>获得了称号<span class=\"yellow\">$b</span><br>\n";
		} else {
			$newsinfo .= "<li>$time,$news,$a,$b,$c,$d,$e<br>\n";
		}
	}

	$newsinfo .= '</ul>';
	return $newsinfo;
		
}

?>