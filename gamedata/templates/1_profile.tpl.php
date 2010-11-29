<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="1" width="550" height="320" cellspacing="0" cellpadding="0"  valign="middle">
<tr><td width="70" colspan="1" class="b1"><b>lv. <?=$lvl?></b></td>
<td colspan="3" class="b1"><b><?=$month?>月 <?=$day?>日 星期<?=$week["$wday"]?> <?=$hour?>:<?=$min?> 
<? if($gamestate == 40 ) { ?>
<font color="red">连斗</font>
<? } ?>
</b></td>
<td class="b1" width="47"><b>经验值</b></td>
<td class="b3" width="78"><?=$exp?> / <?=$upexp?></td>
<td class="b1" width="47"><b>天气</b></td>
<td class="b3" width="79"><b><?=$wthinfo[$weather]?></b></td></tr>
<tr><td rowspan="4" height="70" class="b3"><img src="img/<?=$iconImg?>" border="0" align="middle"></td>
<td width="70" class="b2"><b>姓名</b></td>
<td width="145" colspan="2" class="b3"><?=$name?></td>
<td rowspan="4" height="70" class="b2"><b>状态</b></td>
<td rowspan="4" colspan="4" class="b3" height="70"><img src="img/<?=$infimg?>.gif" align="top" border="0" align="middle"><embed src="img/<?=$hstate?>.swf" height=70 width=140></td></tr>
<tr><td rowspan="1" class="b2"><b>学号</b></td>
<td rowspan="1" colspan="2" class="b3"><?=$sexinfo[$gd]?> <?=$sNo?> 号 </td></tr>
<tr><td class="b2"><b>队伍</b></td>
<td colspan="2" class="b3">
<? if($teamID) { ?>
<?=$teamID?>
<? } else { ?>
无
<? } ?>
</td></tr>
<tr><td class="b2"><b>受伤部位</b></td>
<td colspan="2" class="b3">
<? if($infdata) { ?>
<?=$infdata?>
<? } else { ?>
无
<? } ?>
</td></tr>
<tr><td class="b2"><b>攻击力</b></td>
<td class="b3"><?=$att?> + <?=$wepe?></td>
<td class="b2" width="70"><b>体力</b></td>
<td class="b3" width="74"><?=$sp?> / <?=$msp?></td>
<td colspan="4" class="b3"><?=$spimg?></td></tr>
<tr><td class="b2"><b>防御力</b></td>
<td class="b3"><?=$def?> + <?=$ardef?></td>
<td class="b2"><b>生命</b></td>
<td class="b3"><?=$hp?> / <?=$mhp?></td>
<td colspan="4" class="b3"><?=$hpimg?></td></tr>
<tr><td class="b2"><b>基础姿态</b></td>
<td class="b3"><?=$poseinfo[$pose]?></td>
<td class="b2"><b>怒气</b></td>
<td class="b3"><span class="yellow"><b><?=$rage?></b></span></td>
<td class="b2"><b>熟练度</b></td>
<td colspan="3" class="b3">殴:<?=$wp?> 斩:<?=$wk?> 射:<?=$wg?> 投:<?=$wc?> 爆:<?=$wd?> 灵:<?=$wf?> </td></tr>
<tr><td class="b2"><b>应战策略</b></td>
<td class="b3"><?=$tacinfo[$tactic]?></td>
<td class="b2"><b>内定称号</b></td>
<td class="b3"><?=$clubinfo[$club]?></td>
<td class="b2"><b>击杀数</b></td>
<td class="b3"><?=$killnum?></td>
<td class="b2"><b>金钱</b></td>
<td class="b3"><?=$money?> 元</td></tr>
<tr><td class="b3" colspan="8" height="1" align="center">
<table border="0" cellspacing="0" cellpadding="0" height="110" >
<tr><td>
<table border="1" cellspacing="0" cellpadding="0">
  <tr><td class="b1" colspan="4" rowspan="8" >装备</td>
  <td>
  <tr><td class="b2">种</td><td class="b2">名</td><td class="b2">效</td><td class="b2">耐</td></tr>
  <tr><td class="b3">【<?=$iteminfo[$wepk]?>
<? if(strpos($wepsk,'S') !== false) { ?>
-消
<? } ?>
】</td><td class="b3">
<? if($weps) { ?>
<?=$wep?>
<? } else { ?>
<?=$nowep?>
<? } ?>
</td><td class="b3"><?=$wepe?></td><td class="b3"><?=$weps?></td></tr>
  <tr><td class="b3">【防具(体)】</td><td class="b3">
<? if($arbs) { ?>
<?=$arb?>
<? } else { ?>
<?=$noarb?>
<? } ?>
</td><td class="b3"><?=$arbe?></td><td class="b3"><?=$arbs?></td></tr>
  <tr><td class="b3">【防具(头)】</td><td class="b3">
<? if($arhs) { ?>
<?=$arh?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$arhe?></td><td class="b3"><?=$arhs?></td></tr>
  <tr><td class="b3">【防具(腕)】</td><td class="b3">
<? if($aras) { ?>
<?=$ara?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$arae?></td><td class="b3"><?=$aras?></td></tr>
  <tr><td class="b3">【防具(足)】</td><td class="b3">
<? if($arfs) { ?>
<?=$arf?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$arfe?></td><td class="b3"><?=$arfs?></td></tr>
  <tr><td class="b3">【饰品】</td><td class="b3">
<? if($arts) { ?>
<?=$art?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$arte?></td><td class="b3"><?=$arts?></td></tr>
  </td></tr>
</table>
</td>
<td width="20"></td>
<td>
<table border="1" cellspacing="0" cellpadding="0">
  <tr><td class="b1" colspan="4" rowspan="7" >包裹<td></td>
  <tr><td class="b2">名</td><td class="b2">效</td><td class="b2">耐</td><td class="b2">用途</td></tr>
  <tr><td class="b3">
<? if($itms1) { ?>
<?=$itm1?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$itme1?></td><td class="b3"><?=$itms1?></td><td class="b3"><font color="00ffff">【<?=$iteminfo[$itmk1]?>】</font></td></tr>
  <tr><td class="b3">
<? if($itms2) { ?>
<?=$itm2?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$itme2?></td><td class="b3"><?=$itms2?></td><td class="b3"><font color="00ffff">【<?=$iteminfo[$itmk2]?>】</font></td></tr>
  <tr><td class="b3">
<? if($itms3) { ?>
<?=$itm3?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$itme3?></td><td class="b3"><?=$itms3?></td><td class="b3"><font color="00ffff">【<?=$iteminfo[$itmk3]?>】</font></td></tr>
  <tr><td class="b3">
<? if($itms4) { ?>
<?=$itm4?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$itme4?></td><td class="b3"><?=$itms4?></td><td class="b3"><font color="00ffff">【<?=$iteminfo[$itmk4]?>】</font></td></tr>
  <tr><td class="b3">
<? if($itms5) { ?>
<?=$itm5?>
<? } else { ?>
无
<? } ?>
</td><td class="b3"><?=$itme5?></td><td class="b3"><?=$itms5?></td><td class="b3"><font color="00ffff">【<?=$iteminfo[$itmk5]?>】</font></td></tr>

  </td></tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
