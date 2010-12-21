<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}



function  parse_news($start = 1, $range = 0 , $file = '') {
	global $week,$newsfile,$nowep,$db,$tablepre,$lwinfo,$plsinfo,$wthinfo,$typeinfo;
	$file = $file ? $file : $newsfile;	
	$ninfo = openfile($file);
	$r = sizeof($ninfo) - 1;
	if($range && ($range <= $r)) {
		$r = $range;
	}
	$newsinfo = '<ul>';
	$nday = 0;
	for($i = $start;$i <= $r;$i++) {
		list($time,$news,$a,$b,$c,$d) = explode(',',$ninfo[$i]);
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$time));

		if($day != $nday) {
			$newsinfo .= "<span class=\"evergreen\"><B>{$month}月{$day}日(星期$week[$wday])</B></span><br>";
			$nday = $day;
		}

		if($news == 'newgame') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回常磐大逃杀开始了</span><br>\n";
		} elseif($news == 'gameover') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回常磐大逃杀结束了</span><br>\n";
		} elseif($news == 'newpc') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}({$b})加入了本次游戏</span><br>\n";
		} elseif($news == 'teammake') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$b}创建了队伍{$a}</span><br>\n";
		} elseif($news == 'teamjoin') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$b}加入了队伍{$a}</span><br>\n";
		} elseif($news == 'teamquit') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$b}退出了队伍{$a}</span><br>\n";
		} elseif($news == 'senditem') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}将<span class=\"yellow\">$c</span>赠送给了{$b}</span><br>\n";
		} elseif($news == 'addarea') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，增加禁区：";
			$alist = explode('-',$a);
			foreach($alist as $ar) {
				$newsinfo .= "$plsinfo[$ar] ";
			}
			$newsinfo .= "<span class=\"yellow\">【天气：{$wthinfo[$b]}】</span><br>\n";
		} elseif($news == 'hack') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}启动了hack程序，全部禁区解除！</span><br>\n";
		} elseif($news == 'combo') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏进入连斗阶段！</span><br>\n";
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
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}无意中引爆了核弹，本次游戏无人生还</span><br>\n";
		} elseif(strpos($news,'death') === 0) {
			if($news == 'death11') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">禁区停留</span>死亡";
			} elseif($news == 'death12') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">毒发</span>死亡";
			} elseif($news == 'death13') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">意外事故</span>死亡";
			} elseif($news == 'death14') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">入侵禁区系统失败</span>死亡";
			} elseif($news == 'death15') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">时空特使强行消除。</span>";
			} elseif($news == 'death16') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">由理直接拉入SSS团。</span>";
			} elseif($news == 'death17') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">冰雹砸死</span>";
			} elseif($news == 'death18') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">烧伤发作</span>死亡";
			} elseif($news == 'death20') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span><span class=\"red\">$nowep</span>击飞";
			} elseif($news == 'death21') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>殴打致死";
			} elseif($news == 'death22') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>斩杀";
			} elseif($news == 'death23') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>射杀";
			} elseif($news == 'death24') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>投掷<span class=\"red\">$d</span>致死";
			} elseif($news == 'death25') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>使用<span class=\"red\">$d</span>炸死";
			}	elseif($news == 'death29') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>设置<span class=\"red\">$d</span>炸死";
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
			} elseif($news == 'death30') {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因伪装成核弹按钮的蛋疼机关被炸死";
			} elseif($news == 'death31'){
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因L5发作自己挠破喉咙身亡！";
			}else {
				$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">不明原因</span>死亡";
			}
			
			if($b == 0) {
				$dname = $a;
				$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
				$lastword = $db->result($result, 0);
			} else {
				$dname = "{$typeinfo[$b]} $a";
				$lastword = $lwinfo[$b];
			}

			$newsinfo .= "<span class=\"yellow\">【$dname ： {$lastword}】</span><br>\n";
		} elseif($news == 'poison') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"purple\">{$a}食用了{$b}下毒的{$c}</span><br>\n";
		} elseif($news == 'trap') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}中了{$b}设置的陷阱{$c}</span><br>\n";
		} elseif($news == 'corpseclear') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了凸眼鱼，{$b}具尸体被吸走了！</span><br>\n";
		} elseif($news == 'wthchange') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了天侯棒，天气变成了<span class=\"red\">$wthinfo[$b]</span>！</span><br>\n";
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
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">神北 小毬许愿复活</span><br>\n";
		} elseif($news == 'delcp') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}的尸体被时空特使别动队销毁了</span><br>\n";
		} elseif($news == 'editpc') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}得到了常磐之灵加护！</span><br>\n";
		} elseif($news == 'suisidefail') {
			$newsinfo .= "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}注射了H173，却由于RP太高进入了发狂状态！！</span><br>\n";
		} else {
			$newsinfo .= "<li>$time,$news,$a,$b,$c,$d<br>\n";
		}
	}

	$newsinfo .= '</ul>';
	return $newsinfo;
		
}



?>