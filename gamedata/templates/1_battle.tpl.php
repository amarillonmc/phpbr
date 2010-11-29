<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="1" width="550" height="320" cellspacing="0" cellpadding="0"  valign="middle">
<TR align="center" >
<TD valign="middle" class="b3">
<TABLE border="0" align="center" class="battle">
<tr>
<TD colspan="3"><B><FONT color="#ff0000" size="5" face="Verdana"><?=$battle_title?></FONT></B></TD><TR>
<tr>
<TD width="100"><IMG src="img/<?=$iconImg?>" width="70" height="70" border="0" align="middle"></TD>
<TD></TD>
<TD width="100"><IMG src="img/<?=$w_iconImg?>" width="70" height="70" border="0" align="middle" 
<? if($w_isdead) { ?>
style="filter:Xray()"
<? } ?>
 ></TD>
</TR>
<tr>
<TD><?=$typeinfo[$type]?> （<?=$sexinfo[$gd]?> <?=$sNo?> 号） </TD>
<TD width="50" align="center">VS</TD>
<TD><?=$w_sNoinfo?> </TD>
</TR>
<tr>
<TD><?=$name?></TD>
<TD><B>姓名</B></TD>
<TD><?=$w_name?></TD>
</TR>
<tr>
<TD><?=$hp?> / <?=$mhp?></TD>
<TD><B>生命</B></TD>
<TD><?=$w_hpstate?></TD>
</TR>
<tr>
<TD><?=$wep?></TD>
<TD><B>武器</B></TD>
<TD><?=$w_wep?></TD>
</TR>
</TABLE>
</TD>
</TR>
</table>
