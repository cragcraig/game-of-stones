<?php

// array ( name , number owned , item type , bonus , equipped position [if equipped]) :: OLD AND NO LONGER TRUE. SAME WITH NEXT COMMENT.
// in case of angreal or such: item type is 7, bonus is percent by which one power is multiplied (ex. 250%)

if ($type != "Channeler")
{
	$itmlist = array (
	array ("sharpened stick","","","3"),
	array ("rags","","","2"),
	array ("stone knife","","","1"),
	);
}
elseif ($sex)
{
	$itmlist = array (
	array ("firespark","","","1"),
	array ("rags","","","2"),
	array ("static field","","","-2"),
	array ("upheaval","","","-2"),
	array ("lightning","","","-2"),
	array ("waterspike","","","-2"),
	array ("flood","","","-2"),
	array ("suppression","","","-2"),
	array ("powersurge","","","-2"),
	array ("balefire","","","-2"),
	);
}
else {
	$itmlist = array (
	array ("firespark","","","1"),
	array ("rags","","","2"),
	array ("static field","","","-2"),
	array ("upheaval","","","-2"),
	array ("lightning","","","-2"),
	array ("fireball","","","-2"),
	array ("earthquake","","","-2"),
	array ("eruption","","","-2"),
	array ("powersurge","","","-2"),
	array ("balefire","","","-2"),
	);
}

$itms = serialize($itmlist);
$inventory = array ( array ("",'','','','') );

?>
