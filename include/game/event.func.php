<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function event(){
	global $mode,$log,$hp,$sp,$inf,$pls,$rage;

	$dice1 = rand(0,5);
	$dice2 = rand(20,40);//原为rand(5,10)
	
	if($pls == 0) { //分校
	} elseif($pls == 1) { //音乐区
		$log .= "一曲穿越灵魂的歌声突然袭来！<BR>";
		if($dice1 <= 3){
			$dice2 += 10;
			if($sp <= $dice2){
				$dice2 = $sp-1;
			}
			$sp-=$dice2;
			$log .= "心灵被震撼了，产生了心理创伤！<BR>体力减少 <font color=\"red\"><b>{$dice2}</b></font>  点。<BR>";
		}else{
			$log .= "呼...还好顶住了...<BR>";
		}
	} elseif($pls == 2) { //戒网所 - 现RF高校
		$log = ($log . "突然，一个戴着面具的怪人出现了！<BR>");
		if($dice1 == 2){
			$log = ($log . "“呜嘛呜——！”<br>被怪人打中了头！<BR>");
			$inf = str_replace('h','',$inf);
			$inf = ($inf . 'h');
		}elseif($dice1 == 3){
			$log = ($log . "“呜嘛呜——！”<br>被怪人打中了，受到<font color=\"red\"><b>{$dice2}</b></font> 点伤害！<BR>");
			$hp-=$dice2;
		}else{
			$log = ($log . "呼，总算逃脱了。<BR>");
		}
	} elseif($pls == 3) { //北村公所
	} elseif($pls == 4) { //邮电局
	} elseif($pls == 5) { //消防署
	} elseif($pls == 6) { //观音堂
	} elseif($pls == 7) { //清水池
		$log = ($log . "糟糕，脚下滑了一下！<BR>");
		if($dice1 <= 3){
			$dice2 += 10;
			if($sp <= $dice2){
				$dice2 = $sp-1;
			}
			$sp-=$dice2;
			$log = ($log . "掉下池中了，不过，已努力爬了上来！<BR>体力减少 <font color=\"red\"><b>{$dice2}</b></font> 点。<BR>");
		}else{
			$log = ($log . "呼...幸好没掉下水池...<BR>");
		}
	} elseif($pls == 8) { //西村神社
	} elseif($pls == 9) { //墓地
	} elseif($pls == 10) { //娱乐区
		$log = ($log . "哇！人群中突然钻出来一个光头！<BR>");
		if($dice1 == 2){
			$log = ($log . "已经尽量闪避，不过，还是被光头的反射光烧伤了脚！<BR>");
			$inf = str_replace('f','',$inf);
			$inf = ($inf . "f");
		}elseif($dice1 == 3){
			$log = ($log . "被光头爆了，受到<font color=\"red\"><b>{$dice2}</b></font> 点伤害！<BR>");
			$hp-=$dice2;
		}else{
			$log = ($log . "呼...总算是逃脱了...<BR>");
		}

	} elseif($pls == 11) { //央中电视台 - 现对天使用作战本部
		$log = ($log . "哇！一个大锤向你锤来！<BR>");
		if($dice1 == 2){
			$log = ($log . "大锤落到了脚上，很疼的样子。<BR>");
			$inf = str_replace('f','',$inf);
			$inf = ($inf . "f");
		}elseif($dice1 == 3){
			$log = ($log . "你被击飞出了窗外，受到<font color=\"red\"><b>{$dice2}</b></font> 点伤害！<BR>");
			$hp-=$dice2;
		}else{
			$log = ($log . "呼...总算是避开了...<BR>");
		}
	} elseif($pls == 12) { //夏之镇
		$log = ($log . "突然，天空出现一大群乌鸦！<BR>");
		if($dice1 == 2){
			$log = ($log . "被乌鸦袭击，头部受了伤！<BR>");
			$inf = str_replace('h','',$inf);
			$inf = ($inf . "h");
		}elseif($dice1 == 3){
			$log = ($log . "被乌鸦袭击，受到<font color=\"red\"><b>{$dice2}</b></font> 点伤害！<BR>");
			$hp-=$dice2;
		}else{
			$log = ($log . "呼，总算击退了。<BR>");
		}
	} elseif($pls == 13) { //寺庙
	} elseif($pls == 14) { //废校
	} elseif($pls == 15) { //南村神社
	} elseif($pls == 16) { //常磐森林
		$log = ($log . "野生的皮卡丘从草丛中钻出来了！<BR>");
		if($dice1 == 2){
			$log = ($log . "皮卡丘使用了电击！手臂麻痹了！<BR>");
			$inf = str_replace('a','',$inf);
			$inf = ($inf . "a");
		}elseif($dice1 == 3){
			$log = ($log . "皮卡丘使用了电光石火！受到<font color=\"red\"><b>{$dice2}</b></font> 点伤害！<BR>");
			$hp-=$dice2;
		}else{
			$log = ($log . "成功地逃跑了。<BR>");
		}
	} elseif($pls == 17) { //M记戈壁
		$log = ($log . "糟糕，脚下踩到了憨八嘎！<BR>");
		if($dice1 <= 3){
			$dice2 += 10;
			if($sp <= $dice2){
				$dice2 = $sp-1;
			}
			$sp-=$dice2;
			$log = ($log . "摔出的距离大约有四个憨八嘎那么长吧！<BR>体力减少 <font color=\"red\"><b>{$dice2}</b></font> 点。<BR>");
		}else{
			$log = ($log . "呼...幸好没跌倒...<BR>");
		}

	} elseif($pls == 18) { //秋之镇
		$log = ($log . "突然，天空出现一大群乌鸦！<BR>");
		if($dice1 == 2){
			$log = ($log . "被乌鸦袭击，头部受了伤！<BR>");
			$inf = str_replace('h','',$inf);
			$inf = ($inf . 'h');
		}elseif($dice1 == 3){
			$log = ($log . "被乌鸦袭击，受到<font color=\"red\"><b>{$dice2}</b></font> 点伤害！<BR>");
			$hp-=$dice2;
		}else{
			$log = ($log . "呼，总算击退了。<BR>");
		}

	} elseif($pls == 19) { //诊所
	} elseif($pls == 20) { //灯塔
	} elseif($pls == 21) { //专辑区
		$log .= "一大波字幕突然涌来！<BR>";
		if($dice1 <= 3){
			$dice2 += 10;
			if($sp <= $dice2){
				$dice2 = $sp-1;
			}
			$sp-=$dice2;
			$log .= "字幕太多，浏览器死机了！<BR>体力减少 <font color=\"red\"><b>{$dice2}</b></font>  点。<BR>";
		}else{
			$log .= "很〇很〇〇，我马上把窗口关了。<BR>";
		}

	} else {
	}

	if($hp<=0){
		global $now,$alivenum,$deathnum,$name,$state;
		$hp = 0;
		$state = 13;
		addnews($now,'death13',$name,0);
		$alivenum--;
		$deathnum++;
		include_once GAME_ROOT.'./include/system.func.php';
		save_gameinfo();
	}
	return;
}

?>