<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$numbofresults = 25;
$search=$_POST['search'];
$slast=$_POST['slast'];
$order=intval($_GET['order']);
$time=time();
$clansearch = $_GET[clan];

$query = "SELECT id, name, lastname, type, level, gold, score, location, goodevil, exp, infl, society FROM Users";
if ($clansearch) $query .= " WHERE society = '$clansearch'";
else $query .= " WHERE location='".$char['location']."'";

//  HOW TO ORDER
if ($order==1) $query .= " ORDER BY gold DESC, exp DESC";
elseif ($order==2) $query .= " ORDER BY score DESC, exp DESC";
elseif ($order==3) $query .= " ORDER BY infl DESC, exp DESC";
else $query .= " ORDER BY level DESC, exp DESC";

$searchpage = $_POST['pagenumber'];
$resultnumb = intval($_GET['pagenumb']);
if ($resultnumb == '') $resultnumb = 0;

if (!$clansearch)
{
$soc_name = $char[society];
$society = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='$soc_name'"));
$stance = unserialize($society['stance']);
}

$searchpage = intval($searchpage);
if ($searchpage > 0) $resultnumb = ($searchpage - 1) * $numbofresults;

// Unserialize battled array 
$find_battle = unserialize($char[find_battle]);

// SEARCH FOR CHAR

if ($search || $slast)
{
// SEARCH

$query1 = "SELECT name, lastname FROM Users WHERE name = '$search' AND lastname = '$slast'";

/* query the database */
$result5 = mysql_query($query1);
if (!($searchchar = mysql_fetch_array($result5)))
{
$query1 = "SELECT name, lastname FROM Users WHERE name LIKE '%$search%' AND lastname LIKE '%$slast%'";
$result5 = mysql_query($query1);
$searchchar = mysql_fetch_array($result5);
}
/* Allow access if a matching record was found*/
if ($searchchar)
{
$search = $searchchar[name];
$slast = $searchchar[lastname];
$message = "Asking around, you hear of someone named <a href='bio.php?name=$search&last=$slast&time=$time'>$search $slast</a>";
}
else $message = "Asking around for $search $slast, you are met only by blank stares and muttered curses.";
}

// DISPLAY LIST

// FIND WHERE YOU ARE ON LIST
$resultf = mysql_query($query);
$numchar2 = mysql_num_rows($resultf);
if ($resultnumb > $numchar2) {$resultnumb = intval($numchar2/$numbofresults) * $numbofresults; $resultf = mysql_query($query);}

if ($_GET['first'] == 1)
{
$y = 0;
while ($y < $numchar2 && ($thechar[name] != $name || $thechar[lastname] != $lastname) )
{
$thechar = mysql_fetch_array($resultf);
$y++;
}
$resultnumb = intval(($y-1)/$numbofresults) * $numbofresults;
}

$query .= " LIMIT $resultnumb,$numbofresults";
$result = mysql_query($query);
$numchar = mysql_num_rows($result);

if ($message == '')
{
$message = str_replace('-ap-','&#39;',$char['location'])." has a population of ".number_format($numchar2);
if ($clansearch) $message = $clansearch." contains ".number_format($numchar2)." members";
}

//HEADER
include('header.htm');
$style="style='border-width: 0px; border-bottom: 1px solid #333333'";
?>

<font face="VERDANA"><font class="littletext"><center>

<table border="0" cellpadding="5" cellspacing="0" width="550" height=30>
	<tr>
		<td <?php echo $style; ?> width="200" ><b><font class="littletext"><center>Name</td>
		<td <?php echo $style; ?> width="100"><center><table class='blank'><tr><td><a href="look.php?first=1&order=1&clan=<?php echo "$clansearch"; ?>"><img border='0' src='images/gold.gif'></a></td><td class='littletext'>&nbsp;<b>Gold</b><td></tr></table></td>
		<td <?php echo $style; ?> width="100"><center><table class='blank'><tr><td><a href="look.php?first=1&order=3&clan=<?php echo "$clansearch"; ?>"><img border='0' src='images/support.gif'></a></td><td class='littletext'>&nbsp;<b>Influence</b><td></tr></table></td>
		<td <?php echo $style; ?> width="100"><center><table class='blank'><tr><td><a href="look.php?first=1&order=2&clan=<?php echo "$clansearch"; ?>"><img border='0' src='images/wins.gif'></a></td><td class='littletext'>&nbsp;<b>Wins</b><td></tr></table></td>
		<td <?php echo $style; ?> width="50"><center><table class='blank'><tr><td><a href="look.php?first=1&order=0&clan=<?php echo "$clansearch"; ?>"><img border='0' src='images/lvlup.gif'></a></td><td class='littletext'>&nbsp;<b>Lvl</b><td></tr></table></td>
	</tr>

