<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$doit=$_GET['doit'];
$clan=trim($_POST['clan']);
$id=$char[id];
$message = "Form a new Clan";

// MAKE CLAN

if ($doit == 1)
{

// CHECK IF CLAN EXISTS ALREADY
$query = "SELECT * FROM Soc WHERE name='$clan'";
$resultb = mysql_query($query, $db);

// IF CLAN DOESNT EXIST
if ( !mysql_fetch_row($resultb)  && strlen($clan) <= 30 && !eregi("[^a-z ]+",$clan) && strlen($clan) > 2)
{

// SET DATABASE TABLES

$array = array (
array ("$name", "$lastname" , "Welcome to $clan",time())
);
$ally = array ('0');
$ally[str_replace(" ","_",$clan)] = 1;
$array = serialize($array);
$ally = serialize($ally);
$querya = "INSERT INTO Soc (name, leader, leaderlast, about, forum, invite, members, allow, stance, blocked, score) VALUES ('$clan', '$name', '$lastname', '', '$array', '0', '1', '0', '$ally', '', '0')";
$result = mysql_query($querya);
$querya = "UPDATE Users SET society='$clan' WHERE id='$id'";
$result = mysql_query($querya);
header("Location: $server_name/clan.php?time=$curtime");
exit;
}
}

if ($doit  && (strlen($clan) > 20 || eregi("[^a-z ]+",$clan)) ) $message = "Problem with Clan Name";
elseif ($doit) $message = "Clan already exists";

include('header.htm');

// MAKE CLAN SCREEN
?>
<center>
<font class="littletext">

<!-- CLAN FORM -->
<br>
<form method="post" action="makeclan.php?doit=1">
<b>Clan Name: </b><input type="text" name="clan" id="clan" class="form" maxlength="30" />
<br><br>
<input type="Submit" name="submit" value="Form Clan" class="form">
</form>


<?php
include('footer.htm');
?>
