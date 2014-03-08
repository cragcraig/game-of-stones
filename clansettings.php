<?php

function delete_blank($array)
{
	$new_array='';
	foreach ($array as $key => $value)
	{
		if ($value[0]) $new_array[$key]=$value;
	}
	return $new_array;
}

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");

$message = "Edit clan settings";

$avatar=$_POST['newav'];
$newtype=$_POST['type'];
$newtext=$_POST['aboutchar'];
$kick=$_POST['kick'];
$unblock=$_POST['unblock'];
$allow=$_POST['allow'];
$id=$char['id'];
$soc_name = $char[society];

// KICK OFF PAGE IF NOT CLAN LEADER

$society = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='$soc_name' "));

if ($name != $society[leader] || $lastname != $society[leaderlast] )
{
header("Location: $server_name/clan.php?time=$time&message=Stop trying to cheat");
exit;
}

$blocked = unserialize($society['blocked']);

// Kick Character

if ($kick)
{
$query = "UPDATE Users SET society='', infl='0', support='0' WHERE id='$kick'";
$result = mysql_query($query);
$result = mysql_query("SELECT * FROM Users WHERE id='$kick'");
$char_kick = mysql_fetch_array($result);
// UPDATE NUMBER OF CLAN MEMBERS AND BLOCK
$memnumb = $society[members] - 1;
$blocked[$kick]=array($char_kick[name],$char_kick[lastname]);
$blocked_str=serialize($blocked);
// REMOVE INFLUENCE
mysql_query("UPDATE Users SET infl=infl - ".$char_kick[level]." WHERE id='".$char_kick[support]."'");
mysql_query("UPDATE Users SET support='0' WHERE support='$kick'");
if (strlen($blocked_str)<5000)
{
$query = "UPDATE Soc SET members='$memnumb', blocked='$blocked_str' WHERE name='$soc_name'";
$result = mysql_query($query);
$message="Character kicked and blocked from Clan";
}
else $message="Character kicked but could not be blocked - too many blocked already";
}

// Unblock Character

if ($unblock)
{
$blocked[$unblock][0]=0;
$blocked=delete_blank($blocked);
$query = "UPDATE Soc SET blocked='".serialize($blocked)."' WHERE name='$soc_name'";
$result = mysql_query($query);
$message="Character unblocked from Clan";
}


// Set Allowances

if ($allow)
{
$allow--;
$query = "UPDATE Soc SET allow='$allow' WHERE name='$soc_name' ";
$society[allow]=$allow;
$result = mysql_query($query);
$message="Member requirements updated";
}

// Update Clan Info

if ($_POST['changer2'])
{
$newtext = htmlspecialchars(stripslashes($newtext),ENT_QUOTES);
if (strlen($newtext) < 501 && preg_match('/^[-a-z0-9+.,!@*_&#:\/%;?\s]*$/i',$newtext))
{
$message = "Clan Info updated successfully";
$query = "UPDATE Soc SET about='$newtext' WHERE name='$soc_name' ";
$result = mysql_query($query);
$society[about]=$newtext;
}
else {$message="Info must be a max of 500 <b>supported</b> characters";}
}

// Update Invite Settings

if ($newtype)
{
$message = "Clan Requirements Updated";
$newtype--;
$query = "UPDATE Soc SET invite='$newtype' WHERE name='$soc_name' ";
$result = mysql_query($query);
$society[invite]=$newtype;
}

if ($messages == '') $messages = "$soc_name settings";

include('header.htm');
?>
<font class="littletext">
<font face="VERDANA"><font class="littletext"><center>

<?php

// GET ALL SOCIETY MEMBERS

$query = "SELECT id, name, lastname FROM Users WHERE society='$soc_name' ORDER BY lastname, name";
$result = mysql_query($query);
$numpeople = mysql_num_rows($result);
?>

<center>
<table border="0" cellpadding="0" cellspacing="0"><tr><td>
<p align='left'>

<?php
if ($numpeople > 1)
{
?>

<!-- KICK CLAN MEMBER -->


<form action="clansettings.php" method="post">
<font class="littletext"><b>Kick </b>
 <select name="kick" size="1" class="form">

<?php

// KICK

$x = 0;
while ($x < $numpeople)
{
$charnew = mysql_fetch_array($result);
if ( strtolower($charnew[name]) != strtolower($char[name]) || strtolower($charnew[lastname]) != strtolower($char[lastname]) )
{
?>
<option value="<?php echo $charnew[id]; ?>"><?php echo $charnew[name]." ".$charnew[lastname]; ?></option>
<?php
}
$x++;
}
?>
</select>
 from <?php echo "$soc_name"; ?>   
<input type="Submit" name="submit" value="Kick" class="form">
</form>
<br><br>

<?php
}
?>

<br>
<table border=0 cellspacing=0 cellpadding=0"><tr><td>

<table border="0" cellpadding="0" cellspacing="0"><tr><td>
<p align="left">



<!-- UNBLOCK CLAN MEMBER -->


<form action="clansettings.php" method="post">
<font class="littletext"><b>Unblock </b>
 <select name="unblock" size="1" class="form">
<option value="0">no one</option>

<?php
// UNBLOCK

foreach ($blocked as $c_n => $c_s)
{
?>
<option value="<?php echo $c_n; ?>"><?php echo $c_s[0]." ".$c_s[1]; ?></option>
<?php
}
?>
</select>
 from <?php echo "$soc_name"; ?>   
<input type="Submit" name="submit" value="unblock" class="form">
</form>
<br><br><br>


<!-- INVITE TO CLAN OPTION -->

<form action="clansettings.php" method="post">
<font class="littletext"><b>New members:</b> 
<select name="type" size="1" class="form">
	<option value="0">[keep current]</option>
	<option value="1">freely</option>
	<option value="2">by member invitation</option>
	<option value="3">by leader invitation</option>
</select>

<br><br><br>
<!-- ALLOWANCES -->

<form action="clansettings.php" method="post">
<font class="littletext"><b>Allow:</b> 
<select name="allow" size="1" class="form">
	<option value="0">[keep current]</option>
	<option value="1">any type</option>
	<option value="2">darkfriends</option>
	<option value="3">pure</option>
	<option value="4">not darkfriends</option>
	<option value="5">not pure</option>
	<option value="6">neutral</option>
</select>

<!-- CLAN INFO -->

<br><br><br>

<form action="clansettings.php" method="post">
<font class="littletext"><b>Clan Information</b><br><textarea name="aboutchar" class="form" rows="6" cols="60" wrap="soft"><?php echo $society['about']; ?></textarea>
<br>

<input type="hidden" name="changer2" value="1" id="changer2" />
<br>
<input type="Submit" name="submit" value="Update Settings" class="form">
</form>

</td></tr></table>
</center>

<?php
include('footer.htm');
?>
