<?php
// establish a connection with the database
include("admin/connect.php");
include("admin/userdata.php");

include("admin/itemarray.php");
include("map/mapdata/coordinates.inc");

$itmlist=unserialize($char['itmlist']);
$id = $char['id'];
// $inv = $_GET['inv'];
$equip = $_GET['equip'];
//$drop = $_GET['drop'];
$listsize = count($itmlist);
$doit = $_GET['doit'];
$message = $_GET['message'];
$item_sell=$_REQUEST['itemsell'];
if ($equip >= count($itmlist)) {$doit = 0; $equip = 0;}

// ACTIONS

// CACHE / UNCACHE
if ($_GET['c'])
{
	$itm=$_GET['itm'];
	$set=-1;
	if (($itmlist[$itm][3] == 0 || $itmlist[$itm][3] == -2) && $itmlist[$itm][0] && $item_base[$itmlist[$itm][0]][1] != 8)
	{
		if ($itmlist[$itm][3] == 0) {$itmlist[$itm][3] = -2; $message=iname($itm,$itmlist)." cached";}
		elseif ($itmlist[$itm][3] == -2) {$itmlist[$itm][3] = 0; $message=iname($itm,$itmlist)." uncached";}
		$itmlists = serialize($itmlist);
		$query = mysql_query("UPDATE Users_data SET itmlist='$itmlists' WHERE id=$id");
	}
}

// SELL SINGLE ITEM
$arraycount1=$listsize;
$equipped = $itmlist[$item_sell][3];
if (($doit == -17 || $char[special]) && $equipped == 0 && $item_sell<$arraycount1 && $item_base[$itmlist[$item_sell][0]][1] != 8)
{
$item_name=iname($item_sell,$itmlist);
$worth = intval(0.5*item_val(itp("A0 B0 ".$item_base[$itmlist[$item_sell][0]][0]." ".$item_ix[$itmlist[$item_sell][1]]." ".$item_ix[$itmlist[$item_sell][2]],$item_base[$itmlist[$item_sell][0]][1])));
$blanks=0;
$z = $item_sell;
while ($z < $arraycount1)
{
$itmlist[$z] = $itmlist[$z+1];
if ($itmlist[$z][0]=='') $blanks++;
$z++;
}
for ($x=0; $x<$blanks; $x++) array_pop($itmlist);

// FINALIZE MONEY AND SET DATABASE

$chargold = $worth + $char[gold];

$itmlist1 = serialize($itmlist);
$querya = "UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users.gold='$chargold', Users_data.itmlist='$itmlist1' WHERE Users.id='$id'";
$result = mysql_query($querya);
$message="You have sold $item_name for ".number_format($worth)." gold";
}

// EQUIP

if ($doit == 1)
{
	// Find all equipped items
	$y = 0;
	$a = 0;
	$b = 0;
	$c = 0;
	while ($y < $listsize) {
		if ($itmlist[$y][3] == 1) $a = 1;
		if ($itmlist[$y][3] == 2) $b = 1;
		if ($itmlist[$y][3] == 3) $c = 1;
		$y++;
	}

	$lvl_need = lvl_req($item_base[$itmlist[$equip][0]][0]." ".$item_ix[$itmlist[$equip][2]]." ".$item_ix[$itmlist[$equip][1]]);
	
	if ($itmlist[$equip][3] != -1) {
		if ($char['level']>=$lvl_need) {
			if ($item_base[$itmlist[$equip][0]][1] == 0) {$itmlist[$equip][3] = 2; $message = "Armor Equipped";} // Armor
			elseif (($c && ($item_base[$itmlist[$equip][0]][1] == 6 || $char['final_pow']==4 || $item_base[$itmlist[$equip][0]][1] == 8)) || $item_base[$itmlist[$equip][0]][1] == 7) {$itmlist[$equip][3] = 1; $message = "Equipped successfully";} // Shield or Knife
			elseif (($item_base[$itmlist[$equip][0]][1] > 0 && $item_base[$itmlist[$equip][0]][1] < 7) || $item_base[$itmlist[$equip][0]][1] == 8) {$itmlist[$equip][3] = 3; $message = "Weapon Equipped"; } // Weapon One

			// Unequip Other Items
			$x = 0;
			while ($x < $listsize)
			{
				if ($itmlist[$x][3] == $itmlist[$equip][3] && $x != $equip) $itmlist[$x][3] = -2;
				$x++;
			}

			$itmlists = serialize($itmlist);
			$query = "UPDATE Users_data SET itmlist='$itmlists' WHERE id=$id";
			$result = mysql_query($query);
		}
		else $message = "You must be level $lvl_need to use this item";
	}
}

// UNEQUIP

if ($doit == 3 && $equip < count($itmlist))
{
if (!$_GET['repmes']) $message = "Item Unequipped";
$itmlist[$equip][3] = -2;
$itmlists = serialize($itmlist);
$query = "UPDATE Users_data SET itmlist='$itmlists' WHERE id=$id";
$result = mysql_query($query);
}

