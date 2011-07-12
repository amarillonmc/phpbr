<?php
/*Game Config*/

//地图X轴格子数
//$mapx = 5;
//地图Y轴格子数
//$mapy = 10;

//禁区间隔时间，单位与游戏设定有关
$areahour = 15;
//每次间隔增加的禁区数量
$areaadd = 2;
//玩家激活结束时的增加禁区的回数，相当于已经进行的小时数/间隔时间
$arealimit = 5;
//是否自动逃避禁区 0=只有重视躲避自动躲避，1=所有玩家自动躲避，适合新手较多，不了解禁区机制
$areaesc = 1;

//开启NPC台词功能？0为不开启，1为开启
$npcchaton = 1;
//有台词的NPC
//$npccanchat = Array(1,5,6,7,10);
//反挂机系统间隔时间，单位分钟
$antiAFKertime = 5;
//尸体保护时间，单位秒
$corpseprotect = 10;
//是否启动冷却时间，0为不启动，1为启动；
$coldtimeon = 0;
//是否显示冷却时间倒计时，0为不显示，1为显示；
$showcoldtimer = 0;
//移动的冷却时间，单位毫秒
$movecoldtime=500;
//探索的冷却时间，单位毫秒
$searchcoldtime=500;
//使用物品的冷却时间，单位毫秒
$itemusecoldtime=500;
//是否启动玩家影子系统，0为不启动，1为启动；
$shadowsystem = 1;
//是否启动同伴系统，0为不启动，1为启动；
$companysystem = 1;

//本局游戏人数限制
$validlimit = 240;
//连斗时的人数限制
$combolimit = 15;
//连斗时的死亡人数限制
$deathlimit = 100;
 
// 等级提升基本经验值 
$baseexp = 10;
// 初始耐力最大值 
$splimit = 400;
// 初始生命最大值 
$hplimit = 400;
// 怒气最大值 
//$mrage = 500;
//携带金钱上限
//$moneylimit = 65500;

// 恢复量的设定
//体力恢复时间(秒):*秒1点恢复
$sleep_time = 4;
//生命恢复时间(秒):*秒1点恢复
$heal_time = 8;
//包扎伤口需要的体力
$inf_sp = 50;
//治疗特殊状态需要的体力
$inf_sp_2 = 200;
//创建队伍需要的体力
$team_sp = 200;
//加入队伍需要的体力
$teamj_sp = 100;
//队伍最大人数
$teamlimit = 6;

//随机事件几率(百分比)
$event_obbs = 5;
//强制踩陷阱最小几率(百分比)
$trap_min_obbs = 1;
//强制踩陷阱最大几率(百分比)
$trap_max_obbs = 35;
//地图上每个陷阱增加的踩陷阱几率(百分比)
$trap_per_obbs = 0.2;
//道具发现基础几率(百分比);
$item_obbs = 60;
//敌人发现基础几率(百分比)
$enemy_obbs = 70;
//敌人发现最小几率
$enemy_min_obbs = 10;
//尸体发现几率（百分比）
$corpse_obbs = 50;
//基础先攻率
//$active_obbs = 50;
//基础反击率
//$counter_obbs = 50;

//受伤状态的设定
//h-头部受伤，b-身体受伤,a-手腕受伤，f-足部受伤，p-中毒，u-烧伤，i-冻结，e-麻痹
//各种受伤状态对移动消耗体力的影响，因数
$inf_move_sp = Array('f'=> 1.6, 'i'=> 2.25,'e'=> 1.2);
//各种受伤状态对探索消耗体力的影响，因数
$inf_search_sp = Array('a'=> 1.6, 'i'=> 2.25,'e'=> 1.2);
//各种受伤状态移动时消耗的生命力，因数
$inf_move_hp = Array('p'=> 0.0625, 'u'=> 0.0625);
//各种受伤状态探索时消耗的生命力，因数
$inf_search_hp = Array('p'=> 0.03125, 'u'=> 0.03125);
//无法活动的受伤状态
$inf_cannot_cmd = Array(
	's'=> Array('field' => 'lastslp', 'time' => 45, 'atkref' => true),
	'S'=> Array('field' => 'laststn', 'time' => 30, 'atkref' => false)
);
//各种受伤状态无法移动的概率
$inf_cannot_move_r = Array('w'=> 40,'e'=> 15);
//各种受伤状态无法探索的概率
$inf_cannot_search_r = Array('w'=> 40,'e'=> 15);
//各种受伤状态移动时的恢复概率
$inf_move_ref_r = Array('w'=> 5);
//各种受伤状态探索时的恢复概率
$inf_search_ref_r = Array('w'=> 3);
//hack基础成功率
$hack_obbs = 30;
//NPC AI设定
$NPCAI = Array(//state=1状态的NPC不启动AI
	1 => Array(
		'move' => 60, //移动和探索间隔时间，单位秒，0为关闭
		'mrate' => 0, //移动比率，100-此比率为探索比率
		'arate' => 100, //主动攻击玩家的概率
		'heal' => 0.5, //切换到治疗姿态的HP临界比率
		
	),
	2 => Array(
		'move' => 90, //移动和探索间隔时间，单位秒，0为关闭
		'mrate' => 0, //移动比率，100-此比率为探索比率
		'arate' => 100, //主动攻击玩家的概率
		'heal' => 0.75, //切换到治疗姿态的HP临界比率		
	),
	3 => Array(
		'move' => 120, //移动和探索间隔时间，单位秒，0为关闭
		'mrate' => 70, //移动比率，100-此比率为探索比率
		'arate' => 50, //主动攻击玩家的概率
		'heal' => 0.3, //切换到治疗姿态的HP临界比率
	),
	4 => Array(
		'move' => 90, //移动和探索间隔时间，单位秒，0为关闭
		'mrate' => 30, //移动比率，100-此比率为探索比率
		'arate' => 50, //主动攻击玩家的概率
		'heal' => 0.3, //切换到治疗姿态的HP临界比率
	),
);
//NPC自动包扎伤口的概率
$npcbind = 50;
?>