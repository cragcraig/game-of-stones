<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
include("admin/itemarray.php");

$query = "SELECT * FROM Shop";
$result = mysql_query($query);
$shop = mysql_fetch_array($result);
$message = $_GET[message];
if ($message == '') $message = "<b>Blacksmith Shop</b> - restocked frequently";
$buy = $_GET[buy];

$itmlist=unserialize($shop['inventory']);
$listsize=count($itmlist);

// BUY ITEM
$time = time();
if ($time-$char[lastbuy] >= 60)
{
if (iname($buy-1,$itmlist) == $_GET[name] && $buy<=$listsize && $buy>0)
{
$buy--;
$worth = item_val(itp($item_base[$itmlist[$buy][0]][0]." ".$item_ix[$itmlist[$buy][1]]." ".$item_ix[$itmlist[$buy][2]],$item_base[$itmlist[$buy][0]][1]));
if ($char[gold] >= $worth)
{
$gold = $char[gold];
$itmarray=unserialize($char['itmlist']);
$itmsize=count($itmarray);
if ($itmsize < $inv_max)
{
$gold = $char[gold]-$worth; $char[gold]=$gold;
$itmarray[$itmsize][0] = $itmlist[$buy][0];
$itmarray[$itmsize][1] = $itmlist[$buy][1];
$itmarray[$itmsize][2] = $itmlist[$buy][2];
$itmarray[$itmsize][3] = 0;
$itmlists = serialize($itmarray);
mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users_data.itmlist='$itmlists', Users.gold='$gold', Users.lastbuy='$time' WHERE Users.id=$id");
$message = $_GET[name]." purchased for ".number_format($worth)." gold";

// CHOOSE NEW ITEM
if ($itmlist[$buy][0] != "talisman")
{
if (rand(0,20) < 12) $pre = rand(1,count($some_prefixes)-1); else $pre = 0;
$picker = rand(2,count($item_base)-2);
$x=0;
foreach ($item_base as $n => $a)
{
if ($x == $picker) {$itmlist[$buy][0] = $n; if (!$item_base[$itmlist[$buy][0]][2]) $itmlist[$buy][1] = $some_prefixes[$pre]; else $itmlist[$buy][1]=""; $itmlist[$buy][2] = ""; break;}
$x++;
}
}
else
{
$itmlist[$buy][0] == "talisman";
// first chance
$picker = rand(0,count($item_ix)-1);
$x=0;
foreach ($item_ix as $n => $a)
{
if ($x == $picker)
{
if ($n[0].$n[1] != "of") {$itmlist[$buy][1] = $n; $itmlist[$buy][2] = "";}
else {$itmlist[$buy][1] = ""; $itmlist[$buy][2] = $n;}
break;
}
$x++;
}
// second chance
$picker = rand(0,count($item_ix)-1);
$x=0;
foreach ($item_ix as $n => $a)
{
if ($x == $picker)
{
if ($n[0].$n[1] != "of") {$itmlist[$buy][1] = $n;}
else {$itmlist[$buy][2] = $n;}
break;
}
$x++;
}
}
// END CHOOSE NEW ITEM

$time = time();
$bought_something = 1;
$itmlists = serialize($itmlist);
mysql_query("UPDATE Shop SET inventory='$itmlists', time='$time' WHERE 1");
}
else $message = "Your inventory is full";
}
else $message = "You do not have enough gold";
}
elseif (iname($buy-1,$itmlist) != $_GET[name]) $message = "That ".$_GET[name]." has been sold already";
}
else if ($time-$char[lastbuy] < 60) $message = intval(60-($time-$char[lastbuy]))." seconds until you may make another purchase";

// UPDATE IF NO PURCHASES LATELY
if (time()-$shop[time] >= 200)
{
for ($z=0; $z<5; $z++)
{
$buy = rand(0,count($itmlist)-1);
// CHOOSE NEW ITEM
if ($itmlist[$buy][0] != "talisman")
{
if (rand(0,20) < 12) $pre = rand(1,count($some_prefixes)-1); else $pre = 0;
$picker = rand(2,count($item_base)-2);
$x=0;
foreach ($item_base as $n => $a)
{
if ($x == $picker) {$itmlist[$buy][0] = $n; if (!$item_base[$itmlist[$buy][0]][2]) $itmlist[$buy][1] = $some_prefixes[$pre]; else $itmlist[$buy][1]=""; $itmlist[$buy][2] = ""; break;}
$x++;
}
}
else
{
$itmlist[$buy][0] == "talisman";
// first chance
$picker = rand(0,count($item_ix)-1);
$x=0;
foreach ($item_ix as $n => $a)
{
if ($x == $picker)
{
if ($n[0].$n[1] != "of") {$itmlist[$buy][1] = $n; $itmlist[$buy][2] = "";}
else {$itmlist[$buy][1] = ""; $itmlist[$buy][2] = $n;}
break;
}
$x++;
}
// second chance
$picker = rand(0,count($item_ix)-1);
$x=0;
foreach ($item_ix as $n => $a)
{
if ($x == $picker)
{
if ($n[0].$n[1] != "of") {$itmlist[$buy][1] = $n;}
else {$itmlist[$buy][2] = $n;}
break;
}
$x++;
}
}

$time = time();
$itmlists = serialize($itmlist);
mysql_query("UPDATE Shop SET inventory='$itmlists', time='$time' WHERE 1");
// END CHOOSE NEW ITEM
}
}

// DRAW PAGE

include('header.htm');

echo "<center><table border=0 cellpadding=0 cellspacing=10><tr>";

for ($x=0; $x<$listsize; $x++)
{
$worth = item_val(itp($item_base[$itmlist[$x][0]][0]." ".$item_ix[$itmlist[$x][1]]." ".$item_ix[$itmlist[$x][2]],$item_base[$itmlist[$x][0]][1]));
if ($worth > $char[gold]) $color = "<font color='#ED3915'>"; else $color = "";
echo "<td width='100'><font class='foottext'><center><b>".iname($x,$itmlist)."</b><br><img border=0 bordercolor='black' src=\"items/".str_replace(" ","",$itmlist[$x][0]).".gif\">";
echo "<br>".$color.number_format($worth)." g<font class='foottext'><br>";
echo "[<A HREF=\"javascript:popUp('itemstat.php?base=".$itmlist[$x][0]."&prefix=".$itmlist[$x][1]."&suffix=".$itmlist[$x][2]."')\">Info</A> | <a href=\"shop.php?buy=".($x+1)."&name=".iname($x,$itmlist)."\">Buy</a>]<br><br>";
if (intval(($x+1)/5) == ($x+1)/5) echo "</tr><tr>";
}

echo "</tr></table>";

?>

</center>
<?php
include('footer.htm');
?>