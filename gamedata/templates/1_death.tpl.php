<? if(!defined('IN_GAME')) exit('Access Denied'); ?>
<br>
<div class="subtitle"><?=$stateinfo[$state]?></div>
<div><?=$dinfo[$state]?></div>
<div>死亡时间：<?=$dtime?></div>
<? if(!empty($kname) && $state>=10) { ?>
<div>凶手：<?=$kname?></div>
<? } ?>
<br>
<span class="dmg">你死了。</span>
<br><br>
<input type="button" class="cmdbutton" value="我靠！" disabled>