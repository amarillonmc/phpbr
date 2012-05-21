<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="0" width="720" height="100%" cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td>
<table border="0" width="720" cellspacing="0" cellpadding="0"  valign="middle">
<tr>
<td width="210" colspan="3" class="b1"><span><?=$name?></span></td>
<td width="100" colspan="1" class="b1"><span><?=$sexinfo[$gd]?><?=$sNo?>号</span></td>
<td width="95" colspan="2" class="b1"><span>天气:<?=$wthinfo[$weather]?></span></td>
<td width="215" colspan="1" class="b1"><span><?=$month?>月<?=$day?>日 星期<?=$week["$wday"]?> <?=$hour?>:<?=$min?>
<? if($gamestate == 40 ) { ?>
<span class="yellow">连斗</span>
<? } if($gamestate == 50 ) { ?>
<span class="red">死斗</span>
<? } ?>
</span></td>
</tr>
<tr>
<td rowspan="4" colspan="2" width="150" height="80" class="b3"><span><img src="img/<?=$iconImg?>" border="0" style="width:140;height:80" 
<? if($hp==0) { ?>
style="filter:Xray()"
<? } ?>
 /></span></td>
<td width="70" class="b2"><span>等级</span></td>
<td width="120" class="b3"><span>Lv. <?=$lvl?></span></td>
<td width="60" class="b2"><span>
<? if($wp >= 100) { ?>
殴熟
<? } else { ?>
<span class="grey">殴熟</span>
<? } ?>
</span></td>
<td width="80" class="b3"><span><?=$wp?></span></td>
<td rowspan="8" width="215" height="160" class="b3">
<div>
<table border="0" width=215px height=160px cellspacing="0" cellpadding="0" style="position:relative">
<tr height=160px>
<td width=160px background="img/state1.gif" style="position:relative;background-repeat:no-repeat;background-position:right top;">
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;z-index:10;top:0;left:0;">
<img id="injuerd" 
<? if(strpos($inf,'h') !== false || strpos($inf,'b') !== false ||strpos($inf,'a') !== false ||strpos($inf,'f') !== false) { ?>
src="img/injured.gif"
<? } else { ?>
src="img/injured2.gif"
<? } ?>
 style="position:absolute;top:0;left:10;width:84;height:20;">
<img id="poisoned" 
<? if(strpos($inf,'p') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infp';postCmd('gamecmd','command.php');return false;" 
<? } ?>
src="img/p.gif"
<? } else { ?>
src="img/p2.gif"
<? } ?>
 style="position:absolute;top:20;left:4;width:98;height:20;">
<img id="burned" 
<? if(strpos($inf,'u') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infu';postCmd('gamecmd','command.php');return false;" 
<? } ?>
src="img/u.gif"
<? } else { ?>
src="img/u2.gif"
<? } ?>
 style="position:absolute;top:40;left:11;width:81;height:20;">
<img id="frozen" 
<? if(strpos($inf,'i') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infi';postCmd('gamecmd','command.php');return false;" 
<? } ?>
src="img/i.gif"
<? } else { ?>
src="img/i2.gif"
<? } ?>
 style="position:absolute;top:60;left:13;width:77;height:20;">
<img id="paralysed" 
<? if(strpos($inf,'e') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infe';postCmd('gamecmd','command.php');return false;" 
<? } ?>
src="img/e.gif"
<? } else { ?>
src="img/e2.gif"
<? } ?>
 style="position:absolute;top:80;left:2;width:101;height:20;">
<img id="confused" 
<? if(strpos($inf,'w') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infw';postCmd('gamecmd','command.php');return false;" 
<? } ?>
src="img/w.gif"
<? } else { ?>
src="img/w2.gif"
<? } ?>
 style="position:absolute;top:100;left:3;width:100;height:20;">
