<?php
// establish a connection with the database
include("admin/connect.php");
include("admin/userdata.php");

include("admin/itemarray.php");

$talisman = $_GET[t];
$enhance = $_GET[e]-1;
$itmlist=unserialize($char['itmlist']);
if (!($message = $_GET[message])) $message = ucwords($itmlist[$talisman][1]." ".$itmlist[$talisman][0])." ".str_replace("Of","of",ucwords($itmlist[$talisman][2]));

// ENHANCE

if ($_GET[e] && $_GET[doit] && $item_base[$itmlist[$talisman][0]][1]==12 && $item_base[$itmlist[$enhance][0]][1] != 8)
{
if ((($itmlist[$enhance][1] == "" && $itmlist[$talisman][1] != "" && $itmlist[$talisman][2] == "") || ($itmlist[$enhance][2] == "" && $itmlist[$talisman][2] != "" && $itmlist[$talisman][1] == "") || ($itmlist[$enhance][1] == "" && $itmlist[$enhance][2] == "" && $itmlist[$talisman][1] != "" && $itmlist[$talisman][2] != "")) && !$item_base[$itmlist[$enhance][0]][2] && $itmlist[$enhance][3] != -1)
{
if ($itmlist[$talisman][1]) $itmlist[$enhance][1] = $itmlist[$talisman][1];
if ($itmlist[$talisman][2]) $itmlist[$enhance][2] = $itmlist[$talisman][2];
$x = $talisman;
while ($x < count($itmlist))
{
$itmlist[$x] = $itmlist[$x+1];
$x++;
}
array_pop($itmlist);

$itmlists = serialize($itmlist);
$query = mysql_query("UPDATE Users_data SET itmlist='$itmlists' WHERE id=$id");
$message = "Item enhanced";
$unequip = "";
if (lvl_req($item_base[$itmlist[$enhance][0]][0]." ".$item_ix[$itmlist[$enhance][2]]." ".$item_ix[$itmlist[$enhance][1]]) > $char['level']) {
	$unequip = "&doit=3&equip=$enhance&repmes=1";
	$message = "You cannot used this enhanced item at your current level";
}
header("Location: $server_name/items.php?time=$time&message=$message$unequip");
exit;
}
}

include('header.htm');
// SHOW PAGE

if (!$_GET[e])
{
?>
<center>
<font class=littletext><b>Select an item to enhance</b><br><br>

<table border=0 cellpadding=5 cellspacing=0 class="abox"><tr><td><center>
<table border=0 cellpadding=5 cellspacing=0>
<?php
$draw_one = 0;
for ($x = 0; $x < count($itmlist); $x++)
{
if ((($itmlist[$x][1] == "" && $itmlist[$talisman][1] != "" && $itmlist[$talisman][2] == "") || ($itmlist[$x][2] == "" && $itmlist[$talisman][2] != "" && $itmlist[$talisman][1] == "") || ($itmlist[$x][1] == "" && $itmlist[$x][2] == "" && $itmlist[$talisman][1] != "" && $itmlist[$talisman][2] != "")) && !$item_base[$itmlist[$x][0]][2] && $itmlist[$x][3] != -1 && $item_base[$itmlist[$x][0]][1] != 8)
	{$draw_one = 1; echo "<tr><td valign='center'><font class=littletext><b><center><a href='talisman.php?time=$time&t=$talisman&e=".($x+1)."'>".iname($x,$itmlist)."</a></td></tr>";}
}
?>
</table>

<?php
if (!$draw_one) echo "<font class=foottext>[none]";
?>
</td></tr></table>
<?php
} else {
$lvl_need = lvl_req($item_base[$itmlist[$enhance][0]][0]." ".$item_ix[$itmlist[$enhance][2]]." ".$item_ix[$itmlist[$enhance][1]]." ".$item_ix[$itmlist[$talisman][1]]." ".$item_ix[$itmlist[$talisman][2]]);
?>
<center>
<table border=0 cellpadding=5 cellspacing=0 class="abox"><tr><td><center>
<center><font class=littletext>
<a href='talisman.php?doit=1&t=<?php echo "$talisman&e=".($enhance+1); ?>'><b>Enhance</b></a> <?php echo iname($enhance,$itmlist); ?><br>
</td></tr></table>
<center><font class=littletext><br>
<img border="0" bordercolor="black" src="items/<?php echo str_replace(" ","",$itmlist[$enhance][0]); ?>.gif"><br><br>
to<br><br>
<a href="javascript:popUp('itemstat.php?base=<?php echo $itmlist[$enhance][0]; ?>&prefix=<?php if ($itmlist[$talisman][1]) echo $itmlist[$talisman][1]; elseif ($itmlist[$enhance][1]) echo $itmlist[$enhance][1]; ?>&suffix=<?php if ($itmlist[$talisman][2]) echo $itmlist[$talisman][2]; elseif ($itmlist[$enhance][2]) echo $itmlist[$enhance][2]; ?>')">
<?php
if ($itmlist[$talisman][1]) echo ucwords($itmlist[$talisman][1])." "; elseif ($itmlist[$enhance][1]) echo ucwords($itmlist[$enhance][1])." ";
echo ucwords($itmlist[$enhance][0]);
if ($itmlist[$talisman][2]) echo " ".str_replace("Of","of",ucwords($itmlist[$talisman][2])); elseif ($itmlist[$enhance][2]) echo " ".str_replace("Of","of",ucwords($itmlist[$enhance][2]));;
?>
</a>
?

<br><br>
<?php
if ($lvl_need > $char['level']) echo "<b>Which you will be unable to use until level $lvl_need</b>";
}

include('footer.htm');
?>

