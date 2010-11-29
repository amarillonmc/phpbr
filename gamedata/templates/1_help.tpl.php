<? if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<div class="subtitle" >游戏帮助</div>
<span class="lime">GM重新撰写帮助中，请等待，谢谢！</span><br>

<HR>
<DIV align="center" class=Filler>
<table style="width: 100%">
<tr>
<td>Back-Story 背景故事</td>
<td>Game-Help 游戏帮助</td>
<td>Change-Log 更新记录</td>
</tr>
</table>
</div>

<TABLE class=menutbl style="BORDER-COLLAPSE: collapse" 
cellSpacing=0 cellPadding=0 align=center border=1>
  <TBODY>
  <TR>
    <TD colSpan=5>
      <CENTER><B style="FONT-SIZE: 120%">生存游戏 BRA 说明目录</B></CENTER></TD></TR>
  <TR>
    <TD><A href="help.php#常见问题">常见问题</A></TD>
    <TD><A href="help.php#参加游戏">参加游戏</A></TD>
    <TD><A href="help.php#胜利条件">胜利条件</A></TD>
    <TD><A href="help.php#操作说明">操作说明</A></TD>
    <TD><A href="help.php#指令说明">指令说明</A></TD></TR>
  <TR>
    <TD><A href="help.php#道具说明">道具说明</A></TD>
    <TD><A href="help.php#道具合成">道具合成</A></TD>
    <TD><A href="help.php#社团说明">社团说明</A></TD>
    <TD><A href="help.php#战斗说明">战斗说明</A></TD>
    <TD><A href="help.php#受伤，状态">受伤，状态</A></TD></TR>
  <TR>
    <TD><A href="help.php#基本姿态">基本姿态</A></TD>
    <TD><A href="help.php#应战策略">应战策略</A></TD>
    <TD><A href="help.php#熟练度">熟练度</A></TD>
    <TD><A href="help.php#禁区">禁区</A></TD>
    <TD><A href="help.php#天气">天气</A></TD></TR>
  <TR>
    <TD><A href="help.php#武器">武器</A></TD>
    <TD><A href="help.php#毒药和陷阱">毒药和陷阱</A></TD>
    <TD><A href="help.php#特殊道具">特殊道具</A></TD>
    <TD><A href="help.php#必杀技">必杀技</A></TD>
    <TD><A href="help.php#玩家守则">玩家守则</A></TD></TR>
</TBODY></TABLE>
<HR>
<div align="left">
Change-Log<br>1.92-&gt;1.98<br>系统：<br>1.远程扒人BUG修正<br>2.部分游戏状态显示更改（去那啥词化）<br>
3.更改了部分地点名（用RF高校和对天使用作战本部替代了某两个地点）<br>4.全故事情节洗牌，NPC名单，头像等洗牌。<br>平衡性：<br>
5.钉子和钢钉在商店里面出现的时机从0禁变为3禁<br>
6.武器师安雅的奖赏将不能从某NPC身上捡取，作为补偿商店有卖合成此道具的素材，从0禁开始每禁刷3个，总价格是20000。<br>
7.KEY催泪弹的合成方法改变。原古河渚，神尾观铃和月宫亚由三人的雕像掉落由清水池改成全地图。另外新增天泽 郁末，长森瑞佳和枣铃三人的雕像。合成公式如下：<br>
月宫 亚由的半身像 + 神尾 观铃的半身像 + 古河 渚的半身像 = 四季流转的咏叹调【殴】750/1<br>枣 铃的半身像 + 天泽 郁末的半身像 + 长森 
瑞佳的半身像 = 旁观轮回的覆唱诗【斩】750/1<br>四季流转的咏叹调 + 旁观轮回的覆唱诗 = KEY催泪弹【爆】1800/1<br>
8.清水池新增神北小毬，一之濑琴美和SSS团长的半身像，可以合成得到攻击力是原催泪弹攻击力的约1/10；耐久为原催泪弹的45倍的爆系武器。<br><br>
以上修改，从新服169回游戏开始生效，结束剧情的更改可能要再推后一点，老服的话暂定为WID。<br><br>冴月麟<br>2010-05-20 0221AM 
EST
<br><br>1.92<br>Initial Release of BRA 2.<br>
在开始游戏前请详阅下面的说明书。 内容很多没错， 不过对于第一次入门来说的人有阅读的必要。 <BR>
<B class="red">连下面最常见问题也不看的玩家可能在任何时间被强制杀死退出游戏。</B> <BR>
就算你没时间看完，也请在发问前先来这里搜寻一下有没有你要问的问题。<BR> 这里没有说明的，则是希望让玩家自行探索。 因为这是说明书，不是攻略。<BR>
注意，以下说明中设计的所有数值，都是初始设定，管理员可根据情况修改。一切数据以游戏中的为准。<BR><BR>