<? if(strpos($inf,'h') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:0;left:121;width:37;height:37;" 
<? if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infh';postCmd('gamecmd','command.php');return false;"
<? } ?>
>
<? } if(strpos($inf,'b') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:43;left:121;width:37;height:37;" 
<? if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infb';postCmd('gamecmd','command.php');return false;"
<? } ?>
>
<? } if(strpos($inf,'a') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:17;left:102;width:37;height:37;" 
<? if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infa';postCmd('gamecmd','command.php');return false;"
<? } ?>
>
<? } if(strpos($inf,'f') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:111;left:121;width:37;height:37;" 
<? if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='inff';postCmd('gamecmd','command.php');return false;"
<? } ?>
>
<? } if($hp <= 0) { ?>
<img src="img/dead.gif" style="position:absolute;top:120;left:6;width:94;height:40">
<? } elseif($hp <= $mhp*0.2) { ?>
<img src="img/danger.gif" style="position:absolute;top:120;left:5;width:95;height:37">
<? } elseif($hp <= $mhp*0.5) { ?>
<img src="img/caution.gif" style="position:absolute;top:120;left:5;width:95;height:36">
<? } elseif(!$inf) { ?>
<img src="img/fine.gif" style="position:absolute;top:120;left:12;width:81;height:38">
<? } ?>
</div>
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;top:0px;left:105px;z-index:1;">
<?=$newspimg?>
</div>
</td>
<td width=55px background="img/state2.gif" style="position:relative;background-repeat:no-repeat;background-position:left top;">
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;top:0px;right:55px;z-index:1;">
<?=$newhpimg?>
</div>
</td>
</tr>
</table>
</div>
</td>
</tr>
<tr>
<td class="b2"><span>经验值</span></td>
<td class="b3"><span><?=$exp?> / <?=$upexp?></span></td>
<td class="b2"><span>
<? if($wk >= 100) { ?>
斩熟
<? } else { ?>
<span class="grey">斩熟</span>
<? } ?>
</span></td>
<td class="b3"><span><?=$wk?></span></td>
</tr>
<tr>
<td class="b2"><span>队伍</span></td>
<td class="b3"><span>
<? if($teamID && $gamestate < 40 ) { ?>
<?=$teamID?>
<? } else { ?>
 无 
<? } ?>
</span></td>
<td class="b2"><span>
<? if($wg >= 100) { ?>
射熟
<? } else { ?>
<span class="grey">射熟</span>
<? } ?>
</span></td>
<td class="b3"><span><?=$wg?></span></td>
</tr>
<tr>
<td class="b2"><span>内定称号</span></td>
<td class="b3"><span><?=$clubinfo[$club]?></span></td>
<td class="b2"><span>
<? if($wc >= 100) { ?>
投熟
<? } else { ?>
<span class="grey">投熟</span>
<? } ?>
</span></td>
<td class="b3"><span><?=$wc?></span></td>
</tr>
<tr>
<td width="70" class="b2"><span>攻击力</span></td>
<td width="80" class="b3"><span><?=$att?> + <?=$wepe?></span></td>
<td class="b2"><span>金钱</span></td>
<td class="b3"><span><?=$money?> 元</span></td>
<td class="b2"><span>
<? if($wd >= 100) { ?>
爆熟
<? } else { ?>
<span class="grey">爆熟</span>
<? } ?>
</span></td>
<td class="b3"><span><?=$wd?></span></td>
</tr>
<tr>
<td class="b2"><span>防御力</span></td>
<td class="b3"><span><?=$def?> + <?=$ardef?></span></td>
<td class="b2"><span>受伤部位</span></td>
<td class="b3">
<span>
<? if($inf) { if(is_array($infinfo)) { foreach($infinfo as $key => $val) { if(strpos($inf,$key)!==false) { ?>
<?=$val?>
<? } } } } else { ?>
无
<? } ?>
</span>
</td>
<td class="b2"><span>
<? if($wf >= 100) { ?>
灵熟
<? } else { ?>
<span class="grey">灵熟</span>
<? } ?>
</span></td>
<td class="b3"><span><?=$wf?></span></td>
</tr>
<tr>
<td class="b2"><span>基础姿态</span></td>
<td class="b3">
<span>
<select id="pose" name="pose" onchange="$('mode').value='special';$('command').value=$('pose').value;postCmd('gamecmd','command.php');return false;" 
<? if(CURSCRIPT != 'game' || $mode != 'command') { ?>
disabled
<? } ?>
>
<? if(is_array($poseinfo)) { foreach($poseinfo as $key => $value) { if($value) { ?>
<option value="pose<?=$key?>"
<? if($pose == $key) { ?>
selected
<? } ?>
><?=$value?>
<? } } } ?>
</select>
</span>
</td>
<td class="b2"><span>体力</span></td>
<td class="b3"><span><span class="
<? if($sp <= $msp*0.2) { ?>
grey
<? } elseif($sp <= $msp*0.5) { ?>
yellow
<? } else { ?>
clan
<? } ?>
"><?=$sp?> / <?=$msp?></span></span></td>
<td class="b2"><span>怒气</span></td>
<td class="b3"><span>
<? if($rage >= 30) { ?>
<span class="yellow"><?=$rage?></span>
<? } else { ?>
<?=$rage?>
<? } ?>
</span></td>
</tr>
<tr>
<td class="b2"><span>应战策略</span></td>
<td class="b3">
<span>
<select id="tactic" name="tactic" onchange="$('mode').value='special';$('command').value=$('tactic').value;postCmd('gamecmd','command.php');return false;" 
<? if(CURSCRIPT != 'game' || $mode != 'command') { ?>
disabled
<? } ?>
>
<? if(is_array($tacinfo)) { foreach($tacinfo as $key => $value) { if($value) { ?>
<option value="tac<?=$key?>"
<? if($tactic == $key) { ?>
selected
<? } ?>
><?=$value?>
<? } } } ?>
</select>
</span>
</td>
<td class="b2"><span>生命</span></td>
<td class="b3"><span><span class="
<? if($hp <= $mhp*0.2) { ?>
red
<? } elseif($hp <= $mhp*0.5) { ?>
yellow
<? } else { ?>
clan
<? } ?>
"><?=$hp?> / <?=$mhp?></span></span></td>
<td class="b2"><span>击杀数</span></td>
<td class="b3"><span><?=$killnum?></span></td>
</tr>
</table>
</td>
</tr>
<tr>
<td height="10" class="b5"></td>
</tr>
<tr>
<td>
  		<TABLE border="0" cellSpacing=0 cellPadding=0 height=140 width=720>
  			<tr>
      		<td>
      	<TABLE border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
  						<TR>
          			<TD class=b1 width="60"><span>装备种类</span></TD>
          			<TD class=b1><span>名称</span></TD>
          			<TD class=b1 width="70"><span>属性</span></TD>
          			<TD class=b1 width="40"><span>效</span></TD>
          			<TD class=b1 width="40"><span>耐</span></TD>
          		</tr>
          		<tr>
    						<TD class=b2 height="26"><span>
