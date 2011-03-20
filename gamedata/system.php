<?php

/*Game system settings*/

//文件验证字符串
$checkstr = "<? if(!defined('IN_GAME')) exit('Access Denied'); ?>\n";
//是否允许游客进入插件。0=不允许，1=允许
$isLogin = 1;
//是否缓存css文件。0=不缓存，1=缓存
$allowcsscache = 1;
//站长留言
$adminmsg = 'AC大逃杀<br>
感谢Acg_Xilin提供服务器空间<br>
<h1>当前版本为 2.92 SP1</h1><br>
<strike>讨论群：101336468</strike><br>上面那个群已满，分群：120405311<br>
<h3>10分钟没动作可能被反挂机系统抹杀，请注意！</h3>
<iframe width="100%" height="260" class="share_self"  frameborder="0" scrolling="no" src="http://service.t.sina.com.cn/widget/WeiboShow.php?width=0&height=260&fansRow=0&ptype=1&speed=100&skin=10&isTitle=0&noborder=0&isWeibo=1&isFans=1&uid=1583697851&verifier=d5e5b6db"></iframe>';
//游戏开始方式 0=后台手动开始，1=每天固定时间开始，2=上局结束后，间隔固定小时后的整点开始，3=上局结束后，间隔固定分钟开始
$startmode = 3;
//游戏开始的小时，如果，如果$startmode = 1,表示开始时间0~23，如果$startmode = 2，表示间隔小时，>0，如果$startmode = 3，表示间隔分钟，>0
$starthour = 10;
//游戏开始的分钟数，范围1~59
$startmin = 5;
//游戏所用配置文件
$gamecfg = 1;


//同ip限制激活人数。0为不限制
$iplimit = 0;
//头像数量（男女相同）
$iconlimit = 20;
//游戏进行状况显示条数
$newslimit = 50;
//生存者显示条数
$alivelimit = 30;
//历史优胜者显示条数
$winlimit = 50;
//枪声间隔时间(秒)
$noiselimit = 300;

//游戏内聊天信息显示条数
$chatlimit = 50;
//聊天信息更新时间(单位:毫秒)
$chatrefresh = 15000;
//游戏进行中是否显示聊天。0为不显示，数字为显示条数
$chatinnews = 50;

//开启NPC台词功能？0为不开启，1为开启
$npcchaton = 1;
//有台词的NPC
$npccanchat = Array(1,5,6,7,10);
//反挂机系统间隔时间，单位分钟
$antiAFKertime=10;
//尸体保护时间，单位秒
$corpseprotect=10;

/*template settings*/
//模板编号。默认为1
define('STYLEID', '1');
define('TEMPLATEID', '1');
define('TPLDIR', './templates/default');

?>