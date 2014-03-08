<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$numbofresults = 25;
$time=time();

$types = array("Any","Shadow","Light","Not Shadow","Not Light","Neutral");

$soc_name = $char[society];

$searchpage = $_POST['pagenumber'];
$resultnumb = $_GET['pagenumb'];
if ($resultnumb == '') $resultnumb = 0;

$searchpage = intval($searchpage);
if ($searchpage > 0) $resultnumb = ($searchpage - 1) * $numbofresults;

$query = "SELECT * FROM Soc WHERE 1 ORDER BY score DESC, members DESC";

// DISPLAY LIST
$result = mysql_query($query);
$numchar2 = mysql_num_rows($result);
if ($resultnumb > $numchar2) {$resultnumb = intval($numchar2/$numbofresults) * $numbofresults; $query = "SELECT * FROM Soc WHERE 1 ORDER BY score DESC, members DESC LIMIT $resultnumb,$numbofresults"; $result = mysql_query($query);}

// ACTUAL SELECT DISPLAYED CLANS
$query = "SELECT * FROM Soc WHERE 1 ORDER BY score DESC, members DESC LIMIT $resultnumb,$numbofresults";
$result = mysql_query($query);

if ($message == '') $message = "Clans - World List";

//HEADER
include('header.htm');
?>

<font face="VERDANA"><font class="littletext"><center>

<br>
<center>
<table border="0" cellpadding="5" cellspacing="0" width="550">
<tr><td width="155"><b><font class="littletext"><center>Title</td><td width="98"><b><font class="littletext"><center>Type</td><td width="104"><b><font class="littletext"><center>Affiliation</td><td width="97"><b><font class="littletext"><center>Wins</td><td width="102"><b><font class="littletext"><center>Members</td></tr>
</table>
<?php
echo $div_img;
?>
<table border="0" cellpadding="5" cellspacing="0" width="550">
<?
$x=0;
while ( $listchar = mysql_fetch_array( $result ) )
{
?>
<tr onMouseOver="this.bgColor='#111111';" onMouseOut="this.bgColor='#000000';">
<td width="138"><b><center><font class="foottext"><a href="joinclan.php?name=<?php echo $listchar['name']; echo "&time="; echo time(); ?>"><?php echo $listchar[name]; ?></a></b></td><td width="98"><font class="foottext"><center><?php if ($listchar[invite] == 0) echo "Open"; else echo "Invite Only"; ?></td><td width="104"><font class="foottext"><center><?php echo $types[$listchar[allow]]; ?></td><td width="97"><font class="foottext"><center><?php echo number_format($listchar[score]); ?></td><td width="102"><font class="foottext"><center><?php echo number_format($listchar[members]); ?></td>
</tr>
<?php
$x++;
if ($x == $numbofresults) {break;}
}
echo "</table>";
if ($x == 0) echo "<center><br><b>-No Clans-</b><br><br>";
echo $div_img;
?>

<!-- SWITCH PAGES AND SEARCH -->

<table border="0" width="500">
<tr><td width="100">
<?php
$resultnumb1=$resultnumb + $numbofresults;
$resultnumb2=$resultnumb - $numbofresults;
$resultnumbh=$resultnumb + 1;

if ($resultnumb > 0)
{
?>
<p align="left"><font class="littletext"><a href="viewclans.php?pagenumb=<?php echo "$resultnumb2&time=$time"; ?>">&#60;&#60; Previous</a>
<?php
}
?>
</td><td width="300"><font class="littletext"><center><?php echo "$resultnumbh - $resultnumb1"; ?></td><td width="100">
<?php
if ($resultnumb + $numbofresults < $numchar2)
{
?>
<p align="right"><font class="littletext"><a href="viewclans.php?pagenumb=<?php echo "$resultnumb1&time=$time"; ?>">Next &#62;&#62;</a>
<?php
}
?>
</td></tr></table>
<br>

<font class="littletext">
<form action="viewclans.php" method="post">
Page 
<input type="text" name="pagenumber" id="pagenumber" value="1" class="form" maxlength="7" size="5" /> 
 <input type="submit" name="submit" value="Goto" class="form" />
</form>
<?php if (!$soc_name) echo "<font class='foottext'>[<a href=makeclan.php?$time>Form a Clan</a>]"; ?>
<?php
include('footer.htm');
?>
