<!-- LIST ALL CHARACTERS ON PAGE -->

<?php
$img_ar = Array("s1","w1");
$x=0;
while ( $listchar = mysql_fetch_array( $result ) )
{
if ($listchar['goodevil']==1) $classn="good";
elseif ($listchar['goodevil']==2) $classn="evil";
else $classn="neutral";
if ($listchar['type'] == 'Channeler') $classn="channeler";
?>
<tr onMouseOver="this.bgColor='#111111';" onMouseOut="this.bgColor='#000000';"><td width="200"><center><table border=0 cellpadding=0 cellspacing=0><tr><td><font class="foottext"><?php if ($find_battle[$listchar[id]] > time() - 600) echo "<img src=images/noduelsmall.gif><td width=10></td>"; else if ($stance[str_replace(" ","_",$listchar[society])] && $listchar[id] != $id) echo "<img src=images/".$img_ar[$stance[str_replace(" ","_",$listchar[society])]-1].".gif><td width=10></td>"; ?></td><td><b><center><font class="littletext"><a class="<?php echo "$classn"; ?>" href="bio.php?name=<?php echo $listchar['name']."&last=".$listchar['lastname']."&time=".time(); ?>"><?php echo $listchar['name']." ".$listchar['lastname']; ?></a></b></td></tr></table></td><td width="100"><font class="foottext"><center><?php echo number_format($listchar['gold']);?> g</td><td width="100"><font class="foottext"><center><?php echo number_format($listchar['infl']);?> infl</td><td width="100"><center><font class="foottext"><?php echo number_format($listchar[score]); ?> wins</td><td width="50"><font class="foottext"><center><?php echo $listchar['level']; ?></td></tr>
<?php
$x++;
if ($x == $numbofresults) {break;}
}
// SWITCH PAGES AND SEARCH
?>
<tr><td colspan='5' style='border-width: 0px; border-top: 1px solid #333333'>

<center>
<table border="0" width='100%'>
<tr><td width="100" valign=top>
<?php
$resultnumb1=$resultnumb + $numbofresults;
$resultnumb2=$resultnumb - $numbofresults;
$resultnumbh=$resultnumb + 1;
?>

<?php
if ($resultnumb > 0)
{
?>
<font class="littletext"><a href="look.php?clan=<?php echo "$clansearch"; ?>&order=<?php echo "$order"; ?>&pagenumb=<?php echo "$resultnumb2&time=$time"; ?>">&#60;&#60; Previous</a>
<?php
}
?>
</td><td width="300" valign=top><font class="littletext_f"><center><?php echo "$resultnumbh - $resultnumb1"; ?></td><td width="100" valign=top>
<?php
if ($resultnumb + $numbofresults < $numchar2)
{
?>
<p align="right"><font class="littletext"><a href="look.php?clan=<?php echo "$clansearch"; ?>&order=<?php echo "$order"; ?>&pagenumb=<?php echo "$resultnumb1&time=$time"; ?>">Next &#62;&#62;</a>
<?php
}
?>
</td></tr></table>

</td></tr>
</table>

<br>

<font class="littletext_f">
<form action="look.php?clan=<?php echo "$clansearch"; ?>&order=<?php echo "$order"; ?>" method="post">
Page 
<input type="text" name="pagenumber" id="pagenumber" value="<?php echo (intval($resultnumb/$numbofresults)+1); ?>" class="form" maxlength="7" size="5" style="text-align:center;"> 
 <input type="submit" name="submit" value="Goto" class="form" >
</form>

<br><br>

<center>
<table border="0"><tr><td>
<font class="littletext_f">
<center>
<form action="look.php?first=1&order=<?php echo "$order"; ?>&clan=<?php echo "$clansearch"; ?>" method="post">
  <input type="submit" name="submit" value="Find" class="form"> 
  <input type="text" name="search" id="search" class="form" style="text-align:right;"> of house 
  <input type="text" name="slast" id="slast" class="form">
</form>
</center>
</td></tr></table>

<?php
include('footer.htm');
?>
