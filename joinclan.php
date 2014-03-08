<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$doit=$_GET['doit'];
$noter=$_GET['note'];
$new_stance=intval($_GET['stance']);
$id=$char[id];
$soc_name=str_replace("+"," ",$_GET[name]);

$query = "SELECT * FROM Soc WHERE name='$soc_name' ";
$result = mysql_query($query);
$society = mysql_fetch_array($result);
$message = $soc_name;
$blocked = unserialize($society['blocked']);

if ($char['goodevil']==1) $classn="good"; elseif ($char['goodevil']==2) $classn="evil"; else $classn="neutral";

$soc_name_char = $char[society];
$society_char = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='$soc_name_char' "));
$stance = unserialize($society_char[stance]);

// SET CLAN'S STANCE

if ($name == $society_char[leader] && $lastname == $society_char[leaderlast] && $new_stance && $new_stance <= 2 && $new_stance >= -2 && $soc_name_char != $soc_name)
{
if ($new_stance < 0) $new_stance = 0;
$stance[str_replace(" ","_",$soc_name)] = $new_stance;
$stance[''] = 0;
$changed_stance = serialize($stance);
$query = "UPDATE Soc SET stance='$changed_stance' WHERE name='$soc_name_char' ";
$result = mysql_query($query);
}

// JOIN CLAN

if ($doit == 1)
{
if (!$blocked[$char[id]][0])
{
if ($society[allow] == 0 || ($society[allow] == 1 && $classn == "evil") || ($society[allow] == 2 && $classn == "good") || ($society[allow] == 3 && $classn != "evil") || ($society[allow] == 4 && $classn != "good") || ($society[allow] == 5 && $classn == "neutral"))
{
$notearray=unserialize($char['notes']);
$lengtharray=$noter;

if ($notearray[$lengtharray][1] == $soc_name || $society[invite] == 0)
{

if ($notearray[$lengtharray][1] == $soc_name)
{
$time2 = intval($time/400);

$notearray[$lengtharray][0]="Welcome to $soc_name.";
$notearray[$lengtharray][1]="2";
$notearrays=serialize($notearray);

$id = $char[id];
$queryd = "UPDATE Users_data SET notes='$notearrays' WHERE id='$id' ";
$result = mysql_query($queryd);
}

// SET DATABASE

$querya = "UPDATE Users SET society='$soc_name' WHERE id='$id' ";
$result = mysql_query($querya);
// ADD TO NUMBER OF MEMBERS
$query = "SELECT * FROM Soc WHERE name='$soc_name' ";
$result = mysql_query($query);
$society = mysql_fetch_array($result);
$memnumb = $society[members] + 1;
$query = "UPDATE Soc SET members='$memnumb' WHERE name='$soc_name' ";
$result = mysql_query($query);
header("Location: $server_name/clan.php?message=You Have Joined $soc_name&time=$curtime");
exit;
}
else if ($society[invite]) $message = "Invalid invitation";
}
else $message = "You are of the wrong affiliation for this Clan";
}
else $message = "You have been blocked from this Clan";
}


// CONFIRM JOIN

include('header.htm');
?>

<font class="littletext"><center>

<br>
The Leader of <?php echo "$soc_name"; ?> is <a href="bio.php?name=<?php echo $society[leader]; echo "&last="; echo $society[leaderlast]; echo "&time="; echo time(); ?>"><?php echo $society[leader]; echo " "; echo $society[leaderlast]; ?></a><br>
<br><font class=foottext><?php echo "$soc_name members have won ".number_format($society[score])." battles."; ?><font class=littletext>
<?php
if ($name == $society_char[leader] && $lastname == $society_char[leaderlast] && $soc_name_char != $soc_name)
{
echo "<br><br>Mark as: <font class=foottext>";
if ($stance[str_replace(" ","_",$soc_name)] != 1) echo "[<a href=\"joinclan.php?stance=1&name=$soc_name\">Ally</a>] "; else echo "[<a href=\"joinclan.php?stance=-1&name=$soc_name\">Non-Ally</a>]";
if ($stance[str_replace(" ","_",$soc_name)] != 2) echo "[<a href=\"joinclan.php?stance=2&name=$soc_name\">Enemy</a>] "; else echo "[<a href=\"joinclan.php?stance=-2&name=$soc_name\">Non-Enemy</a>]";
}
?>

<br><br><i><?php echo nl2br($society[about]); ?></i><br><br>

<font class="foottext">

<?php
if ($society[allow] == 0 || ($society[allow] == 1 && $classn == "evil") || ($society[allow] == 2 && $classn == "good") || ($society[allow] == 3 && $classn != "evil") || ($society[allow] == 4 && $classn != "good") || ($society[allow] == 5 && $classn == "neutral"))
{
if ($char[society] == '' && ( $society[invite] == 0 || $_GET[invite] > 200) )
{
?>
[<a href="joinclan.php?doit=1&name=<?php echo "$soc_name"; ?>&note=<?php echo "$noter"; ?>" >Join <?php echo "$soc_name"; ?></a>]
<?php
}
elseif ($society[invite] != 0)
{
?>
<b>This clan requires an invitation to join</b>
<?php
}
}
else echo "<b>You are of the wrong affiliation for this clan</b>";

if ($char[society] != '')
{
if ($char[society] != $soc_name) echo "<br><br>You may only be in one clan at a time";
else echo "<br><br>You are already in this clan";
}
?>
  
<font class="foottext">[<a href="look.php?first=1&clan=<?php echo "$soc_name"; ?>&time=<?php echo "$curtime"; ?>">View Members</a>]

</center>

<?php
include('footer.htm');
?>


