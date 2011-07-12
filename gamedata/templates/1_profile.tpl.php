<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="0" width="640" cellspacing="0" cellpadding="0"  valign="middle">
<tr><td>
<table border="0" width="640" cellspacing="0" cellpadding="0"  valign="middle" background="img/profile.gif" style="background-position:bottom left;background-repeat:no-repeat;">
<tr><td height="20" width="60" class="b1"><div class=nttx><?=$pdata['display']['gamehonourinfo']?></div></td>
<td width="145" colspan="2" class="b1"><div class=nttx><?=$pdata['name']?></div></td>
<td width="100" colspan="1" class="b1"><div class=nttx><?=$sexinfo[$pdata['gd']]?><?=$pdata['sNo']?>号</div></td>
<td width="95" colspan="2" class="b1"><div class=nttx>天气:<?=$wthdata[$weather]['name']?></div></td>
<td width="215" colspan="1" class="b1"><div class=nttx><?=$month?>月<?=$day?>日 星期<?=$week["$wday"]?> <?=$hour?>:<?=$min?>
<? if($gamestate == 40 ) { ?>
<span class="yellow">连斗</span>
<? } if($gamestate == 50 ) { ?>
<span class="red">死斗</span>
<? } ?>
</div></td>
</tr>
<tr><td rowspan="4" colspan="2" width="150" height="80" class="b3"><div class=nttx><img src="img/<?=$pdata['display']['iconImg']?>" border="0" style="width:140;height:80" 
<? if($pdata['hp']==0) { ?>
style="filter:Xray()"
<? } ?>
 /></div></td>