<B style="COLOR: #f00"><A name=FAQ>■FAQ 常见问题</A></B><BR>
<DIV align="left" class=FAQ>下面列出许多新手最常问的问题。 若问了这里已有的问题会被视为没看说明书。<BR>
<DL>
  <DT>问：我是新手，这怎么玩？ (看不懂/教我玩/带我玩) 
  <DD>答：这是一个网页游戏，请大略把这整份说明书看过一遍，<BR>第一次看可能很多地方看不懂，你不用急着把它背起来，<BR>先有个概念，然后时间到注册进去，顺便把说明书的窗口开在旁边对照着看，<BR>玩个两三遍很快就会了解了。大部份的人上手后都同意其实一开始想问的问题 在这份说明书中都有答案。<BR>新手不是罪恶，但更不是借口。问问题请仔细提出你哪里不懂，如果只有一句看不懂或教我玩，别人想帮也不知何从帮起，所以我们只好视为干扰游戏进行。 <BR>
  <DT>问：怎么注册？ 我之前注的账号不能用了？ 
  <DD>答：你只要注册游戏所在的论坛账号就可以了。<BR> 
  <DT>问：钱(资金)是干么用的、怎么赚钱、怎么买东西、能不能卖东西? 
  <DD>答：钱可以在商店买东西，不能卖东西，只有杀人才可以拿钱。 商店的位置在分校与废校的「特殊」指令。 
  <DT>问：怎么组队？ 怎样加入队伍？ 怎么给队友东西？给队友钱？ 
  <DD>答：组队或加入队伍请选「队伍」，<BR>创立队伍请直接输入你想用的队伍名称以及想用的密码，<BR>加入别人的队伍则输入对方的队名密码。<BR>公频上常有人在临时公开招人（会自报名称密码），想加入可多注意公频。<BR>(如通常「XXX/123」表示队名是 XXX，密码是 123)<BR>钱不能给队友。<BR>
  <DT>问：公频上有人在换东西！ 怎么跟他交换？ 
  <DD>答：只能用队友给东西的方式。 通常换东西是会先沟通好进同一队，<BR>然后约好地方互探（记得留空格），等到彼此都给了东西就 OK 了。<BR>但请小心人心险恶，交易过程中发生什么事原则上站方是不处理的。<BR>（若有人过度恶意行为被多次告发等等还是会介入进行调查）<BR>
  <DT>问：怎样杀人？ 怎样才会死？ 
  <DD>答：碰到(原地探索与移动)人就可攻击。 对方或自己血扣完就死了。 
  <DT>问：怎样回复(增加)体力、耐力？ 
  <DD>答：回体耐可以吃东西或用睡眠、治疗、静养等指令回复。<BR>但注意要按返回或有战斗才会显示回复量，且太快返回可能会无回复效果。 
  <DT>问：受伤怎么办？ 中毒怎么办？ 
  <DD>答：受伤跟中毒都不会自动解除。 受伤时在「特殊」会多出「包扎伤口」(要耐力够才行)， 中毒只有找或买解毒剂。 
  </DD></DL></DIV>

<B class="red">注意：以下涉及的数据，皆为游戏原始数据，管理员可能会修改，以实际游戏中数据为准！</B> <BR>
<br>
<B style="COLOR: #f00"><A name=参加游戏>1。参加游戏</A></B><BR>
<br>
本游戏是以论坛插件形式存在的，所以在进行游戏前，必须首先注册相应论坛的账号。<br>
然后在登陆论坛后，进入论坛的“生存游戏”插件。<br>
在“生存游戏”的首页，可以看到“进入游戏”的按钮。<br>
当游戏状态为“开放激活”时，点击此按钮即可进入游戏角色激活流程。<br>
如果已经激活了账号，将直接进入游戏进行界面。<br>
注意，每局游戏开始时，必须重新激活一次角色。<br><br>

如果中途要退出游戏，只要退出论坛或者关闭浏览器即可。<br>
但是，此时你的人物还是会继续在游戏中生存，所以有可能被别人杀死，或者因停留在禁区被爆头。<br>
所以，在离开前请注意调整自己的策略和姿态。<br>

<br>
<B style="COLOR: #f00"><A name=胜利条件>2。胜利条件</A></B><BR>
<br>
游戏中共有22个地区。<br>
每隔一定的时间，就会有一些地区变为禁区。<br>
玩家必须在全部地区变为禁区之前，将其他参加游戏者全部杀死。<br>
最后存活的那个人，就是本局游戏的胜利者。<br>
可能存在其它的结局，期待玩家可以找到。<br>
<br>
<B style="COLOR: #f00"><A name=操作说明>3。操作说明</A></B><BR>
<br>
<DIV style="BORDER-RIGHT: white 1px solid; PADDING-RIGHT: 0.5em; BORDER-TOP: white 1px solid; PADDING-LEFT: 0.5em; PADDING-BOTTOM: 0.5em; MARGIN: 1em; BORDER-LEFT: white 1px solid; WIDTH: 500px; PADDING-TOP: 0.5em; BORDER-BOTTOM: white 1px solid; TEXT-ALIGN: center; color:lime;">下面这是一般游戏进行中的画面。整个游戏是以选指令的方式进行的。<BR>左上为你的状态显示，左下部分为聊天框，右半边就是所有下指令的地方。<BR><IMG src="img/help_1.gif"></DIV>

<B style="COLOR: #f00"><A name=指令说明>4。指令说明</A></B><BR>
<br>
『移动』 离开现在的区域，移动到别的区域。容易与其他人遭遇。 （消耗15点耐力）<br> 
『探索』 探索现在的区域，可能发现道具或者敌人等。 （消耗15点耐力）  <br>
『道具使用』 根据道具的类型使用或者装备所选的道具。可拥有的道具上限是5个<br><br>

