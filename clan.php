<?php
/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");

$message=$_GET['message'];
$submitted=$_POST['submitted'];
$note=$_POST['note'];

$wait_post = 30;

if (!$char['society']) header("Location: $server_name/viewclans.php?autologin=$time");

$id=$char['id'];

$soc_name = $char['society'];

// REMOVE ALL MEMBERS OVER 20 DAYS

$delete_before = (time() - (86400*20))/3600;
mysql_query("UPDATE Users SET society='' WHERE society='$soc_name' AND lastcheck<'$delete_before' ");

// LOAD SOCIETY TABLE

$query = "SELECT * FROM Soc WHERE name='$soc_name'";
$result = mysql_query($query);
$society = mysql_fetch_array($result);

$notes=unserialize($society['forum']);
$lengtharray=count($notes);
$stance = unserialize($society['stance']);

// UPDATE NUMBER OF MEMEBERS

$query = "SELECT COUNT(*) FROM Users WHERE society='$soc_name' ";
$resultf = mysql_fetch_array(mysql_query($query));
$numchar = $resultf[0];
$query = "UPDATE Soc SET members='$numchar' WHERE name='$soc_name' ";
if ($numchar!=$society['members']) $result = mysql_query($query);

$message = "<b>$soc_name</b> - Contains ".$society[members]." members";

/*// BRING UP NEW LEADER IF HE IS OVER 5 DAYS
$leader_name = $society[leader];
$leader_last = $society[leaderlast];
$deleteleader_before = (time() - (86400*5))/3600;
$result = mysql_query("SELECT id, lastcheck FROM Users WHERE name='$leader_name' AND lastname='$leader_last' ",$db);
$char_leader = mysql_fetch_array($result);

if ($char_leader[lastcheck] < $deleteleader_before )
{
$query = "SELECT name, lastname FROM Users WHERE society='$soc_name' AND lastcheck>='$deleteleader_before' ORDER BY infl DESC LIMIT 1";
$result = mysql_query($query);
if (mysql_num_rows($result))
{
$charnew = mysql_fetch_array($result);
$charname = $charnew[name];
$charlast = $charnew[lastname];
$querya = "UPDATE Soc SET leader='$charname', leaderlast='$charlast' WHERE name='$soc_name' ";
$result = mysql_query($querya);
$society[leader] = $charname;
$society[leaderlast] = $charlast;
}
}*/

// ADD MESSAGE

if ($note && time() - intval($char['lastpost']) > $wait_post)
{

if (strlen($note) < 500)
{

$note = htmlspecialchars(stripslashes($note),ENT_QUOTES);
if (preg_match('/^[-a-z0-9+.,!@(){}<>^$\[\]*_&=#:\/%;?\s]*$/i',$note))
{

// IF TEN OR MORE POSTS THEN DELETE OLDEST

if ($lengtharray >= 10)
{
$y=0;
while ($y < $lengtharray)
{
if ( $lengtharray - 1 > $y ) $notes[$y] = $notes[$y+1];
$y ++;
}
array_pop($notes);
}

// END DELETE OLDEST MESSAGE

$lengtharray=count($notes);
$note=strip_tags($note);

$notes[$lengtharray][0]="$name";
$notes[$lengtharray][1]="$lastname";
$notes[$lengtharray][2]="$note";
$notes[$lengtharray][3]=time();
$notes2=serialize($notes);

if (strlen($notes2) < 65535)
{
$query = "UPDATE Soc SET forum='$notes2' WHERE name='$soc_name' ";
$result = mysql_query($query);
mysql_query("UPDATE Users SET lastpost='".time()."' WHERE id='".$char['id']."'");
$char['lastpost'] = time();

$society['forum'] = $notes;
$lengtharray=count($notes);
}
else $message="Recipient is already weighted down with too many messages";
}
else $message="What strange characters you used, messagers refuse to touch the note";
}
else
{
$message="Message is too wordy, no one wants to listen to that";
}
}
if ($submitted && !$note) $message="Why ever would you want to say nothing?";

// JAVASCRIPT
$timeout = 1000 * intval($wait_post - (time() - $char['lastpost']));
if (time() - intval($char['lastpost']) <= $wait_post) $needShow = "setTimeout('setShow();',$timeout);";
else $needShow = "";
$javascripts=<<<SJAVA
<SCRIPT LANGUAGE="JavaScript">
	$needShow
	function setShow() {
		document.getElementById('showBut').style.display='block';
		document.getElementById('showBut2').style.display='none';
	}
</SCRIPT>
SJAVA;

include('header.htm');
?>

<font face="VERDANA"><font class="littletext"><center>

The Leader of <b><?php echo "$soc_name"; ?></b> is <a href="bio.php?name=<?php echo $society[leader]; echo "&last="; echo $society[leaderlast]; echo "&time="; echo time(); ?>"><?php echo $society[leader]; echo " "; echo $society[leaderlast]; ?></a><br><br>