<td width="60" class="b2"><div class=nttx>等级</div></td>
<td width="100" class="b3"><div class=nttx>Lv. <?=$pdata['lvl']?></div></td>
<td width="45" class="b2"><div class=nttx>
<? if($pdata['wp'] >= 25) { ?>
殴熟
<? } else { ?>
<span class="grey">殴熟</span>
<? } ?>
</div></td>
<td width="55" class="b3"><div class=nttx><?=$pdata['wp']?></div></td>
<td rowspan="8" width="215" height="160" class="b3">
<div class=nttx>
<table border="0" width=215px height=160px cellspacing="0" cellpadding="0">
<tr height=160px>
<td width=160px background="img/state1.gif" style="position:relative;background-repeat:no-repeat;background-position:right top;">
<div style="border:0; margin:0; cellspacing:0; cellpadding:0; position:absolute;z-index:10;top:0;left:0;">
<?=$infimg?>
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
<tr><td height="20" class="b2"><div class=nttx>经验值</div></td>
<td class="b3"><div class=nttx><?=$pdata['exp']?> / <?=$pdata['display']['upexp']?></div></td>
<td class="b2"><div class=nttx>
<? if($pdata['wk'] >= 25) { ?>
斩熟
<? } else { ?>
<span class="grey">斩熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$pdata['wk']?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>队伍</div></td>
<td class="b3"><div class=nttx>
<? if($pdata['teamID'] && $gamestate < 40 ) { ?>
<?=$pdata['teamID']?>
<? } else { ?>
 无 
<? } ?>
</div></td>
<td class="b2"><div class=nttx>
<? if($pdata['wg'] >= 25) { ?>
射熟
<? } else { ?>
<span class="grey">射熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$pdata['wg']?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>内定称号</div></td>
<td class="b3"><div class=nttx><?=$clubinfo[$pdata['club']]?></div></td>
<td class="b2"><div class=nttx>
<? if($pdata['wc'] >= 25) { ?>
投熟
<? } else { ?>
<span class="grey">投熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$pdata['wc']?></div></td>
</tr>
<tr><td height="20" width="60" class="b2"><div class=nttx>攻击力</div></td>
<td width="80" class="b3"><div class=nttx><?=$pdata['att']?> + <?=$pdata['wepe']?></div></td>
<td class="b2"><div class=nttx>金钱</div></td>
<td class="b3"><div class=nttx><?=$pdata['money']?> 元</div></td>
<td class="b2"><div class=nttx>
<? if($pdata['wd'] >= 25) { ?>
爆熟
<? } else { ?>
<span class="grey">爆熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$pdata['wd']?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>防御力</div></td>
<td class="b3"><div class=nttx><?=$pdata['def']?> + <?=$pdata['display']['ardef']?></div></td>
<td class="b2"><div class=nttx>受伤部位</div></td>
<td class="b3"><div class=nttx><?=$pdata['display']['infwords']?></div></td>
<td class="b2"><div class=nttx>
<? if($pdata['wf'] >= 25) { ?>
灵熟
<? } else { ?>
<span class="grey">灵熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$pdata['wf']?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>基础姿态</div></td>
<td class="b3"><div class=nttx><?=$poseinfo[$pdata['pose']]?></div></td>
<td class="b2"><div class=nttx>体力</div></td>
<td class="b3"><div class=nttx><span class="<?=$spcolor?>"><?=$pdata['sp']?> / <?=$pdata['msp']?></span></div></td>
<td class="b2"><div class=nttx>怒气</div></td>
<td class="b3"><div class=nttx><span class="yellow"><?=$pdata['rage']?></span></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>应战策略</div></td>
<td class="b3"><div class=nttx><?=$tacinfo[$pdata['tactic']]?></div></td>
<td class="b2"><div class=nttx>生命</div></td>
<td class="b3"><div class=nttx><span class="<?=$hpcolor?>"><?=$pdata['hp']?> / <?=$pdata['mhp']?></span></div></td>
<td class="b2"><div class=nttx>击杀数</div></td>
<td class="b3"><div class=nttx><?=$pdata['killnum']?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>工事中</div></td>
<td class="b3"><div class=nttx>工事中</div></td>
<td class="b2"><div class=nttx>工事中</div></td>
<td class="b3"><div class=nttx>工事中</div></td>
<td class="b2"><div class=nttx>技能</div></td>
<td class="b3" colspan="2"><div class=nttx><?=$pdata['display']['techwords']?></div></td>
</tr>
</td>
</tr>
</table>
</td>
</tr>
<tr><td height="10" class="b5"></td></tr>
<tr><td>
  		<TABLE border="0" cellSpacing=0 cellPadding=0 height=140 width=640 background="img/enp.gif" style="background-position:bottom left;background-repeat:no-repeat;">
  			<tr>
  				<td>
  				<TABLE border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
  				<TR>
          			<TD class=b1 height="20" width="65"><div class=nttx>装备种类</div></TD>
          			<TD class=b1><div class=nttx>名称</div></TD>
          			<TD class=b1 width="65"><div class=nttx>属性</div></TD>
          			<TD class=b1 width="25"><div class=nttx>效</div></TD>
          			<TD class=b1 width="25"><div class=nttx>耐</div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($pdata['display']['wepk_words']) { ?>
<?=$pdata['display']['wepk_words']?>
<? } else { ?>
<?=$mltwk?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['weps']) { ?>
<?=$pdata['wep']?>
<? } else { ?>
<?=$nowep?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['wepsk_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['wepe']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['weps']?></div></TD>
          </tr>
          <tr>
          	          <TD class=b2 height="20"><div class=nttx>
<? if($pdata['arbs']) { ?>
<?=$iteminfo['DB']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DN']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['arbs']) { ?>
<?=$pdata['arb']?>
<? } else { ?>
<?=$noarb?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['arbsk_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arbe']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arbs']?></div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($pdata['arhs']) { ?>
<?=$iteminfo['DH']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DH']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['arhs']) { ?>
<?=$pdata['arh']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['arhsk_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arhe']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arhs']?></div></TD>
          </tr>
          <tr>
          			  		<TD class=b2 height="20"><div class=nttx>
<? if($pdata['aras']) { ?>
<?=$iteminfo['DA']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DA']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['aras']) { ?>
<?=$pdata['ara']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['arask_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arae']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['aras']?></div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($pdata['arfs']) { ?>
<?=$iteminfo['DF']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DF']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['arfs']) { ?>
<?=$pdata['arf']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['arfsk_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arfe']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arfs']?></div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($pdata['arts']) { ?>
<?=$pdata['display']['artk_words']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['A']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['arts']) { ?>
<?=$pdata['art']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['artsk_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arte']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['arts']?></div></TD>
          </tr>
        </table>
      </td>
      <td>
      	<TABLE border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
      		<tr>
         			<TD class=b1 height="20" width="65"><div class=nttx>道具用途</div></TD>
         			<TD class=b1><div class=nttx>名称</div></TD>
         			<TD class=b1 width="65"><div class=nttx>属性</div></TD>
          			<TD class=b1 width="25"><div class=nttx>效</div></TD>
          			<TD class=b1 width="25"><div class=nttx>耐</div></TD>
          </TR>
          		<tr>          			  
                      <TD class=b2><div class=nttx>
<? if($pdata['display']['itmk1_words']) { ?>
<?=$pdata['display']['itmk1_words']?>
<? } else { ?>
<span class="grey">包裹1</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['itms1']) { ?>
<?=$pdata['itm1']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['itmsk1_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itme1']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itms1']?></div></TD>
                      </tr>
                <tr>
                      <TD class=b2><div class=nttx>
<? if($pdata['display']['itmk2_words']) { ?>
<?=$pdata['display']['itmk2_words']?>
<? } else { ?>
<span class="grey">包裹2</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['itms2']) { ?>
<?=$pdata['itm2']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['itmsk2_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itme2']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itms2']?></div></TD>
                      </tr>
                <tr>          			  
                      <TD class=b2><div class=nttx>
<? if($pdata['display']['itmk3_words']) { ?>
<?=$pdata['display']['itmk3_words']?>
<? } else { ?>
<span class="grey">包裹3</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['itms3']) { ?>
<?=$pdata['itm3']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['itmsk3_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itme3']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itms3']?></div></TD>
                      </tr>
                <tr>          	
                      <TD class=b2><div class=nttx>
<? if($pdata['display']['itmk4_words']) { ?>
<?=$pdata['display']['itmk4_words']?>
<? } else { ?>
<span class="grey">包裹4</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['itms4']) { ?>
<?=$pdata['itm4']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['itmsk4_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itme4']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itms4']?></div></TD>
                      </tr>
                <tr>          			  
                      <TD class=b2><div class=nttx>
<? if($pdata['display']['itmk5_words']) { ?>
<?=$pdata['display']['itmk5_words']?>
<? } else { ?>
<span class="grey">包裹5</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['itms5']) { ?>
<?=$pdata['itm5']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['itmsk5_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itme5']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itms5']?></div></TD>
                      </tr>
               <tr>          			  
                      <TD class=b2><div class=nttx>
<? if($pdata['display']['itmk6_words']) { ?>
<?=$pdata['display']['itmk6_words']?>
<? } else { ?>
<span class="grey">包裹6</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($pdata['itms6']) { ?>
<?=$pdata['itm6']?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['display']['itmsk6_words']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itme6']?></div></TD>
                      <TD class=b3><div class=nttx><?=$pdata['itms6']?></div></TD>
               </tr>
              </table>
            </td></tr>
  			
  		</TABLE>
</td></tr>
</table>