“道具”：<br>
『道具合成』  将2或3个不同的道具，合成为一个新道具。常见的合成公式请参考下文<br>
『道具丢弃』  将包裹里的道具丢掉。丢弃的道具不会消失，将放置在当前的地图上。<br>
『整理包裹』  可以将完全相同的道具合并的一起，节约包裹空间。<br>
『卸下装备』  将身上的武器或者防具写下放置到包裹里。<br><br>


“恢复”：<br>
『睡眠』 恢复体力<br>
『治疗』 恢复生命，但是不会治疗伤口（伤口治疗在“特殊”功能内）<br>
『精养』 同时恢复生命和体力，只能在诊所内进行<br><br>


“特殊”：<br>
『商店』：在分校或者废校时，可以进入商店购买道具。购买前请确认包裹里有空位放置道具。<br>
『基础姿态』 更改自己的行动姿态，详情见下文<br>
『应战策略』更改自己的应战策略，详情见下文。<br>
『留言变更』 修改自己的口头禅，杀人留言和遗言<br>
『包扎伤口』 如果在战斗中受伤，则使用此功能处理伤口。（消耗25点体力）<br>
『查毒』 检查饮食中是否有毒，只有烹饪社具有此功能。<br><br>



“队伍”：<br>
同一队伍的人可以赠送道具，但是不能互相攻击。（特殊天气下，可能会误伤）<br>
进入连斗后，所有的队伍都将被取消，并且不能再组建或者加入队伍。<br>
『创建队伍』  组建一个新的队伍，队伍必须有名称和密码。（消耗50点体力）<br>
『加入队伍』  加入一个已经存在的队伍，需要填写队伍名称和密码。每支队伍最多有5人。（消耗25点体力）<br>
『脱离队伍』  脱离目前已经加入的队伍。<br><br>

<br>

<B style="COLOR: #f00"><A name=道具说明>5。道具说明</A></B><BR>

<br>
道具有以下几种类型：<br>
『生命恢复』：使用后恢复生命<br>
『体力恢复』：使用后恢复体力<br>
『命体恢复』：使用后同时恢复生命和体力<br>
小提示：所有的恢复类道具，都有可能含毒。<br>
『武器』：使用后装备在手上，增加攻击力。分为殴，斩，射，投，爆五系<br>
『防具』：使用后装备在身体上，增加防御力，并保护该部位不会受伤。分为体，头，腕，足四个部位<br>
『饰品』：使用后佩戴在身体上，有特殊用途。<br>
『特殊』：特殊道具，部分特殊道具的用途见“特殊道具”部分<br>

<br>
<B style="COLOR: #f00"><A name=道具合成>6。道具合成</A></B><BR>
<br>
可以将某些物品合成为另一种物品，只要名称正确即可合成。合成道具时可增加爆熟。<br>
常见物品合成表：
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD>
      <TABLE style="WIDTH: 500px" cellSpacing=0 cellPadding=0 border=1>
        <TBODY>
        <TR>
          <TD bgColor=#ffff00><B><FONT color=#000000>合成材料</FONT></B></TD>
          <TD bgColor=#ffff00>　</TD>
          <TD bgColor=#ffff00><B><FONT color=#000000>合成结果</FONT></B></TD></TR>
        <TR>
          <TD>轻油 + 肥料</TD>
          <TD>→</TD>
          <TD>火药</TD></TR>
        <TR>
          <TD>水 + 地雷</TD>
          <TD>→</TD>
          <TD>水鸳鸯</TD></TR>
        <TR>
          <TD>灯油 + 钉</TD>
          <TD>→</TD>
          <TD>☆仙女棒☆</TD></TR>
        <TR>
          <TD>雷达用电池 + 打火机</TD>
          <TD>→</TD>
          <TD>☆自爆电池☆</TD></TR>
        <TR>
          <TD>汽油 + 空瓶</TD>
          <TD>→</TD>
          <TD>☆火焰瓶☆</TD></TR>
        <TR>
          <TD>信管 + 火药</TD>
          <TD>→</TD>
          <TD>★炸药★</TD></TR>
        <TR>
          <TD>导火线 + 火药</TD>
          <TD>→</TD>
          <TD>★炸药★</TD></TR>
        <TR>
          <TD>喷雾器罐 + 打火机</TD>
          <TD>→</TD>
          <TD>★简易火焰放射器★</TD></TR>
        <TR>
          <TD>简易雷达 + 天线</TD>
          <TD>→</TD>
          <TD>雷达</TD></TR>
        <TR>
          <TD>安雅人体冰雕 + 解冻药水</TD>
          <TD>→</TD>
          <TD>武器师安雅的奖赏</TD>
        <TR>
          <TD>手机 + 笔记本电脑</TD>
          <TD>→</TD>
          <TD>移动PC</TD>
        <TR>
          <TD>杂炊 + 松茸<BR></TD>
          <TD>→</TD>
          <TD>松茸御饭</TD>
        <TR>
          <TD>咖喱 + 面包<BR></TD>
          <TD>→</TD>
          <TD>咖喱面包</TD>
        <TR>
          <TD>牛奶 + 立顿茶包 + 糯米丸子</TD>
          <TD>→</TD>
          <TD>珍珠奶茶</TD></TR>