<? if($wepk_words) { ?>
<?=$wepk_words?>
<? } else { ?>
<?=$mltwk?>
<? } ?>
</span></TD>
                <TD class=b3>
                
<? if(CURSCRIPT == 'game' && $mode == 'command' && $wepe) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offwep';postCmd('gamecmd','command.php');return false;"</span>
<? } ?>
                	<span>
<? if($weps) { ?>
<?=$wep?>
<? } else { ?>
<?=$nowep?>
<? } ?>
</span>
                </TD>
                <TD class=b3><span><?=$wepsk_words?></span></TD>
                <TD class=b3><span><?=$wepe?></span></TD>
                <TD class=b3><span><?=$weps?></span></TD>
          </tr>
          <tr>
    	          <TD class=b2 height="26"><span>
<? if($arbs) { ?>
<?=$iteminfo['DB']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DB']?></span>
<? } ?>
</span></TD>
                <TD class=b3>
                
<? if(CURSCRIPT == 'game' && $mode == 'command' && $arbe) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarb';postCmd('gamecmd','command.php');return false;"</span>
<? } ?>
                	<span>
<? if($arbs) { ?>
<?=$arb?>
<? } else { ?>
<?=$noarb?>
<? } ?>
</span>
                </TD>
                <TD class=b3><span><?=$arbsk_words?></span></TD>
                <TD class=b3><span><?=$arbe?></span></TD>
                <TD class=b3><span><?=$arbs?></span></TD>
          </tr>
          <tr>
    						<TD class=b2 height="26"><span>
<? if($arhs) { ?>
<?=$iteminfo['DH']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DH']?></span>
<? } ?>
</span></TD>
                <TD class=b3>
                
<? if(CURSCRIPT == 'game' && $mode == 'command' && $arhs) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarh';postCmd('gamecmd','command.php');return false;"</span>
<? } ?>
                	<span>
<? if($arhs) { ?>
<?=$arh?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span>
                </TD>
                <TD class=b3><span><?=$arhsk_words?></span></TD>
                <TD class=b3><span><?=$arhe?></span></TD>
                <TD class=b3><span><?=$arhs?></span></TD>
          </tr>
          <tr>
    			  		<TD class=b2 height="26"><span>
<? if($aras) { ?>
<?=$iteminfo['DA']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DA']?></span>
<? } ?>
</span></TD>
                <TD class=b3>
                
<? if(CURSCRIPT == 'game' && $mode == 'command' && $aras) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offara';postCmd('gamecmd','command.php');return false;"</span>
<? } ?>
                	<span>
<? if($aras) { ?>
<?=$ara?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span>
                </TD>
                <TD class=b3><span><?=$arask_words?></span></TD>
                <TD class=b3><span><?=$arae?></span></TD>
                <TD class=b3><span><?=$aras?></span></TD>
          </tr>
          <tr>
    						<TD class=b2 height="26"><span>
<? if($arfs) { ?>
<?=$iteminfo['DF']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DF']?></span>
<? } ?>
</span></TD>
                <TD class=b3>
                
<? if(CURSCRIPT == 'game' && $mode == 'command' && $arfs) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarf';postCmd('gamecmd','command.php');return false;"</span>
<? } ?>
                	<span>