// SELL ALL UNCACHED
if ($_GET['sellall']==2)
{
	$gold=0;
	$items=count($itmlist);
	for ($i=0; $i<$items-$sold; $i++)
	{
		$item=$i;
		if (($itmlist[$item][3] == 0 && $item_base[$itmlist[$item][0]][1] != 8) || (!$itmlist[$item][0] && !$itmlist[$item][1] && !$itmlist[$item][2]) && $item_base[$itmlist[$item_sell][0]][1] != 8)
		{
			$worth = intval(0.5*item_val(itp("A0 B0 ".$item_base[$itmlist[$item][0]][0]." ".$item_ix[$itmlist[$item][1]]." ".$item_ix[$itmlist[$item][2]],$item_base[$itmlist[$item][0]][1])));
			$gold+=$worth;
			// REMOVE EACH ITEM
			$z = $i;
			while ($z <$items)
			{
				$itmlist[$z] = $itmlist[$z+1];
				$z++;
			}
			array_pop($itmlist);
			$items--;
			$i--;
		}
	}
	
	// FINISH
	$char['gold']+=$gold;
	$itmlists = serialize($itmlist);
	$querya = "UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users.gold='".$char['gold']."', Users_data.itmlist='$itmlists' WHERE Users.id='$id'";
	$result = mysql_query($querya);
	$message="Uncached items sold for ".number_format($gold)." gold";
}

if ($message == '') $message = $listsize."/$inv_max items";
include('header.htm');

if ($_GET['sellall']==1)
{
	?>
		<center>
		<font class="medtext">
		<br><br>
		<b>Sell all non-cached items?</b>
		<br>
		<img border="0" src="items/gold.gif">
		<br>
		[<b><a href='items.php?sellall=2&time=<?php echo time(); ?>'>YES</a></b> | <b><a href='items.php?time=<?php echo time(); ?>'>NO</a></b>]
	<?php
	include('footer.htm');
	exit;
}

// INVENTORY

?>
<center>
<font face="VERDANA"><font class="littletext"><center>

<!-- MAIN PAGE -->
<center>
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" valign="top">
<tr><td><center>
<table border=0 cellspacing=0 cellpadding="5">
<tr>
<?php
// DRAW EQUIPPED ITEMS
$time = time();
$item_one = 0;
$placeequip = 0;
$last_blit = 0;
while ($placeequip < 3)
{
$itemtoblit = 0;
$blited = 0;
while ($itemtoblit < $listsize)
{
if ($itmlist[$itemtoblit][3] == $placeequip + 1)
{
$blited = 1;
?>
<td width="100" valign=top><center><table border=0 cellspacing=0 cellpadding=0><tr><td valign="center" height=35><font class=foottext><b><center><?php echo iname($itemtoblit,$itmlist); ?></td></tr></table><font class=foottext_f><img border="0" bordercolor="black" src="items/<?php echo str_replace(" ","",$itmlist[$itemtoblit][0]); ?>.gif"><br><?php if ($placeequip == 0) echo "Hand"; if ($placeequip == 1) echo "Body"; if ($placeequip == 2) echo "Hand"; ?><br>[<a href="items.php?doit=3&equip=<?php echo "$itemtoblit"."&time=$time"; ?>">Unequip</a> | <A HREF="javascript:popUp('itemstat.php?<?php echo "base=".$itmlist[$itemtoblit][0]."&prefix=".$itmlist[$itemtoblit][1]."&suffix=".$itmlist[$itemtoblit][2]; ?>')">Info</A>]</td>
<?php
$last_blit = $placeequip;
if ($last_blit == 0) { $item_one = $itemtoblit + 1;}
}
$itemtoblit++;
}
$placeequip++;

// Draw nothing equipped images

if ($blited == 0 && $placeequip == 1) { echo "<td width=100 valign=top><table border=0 cellspacing=0 cellpadding=0 height=35><tr><td></td></tr></table></a><img src=items/hand1.gif border=0></td>";}
if ($blited == 0 && $placeequip == 2) { echo "<td width=100 valign=top><table border=0 cellspacing=0 cellpadding=0 height=35><tr><td></td></tr></table><img src=items/torso".$char[sex].".gif border=0></td>";}
if ($blited == 0 && $placeequip == 3) { echo "<td width=100 valign=top><table border=0 cellspacing=0 cellpadding=0 height=35><tr><td></td></tr></table><img src=items/hand2.gif border=0><br><br></td>";}
}

$style="style='border-width: 0px; border-bottom: 1px solid #333333'";
?>
</tr></table><font class="littletext">
</td></tr></table>
<br><br>

<!--  SECOND SET OF ITEMS        888888888888888888888888888888888888888 -->

