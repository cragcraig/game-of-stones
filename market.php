<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
include("admin/itemarray.php");

$location=$char['location'];
$s_accept=$_REQUEST['item4'];
$s_doit=$_REQUEST['doit'];
$s_item=$_REQUEST['itemname'];
$s_type=$_REQUEST['type'];
$s_bonus=$_REQUEST['bonus'];
$s_costmin=intval($_REQUEST['costmin']);
$s_costmax=intval($_REQUEST['costmax']);
$world=$_REQUEST['world'];
$resultnumb=$_REQUEST['pagenumb'];
if (!$resultnumb) $resultnumb = 0;
$numbofresults = 20;
$time=time();

include("map/mapdata/coordinates.inc");
if ($char['ismarket']) $is_market=1;
else $is_market=0;

$message = "Search Marketplace&nbsp;&nbsp;</b>or&nbsp;&nbsp;<b>[</b><a href='charshop.php?time=$time'>Enter Your Shop</a><b>]";
if (!$is_market) $message = "There is no Marketplace in ".str_replace('-ap-','&#39;',$char['location']);

// ACCEPT TRADE

if ($s_doit == 1)
{
$query = "SELECT * FROM Market WHERE id='$s_accept'";
$resultb = mysql_query($query, $db);
$listchar = mysql_fetch_array( $resultb );

$query = "SELECT * FROM  Users LEFT JOIN Users_data ON Users.id=Users_data.id  WHERE Users.name='".$listchar[sname]."' AND Users.lastname='".$listchar[slastname]."'";
$resultb = mysql_query($query, $db);
$seller = mysql_fetch_array( $resultb );

$itmlist=unserialize($char['itmlist']);
$arraycount1=count($itmlist);
$itmlist_seller=unserialize($seller['itmlist']);
$arraycount2=count($itmlist_seller);

$costitm = $listchar[cost];

// SELL THE ITEM
$seller_have_item = 0;
$x=0;
$idmarket=$listchar[id];
while ( !($listchar[base] == $itmlist_seller[$x][0] && $listchar[prefix] == $itmlist_seller[$x][1] && $listchar[suffix] == $itmlist_seller[$x][2] && $itmlist_seller[$x][3] == -1) && $x < $arraycount2 ) $x++;
if ($x != $arraycount2) $seller_have_item = 1;
else {$sendmessage = "The other character no longer has that item. How rude."; mysql_query("DELETE FROM Market WHERE id='$idmarket'");}

$z = $x;
while ($z < $arraycount2-1)
{
	$itmlist_seller[$z] = $itmlist_seller[$z+1];
	$z++;
}
array_pop($itmlist_seller);

// ADD ITEM

$itmlist[$arraycount1][0] = $listchar[base];
$itmlist[$arraycount1][1] = $listchar[prefix];
$itmlist[$arraycount1][2] = $listchar[suffix];
$itmlist[$arraycount1][3] = 0;

if ($char[gold] < $costitm) $sendmessage = "You do not have enough gold";

// FINISH THE SALE
if ($seller_have_item == 1 && $char[gold] >= $costitm && ($listchar[sname] != $name || $listchar[slastname] != $lastname))
{
if ($char['location']==$listchar['location'])
{
if (count($itmlist) <= $inv_max)
{
$idmarket=$listchar[id];
$itmlist1 = serialize($itmlist);
$itmlist2 = serialize($itmlist_seller);
$gold = $char[gold] - $costitm;
// ADD TO BANK
//$allow_bank = floor(($seller[bankgold]+$seller[gold])*0.8);
//if ($costitm<=$allow_bank) {$bank_seller=$seller[bankgold]+$costitm; $costitm=0;}
//else {$bank_seller=$allow_bank; $costitm=$costitm-($bank_seller-$seller[bankgold]);}
$gold_seller = $seller[shopgold] + $costitm;
//
$trade_id = $seller[id];

$querya = "UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users_data.itmlist='$itmlist1', Users.gold='$gold' WHERE Users.id='$id'";
$result = mysql_query($querya);
$querya = "UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users_data.itmlist='$itmlist2', Users.shopgold='$gold_seller' WHERE Users.id='$trade_id'";
$result = mysql_query($querya);
$query = "DELETE FROM Market WHERE id='$idmarket'";
$result = mysql_query($query);
}
else {header("Location: $server_name/items.php?message=Your inventory is full&time=$time"); exit;}
}
else  {header("Location: $server_name/items.php?message=You are not in the town this market is in&time=$time"); exit;}
header("Location: $server_name/items.php?message=Sale Completed&time=$time");
exit;
}
else
{
header("Location: $server_name/items.php?message=$sendmessage&time=$time");
exit;
}
}