</TBODY></TABLE></TD></TR></TBODY></TABLE>　　 

<br>
<B style="COLOR: #f00"><A name=社团说明>7。社团说明</A></B><BR>
<br>
社团在进入游戏时随机生成。一般类社团出现机率较高。各社团特点：

<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD>
      <TABLE cellSpacing=0 cellPadding=0 border=1>
        <TBODY>
        <TR>
          <TD align=middle bgColor=#cccccc>社团名</FONT></TD>
          <TD align=middle bgColor=#cccccc>属性变化</FONT></TD>
          <TD align=middle bgColor=#cccccc>特征</FONT></TD>
          <TD align=middle bgColor=#cccccc>种类</FONT></TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>棒球社</TD>
          <TD bgColor=#333333>殴 初始熟练20</TD>
          <TD bgColor=#333333>　</TD>
          <TD align=middle bgColor=#333333>一般</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>击剑社</TD>
          <TD bgColor=#333333>斩 初始熟练20</TD>
          <TD bgColor=#333333>　</TD>
          <TD align=middle bgColor=#333333>一般</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>弓道社</TD>
          <TD bgColor=#333333>射 初始熟练20</TD>
          <TD bgColor=#333333>　</TD>
          <TD align=middle bgColor=#333333>一般</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>篮球社</TD>
          <TD bgColor=#333333>投 初始熟练20</TD>
          <TD bgColor=#333333>　</TD>
          <TD align=middle bgColor=#333333>一般</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>化学社</TD>
          <TD bgColor=#333333>爆 初始熟练20</TD>
          <TD bgColor=#333333>合成道具时有额外爆熟加成</TD>
          <TD align=middle bgColor=#333333>一般</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>足球社</TD>
          <TD bgColor=#333333>　</TD>
          <TD bgColor=#333333>移动时消耗的体力减少</TD>
          <TD align=middle bgColor=#333333>特殊</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>侦探社</TD>
          <TD bgColor=#333333>　</TD>
          <TD bgColor=#333333>搜索时消耗的体力减少</TD>
          <TD align=middle bgColor=#333333>特殊</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>电脑社</TD>
          <TD bgColor=#333333>　</TD>
          <TD bgColor=#333333>hack成功率100%</TD>
          <TD align=middle bgColor=#333333>特殊</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>动漫社</TD>
          <TD bgColor=#333333>　</TD>
          <TD bgColor=#333333>可自由控制怒气使用必杀技</TD>
          <TD align=middle bgColor=#333333>特殊</TD></TR>
        <TR>
          <TD align=middle bgColor=#333333>烹饪社</TD>
          <TD bgColor=#333333>　</TD>
          <TD bgColor=#333333>可以使用“检查毒物”指令。<BR>下毒效果加倍。<BR>合成食物有数量加成。</TD>
          <TD align=middle bgColor=#333333>特殊</TD></TR>
</TBODY></TABLE></TD></TR></TBODY></TABLE>
<BR>
<B style="COLOR: #f00"><A name=战斗说明>8。战斗说明</A></B><BR>
<br>
在区域移动，或是在探索区域的时候遇见了其它的玩家便会发生战斗。<BR>　　如果是我方先发现对手，就可以先制攻击。<BR>　　相反的，如果被对方先发现，就是对方先发动攻击。<BR><BR>　　在战斗当中杀害对手时，对方所拥有的物品、武器、防具能够择一拿走。<BR>　　不过，在自己休息中被偷袭而反击之后杀了对方的时候，没有办法夺取对方的物品。 
<BR>　　战斗时，先发现对手的人可以先攻击。而在先发动攻击的场合，可以选择『攻击』、『逃走』、『讯息』。<BR>　　另外，『战斗』会依照装备的武器而有殴打、斩刺等等命令可以选择。<br><br>
<B style="COLOR: #f00"><A name=受伤，状态>9。受伤，状态</A></B><BR>
<br>
战斗中，有一定的机率受伤，根据受伤的部分，会受到以下的限制。<BR>　　另外，根据武器的不同，受伤的时候，部位也会不同。<BR>　　以特定的防具可以防止受伤。<BR>　　每当防具防止玩家受伤的时候，防具的耐久度都会下降，直至损坏。<BR><BR>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD>
      <TABLE cellSpacing=0 cellPadding=0 border=1>
        <TBODY>
        <TR>
          <TD>『头』</TD>
          <TD>攻击的命中率下降</TD></TR>
        <TR>
          <TD>『腕』</TD>
          <TD>攻击力下降</TD></TR>
        <TR>
          <TD>『腹』</TD>
          <TD>睡觉，治疗时的回复力下降</TD></TR>
        <TR>
          <TD>『足』</TD>
          <TD>移动，探索时耐力消耗增加</TD></TR>
        <TR>
          <TD>『毒』</TD>
          <TD>移动、探索时体力减少。</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>　
受伤的话，可以用“特殊”命令中的「包扎伤口」来治疗（一次一个部位）<BR>　　要进行包扎，必须要25点耐力。<BR>　　如果是中毒需要用解毒剂。（关于毒请见下列详细说明