<? if($arfs) { ?>
<?=$arf?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span>
                </TD>
                <TD class=b3><span><?=$arfsk_words?></span></TD>
                <TD class=b3><span><?=$arfe?></span></TD>
                <TD class=b3><span><?=$arfs?></span></TD>
          </tr>
          <tr>
    						<TD class=b2 height="26"><span>
<? if($arts) { ?>
<?=$artk_words?>
<? } else { ?>
<span class="grey"><?=$iteminfo['A']?></span>
<? } ?>
</span></TD>
                <TD class=b3>
                
<? if(CURSCRIPT == 'game' && $mode == 'command' && $arts) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offart';postCmd('gamecmd','command.php');return false;"</span>
<? } ?>
                	<span>
<? if($arts) { ?>
<?=$art?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span>
                </TD>
                <TD class=b3><span><?=$artsk_words?></span></TD>
                <TD class=b3><span><?=$arte?></span></TD>
                <TD class=b3><span><?=$arts?></span></TD>
          		</tr>
        		</table>
</td>
<td>
  					<TABLE border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
      		<tr>
<TD class=b1 width="60"><span>道具用途</span></TD>
<TD class=b1><span>名称</span></TD>
<TD class=b1 width="70"><span>属性</span></TD>
<TD class=b1 width="40"><span>效</span></TD>
<TD class=b1 width="40"><span>耐</span></TD>
</TR>
<tr>          			  
<TD class=b2 height="26"><span>
<? if($itmk1_words) { ?>
<?=$itmk1_words?>
<? } else { ?>
<span class="grey">包裹1</span>
<? } ?>
</span></TD>
<TD class=b3><span>
<? if($itms1) { ?>
<?=$itm1?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span></TD>
<TD class=b3><span><?=$itmsk1_words?></span></TD>
<TD class=b3><span><?=$itme1?></span></TD>
<TD class=b3><span><?=$itms1?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<? if($itmk2_words) { ?>
<?=$itmk2_words?>
<? } else { ?>
<span class="grey">包裹2</span>
<? } ?>
</span></TD>
<TD class=b3><span>
<? if($itms2) { ?>
<?=$itm2?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span></TD>
<TD class=b3><span><?=$itmsk2_words?></span></TD>
<TD class=b3><span><?=$itme2?></span></TD>
<TD class=b3><span><?=$itms2?></span></TD>
</tr>
<tr>          			  
<TD class=b2 height="26"><span>
<? if($itmk3_words) { ?>
<?=$itmk3_words?>
<? } else { ?>
<span class="grey">包裹3</span>
<? } ?>
</span></TD>
<TD class=b3><span>
<? if($itms3) { ?>
<?=$itm3?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span></TD>
<TD class=b3><span><?=$itmsk3_words?></span></TD>
<TD class=b3><span><?=$itme3?></span></TD>
<TD class=b3><span><?=$itms3?></span></TD>
</tr>
<tr>          	
<TD class=b2 height="26"><span>
<? if($itmk4_words) { ?>
<?=$itmk4_words?>
<? } else { ?>
<span class="grey">包裹4</span>
<? } ?>
</span></TD>
<TD class=b3><span>
<? if($itms4) { ?>
<?=$itm4?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span></TD>
<TD class=b3><span><?=$itmsk4_words?></span></TD>
<TD class=b3><span><?=$itme4?></span></TD>
<TD class=b3><span><?=$itms4?></span></TD>
</tr>
<tr>          			  
<TD class=b2 height="26"><span>
<? if($itmk5_words) { ?>
<?=$itmk5_words?>
<? } else { ?>
<span class="grey">包裹5</span>
<? } ?>
</span></TD>
<TD class=b3><span>
<? if($itms5) { ?>
<?=$itm5?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span></TD>
<TD class=b3><span><?=$itmsk5_words?></span></TD>
<TD class=b3><span><?=$itme5?></span></TD>
<TD class=b3><span><?=$itms5?></span></TD>
</tr>
<tr>          			  
<TD class=b2 height="26"><span>
<? if($itmk6_words) { ?>
<?=$itmk6_words?>
<? } else { ?>
<span class="grey">包裹6</span>
<? } ?>
</span></TD>
<TD class=b3><span>
<? if($itms6) { ?>
<?=$itm6?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</span></TD>
<TD class=b3><span><?=$itmsk6_words?></span></TD>
<TD class=b3><span><?=$itme6?></span></TD>
<TD class=b3><span><?=$itms6?></span></TD>
</tr>
</table>
      		</td>
      	</tr>
</TABLE>
</td>
</tr>
</table>