// DISPLAY RESULTS
if (!$world) $loc_search = "location='$location' AND ";
else $loc_search = "";
if ( $s_costmin && $s_costmax && $is_market)
{
// SEARCH MARKET
if ($s_item == "" && $s_type == "" )
{
$query = "SELECT * FROM Market WHERE ".$loc_search."cost>='$s_costmin' AND cost<='$s_costmax' AND bonus LIKE BINARY '%$s_bonus%' ORDER BY cost";
}
elseif ($s_item == "")
{
$query = "SELECT * FROM Market WHERE ".$loc_search."cost>='$s_costmin' AND cost<='$s_costmax' AND type='$s_type' AND bonus LIKE BINARY '%$s_bonus%' ORDER BY cost";
}
elseif ($s_type == "")
{
$query = "SELECT * FROM Market WHERE ".$loc_search."name LIKE '%$s_item%' AND cost>='$s_costmin' AND cost<='$s_costmax' AND bonus LIKE BINARY '%$s_bonus%' ORDER BY cost";
}
else
{
$query = "SELECT * FROM Market WHERE ".$loc_search."name LIKE '%$s_item%' AND cost>='$s_costmin' AND cost<='$s_costmax' AND type='$s_type' AND bonus LIKE BINARY '%$s_bonus%' ORDER BY cost";
}
$query.=" LIMIT $resultnumb,".($numbofresults+1);
$resultb = mysql_query($query, $db);
$numchar = mysql_num_rows($resultb);

//HEADER
if (!$world) $search_text = "the ".str_replace('-ap-','&#39;',$char['location'])." Market";
else $search_text = "markets around the world";
if ($numchar<=$numbofresults) $message = "There are ".($resultnumb+$numchar)." items in ".$search_text." matching your description";
else  $message = "There many items in ".$search_text." matching your description";
include('header.htm');

// DISPLAY LIST
$style="style='border-width: 0px; border-bottom: 1px solid #333333'";
?>
<font class="littletext"><center>

<table border="0" cellspacing="0" cellpadding="5" width="550">
	<tr>
		<td <?php echo $style; ?> width="200"><font class="littletext"><b><center>Item Info</td>
		<td <?php echo $style; ?> width="100"><b><font class="littletext"><center><?php if (!$world) echo "&nbsp;"; else echo "Location"; ?></td>
		<td <?php echo $style; ?> width="100"><b><font class="littletext"><center>Cost</b></td>
		<td <?php echo $style; ?> width="100"><b><font class="littletext"><center>Type</b></td>
	</tr>
<?php
// DRAW RESULTS

$x=0;
while ( $listchar = mysql_fetch_array($resultb))
{
?>
<tr onMouseOver="this.bgColor='#111111';" onMouseOut="this.bgColor='#000000';">
<td><font class="foottext"><center><b><A HREF="javascript:popUp('itemstat.php?<?php echo "base=".$listchar['base']."&prefix=".$listchar['prefix']."&suffix=".$listchar['suffix']; ?>')"><?php echo $listchar['name']; ?></A></b></td>
<td><center><font class="foottext"><?php if ($char[gold] >= $listchar[cost] && !($listchar[sname] == $name && $listchar[slastname] == $lastname) && !$world) { ?>[<a href="market.php?doit=1&item4=<?php echo $listchar['id']; echo "&time="; echo time(); ?>">Buy</a>]<?php } elseif ($world) echo str_replace('-ap-','&#39;',$listchar['location']);?></td>
<td><center><font class="foottext"><?php if ($char[gold] < $listchar[cost]) echo "<font color='ED3915'>"; echo number_format( $listchar['cost'], 0, '.', ','); ?> g</td>
<td><font class="foottext"><center><?php echo ucwords($item_type[$item_base[$listchar[base]][1]]); ?></td>
</tr>
<?php
$x++;
if ( $x == $numbofresults ) break;
}
if ($x == 0 && !$world) echo "<tr><td colspan='4'><br><font class=littletext><center><b>-No results, try another town's market-</b><br><br></td></tr>";
else if ($x == 0)  echo "<tr><td colspan='4'><br><font class=littletext><center><b>-No results found anywhere in the world-</b><br><br></td></tr>";

// END DRAW RESULTS
?>
<tr><td colspan='4' style='border-width: 0px; border-top: 1px solid #333333'>

<center>
<table border="0" width="100%">
<tr><td width="100" valign='top'>
<?php
$resultnumb1=$resultnumb + 20;
$resultnumb2=$resultnumb - 20;
$search=str_replace(" ","_",$search);


if ($resultnumb > 0)
{
?>
<p align="left"><font class="littletext"><a href="market.php?world=<?php echo $world; ?>&pagenumb=<?php echo "$resultnumb2&itemname=$s_item&type=$s_type&costmin=$s_costmin&costmax=$s_costmax&bonus=$s_bonus&time=$time"; ?>">&#60;&#60; Previous</a>
<?php
}
?>
</td><td width="300" valign='top'><font class="littletext_f"><center>Page <?php echo (($resultnumb / $numbofresults) + 1); ?></td><td width="100" valign='top'>
<?php
if ($numchar>$numbofresults)
{
?>
<p align="right"><font class="littletext"><a href="market.php?world=<?php echo $world; ?>&pagenumb=<?php echo "$resultnumb1&itemname=$s_item&type=$s_type&costmin=$s_costmin&costmax=$s_costmax&bonus=$s_bonus&time=$time"; ?>">Next &#62;&#62;</a>
<?php
}
?>
</td></tr></table>

</td></tr>
</table>

<?php
}
else
{
// IF NO RESULTS YET, DISPLAY SEARCH BOXES

if ($s_item || $s_type && !($s_costmin && $s_costmax && $s_bonusmin && $s_bonusmax)) $message = "You must enter a value for each of the cost and bonus fields";
include('header.htm');
?>

<center><font class="littletext">
<form method="post" action="market.php">
<table border="0" cellspacing="25">
<tr>
<td><font class="littletext"><b>Item Name:</b><br></td>
<td><input type="text" name="itemname" id="itemname" class="form" maxlength="20" /></td>
</tr>
<tr>
<td><label for="type"><font class="littletext"><b>Type:</b></label><br></b></td>
<td>
<select name="type" size="1" class="form">
	<option selected value="">All Items</option>
	<option value="0">Armor</option>
	<option value="1">Sword</option>
	<option value="2">Axe</option>
	<option value="3">Spear</option>
	<option value="4">Bows</option>
	<option value="5">Bludgeon</option>
	<option value="6">Knives</option>
	<option value="7">Shield</option>
	<option value="12">Talisman</option>
</select>
<br>
</td>
</tr>
<tr>
<td><label for="bonus"><font class="littletext"><b>Bonus:</b></label><br></b></td>
<td>
<select name="bonus" size="1" class="form">
	<option selected value="">Any</option>
	<option value="A">Damage</option>
	<option value="D">Block</option>
	<option value="O">Attack</option>
	<option value="N">Defense</option>
	<option value="S">Stun</option>
	<option value="U">Unblockable</option>
	<option value="C">Agility</option>
	<option value="G">Gain Health</option>
	<option value="P">Poison</option>
	<option value="T">Taint</option>
	<option value="pG">Gold steal</option>
	<option value="pX">Exp gain</option>
</select>
<br>
</td>
</tr>
<tr> 
<td><label for="costmin"><font class="littletext"><b>Cost:<br></b></label></td>
<td> <input type="text" name="costmin" size="10" id="costmin" value="1" class="form" maxlength="9" /><font class="littletext_f"> to <input type="text" name="costmax" size="12" id="costmax" value="999999999" class="form" maxlength="10" /></td>
</tr>
</table>
<br>
<input type="Submit" name="submit" value="Search Here" class="form">&nbsp;&nbsp;&nbsp;
<input type="Submit" name="world" value="Search World" class="form">
</form>

<?php
}
?>

<br>
</center>

<?php
include('footer.htm');
?>