<br><br>
<B style="COLOR: #f00"><A name=基本姿态>10。基本姿态</A></B><BR>
<br>
<TABLE cellSpacing=0 cellPadding=0 width=663 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD width=643>
      <TABLE id=AutoNumber7 
      style="BORDER-RIGHT: black 0.75pt solid; BORDER-TOP: black 0.75pt solid; BORDER-LEFT: black 0.75pt solid; BORDER-BOTTOM: black 0.75pt solid; BORDER-COLLAPSE: collapse; BACKGROUND-COLOR: white" 
      height=279 cellSpacing=0 cellPadding=0 width=683 border=1 
      fpstyle="29,111111100">
        <TBODY>
        <TR>
          <TD class=t1 width=80 height=32>方针</TD>
          <TD class=t2 width=269 height=28>说明</TD>
          <TD class=t2 width=72 height=28>&nbsp;攻击力&nbsp;</TD>
          <TD class=t2 width=72 height=28>&nbsp;防御力&nbsp;</TD>
          <TD class=t2 width=72 height=28>&nbsp;发见率&nbsp;</TD>
          <TD class=t2 width=98 height=28>先制攻击率</TD></TR>
        <TR>
          <TD class=t3 bgColor=#cccccc height=40>通常</TD>
          <TD class=t4 style="TEXT-ALIGN: left" width=269 height=40><BR><FONT 
            color=#ffffff>没有什么可以也没什么不可以的。通常行动。</FONT><BR>　</TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=98 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#999999 height=40>攻击姿态</TD>
          <TD class=t5 style="TEXT-ALIGN: left" width=269 height=40><BR><FONT 
            color=#ffffff>把攻击当作重点来行动。</FONT><BR>　</TD>
          <TD class=t5 width=72 height=40><FONT 
          color=#00ffff><B>↑↑↑</B></FONT></TD>
          <TD class=t5 width=72 height=40><FONT 
          color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t5 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t5 width=98 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#cccccc height=40>防御姿态</TD>
          <TD class=t4 style="TEXT-ALIGN: left" width=269 height=40><BR><FONT 
            color=#ffffff>把防御当作重点来行动。</FONT><BR>　</TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#00ffff><B>↑↑↑</B></FONT></TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=98 height=40><FONT 
          color=#ffff00><B>↓↓</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#999999 height=40>探索姿态</TD>
          <TD class=t5 style="TEXT-ALIGN: left" width=269 height=40><FONT 
            color=#ffffff><BR>为了探索而行动。</FONT><BR>　</TD>
          <TD class=t5 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t5 width=72 height=40><FONT 
          color=#ffff00><B>↓↓</B></FONT></TD>
          <TD class=t5 width=72 height=40><FONT 
          color=#00ffff><B>↑↑</B></FONT></TD>
          <TD class=t5 width=98 height=40><FONT 
          color=#00ffff><B>↑↑</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#cccccc height=40>隐密姿态</TD>
          <TD class=t4 style="TEXT-ALIGN: left" width=269 height=40><BR><FONT 
            color=#ffffff>不被敌人发现为目的，隐密的移动。</FONT><BR>　</TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=72 height=40><FONT 
          color=#ffff00><B>↓↓</B></FONT></TD>
          <TD class=t4 width=98 height=40><FONT 
          color=#00ffff><B>↑↑↑</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#999999 height=40>治疗姿态</TD>
          <TD class=t5 style="TEXT-ALIGN: left" width=269 height=37><FONT 
            color=#ffffff>提高休息时体力和生命的恢复速度，但是会严重影响战斗能力</FONT></TD>
          <TD class=t5 width=72 height=37><FONT color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t5 width=72 height=37><FONT color=#ffff00><b>↓↓↓</FONT></TD>
          <TD class=t5 width=72 height=37><FONT color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t5 width=98 height=37><FONT color=#ffff00><B>↓↓↓</B></FONT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
