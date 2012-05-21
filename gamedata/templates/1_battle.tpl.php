<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="0" width=720px height=380px cellspacing="0" cellpadding="0" valign="middle">
<TR align="center" >
<TD valign="middle" class="b5">
<TABLE border="0" width=720px height=380px align="center" cellspacing="0" cellpadding="0" class="battle">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td>
<table border="0"  cellspacing="0" cellpadding="0" valign="top" width="100%">
<tr>
<td class="b1" colspan=2 height=20px><span>Lv. <?=$w_lvl?></span></td>											
<td class="b1" colspan=2><span><?=$w_name?></span></td>											
<td class="b1" colspan=2><span><?=$w_sNoinfo?></span></td>											
<td class="b5" rowspan=4 width=140px colspan=1 height=80px><IMG src="img/<?=$w_iconImg?>" height=80px border="0" align="middle" 
<? if($w_hp==0) { ?>
style="filter:Xray()"
<? } ?>
 /></td>
</tr>
<tr>
<td class="b2" width=75px height=20px><span>怒气</span></td>
<td class="b3" width=90px><span><?=$w_ragestate?></span></td>
<td class="b2" width=75px><span>体力</span></td>
<td class="b3" width=90px><span><?=$w_spstate?></span></td>
<td class="b2" width=100px><span>生命</span></td>
<td class="b3" width=145px><span><?=$w_hpstate?></span></td>
</tr>
<tr>
<td class="b2" height=20px><span>武器攻击</span></td>
<td class="b3"><span><?=$w_wepestate?></span></td>
<td class="b2"><span>武器种类</span></td>
<td class="b3"><span>
<? if($w_wepk != '') { ?>
<?=$iteminfo[$w_wepk]?>
<? } else { ?>
？？？
<? } ?>
</span></td>
<td class="b2"><span>武器</span></td>
<td class="b3"><span><?=$w_wep?></span></td>
</tr>
<tr>
<td class="b2" height=20px><span>应战策略</span></td>
<td class="b3"><span>
<? if($w_tactic >= 0) { ?>
<?=$tacinfo[$w_tactic]?>
<? } else { ?>
？？？
<? } ?>
</span></td>
<td class="b2"><span>基础姿态</span></td>
<td class="b3"><span>
<? if($w_pose >= 0) { ?>
<?=$poseinfo[$w_pose]?>
<? } else { ?>
？？？
<? } ?>
</span></td>
<td class="b2"><span>受伤部位</span></td>
<td class="b3"><span>
<? if($w_infdata) { ?>
<?=$w_infdata?>
<? } else { ?>
无
<? } ?>
</span></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</TR>
<tr>
<TD class="b3" height="100%">
<span><B><FONT color="#ff0000" size="5" face="黑体"><?=$battle_title?></FONT></B></span>
</TD>
</TR>
<tr>
<td>
<table border="0" width=720px cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td class="b5" rowspan=4 colspan=1 width=140px height=80px><IMG src="img/<?=$iconImg?>" height=80px border="0" align="middle" 
<? if($hp==0) { ?>
style="filter:Xray()"
<? } ?>
 /></td>
<td class="b1" colspan=2 height=20px><span><?=$typeinfo[$type]?>(<?=$sexinfo[$gd]?><?=$sNo?>号)</span></td>
<td class="b1" colspan=2><span><?=$name?></span></td>
<td class="b1" colspan=2><span>Lv. <?=$lvl?></span></td>
</tr>
<tr>
<td class="b2" width=100px height=20px><span>生命</span></td>
<td class="b3" width=145px><span><span class="<?=$hpcolor?>"><?=$hp?> / <?=$mhp?></span></span></td>
<td class="b2" width=75px><span>体力</span></td>
<td class="b3" width=90px><span><?=$sp?> / <?=$msp?></span></td>
<td class="b2" width=75px><span>怒气</span></td>
<td class="b3" width=90px><span>
<? if($rage >=30) { ?>
<span class="yellow"><?=$rage?></span>
<? } else { ?>
<?=$rage?>
<? } ?>
</span></td>
</tr>
<tr>
<td class="b2" height=20px><span>武器</span></td>
<td class="b3"><span><?=$wep?></span></td>
<td class="b2"><span>武器种类</span></td>
<td class="b3"><span><?=$iteminfo[$wepk]?></span></td>
<td class="b2"><span>武器攻击</span></td>
<td class="b3"><span><?=$wepe?></span></td>
</tr>
<tr>
<td class="b2" height=20px><span>受伤部位</span></td>
<td class="b3">
<span>
<? if($inf) { if(is_array($infinfo)) { foreach($infinfo as $key => $val) { if(strpos($inf,$key)!==false) { ?>
<?=$val?>
<? } } } } else { ?>
无
<? } ?>
</span>
</td>
<td class="b2"><span>基础姿态</span></td>
<td class="b3"><span><?=$poseinfo[$pose]?></span></td>
<td class="b2"><span>应战策略</span></td>
<td class="b3"><span><?=$tacinfo[$tactic]?></span></td>
</tr>
</table>
</td>
</tr>
</TABLE>
</TD>
</TR>
</table>