<font class="foottext">[<a href="look.php?first=1&clan=<?php echo "$soc_name"; ?>&time=<?php echo "$curtime"; ?>">View Members</a>] [<a href="viewclans.php?time=<?php echo "$curtime"; ?>">View all Clans</a>] [<a href="javascript:popConfirm('Leave <?php echo $soc_name; ?>? You will loose all of your influence.','clanleave.php?doit=1&time=<?php echo "$curtime"; ?>');">Leave <?php echo "$soc_name"; ?></a>]<?php if (strtolower($name) == strtolower($society[leader]) && strtolower($lastname) == strtolower($society[leaderlast]) ) {?> [<a href="clansettings.php?time=<?php echo "$curtime"; ?>">Clan Settings</a>]<?php } ?>
<br><br>

<!-- ALLIES AND ENEMIES OF THE CLAN -->
<font class="foottext"><?php echo "$soc_name members have won ".number_format($society[score])." battles."; ?><br><br>
<table border=0 cellpadding=0 cellspacing=0><tr>
<td valign="top">
<table border=0 cellpadding=0 cellspacing=0><tr><td><center><table border=0 cellpadding=5 cellspacing=0><tr><td width="15"><img src="images/support.gif"></td><td><center><font class="foottext"><b>Allies</b></td><td width="15"><img src="images/support.gif"></td></tr></table></td></tr>
<?php
$draw_any = 0;
foreach ($stance as $c_n => $c_s)
{
if ($c_s == 1) {echo "<tr><td><center><font class=foottext><a href=\"joinclan.php?name=".str_replace("_"," ",$c_n)."&time=$curtime\">".str_replace("_"," ",$c_n)."</a></td></tr>"; $draw_any = 1;}
}
if ($draw_any == 0) echo "<tr><td><center><font class=foottext>no allies</td></tr>";
?>
</table>
</td>

<td width="100"></td>

<td valign="top">
<table border=0 cellpadding=0 cellspacing=0><tr><td><center><table border=0 cellpadding=5 cellspacing=0><tr><td width="15"><img src="images/wins.gif"></td><td><center><font class="foottext"><b>Enemies</b></td><td width="15"><img src="images/wins.gif"></td></tr></table></td></tr>
<?php
$draw_any = 0;
foreach ($stance as $c_n => $c_s)
{
if ($c_s == 2) {echo "<tr><td><center><font class=foottext><a href=\"joinclan.php?name=".str_replace("_"," ",$c_n)."&time=$curtime\">".str_replace("_"," ",$c_n)."</a></td></tr>"; $draw_any = 1;}
}
if ($draw_any == 0) echo "<tr><td><center><font class=foottext>no enemies</td></tr>";
?>
</table>
</td>

</tr></table>

<!-- END DRAW ALLIES AND ENEMIES -->

<br><br>
<font class="littletext">
<i><?php echo nl2br($society[about]); ?></i><br><br><br>

<!-- DISPLAY MESSAGE LIST -->

<table cellspacing="0" border="0" cellpadding="0" width="600"><tr><td width="150"><font class="littletext"><center><b>Member</b></td><td><font class="littletext"><center><b>Comment</b></td></tr></table>
<?php echo $div_img; ?>
<br>

<?php
$x = $lengtharray - 25;
if ($x < 0) $x = 0;

while ($x < $lengtharray)
{
$curnote=nl2br($notes[$x][2]);
$curnote=str_replace("note=","note=$x",$curnote);
$senttime = "Seconds ago";
$minpast = intval((time()-$notes[$x][3])/60);
if ($minpast > 1 && $minpast < 60) $senttime = "$minpast minutes ago";
elseif ($minpast >= 60 && $minpast < 120) $senttime = "An hour ago";
elseif ($minpast >= 120 && $minpast < 1440) $senttime = intval($minpast/60)." hours ago";
elseif ($minpast >= 1440 && $minpast < 2880) $senttime = "A day ago";
elseif ($minpast >= 2880) $senttime = intval($minpast/1440)." days ago";
?>
<table border="0" cellspacing="0" cellpadding="5" width="550">
<tr><td width="150" valign='middle'><font class="littletext_f"><b><a href="bio.php?name=<?php echo $notes[$x][0]; echo "&last="; echo $notes[$x][1]; echo "&time="; echo time(); ?>"><?php echo $notes[$x][0]; echo " "; echo $notes[$x][1]; ?></a></b><br><br><?php echo $senttime; ?><br><br></td><td valign='middle'><font class="littletext"><?php echo "$curnote"; ?><br><br></td></tr>
</table>
<?php echo $div_img; ?>
<br>
<?php
$x ++;
}
?>
<a name="goto"></a>
<br>

<!-- ADD A MESSAGE -->

<center>
<table border="0"><tr><td>
<br><br>
<center>
<font class="littletext">
<b>Add Comment:</b><br>
<form action="clan.php?message=Comment Added#goto" method="post">
<textarea class="form" name="note" rows="6" cols="50" wrap="soft"></textarea><br>
<br>
  <center><input type="submit" name="submit" id="showBut" value="Add Comment" class="form" style="display: <?php if (time() - intval($char['lastpost']) > $wait_post) echo "block"; else echo "none"; ?>">
  <div id="showBut2" value="Add Comment" style="display: <?php if (time() - intval($char['lastpost']) > $wait_post) echo "none"; else echo "block"; ?>">
  	<font class="littletext_f">Wait 30 seconds between posts...
  </div>
</form>
</td></tr></table>




<?php
include('footer.htm');
?>