<B style="COLOR: #f00"><A name=应战策略>11。应战策略</A></B><BR>
<br>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD>
      <TABLE id=AutoNumber8 
      style="BORDER-RIGHT: black 0.75pt solid; BORDER-TOP: black 0.75pt solid; BORDER-LEFT: black 0.75pt solid; BORDER-BOTTOM: black 0.75pt solid; BACKGROUND-COLOR: white" 
      height=229 cellSpacing=0 cellPadding=0 width=683 border=1 
      fpstyle="29,111111100">
        <TBODY>
        <TR>
          <TD class=t1 width=80 height=32>方针</TD>
          <TD class=t2 width=269 height=28>说明</TD>
          <TD class=t2 width=72 height=28>&nbsp;攻击力&nbsp;</TD>
          <TD class=t2 width=72 height=28>&nbsp;防御力&nbsp;</TD>
          <TD class=t2 width=90 height=28>&nbsp;回避率&nbsp;</TD>
          <TD class=t2 width=80 height=28>&nbsp;被发现率</TD></TR>
        <TR>
          <TD class=t3 bgColor=#cccccc height=27>通常</TD>
          <TD class=t4 style="TEXT-ALIGN: left" width=269 height=27><FONT 
            color=#ffffff><BR>没什么可以也没什么不行。<BR>通常的战斗。　</FONT></TD>
          <TD class=t4 width=72 height=27><FONT color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=72 height=27><FONT color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=90 height=27><FONT color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=80 height=27><FONT color=#ffffff><B>　</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#999999 height=27>重视反击</TD>
          <TD class=t5 style="TEXT-ALIGN: left" width=269 height=27><FONT 
            color=#ffffff><BR>对对手的反击机会上升。<BR>　</FONT></TD>
          <TD class=t5 width=72 height=27><FONT color=#00ffff><B>↑↑↑</B></FONT></TD>
          <TD class=t5 width=72 height=27><FONT color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t5 width=90 height=27><FONT color=#ffffff><B>　</B></FONT></TD>
          <TD class=t5 width=80 height=27><FONT color=#ffff00><B>↓</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#cccccc height=27>重视防御</TD>
          <TD class=t4 style="TEXT-ALIGN: left" width=269 height=27><FONT 
            color=#ffffff><BR>彻底的防御。<BR>　</FONT></TD>
          <TD class=t4 width=72 height=27><FONT color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t4 width=72 height=27><FONT color=#00ffff><B>↑↑↑</B></FONT></TD>
          <TD class=t4 width=90 height=27><FONT color=#ffffff><B>　</B></FONT></TD>
          <TD class=t4 width=80 height=27><FONT color=#00ffff><B>↑</B></FONT></TD></TR>
        <TR>
          <TD class=t3 bgColor=#999999 height=37>重视躲避</TD>
          <TD class=t5 style="COLOR: white; TEXT-ALIGN: left" width=269 
          height=37>禁区增加时，可以自动躲避禁区。但是无法反击对手</TD>
          <TD class=t5 width=72 height=27><FONT color=#ffff00><B>↓↓↓</B></FONT></TD>
          <TD class=t5 style="COLOR: #ff0" width=72 height=37><B>↓↓</B> 
          <TD class=t5 width=72 height=27><FONT color=#00ffff><B>↑↑</B></FONT></TD>
          <TD class=t5 style="COLOR: #fff" width=80 height=37><B>　</B> 
        </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<FONT color=#ff0000>　　※被发现率表示被其它玩家发现的机率</FONT> <BR><BR>
<B style="COLOR: #f00"><A name=熟练度>12。熟练度</A></B><BR>
<br>
使用武器时，各个类别都有熟练度存在，每次用该武器攻击敌人，就能提高熟练度。<BR>&nbsp;&nbsp;&nbsp; 
即使攻击不中时，熟练度也会上升（如果不攻击就逃走，是得不到熟练度的）。 <BR>&nbsp;&nbsp;&nbsp; 
熟练度到达规定值的时候，熟练等级就会上升。<BR>&nbsp;&nbsp;&nbsp; 
比较特别的是，合成物品也可以提升<B>爆</B>系武器的熟练度。<BR>&nbsp;&nbsp;&nbsp; 
如果用不熟悉的武器战斗的话，是无法发挥该武器100%的威力的。<BR>&nbsp;&nbsp;&nbsp; 
<BR><BR>

