<?php
/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$notes=unserialize($char['log']);
$lengtharray=count($notes);
$message=$_GET['deletera'];
$id=$char['id'];
$resultnumb = 0;
$resultnumb = $_GET['resultnumb'];

if ($message == '') $message = "You have $lengtharray defense log entries (".intval(100*$lengtharray/50)."%)";
include('header.htm');
?>

<font face="VERDANA"><font class="littletext"><center>

<form method="post" action="logsdel.php">
<?php
$x=$resultnumb;

if ($lengtharray > 0)
{
?>
<table cellspacing="0" border="0" cellpadding="0" width="600"><tr><td width="150"><font class="littletext"><center><b>Attacker</b></td><td><font class="littletext"><center><b>Results</b></td></tr></table>
<?php echo $div_img; ?>
<br>
<?php
while ($x < $lengtharray)
{
$y = $lengtharray - $x - 1;
if ($notes[$y][0])
{
$curnote=nl2br($notes[$y][0]);
$curnote=str_replace("note=","note=$y",$curnote);
$senttime = "Seconds ago";
$minpast = intval((time()-$notes[$y][4])/60);
if ($minpast > 1 && $minpast < 60) $senttime = "$minpast minutes ago";
elseif ($minpast >= 60 && $minpast < 120) $senttime = "An hour ago";
elseif ($minpast >= 120 && $minpast < 1440) $senttime = intval($minpast/60)." hours ago";
elseif ($minpast >= 1440 && $minpast < 2880) $senttime = "A day ago";
elseif ($minpast >= 2880) $senttime = intval($minpast/1440)." days ago";
?>
<table border="0" cellspacing="0" cellpadding="15" width="550" >
<tr><td width="150"><font class="littletext_f"><p align="left"><input type="checkbox" name="<?php echo"$y"; ?>" value="1"> <b><a href="bio.php?name=<?php echo $notes[$y][2]; echo "&last="; echo $notes[$y][3]; echo "&time="; echo time(); ?>"><?php echo $notes[$y][2]; echo " "; echo $notes[$y][3]; ?></a></b><br><br><?php echo $senttime; ?></td><td><font class="littletext"><p align="left"><?php echo "$curnote"; ?></td></tr>
</table>
<br>
<?php echo $div_img; ?>
<br>
<?php
$x ++;
}
if ($x >= $resultnumb + 20) {break;}
}

// END DRAWING MESSAGES
}
else echo "<br><br><b>-No Defenses Logged-</b>";
?>

<center>
<input type="hidden" name="ifsubmitted" value="1">
<?php
if ($lengtharray > 0)
{
// NEXT PAGE LINKS
$resultnumb1=$resultnumb + 20;
$resultnumb2=$resultnumb - 20;
echo "<table border=0 width=400><tr><td width=150>";

if ($resultnumb > 0)
{
?>
<p align="left"><font class="littletext"><a href="log.php?resultnumb=<?php echo "$resultnumb2&time=$time"; ?>">&#60;&#60; Previous</a>
<?php
}
?>
</td><td width="100"><font class="littletext"><center>Page <?php echo (($resultnumb / 20) + 1); ?></td><td width="150">
<?php
if ($resultnumb + 20 < $lengtharray)
{
?>
<p align="right"><font class="littletext"><a href="log.php?resultnumb=<?php echo "$resultnumb1&time=$time"; ?>">Next &#62;&#62;</a>
<?php
}
echo "</td></tr></table><br>";

// DELETE MESSAGE FORMS
?>
<table border=0><tr><td valign=top>
<input type="Submit" name="submit" value="Discard Checked" class="form">
</form>

<form method="post" action="logsdel.php">
<input type="hidden" name="trashall" value="1"></td><td valign=top>
<input type="Submit" name="submit" value="Discard All" class="form">
</form></td></tr></table>
<?php
}
?>

<br>
<br>

</center>

<?php
include('footer.htm');
?>



