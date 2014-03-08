<?php
/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$notes=unserialize($char['log']);
$lengtharray=count($notes);
$deleter=$_POST['ifsubmitted'];
$id=$char['id'];
$time=time();

// DELETE MESSAGES

if ( $deleter == 1)
{

$z=0;
$x=0;
while ($x < $lengtharray)
{
if (isset($_POST[$x + $z]))
{
$y = $x;
while ($y < $lengtharray)
{
if ( $lengtharray - 1 > $y ) $notes[$y] = $notes[$y+1];
$y ++;
}
$z++;
$x = $x - 1;
$lengtharray = $lengtharray - 1;
array_pop($notes);
}
$x ++;
}

$id = $char[id];
$notess = serialize($notes);
$query = "UPDATE Users_data SET log='$notess' WHERE id=$id";
$result = mysql_query($query);
header("Location: $server_name/log.php?resultnumb=0&deletera=Selected entries removed&time=$time");
exit;
}

// DELETE ALL

if ( $_POST['trashall'] )
{

$x=0;
while ($x < $lengtharray)
{
array_pop($notes);
$x ++;
}

$notess = serialize($notes);
$query = "UPDATE Users_data SET log='$notess' WHERE id=$id";
$result = mysql_query($query);
header("Location: $server_name/bio.php?message=All log entries removed&time=$time");
exit;
}

mysql_close($db);
?>