<center>
<table border="0" cellspacing="0" cellpadding="5">
<tr>
	<td <?php echo $style; ?> width="200"><center><font class="littletext"><b>Item</b></td>
	<td <?php echo $style; ?> width="100"><center><font class="littletext"><b>Type</b></td>
	<td <?php echo $style; ?> width="100"><center><font class="littletext"><b>Action</b></td>
	<td <?php echo $style; ?> width="100"><center><font class="littletext"><b>Sell <font class='foottext'>[<a href="javascript:popConfirm('Sell all uncashed items?','items.php?sellall=2&time=<?php echo $time; ?>')">all</a>]</b></td>
	<td <?php echo $style; ?> width='10'>&nbsp;</td>
</tr>

<?php
//echo $div_img;
$draw_some = 0;
for ($itemtoblit = 0; $itemtoblit < count($itmlist); $itemtoblit++)
{
if ($itmlist[$itemtoblit][3] == 0 || $itmlist[$itemtoblit][3] == -2)
{
// DRAW ITEM IN LIST
?>
<tr onMouseOver="this.bgColor='#111111';" onMouseOut="this.bgColor='#000000';">
<td width="200"><b><center><font class="foottext"><A HREF="javascript:popUp('itemstat.php?<?php echo "base=".$itmlist[$itemtoblit][0]."&prefix=".$itmlist[$itemtoblit][1]."&suffix=".$itmlist[$itemtoblit][2]; ?>')"><?php echo ucwords($itmlist[$itemtoblit][1]." ".$itmlist[$itemtoblit][0])." ".str_replace("Of","of",ucwords($itmlist[$itemtoblit][2])); ?></A></b></td>

<!-- DISPLAY ITEM TYPE -->
<td width="100"><center><font class="foottext">
<?php
echo ucwords($item_type[$item_base[$itmlist[$itemtoblit][0]][1]]);
?>
</td>
<td width="100"><center><font class="foottext_f">

<!-- DIFFERENT ACTIONS -->

<?php
$worth = item_val(itp("A0 B0 ".$item_base[$itmlist[$itemtoblit][0]][0]." ".$item_ix[$itmlist[$itemtoblit][1]]." ".$item_ix[$itmlist[$itemtoblit][2]],$item_base[$itmlist[$itemtoblit][0]][1]));
if ($item_base[$itmlist[$itemtoblit][0]][1] < 10) {
?>
[<a href="items.php?doit=1&equip=<?php echo "$itemtoblit"."&time=$time"; ?>">Equip</a>
<?php } elseif ($item_base[$itmlist[$itemtoblit][0]][1] == 13) {?>
[<a href="items.php?doit=5&equip=<?php echo "$itemtoblit"."&time=$time"; ?>">Use</a>
<?php } elseif ($item_base[$itmlist[$itemtoblit][0]][1] == 12) {?>
[<a href="talisman.php?t=<?php echo "$itemtoblit"."&time=$time"; ?>">Use</a>
<?php
}
// Cache
if ($item_base[$itmlist[$itemtoblit][0]][1] != 8) {
	if ($itmlist[$itemtoblit][3] == 0)
		echo " | <a href='items.php?c=1&itm=$itemtoblit&time=$time'>Cache</a>]";
	else
		echo " | <a href='items.php?c=1&itm=$itemtoblit&time=$time'>Uncache</a>]";
}
else echo "]";
?>

</td>
<td width="100"><center><font class="foottext_f">
<?php if ($itmlist[$itemtoblit][3] == 0) { ?>
[<a href="javascript:popConfirm('Sell <?php echo iname($itemtoblit,$itmlist)." for ".number_format(intval(0.5*$worth)); ?> gold?','items.php?doit=-17&itemsell=<?php echo "$itemtoblit&time=".time(); ?>');">Sell</a><?php if ($location_array[$char['location']][3]) { ?>
 | 
 <a href="javascript:showMe('<form action=charshop.php?itemsell=<?php echo "$itemtoblit"; ?> method=post>Offer <?php echo iname($itemtoblit,$itmlist)." in the ".$char['location']." market for "; ?><input type=text style=text-align:right; name=cost value=<?php echo $worth; ?> id=cost size=3 maxlength=9 class=popper_text> gold?<br><br><input type=submit name=submit value=Offer class=popper>&nbsp;&nbsp;<input type=button onClick=hideMe(); value=Cancel class=popper></form>',0);">Market</a><?php } ?>]
<?php } else echo "[Cached]"; ?>
</td>
<td></td>
</tr>
<?php
$draw_some = 1;
}
}
?>

<font class="littletext">
<?php
if ($draw_some == 0) echo "<tr><td colspan='5'><center><font class='littletext_f'><br><b>You are not carrying any items</b><br><br></td></tr>";
?>

<tr><td colspan='5' style='border-width: 0px; border-top: 1px solid #333333'>
	&nbsp;
</td></tr></table>


<br>

<br><br>

</center>

<?php
include('footer.htm');
?>