<B style="COLOR: #f00"><A name=禁区>13。禁区</A></B><BR>
每天固定时间班主任会发布禁止进入的区域，具体情况可在“进行状况”中察看。<BR>
如果游戏系统没有开启“自动逃避禁区”功能的话，时间一到，要是玩家在禁止区域的话，会颈环爆炸，爆头而死。<BR>
注意，进入连斗后，自动逃避禁区功能关闭。<BR>
如果设置应战策略为“重视躲避”，则禁区增加是可自动逃避禁区。
<B style="COLOR: #f00"><A name=天气>14。天气</A></B><BR>
<br>
在禁止区域发表的同时天气也会变化。<BR>　　天候会影响游戏状况中的各种数据的变化，变化的程度请参考以下的表。<BR><!--
　　基本上，天候只会有些微的变化，最多也只会变化两个阶段。<br>
-->　　当天气不好的时候，有一定的机率没有办法确定对手。<BR>　　这时可能会攻击到自己的队员，请小心。<BR>　　听说还有未知的天气，数值不明、出现时机不明，请自行实验。<BR><BR>
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD>
      <TABLE id=AutoNumber10 
      style="BORDER-RIGHT: black 0.75pt solid; BORDER-TOP: black 0.75pt solid; BORDER-LEFT: black 0.75pt solid; BORDER-BOTTOM: black 0.75pt solid; BORDER-COLLAPSE: collapse; BACKGROUND-COLOR: white" cellSpacing=0 cellPadding=0 border=1 fpstyle="29,111111100">
        <TBODY>
        <TR>
          <TD class=t1 width=70 height=22>天气</TD>
          <TD class=t2>&nbsp;攻击力&nbsp;</TD>
          <TD class=t2>&nbsp;防御力&nbsp;</TD>
          <TD class=t2>&nbsp;回避率&nbsp;</TD>
          <TD class=t2>&nbsp;发现率&nbsp;</TD>
          <TD class=t2>&nbsp;视野&nbsp;</TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#cccccc height=22>『大晴』</TD>
          <TD class=t4><FONT color=#0000ff>极强</FONT></TD>
          <TD class=t4><FONT color=#0000ff>极强</FONT></TD>
          <TD class=t4><FONT color=#0000ff>极强</FONT></TD>
          <TD class=t4><FONT color=#0000ff>极强</FONT></TD>
          <TD class=t4><FONT color=#0000ff>高</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#999999 height=22>『晴天』</TD>
          <TD class=t5><FONT color=#00ffff>强</FONT></TD>
          <TD class=t5><FONT color=#00ffff>强</FONT></TD>
          <TD class=t5><FONT color=#00ffff>强</FONT></TD>
          <TD class=t5><FONT color=#00ffff>强</FONT></TD>
          <TD class=t5><FONT color=#0000ff>高</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#cccccc height=22>『多云』</TD>
          <TD class=t4><FONT color=#ffffff>无</FONT></TD>
          <TD class=t4><FONT color=#ffffff>无</FONT></TD>
          <TD class=t4><FONT color=#ffffff>无</FONT></TD>
          <TD class=t4><FONT color=#ffffff>无</FONT></TD>
          <TD class=t4><FONT color=#0000ff>无</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#999999 height=23>『小雨』</TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微低</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#cccccc height=23>『暴雨』</TD>
          <TD class=t4><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t4><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t4><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t4><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t4><FONT color=#ffff00>低</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#999999 height=23>『雷雨』</TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#ffff00>低</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#cccccc height=23>『台风』</TD>
          <TD class=t4><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t4><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t4><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t4><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t4><FONT color=#ffff00>低</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#999999 height=23>『下雪』</TD>
          <TD class=t5><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t5><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微弱</FONT></TD>
          <TD class=t5><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t5><FONT color=#00ff00>微低</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#cccccc height=23>『起雾』</TD>
          <TD class=t4><FONT color=#ffffff>无</FONT></TD>
          <TD class=t4><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t4><FONT color=#ff00ff>强</FONT></TD>
          <TD class=t4><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t4><FONT color=#ff00ff>极低</FONT></TD></TR>
        <TR>
          <TD class=t3 width=70 bgColor=#999999 height=23>『浓雾』</TD>
          <TD class=t5><FONT color=#ff00ff>微强</FONT></TD>
          <TD class=t5><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t5><FONT color=#ffffff>无</FONT></TD>
          <TD class=t5><FONT color=#ffff00>弱</FONT></TD>
          <TD class=t5><FONT color=#ff0000>超低</FONT></TD>
  </TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
<B style="COLOR: #f00"><A name=武器>15。武器</A></B><BR>
<br>
武器有一定机率损坏。<BR>　　损坏到效用 
=0 
的武器不能再次使用，必须空手作战。<BR>　　无限的武器不会不见，但损坏时会变钝、降低攻击力。<BR>　　枪类武器，不装子弹时只能直接作为钝器攻击，会很快敲坏。 
<BR>　　子弹可以装 6 或 12 
发，装的方法是装备枪时「使用」子弹。<BR>　　投掷或爆类的武器，投出以后就变少了，属于消耗品。<BR><BR>　　特定装备武器种类「使用」属性为「强化」类的道具的话，可以增加攻击力或其它能力。 
<BR><BR>　　已知加攻击力的道具有：磨刀石类(对任何剑系武器)、<BR>　　钉类(只能作用于殴系「棍棒」或「钉棍棒」，注意其它名字如球棒都是不行的)<BR>　　另外针线包可以增加防具的有效次数，灭音器可以让枪消音，<BR>　　都是身上穿着或拿着对应的武器或防具然后「使用」对应的道具即可。<BR>　　其它还有一些道具则要靠你自己摸索猜测。<BR><BR>
<TABLE class=weapon >
  <TBODY>
  <TR>
    <TH>武器种类
    <TH>优势
    <TH>缺点</TR> 
  <TR class=t1>
    <TD>　　枪　　
    <TD>初期高攻击力
    <TD>要上子弹</TD>
  <TR class=t2>
    <TD>　　剑　　
    <TD>可变动攻击力，让对手负伤范围最广
    <TD>变动攻击力,最高损坏率</TD>
  <TR class=t1>
    <TD>　　殴　　
    <TD>空手也有加成,少数有变动攻击力
    <TD>原始攻击力普通 
  <TR class=t2>
    <TD>　　投　　
    <TD>ITD 一定命中，最适合磨掉装甲
    <TD>攻击力小 
  <TR class=t1>
    <TD>　　爆　　
    <TD>ITD 保证伤害值，让低等级也能挑战高等级
    <TD>数量少 
  <TR class=t2>
    <TD>[性别加成]
    <TD align=left colSpan=2>有些武器有特殊性别加成，打同性或异性时可能产生伤害只有 1 或大于100的爆击效果。 
      <BR>所谓性别加成是看双方的性别， 不是只看使用者的性别。 <BR>此类武器不会特别显示， 请玩家自行观察注意。 
