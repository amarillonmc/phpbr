<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="0" width="640" cellspacing="0" cellpadding="0"  valign="middle">
<tr><td>
<table border="0" width="640" cellspacing="0" cellpadding="0"  valign="middle" background="img/profile.gif" style="background-position:bottom left;background-repeat:no-repeat;">
<tr><td height="20" width="210" colspan="3" class="b1"><div class=nttx><?=$name?></div></td>
<td width="100" colspan="1" class="b1"><div class=nttx><?=$sexinfo[$gd]?><?=$sNo?>号</div></td>
<td width="95" colspan="2" class="b1"><div class=nttx>天气:<?=$wthinfo[$weather]?></div></td>
<td width="215" colspan="1" class="b1"><div class=nttx><?=$month?>月<?=$day?>日 星期<?=$week["$wday"]?> <?=$hour?>:<?=$min?>
<? if($gamestate == 40 ) { ?>
<span class="yellow">连斗</span>
<? } if($gamestate == 50 ) { ?>
<span class="red">死斗</span>
<? } ?>
</div></td>
</tr>
<tr><td rowspan="4" colspan="2" width="150" height="80" class="b3"><div class=nttx><img src="img/<?=$iconImg?>" border="0" style="width:140;height:80" 
<? if($hp==0) { ?>
style="filter:Xray()"
<? } ?>
 /></div></td>
