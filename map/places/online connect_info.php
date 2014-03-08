<?php
$db = mysql_connect("localhost", "gostone_urgy","cdh893");
if (!$db) {echo "<br><b>Cannot connect to the MySQL database. Dont worry, just give it a little while, it's probably being refreshed.</b>"; exit;}
mysql_select_db("gostone_breaking",$db);
$result = mysql_query("SELECT id, gold, bankgold, lastbank, travelmode, travelmode_name, feedneed, travelmode2, location, travelto, arrival, depart, traveltype FROM Users WHERE name='$name' AND lastname='$lastname' AND password='$password'",$db);
$char = mysql_fetch_array($result);
$id=$char['id'];
?>