</TR></TBODY></TABLE><BR>　　
注： ITD 表示 Ignore Target Defense 无视对方防御<BR>     防御可表现在两种地方上,一种是影响命中机率一种减低伤害值<BR>      所以投与爆的 ITD 各只对应一种
<BR>
<B style="COLOR: #f00"><A name=毒药和陷阱>16。毒药和陷阱</A></B><BR>
<br>
　　有些道具「使用」之后可以在所在的区域设下陷阱。<BR>　　毒药「使用」后可对大部份回复物品下毒，<BR>　　另外也有天然含有毒性的回复道具。<BR>　　设陷阱的时候可以增加经验值和爆熟。<BR>　　自己设的陷阱可能会被自己触发，<BR>　　所以在设下陷阱或是下毒的时候要小心。<BR><BR>　　除此之外，应急治伤没有办法解毒，所以手上要有『解毒剂』比较好。 <BR><BR>
<B style="COLOR: #f00"><A name=特殊道具>17。特殊道具</A></B><BR>
<br>
游戏中部分特殊道具的用途
<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD width=20>　</TD>
    <TD>
      <TABLE style="WIDTH: 500px" cellSpacing=0 cellPadding=0 border=1>
        <TBODY>
        <TR>
          <TD bgColor=#ffff00 width="25%"><B><FONT color=#000000>道具名称</FONT></B></TD>
          <TD bgColor=#ffff00 width="5%">　</TD>
          <TD bgColor=#ffff00 width="70%"><B><FONT color=#000000>道具用途</FONT></B></TD></TR>
        <TR>
          <TD>雷达</TD>
          <TD>→</TD>
          <TD>可显示各个地图上的生存人数</TD></TR>
        <TR>
          <TD>雷达用电池</TD>
          <TD>→</TD>
          <TD>给雷达充电</TD></TR>
        <TR>
          <TD>移动pc</TD>
          <TD>→</TD>
          <TD>使用成功时，可以取消所有的禁区<br>不过也可能导致不幸的后果发生。</TD></TR>
        <TR>
          <TD>电池</TD>
          <TD>→</TD>
          <TD>给移动pc充电</TD></TR>
        <TR>
          <TD>毒药</TD>
          <TD>→</TD>
          <TD>给恢复道具下毒</TD></TR>
        <TR>
          <TD>解毒剂</TD>
          <TD>→</TD>
          <TD>解除自己的中毒状态</TD></TR>
        <TR>
          <TD>磨刀石</TD>
          <TD>→</TD>
          <TD>可以提高斩系武器的攻击力，必须先装备武器</TD></TR>
        <TR>
          <TD>钉</TD>
          <TD>→</TD>
          <TD>可以提高棍棒类武器的攻击力，必须先装备武器</TD></TR>
        <TR>
          <TD>针线包</TD>
          <TD>→</TD>
          <TD>提高护体防具的防御力</TD></TR>
        <TR>
          <TD>避弹衣</TD>
          <TD>→</TD>
          <TD>减少枪械的伤害</TD>
        <TR>
          <TD>消音器</TD>
          <TD>→</TD>
          <TD>可以使你的枪械在攻击时不会发出声音</TD>
        <TR>
          <TD>去死去死团员证</TD>
          <TD>→</TD>
          <TD>佩带后，对同性产生莫大的仇恨，容易被异性迷惑</TD>
        <TR>
          <TD>好人证书</TD>
          <TD>→</TD>
          <TD>佩带后，对异性产生莫大的仇恨，容易被同性迷惑</TD>
         <TR>
          <TD>士兵证书</TD>
          <TD>→</TD>
          <TD>佩带后，提高攻击力</TD>
         <TR>
          <TD>教师证书</TD>
          <TD>→</TD>
          <TD>佩带后，提高防御力</TD>
         <TR>
          <TD>毒物说明书</TD>
          <TD>→</TD>
          <TD>佩带后，下毒效果增加50%。不能与烹饪社下毒效果叠加</TD>
       <TR>
          <TD>御神签<BR></TD>
          <TD>→</TD>
          <TD>占卜自己的命运，根据结果的凶吉改变属性</TD>
        <TR>
          <TD>凸眼鱼<BR></TD>
          <TD>→</TD>
          <TD>可以让地面上所有的尸体都消失</TD>
        <TR>
          <TD>武器师安雅的奖赏<BR></TD>
          <TD>→</TD>
          <TD>大幅强化你的武器，甚至更改武器的类型</TD>
        <TR>
          <TD>天候棒</TD>
          <TD>→</TD>
          <TD>出现异常天气</TD>
        <TR>
          <TD>■DeathNote■</TD>
          <TD>→</TD>
          <TD>？？？？</TD>
</TR>
</TBODY></TABLE></TD></TR></TBODY></TABLE><BR><BR>

<B style="COLOR: #f00"><A name=必杀技>18。必杀技</A></B><BR>
<br>
当等级、熟练度、怒气达到一定条件时，在攻击时有可能出现重击。<BR>
如果是“动漫社”，则可以自由的控制自己的怒气，释放出“必杀技”！！！<BR>
释放方式为在攻击时向对方 <span class="lime">喊话</span> 。<BR>
根据喊话的不同，甚至可以释放出不同的必杀技！（此功能尚未开放。）<BR>
<BR><BR>
<B style="COLOR: #f00"><A name=玩家守则>19。玩家守则</A></B><BR>
<br>
<span class="green">
1。使用不雅昵称或口出脏言者，斩首示众<br>
2。同IP重复登陆者，五马分尸<br>
3。利用系统漏洞谋取利益者，凌迟处死
</span>

</div>



<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
<? include template('footer'); ?>
