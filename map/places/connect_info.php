<?php
$db = mysql_connect("localhost", "root","");
mysql_select_db("gos",$db);
if (!$db) {echo "<br><b>Cannot connect to the MySQL database.</b>"; exit;}
if (!$no_query)
{
	$result = mysql_query("SELECT Users.id, Users.gold, Users.bankgold, Users.lastbank, Users.travelmode, Users.travelmode_name, Users.feedneed, Users.travelmode2, Users.location, Users.travelto, Users.arrival, Users.depart, Users.password, Users.traveltype, Users.r_start FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.id='$id'");
	$char = mysql_fetch_array($result);
	$id=$char['id'];
}
else {
	$result = mysql_query("SELECT password FROM Users WHERE id='$id'");
	$char = mysql_fetch_array($result);
}
if ($char['password'] != $password) {
	echo "<center><font color='white'><br>Nice try, cheater.";
	exit;
}
?>
