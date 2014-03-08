<link REL="StyleSheet" TYPE="text/css" HREF="stylea.css">
<html>
<head>

<?php
include("admin/itemarray.php");
$base=str_replace("_"," ",$_GET['base']);
$prefix=str_replace("_"," ",$_GET['prefix']);
$suffix=str_replace("_"," ",$_GET['suffix']);

$r_list=array(
array("Pine","Oak","Ash","Yew"),
array("Rawhide","Buckskin","Pelt","Leather"),
array("Copper","Iron","Silver","Gold"),
);

$units=array("board","pelt","lb");
?>

<title><?php echo ucwords($prefix)." ".ucwords($base)." ".str_replace("Of","of",ucwords($suffix)); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body style="background-color: black; font-family: Verdana, Helvetica, Arial, sans-serif; font-size: 12px; color: #C6CCD8; margin: 0px; padding: 0px">
<font face="VERDANA"><font class="bigtext"><center><br>

<?php
// RESOLVE all common terms and place in array
$weapon = itp($item_base[$base][0]." ".$item_ix[$prefix]." ".$item_ix[$suffix],$item_base[$base][1]);
$worth = number_format(item_val($weapon));
if ($_GET[data]) echo "<font class=littletext>".$weapon;
$char_stats = cparse($weapon,0);


$type_n=ucwords($item_type[$item_base[$base][1]]);
$lvl_need = lvl_req($item_base[$base][0]." ".$item_ix[$suffix]." ".$item_ix[$prefix]);

if (!$_GET['sup']) {
	$bonus="<font class='littletext_f'><center>".$type_n."<br><font class='foottext_f'>";
	if ($item_base[$base][1] != 8) $bonus .= "worth ".$worth." g";
	if ($item_base[$base][1] != 12) $bonus .= "&nbsp;&nbsp;::&nbsp;&nbsp;lvl req. $lvl_need";
	$bonus .= "<font class=littletext><br><p align='left'>";
	$bonus .= itm_info($char_stats);
}
else {
	$var = add_bigarrays20(itm_req($item_base[$base][0]),itm_req($item_ix[$suffix]));
	$bonus = "<table border='0' cellpadding='0' cellspacing='0'>";
	for ($x=0; $x<3; $x++) {
		for ($y=0; $y<4; $y++) {
			if (intval($var[$x][$y])) $bonus .= "<tr><td class='littletext'><p align='right'><i>".$r_list[$x][$y]."</i> - </td><td class='littletext'>&nbsp;".$var[$x][$y]." <font class='foottext'>".$units[$x]."s</td></tr>";
		}
	}
	$bonus .= "</table>";
}
?>

<table border="1" cellpadding="10" rules="rows" cellspacing="0" bgcolor="#0f0f0f" bordercolor="#555555" width="90%"><tr><td>
<center><font class="littletext"><b><?php echo ucwords($prefix)." ".ucwords($base)." ".str_replace("Of","of",ucwords($suffix)); ?></b><font class="littletext"><br>
</td></tr></table><br>
<img border="0" bordercolor="black" src="items/<?php echo str_replace(" ","",$base); ?>.gif"><br>
<br>
<table border=0 cellpadding=0 cellspacing=0><tr><td>
<font class="littletext"><?php echo $bonus; ?>
</td></tr></table>

</body>
</html>
