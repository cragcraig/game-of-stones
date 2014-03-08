<?php
$itmlist = unserialize($char['itmlist']);
$itmlist2 = unserialize($char2['itmlist']);

// Find Equipped Items CHAR ONE
$y = 0;
while ($y < count($itmlist))
{
if ($itmlist[$y][3]) $itm_a[$itmlist[$y][3]-1] = $itmlist[$y];
$y++;
}
if (!$itm_a[0][0]) $itm_a[0][0] = "fist";
if (!$itm_a[1][0]) $itm_a[1][0] = "body";
if (!$itm_a[2][0]) $itm_a[2][0] = "fist";

// Find Active Skills CHAR ONE
$y = 0;
while ($y < count($skill_list))
{
if ($skills[$skill_list[$y]][0]) $skill_a[$skills[$skill_list[$y]][0]-1] = $skill_list[$y];
$y++;
}

// Find Equipped Items CHAR 2
$y = 0;
while ($y < count($itmlist2))
{
if ($itmlist2[$y][3]) $itm_b[$itmlist2[$y][3]-1] = $itmlist2[$y];
$y++;
}
if (!$itm_b[0][0]) $itm_b[0][0] = "fist";
if (!$itm_b[1][0]) $itm_b[1][0] = "body";
if (!$itm_b[2][0]) $itm_b[2][0] = "fist";

// Find Active Skills CHAR 2
$y = 0;
while ($y < count($skill_list2))
{
if ($skills2[$skill_list2[$y]][0]) $skill_b[$skills2[$skill_list2[$y]][0]-1] = $skill_list2[$y];
$y++;
}

?>