<td width="60" class="b2"><div class=nttx>等级</div></td>
<td width="100" class="b3"><div class=nttx>Lv. <?=$lvl?></div></td>
<td width="45" class="b2"><div class=nttx>
<? if($wp >= 25) { ?>
殴熟
<? } else { ?>
<span class="grey">殴熟</span>
<? } ?>
</div></td>
<td width="55" class="b3"><div class=nttx><?=$wp?></div></td>
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
<td class="b3"><div class=nttx><?=$exp?> / <?=$upexp?></div></td>
<td class="b2"><div class=nttx>
<? if($wk >= 25) { ?>
斩熟
<? } else { ?>
<span class="grey">斩熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$wk?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>队伍</div></td>
<td class="b3"><div class=nttx>
<? if($teamID && $gamestate < 40 ) { ?>
<?=$teamID?>
<? } else { ?>
 无 
<? } ?>
</div></td>
<td class="b2"><div class=nttx>
<? if($wg >= 25) { ?>
射熟
<? } else { ?>
<span class="grey">射熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$wg?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>内定称号</div></td>
<td class="b3"><div class=nttx><?=$clubinfo[$club]?></div></td>
<td class="b2"><div class=nttx>
<? if($wc >= 25) { ?>
投熟
<? } else { ?>
<span class="grey">投熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$wc?></div></td>
</tr>
<tr><td height="20" width="60" class="b2"><div class=nttx>攻击力</div></td>
<td width="80" class="b3"><div class=nttx><?=$att?> + <?=$wepe?></div></td>
<td class="b2"><div class=nttx>金钱</div></td>
<td class="b3"><div class=nttx><?=$money?> 元</div></td>
<td class="b2"><div class=nttx>
<? if($wd >= 25) { ?>
爆熟
<? } else { ?>
<span class="grey">爆熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$wd?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>防御力</div></td>
<td class="b3"><div class=nttx><?=$def?> + <?=$ardef?></div></td>
<td class="b2"><div class=nttx>受伤部位</div></td>
<td class="b3"><div class=nttx>
<? if($infdata) { ?>
<?=$infdata?>
<? } else { ?>
无
<? } ?>
</div></td>
<td class="b2"><div class=nttx>
<? if($wf >= 25) { ?>
灵熟
<? } else { ?>
<span class="grey">灵熟</span>
<? } ?>
</div></td>
<td class="b3"><div class=nttx><?=$wf?></div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>基础姿态</div></td>
<td class="b3"><div class=nttx><?=$poseinfo[$pose]?></div></td>
<td class="b2"><div class=nttx>体力</div></td>
<td class="b3"><div class=nttx><span class="<?=$spcolor?>"><?=$sp?> / <?=$msp?></span></div></td>
<td class="b2"><div class=nttx>怒气</div></td>
<td class="b3"><div class=nttx>
<? if($rage >= 30) { ?>
<span class="yellow"><?=$rage?></span>
<? } else { ?>
<?=$rage?>
<? } ?>
</div></td>
</tr>
<tr><td height="20" class="b2"><div class=nttx>应战策略</div></td>
<td class="b3"><div class=nttx><?=$tacinfo[$tactic]?></div></td>
<td class="b2"><div class=nttx>生命</div></td>
<td class="b3"><div class=nttx><span class="<?=$hpcolor?>"><?=$hp?> / <?=$mhp?></span></div></td>
<td class="b2"><div class=nttx>击杀数</div></td>
<td class="b3"><div class=nttx><?=$killnum?></div></td>
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
<? if($wepk_words) { ?>
<?=$wepk_words?>
<? } else { ?>
<?=$mltwk?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($weps) { ?>
<?=$wep?>
<? } else { ?>
<?=$nowep?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$wepsk_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$wepe?></div></TD>
                      <TD class=b3><div class=nttx><?=$weps?></div></TD>
          </tr>
          <tr>
          	          <TD class=b2 height="20"><div class=nttx>
<? if($arbs) { ?>
<?=$iteminfo['DB']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DB']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($arbs) { ?>
<?=$arb?>
<? } else { ?>
<?=$noarb?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$arbsk_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$arbe?></div></TD>
                      <TD class=b3><div class=nttx><?=$arbs?></div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($arhs) { ?>
<?=$iteminfo['DH']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DH']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($arhs) { ?>
<?=$arh?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$arhsk_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$arhe?></div></TD>
                      <TD class=b3><div class=nttx><?=$arhs?></div></TD>
          </tr>
          <tr>
          			  		<TD class=b2 height="20"><div class=nttx>
<? if($aras) { ?>
<?=$iteminfo['DA']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DA']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($aras) { ?>
<?=$ara?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$arask_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$arae?></div></TD>
                      <TD class=b3><div class=nttx><?=$aras?></div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($arfs) { ?>
<?=$iteminfo['DF']?>
<? } else { ?>
<span class="grey"><?=$iteminfo['DF']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($arfs) { ?>
<?=$arf?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$arfsk_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$arfe?></div></TD>
                      <TD class=b3><div class=nttx><?=$arfs?></div></TD>
          </tr>
          <tr>
          						<TD class=b2 height="20"><div class=nttx>
<? if($arts) { ?>
<?=$artk_words?>
<? } else { ?>
<span class="grey"><?=$iteminfo['A']?></span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($arts) { ?>
<?=$art?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$artsk_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$arte?></div></TD>
                      <TD class=b3><div class=nttx><?=$arts?></div></TD>
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
<? if($itmk1_words) { ?>
<?=$itmk1_words?>
<? } else { ?>
<span class="grey">包裹1</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($itms1) { ?>
<?=$itm1?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$itmsk1_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$itme1?></div></TD>
                      <TD class=b3><div class=nttx><?=$itms1?></div></TD>
                      </tr>
                <tr>
                      <TD class=b2><div class=nttx>
<? if($itmk2_words) { ?>
<?=$itmk2_words?>
<? } else { ?>
<span class="grey">包裹2</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($itms2) { ?>
<?=$itm2?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$itmsk2_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$itme2?></div></TD>
                      <TD class=b3><div class=nttx><?=$itms2?></div></TD>
                      </tr>
                <tr>          			  
                      <TD class=b2><div class=nttx>
<? if($itmk3_words) { ?>
<?=$itmk3_words?>
<? } else { ?>
<span class="grey">包裹3</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($itms3) { ?>
<?=$itm3?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$itmsk3_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$itme3?></div></TD>
                      <TD class=b3><div class=nttx><?=$itms3?></div></TD>
                      </tr>
                <tr>          	
                      <TD class=b2><div class=nttx>
<? if($itmk4_words) { ?>
<?=$itmk4_words?>
<? } else { ?>
<span class="grey">包裹4</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($itms4) { ?>
<?=$itm4?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$itmsk4_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$itme4?></div></TD>
                      <TD class=b3><div class=nttx><?=$itms4?></div></TD>
                      </tr>
                <tr>          			  
                      <TD class=b2><div class=nttx>
<? if($itmk5_words) { ?>
<?=$itmk5_words?>
<? } else { ?>
<span class="grey">包裹5</span>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx>
<? if($itms5) { ?>
<?=$itm5?>
<? } else { ?>
<?=$noitm?>
<? } ?>
</div></TD>
                      <TD class=b3><div class=nttx><?=$itmsk5_words?></div></TD>
                      <TD class=b3><div class=nttx><?=$itme5?></div></TD>
                      <TD class=b3><div class=nttx><?=$itms5?></div></TD>
                      </tr>
               <tr>          			  
                      <TD class=b2><div class=nttx>工事中</div></TD>
                      <TD class=b3><div class=nttx> </div></TD>
                      <TD class=b3><div class=nttx> </div></TD>
                      <TD class=b3><div class=nttx> </div></TD>
                      <TD class=b3><div class=nttx> </div></TD>
               </tr>
              </table>
            </td></tr>
  			
  		</TABLE>
</td></tr>
</table>