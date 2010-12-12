<?php
//基础反击率
$counter_obbs =  Array('N' => 50, 'P' => 50, 'K' => 50, 'G' => 50, 'C' => 50, 'D' => 50, 'F'=> 30);

//各种攻击方式的基础命中率
$hitrate_obbs = Array('N' => 80, 'P' => 75, 'K' => 75, 'G' => 70, 'C' => 70, 'D' => 60, 'F' => 70);
//$hitrate_obbs = Array('N' => 80, 'P' => 80, 'K' => 80, 'G' => 75, 'C' => 100, 'D' => 60, 'F' => 70);
//各种攻击方式的最高命中率
$hitrate_max_obbs = Array('N' => 90, 'P' => 90, 'K' => 90, 'G' => 95, 'C' => 95, 'D' => 85, 'F' => 100);
//熟练度对命中的影响，每点熟练增加的命中，可以考虑区分武器
$hitrate_r =  Array('N' => 0.04, 'P' => 0.03, 'K' => 0.03, 'G' => 0.02, 'C' => 0.2, 'D' => 0.05, 'F'=> 0.08);
//$hitrate_r =  Array('N' => 0.02, 'P' => 0.02, 'K' => 0.02, 'G' => 0.01, 'C' => 0, 'D' => 0.03, 'F'=> 0.05);

//熟练度对伤害的影响，每点熟练增加的伤害
$skill_dmg = Array('N' => 0.5, 'P' => 0.5, 'K' => 0.5, 'G' => 0.6, 'C' => 0.3, 'D' => 0.4, 'F'=> 1.2);

//各种攻击方式可能导致受伤的部位
$infatt = Array('N' => '', 'P' => 'ha', 'K' =>'bha', 'G' =>'bhaf', 'C'=> 'ha', 'D' => 'bhaf', 'F'=> 'bhaf');
//各种攻击方式的致伤率
$infobbs = Array('N' => 5, 'P' => 15, 'K' => 30, 'G' => 30, 'C' => 15, 'D' => 40, 'F' => 30);
//各种攻击方式的武器损伤概率
$wepimprate = Array('N' => -1, 'P' => 12, 'K' => 25, 'G' => -1, 'C' => -1, 'D' => -1, 'F' => -1);

?>