<?php

/*Game system settings*/

//文件验证字符串
$checkstr = "<? if(!defined('IN_GAME')) exit('Access Denied'); ?>\n";
//是否允许游客进入插件。0=不允许，1=允许
$isLogin = 1;
//是否缓存css文件。0=不缓存，1=缓存
$allowcsscache = 1;
//站长留言
//$adminmsg = '';
//游戏开始方式 0=后台手动开始，1=每天固定时间开始，2=上局结束后，间隔固定小时后的整点开始，3=上局结束后，间隔固定分钟开始
$startmode = 0;
//游戏开始的小时，如果，如果$startmode = 1,表示开始时间0~23，如果$startmode = 2，表示间隔小时，>0，如果$startmode = 3，表示间隔分钟，>0
$starthour = 0;
//游戏开始的分钟数，范围1~59
$startmin = 3;
//游戏所用配置文件
$gamecfg = 1;


//同ip限制激活人数。0为不限制
$iplimit = 3;
//头像数量（男女相同）
$iconlimit = 20;
//游戏进行状况显示条数
$newslimit = 50;
//生存者显示条数
$alivelimit = 30;
//历史优胜者显示条数
$winlimit = 50;
//玩家排行榜显示条数
$ranklimit = 10;
//枪声间隔时间(秒)
$noiselimit = 300;
//枪声显示条数
$noisenum = 3;


//游戏内聊天信息显示条数
$chatlimit = 50;
//聊天信息更新时间(单位:毫秒)
$chatrefresh = 15000;
//游戏进行中是否显示聊天。0为不显示，数字为显示条数
$chatinnews = 50;

//开启gzip压缩功能？0为不开启，1为开启
//$gzipcompress=0;


/*Infomations*/
$_INFO = Array
	(
	'reg_success' => '注册成功！请返回首页登陆游戏。',
	'pass_success' => '修改密码成功。',
	'pass_failure' => '未修改密码。',
	'data_success' => '接受对帐户资料的修改。',
	'data_failure' => '未修改帐户资料。'
	);

/*Error settings*/
$_ERROR = Array
	(
	'db_failure' => '数据库读写异常，请重试或通知管理员',
	'name_not_set' => '用户名不能为空，请检查用户名输入',
	'name_too_long' => '用户名过长，请检查用户名输入',
	'name_invalid' => '用户名含有非法字符，请检查用户名输入',
	'name_banned' => '用户名含有违禁用语，请检查用户名输入',
	'name_exists' => '用户名已被注册，请更换用户名',
	'name_npc' => '用户名为游戏保留字，请更换用户名',
	'pass_not_set' => '密码不能为空，请检查密码输入',
	'pass_not_match' => '两次输入的密码不一致，请检查密码输入',
	'pass_too_short' => '密码过短，请检查密码输入',
	'pass_too_long' => '密码过长，请检查密码输入',
	'ip_banned' => '此IP已被封禁，请与管理员联系',
	'logged_in' => '用户已登录，请先退出登陆再注册',
	'user_not_exists' => '用户不存在，请检查用户名输入',
	'reg_off' => '新用户注册已关闭，请与管理员联系',
	
	'no_login' => '用户未登陆，请从首页登录后再进入游戏',
	'login_check' => '用户信息验证失败，请清空缓存后进入游戏',
	'login_time' => '登录间隔时间过长，请重新登录后进入游戏',
	'login_info' => '用户信息不正确，请清空缓存和Cookie后进入游戏',
	'player_limit' => '本局游戏参加人数已达上限，无法进入，请下局再来',
	'wrong_pw' => '用户名或密码错误，请检查输入',
	'player_exist' => '角色已经存在，请不要重复激活',
	'no_start' => '游戏尚未开始，请耐心等待',
	'valid_stop' => '本游戏已经停止激活，无法进入，请下局再来',
	'user_ban' => '此账号禁止进入游戏，请与管理员联系',
	'no_admin' => '你不是管理员，不能使用此功能',
	'ip_limit' => '本局此IP激活人数已满，请下局再来',
	'no_power' => '你的管理权限不够，不能进行此操作',
	'wrong_adcmd' => '指令错误，请重新输入',
	);

/*template settings*/
//模板编号。默认为1
define('STYLEID', '1');
define('TEMPLATEID', '1');
define('TPLDIR', './templates/default');

?>