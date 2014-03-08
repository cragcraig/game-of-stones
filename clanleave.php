<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$doit=$_GET['doit'];
$id=$char[id];


$soc_name = $char['society'];

// LEAVE CLAN

if ($doit == 1)
{

// SET DATABASE

$querya = "UPDATE Users SET society='', infl='0', support='0' WHERE id='$id'";
$result = mysql_query($querya);

$querya = "UPDATE Users SET infl=infl - ".$char[level]." WHERE id='".$char[support]."'";
$result = mysql_query($querya);

$query = "SELECT * FROM Soc WHERE name='$soc_name' ";
$result = mysql_query($query);
$society = mysql_fetch_array($result);

mysql_query("UPDATE Users SET support='0' WHERE support='$id'");

// CHECK IF LEADER CHANGES
$user = mysql_fetch_array(mysql_query("SELECT * FROM Users WHERE society='".$char['society']."' ORDER BY infl DESC LIMIT 1"));
$soci = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='".$char['society']."'"));
if ($soci['leader']!=$user['name'] ||  $soci['leaderlast']!=$user['lastname']) mysql_query("UPDATE Soc SET leader='".$user['name']."', leaderlast='".$user['lastname']."' WHERE name='".$char['society']."'");

// ADD TO NUMBER OF MEMBERS
$memnumb = $society[members] - 1;
$query = "UPDATE Soc SET members='$memnumb' WHERE name='$soc_name' ";
$result = mysql_query($query);

$query = "SELECT name, lastname FROM Users WHERE society='$soc_name' ORDER BY infl DESC";
$result = mysql_query($query);
$numchar = mysql_num_rows($result);

if ($numchar == 0)
{
// IF THERE IS NO ONE LEFT IN THE CLAN THEN DELETE IT
$query = "DELETE FROM Soc WHERE name='$soc_name' ";
$result5 = mysql_query($query, $db);
}

header("Location: $server_name/viewclans.php?message=You have left $soc_name&time=$curtime");
exit;
}

$message = "Confirm clan departure";

// CONFIRM LEAVE CLAN
include('header.htm');
?>

<font face="VERDANA"><font class="bigtext"><center>
<br><br>
<b>Do you want to leave <?php echo "$soc_name"; ?>?</b><font class="littletext"><br><br>

<a href="clanleave.php?doit=1" >Confirm Departure</a>

</center>

<?php
include('footer.htm');
?>


