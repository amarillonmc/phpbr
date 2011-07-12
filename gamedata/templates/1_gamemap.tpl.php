<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<TABLE border="1" cellspacing="0" cellpadding="0" align=center background="map/neomap.jpg" style="position:relative;background-repeat:no-repeat;background-position:right bottom;">
<tr align="center">
<td width="48" height="48" align="middle" id="26"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 26) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(26,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='26';postCommand(); title="<?=$nmap['26']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(26,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(26,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>剑刃墓场</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="17"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 17) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(17,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='17';postCommand(); title="<?=$nmap['17']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(17,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(17,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>学院都市</span></td>
<td width="48" height="48" align="middle" id="1"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 1) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(1,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='1';postCommand(); title="<?=$nmap['1']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(1,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(1,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>端点</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="23"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 23) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(23,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='23';postCommand(); title="<?=$nmap['23']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(23,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(23,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>幻想世界</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<? if($arealock) { ?>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<? } else { ?>
<td width="48" height="48" align="middle" id="30"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 30) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(30,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='30';postCommand(); title="<?=$nmap['30']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span class="mapspanlime">冬木市</span></td>
<? } ?>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="0"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 0) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(0,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='0';postCommand(); title="<?=$nmap['0']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(0,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(0,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>无月之影</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="3"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 3) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(3,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='3';postCommand(); title="<?=$nmap['3']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(3,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(3,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>雪之镇</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="24"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 24) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(24,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='24';postCommand(); title="<?=$nmap['24']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(24,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(24,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>永恒的世界</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="25"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 25) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(25,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='25';postCommand(); title="<?=$nmap['25']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(25,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(25,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>妖精驿站</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="19"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 19) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(19,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='19';postCommand(); title="<?=$nmap['19']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(19,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(19,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>精灵中心</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="5"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 5) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(5,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='5';postCommand(); title="<?=$nmap['5']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(5,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(5,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>指挥中心</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="11"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 11) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(11,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='11';postCommand(); title="<?=$nmap['11']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(11,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(11,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>对天使用作战本部</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
<tr align="center">
<td width="48" height="48" align="middle" id="28"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 28) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(28,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='28';postCommand(); title="<?=$nmap['28']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(28,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(28,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>FARGO前基地</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="8"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 8) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(8,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='8';postCommand(); title="<?=$nmap['8']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(8,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(8,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>白穗神社</span></td>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="7"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 7) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(7,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='7';postCommand(); title="<?=$nmap['7']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(7,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(7,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>清水池</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="29"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 29) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(29,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='29';postCommand(); title="<?=$nmap['29']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(29,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(29,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>风祭森林</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="21"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 21) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(21,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='21';postCommand(); title="<?=$nmap['21']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(21,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(21,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>圣Gradius学园</span></td>
<td width="48" height="48" align="middle" id="27"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 27) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(27,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='27';postCommand(); title="<?=$nmap['27']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(27,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(27,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>花菱商厦</span></td>
<td width="48" height="48" align="middle" id="12"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 12) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(12,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='12';postCommand(); title="<?=$nmap['12']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(12,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(12,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>夏之镇</span></td>
<td width="48" height="48" align="middle" id="4"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 4) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(4,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='4';postCommand(); title="<?=$nmap['4']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(4,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(4,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>索拉利斯</span></td>
</tr>
<tr align="center">
<td width="48" height="48" align="middle" id="15"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 15) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(15,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='15';postCommand(); title="<?=$nmap['15']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(15,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(15,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>守矢神社</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="18"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 18) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(18,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='18';postCommand(); title="<?=$nmap['18']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(18,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(18,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>秋之镇</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="2"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 2) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(2,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='2';postCommand(); title="<?=$nmap['2']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(2,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(2,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>RF高校</span></td>
<td width="48" height="48" align="middle" id="13"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 13) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(13,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='13';postCommand(); title="<?=$nmap['13']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(13,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(13,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>三体星</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="6"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 6) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(6,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='6';postCommand(); title="<?=$nmap['6']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(6,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(6,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>梦幻馆</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="14"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 14) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(14,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='14';postCommand(); title="<?=$nmap['14']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(14,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(14,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>光坂高校</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="10"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 10) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(10,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='10';postCommand(); title="<?=$nmap['10']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(10,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(10,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>麦斯克林</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
<tr align="center">
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="16"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 16) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(16,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='16';postCommand(); title="<?=$nmap['16']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(16,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(16,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>常磐森林</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="9"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 9) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(9,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='9';postCommand(); title="<?=$nmap['9']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(9,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(9,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>墓地</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" align="middle" id="22"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 22) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(22,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='22';postCommand(); title="<?=$nmap['22']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(22,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(22,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>初始之树</span></td>
<td width="48" height="48" align="middle" id="20"
<? if(CURSCRIPT == 'game') { if($pdata['pls'] == 20) { ?>
class="maptdyellow" onclick=$('command').value='search';postCommand(); title="!search!"
<? } elseif(in_array(20,array_keys($nmap))) { ?>
class="maptdlime" onclick=$('command').value='move';$('moveto').value='20';postCommand(); title="<?=$nmap['20']?>"
<? } else { ?>
class="map2"
<? } } else { ?>
class="map2"
<? } ?>
><span 
<? if($hack || array_search(20,$arealist) > ($areanum + $areaadd)) { ?>
class="mapspanlime"
<? } elseif(array_search(20,$arealist) <= $areanum) { ?>
class="mapspanred"
<? } else { ?>
class="mapspanyellow"
<? } ?>
>春之镇</span></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>
</tr>
</table>