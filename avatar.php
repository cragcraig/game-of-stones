<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");

$avatar=$_POST['newav'];
$newtext=$_POST['aboutchar'];
$id=$char['id'];
$message="Edit character settings";

// Update avatar

if ($_POST['changer'])
{
$message = "Character Info updated successfully";
error_reporting(1);
if ( $avatar && strlen($avatar) < 200 && preg_match("/jpg\Z/i", $avatar) )
{
$query = "UPDATE Users SET avatar='$avatar' WHERE id='$id'";
$result = mysql_query($query);
$char['avatar']=$avatar;
}
else
{
if ($avatar)
{
$message = "Problem with Chosen Avatar";
}
else
{
$char['avatar']='';
$query = "UPDATE Users SET avatar='' WHERE id=$id";
$result = mysql_query($query);
}
}
}

// UPDATE PASSWORD

if ($_POST['password'] && $_POST['passworda'] && $_POST['passwordb'])
{
if ($_POST['passworda'] == $_POST['passwordb'] && strlen($_POST['passworda']) > 4 && strlen($_POST['passworda']) < 11 && $_POST['password'] == $char[pswd])
{
$pass = $_POST['passworda'];
$password = sha1($pass);
setcookie("password", "$password", time()+99999999, "/");
$query = "UPDATE Users SET password='$password', pswd='$pass' WHERE id='$id' ";
$result = mysql_query($query);
}
else $message = "Problem with the password";
}

// Update Character Info

if ($_POST['changer'])
{
$newtext = htmlspecialchars(stripslashes($newtext),ENT_QUOTES);
if (strlen($newtext) < 501 && preg_match('/^[-a-z0-9+.,!@*_&#:\/%;?\s]*$/i',$newtext))
{
$char['about']=$newtext;
$query = "UPDATE Users_data SET about='$newtext' WHERE id='$id'";
$result = mysql_query($query);
}
else $message="Info must be a max of 500 characters";
}

mysql_close($db);

include('header.htm');
?>
<font class="littletext">
<font face="VERDANA">
<center>

<table border="0" cellpadding="0" cellspacing="0"><tr><td>
<p align="left"><font class="littletext">

<form action="avatar.php" method="post">
<b>Offsite Avatar URL:</b> <input type="text" class="form" name="newav" value="<?php echo $char['avatar']; ?>" id="newav" MAXLENTH="200" />
<br><br>

<b>Rules</b><br><i><b>1.</b> Only ".jpg" images aprox. 200x300 pixels<br><b>2.</b> No offensive or adult themed images<br><b>3.</b> Leave input field blank for default avatar</i><br><br>

<input type="hidden" name="changer" value="1" id="changer" />

<br>

<form action="avatar.php" method="post">
<b>Change Password</b><br>
<table border="0"><tr><td></td></tr>
<tr><td><font class="littletext">Old Password </td><td> <input type="password" class="form" name="password" maxlength="10" id="password" /></td></tr>
<tr><td><font class="littletext">New Password </td><td> <input type="password" class="form" name="passworda" maxlength="10" id="passworda" /></td></tr>
<tr><td><font class="littletext">Confirm Password </td><td> <input type="password" class="form" name="passwordb" maxlength="10" id="passwordb" /></td></tr>
</table>
<br><br>


<form action="avatar.php" method="post">
<b>Character Information</b><br><textarea name="aboutchar" class="form" rows="6" cols="60" wrap="soft"><?php echo $char['about']; ?></textarea>
<br><br>

<input type="Submit" name="submit" value="Update Settings" class="form">
</form>

</td></tr></table>

</center>

<?php
include('footer.htm');
?>
