<?php

$wait_post = 15;

function draw_bar($class)
{
	return "<table border='0' cellpadding='0' cellspacing='0' width='100' height='10' bgcolor='C51F1F' background='images/redbar.gif'><tr><td width='$class' bgcolor='2344D6' background='images/bluebar.gif'></td><td width='".(100-$class)."'></td></tr></table>";
}

function array_delel($array,$del)
{
	$arraycount1=count($array);
	$z = $del;
	while ($z < $arraycount1) {
		$array[$z] = $array[$z+1];
		$z++;
	}
	array_pop($array);
	return $array;
}

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
include("admin/itemarray.php");
$namesender = $_COOKIE['name'];
$lastnamesender = $_COOKIE['lastname'];
$name=$_GET['name'];
$submitted=$_POST['submitted'];
$lastname=$_GET['last'];
if ($name == '' || $lastname == '') {$name = $namesender; $lastname = $lastnamesender;}
if (strtolower($namesender) == strtolower($name) && strtolower($lastnamesender) == strtolower($lastname)) $is_same = 1; else $is_same = 0;
$note=$_POST['note'];
$extra=$_POST['extra'];
$message=$_GET['message'];
$time=time();
$charother = $char;
$socnameother = $charother[society];
$query = "SELECT * FROM Soc WHERE name='$socnameother' ";
$result = mysql_query($query);
$societyo = mysql_fetch_array($result);

$friends=unserialize($charother[friends]);

$result = mysql_query("SELECT * FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.name='$name' AND Users.lastname='$lastname'",$db);
$char = mysql_fetch_array($result);
$id=$char['id'];
$notearray=unserialize($char['notes']);
$time=time();

$socname = $char[society];
$query = "SELECT leader, leaderlast FROM Soc WHERE name='$socname'";
$result = mysql_query($query);
$society = mysql_fetch_array($result);

// IF CREATOR

if ($name == "The" && $lastname == "Creator") $is_creator = 1; else $is_creator = 0;

// ADD / REMOVE FRIENDS

if ($_GET[set_s] && !$is_same) {
	function delete_ele($array,$ele)
	{
		$new_array=array();
		foreach ($array as $key => $value)
		{
			if ($key!=$ele) $new_array[$key]=$value;
		}
		return $new_array;
	}

	if ($_GET[set_s]==3) {$friends=delete_ele($friends,$char[id]);  $message="$name $lastname removed";}
	elseif (count($friends)<=25) {$friends[$char[id]]=array($char[name],$char[lastname],$_GET[set_s]-1); $message="$name $lastname added";}
	else $message="You have too many friends/enemies";
	
	mysql_query("UPDATE Users_data SET friends='".serialize($friends)."' WHERE id=".$charother[id]);
}

// ACCEPT TRADE

if ($_GET[accept]) {
	$notes=unserialize($charother[notes]);
	$data=explode('|',$notes[$_GET[note]][1]);
	if ($data[0]==$char[id]) {
		if (intval($data[5])>time()) {
			if ($charother[gold]-$data[4]>=0) {
				$itmlist=unserialize($char['itmlist']);
				$num_itms=count($itmlist);
				$x=0;
				while (!($itmlist[$x][3]==0 || $itmlist[$x][3]==-2) || $itmlist[$x][0]!=$data[1] || $itmlist[$x][1]!=$data[2] || $itmlist[$x][2]!=$data[3]) {$x++; if ($x>$num_itms) break;}
				if ($x<=$num_itms && $item_base[$data[1]][1] != 8) {
					$itmlistother=unserialize($charother['itmlist']);
					$num_itmso=count($itmlistother);
					if ($num_itmso<$inv_max) {
						$itmlist=array_delel($itmlist,$x);
						$itmlistother[$num_itmso][0]=$data[1];
						$itmlistother[$num_itmso][1]=$data[2];
						$itmlistother[$num_itmso][2]=$data[3];
						$itmlistother[$num_itmso][3]="-2";
						$notes[$_GET[note]][1]="";
						$notes[$_GET[note]][0]=preg_replace('/\[.*\]/',"[trade completed]",$notes[$_GET[note]][0]);
						$notes=serialize($notes);
						$charother[gold]-=$data[4];
						$char[gold]+=$data[4];
						mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users_data.notes='$notes', Users.gold='".$charother['gold']."', itmlist='".serialize($itmlistother)."' WHERE Users.id=".$charother[id]);
						mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users.gold='".$char['gold']."', itmlist='".serialize($itmlist)."' WHERE Users.id=".$char[id]);
						$message="Trade completed successfully";
					} else $message="You do not have enough space";
				} else $message="This character no longer has that item";
			} else $message="You do not have enough gold";
		} else $message="This trade offer expired ".number_format(intval((time()-$data[5])/3600)+1)." hours ago";
	} else $message="Invalid trade link";
}


// SEND INVITE

if ($_GET['join'] != '' && $socnameother)
{
	// Send Invite
	
	$notearray=unserialize($char['notes']);
	$lengtharray=count($notearray);
	
	$time2 = intval($time/400);

	$socnameother2 = str_replace(" ","+",$socnameother);
	$notearray[$lengtharray][0]="<b>Invitation to join $socnameother</b><br><br><a href=joinclan.php?invite=$time2&name=$socnameother2&note= >Accept Invitation</a>";
	$notearray[$lengtharray][1]="$socnameother";
	$notearray[$lengtharray][2]="$namesender";
	$notearray[$lengtharray][3]="$lastnamesender";
	$notearray[$lengtharray][4]=time();
	$notearrays=serialize($notearray);

	if (count($notearray) < 50 && ($societyo[invite] == 1 || ($societyo[invite] == 2 && strtolower($societyo[leader]) == strtolower($charother[name]) && strtolower($societyo[leaderlast]) == strtolower($charother[lastname])) && $socnameother)) {
		$message = "Invitation Sent";
		$id = $char[id];
		$queryd = "UPDATE Users_data SET notes='$notearrays' WHERE id='$id' ";
		$result = mysql_query($queryd);
	}
	else $message = 'Recipient is already weighted down with too many messages';
}

// SET UP TRADE
if ($_GET[trade] && $_POST[gold]==abs(intval($_POST[gold])) && $_POST[item]) {
	if ($_POST[gold]>0) {
		$bypass_check=1;
		$itm=explode('|',$_POST[item]);
		$item_name=ucwords($itm[1])." ".ucwords($itm[0])." ".str_replace("Of","of",ucwords($itm[2]));
		$link="<font class=foottext>[<a href=bio.php?accept=1&name=".$charother[name]."&last=".$charother[lastname]."&note=>Accept Offer</a>]";
		$note="<i><b>Trade offer:</b></i> $link<font class=littletext><br><br>One <b>".$item_name."</b> in return for ".number_format($_POST[gold])." gold.<br><br><img src=items/".str_replace(" ","",$itm[0]).".gif>";
		$note_extra=$charother[id]."|".$_POST[item]."|".$_POST[gold]."|".intval(time()+intval($_POST[time]));
		$message="Trade offer dispatched";
	} else $message="You must trade for some amount of gold";
} else {$bypass_check=0; if ($_POST[gold]) $message="Invalid trade offer";}

// SEND NOTE

if ($note && time() - intval($charother['lastpost']) > $wait_post && $char[id] && $charother[id])
{
if (strlen($note) < 1000)
{
if (!$bypass_check) $note = htmlspecialchars(stripslashes($note),ENT_QUOTES);
if (preg_match('/^[-a-z0-9+.,!@(){}<>^$\[\]*_&=#:\/%;?\s]*$/i',$note) || $bypass_check)
{
$lengtharray=count($notearray);
if (!$bypass_check) $note=strip_tags($note);

$notearray[$lengtharray][0]=$note;
$notearray[$lengtharray][1]=$note_extra;
$notearray[$lengtharray][2]="$namesender";
$notearray[$lengtharray][3]="$lastnamesender";
$notearray[$lengtharray][4]=time();
$notearrays=serialize($notearray);

if ($lengtharray < 50)
{
	mysql_query("UPDATE Users SET lastpost='".time()."' WHERE id='".$charother['id']."'");
	$charother['lastpost'] = time();
	$query = "UPDATE Users_data SET notes='$notearrays' WHERE id=$id";
	$result = mysql_query($query);
}
else $message="Recipient is already weighted down with too many messages";
}
else $message="Some punctuation marks are not supported. Please remove them and try again.";
}
else
{
$message="Message is too wordy, the messenger refuses to carry so much weight";
}
}
else if ($note) $message = "You must wait 30 seconds between messages";
if ($submitted && !$note) $message="Why would you send a blank message?";

// TRANSFER ORG LEADERSHIP
if ($_GET[transfer] && $char[society] == $charother[society] && strtolower($societyo[leader]) == strtolower($charother[name]) && strtolower($societyo[leaderlast]) == strtolower($charother[lastname]))
{
$query = "UPDATE Soc SET leader='$name', leaderlast='$lastname' WHERE name='".$char[society]."'";
$result = mysql_query($query);
$message = $char[society]." leadership transfered";
}

if (!$message && $is_same) $message="[<a href='avatar.php?time=$time'>Settings</a> | <a href='logout.php?time=$time'>Logout</a>]";
elseif (!$message && $char['born']) {
	if ($char['location']==$char['travelto']) $message="Residing in ".str_replace('-ap-','&#39;',$char['location']);
	else $message="Traveling from ".str_replace('-ap-','&#39;',$char['location'])." to ".str_replace('-ap-','&#39;',$char['travelto']);
}

// JAVASCRIPT
$timeout = 1000 * intval($wait_post - (time() - $charother['lastpost']));
if (time() - intval($charother['lastpost']) <= $wait_post) $needShow = "setTimeout('setShow();',$timeout);";
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

$charb=$char;
$char=$charother;
include('header.htm');
$char=$charb;
if (!$char['born']) {echo "<center><br><br><font class='medtext'>$name $lastname does not exist"; include('footer.htm'); exit;}

// SUPPORT / UNSUPPORT

if ($char[society] == $charother[society] && $char[society] && !$is_same && ($char['id'] != $charother['support'] || $_GET['support']==2)) {
	if ($_GET['support']==1 && $charother['support']!=0) mysql_query("Update Users SET infl=infl - ".$charother['level']." WHERE id='".$charother['support']."'");
	if ($_GET['support']==1) {mysql_query("Update Users SET support='".$char['id']."' WHERE id='".$charother['id']."'"); $charother['support']=$char['id']; $char['infl']+=$charother['level'];}
	elseif ($_GET['support']==2 && $charother['support']==$char['id']) {mysql_query("Update Users SET support='0' WHERE id='".$charother['id']."'"); $charother['support']=0; $char['infl']-=$charother['level'];}
	mysql_query("Update Users SET infl='".$char['infl']."' WHERE id='".$char['id']."'");
	// CHECK IF LEADER CHANGES
	$user = mysql_fetch_array(mysql_query("SELECT * FROM Users WHERE society='".$char['society']."' ORDER BY infl DESC LIMIT 1"));
	$soci = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='".$char['society']."'"));
	if ($soci['leader']!=$user['name'] ||  $soci['leaderlast']!=$user['lastname']) mysql_query("UPDATE Soc SET leader='".$user['name']."', leaderlast='".$user['lastname']."' WHERE name='".$char['society']."'");
}
?>

<font face="VERDANA"><font class="littletext"><center>

<!-- CHARACTER DISPLAY STUFF -->

<br>
<table border="0" cellspacing="10" cellpadding='0'>
<tr>

<?php
$avatar=$char['avatar'];
if (!($avatar))
{
$type = $char['type'];
$image=strtolower($type);
$sex=$char['sex'];
$avatar = "char/$image"."$sex".".gif";
}
/*
$size = GetImageSize($avatar);
if ($size[0] > 300) $img_att = "WIDTH=\"300\" HEIGHT=\"".(300*$size[1]/$size[0])."\"";
else $img_att = ""; // <?php echo $img_att; ?>
*/

$time=time();
$gold = number_format($char['gold']);
$lvl = number_format($char['level']);
$exp = number_format($char['score']);

$stance = unserialize($societyo[stance]);
$associated = "";
if ($stance[str_replace(" ","_",$char[society])] == 1 && $char[society]!=$charother[society] && $char[society]) $associated = "ally ";
if ($stance[str_replace(" ","_",$char[society])] == 2 && !$is_same) $associated = "enemy ";

if ($char[society] != '')
{
	if (strtolower($society[leader]) == strtolower($char[name]) && strtolower($society[leaderlast]) == strtolower($char[lastname])) $society = "Leader of ";
	else $society = "Member of ";
	$society .= $associated;
	if ($char[society]!=$charother[society]) $society .= "<a href='joinclan.php?name=".$char[society]."'>".str_replace(" ","&nbsp;",$char[society])."</a><br><br>";
	else $society .= "<a href='clan.php'>".str_replace(" ","&nbsp;",$char[society])."</a><br><br>";
} else $society = '';
$health = $char[vitality];

if ($char['goodevil']==1) $classn="color: #5483C9;";
elseif ($char['goodevil']==2) $classn="color: #E14545;";
else $classn="";

if ($is_creator) {$gold = "no need"; $society = ""; $associated = ""; $classn=""; $type="God"; $lvl="infinite"; $health="limitless"; $exp="Tremble in awe";}

$win_per="(".intval(100*$char['score']/($char['battles']+0.0001)+0.5)."%) ";
if (strlen($name." ".$lastname)>16) {$font_size="class='medtext' style='font-size: 11px; $classn'"; $society="<br>".$society;} else $font_size="class='medtext' style='$classn'";

$field_s= "<FIELDSET class='abox'><LEGEND><font class=medtext><b>Actions</b></LEGEND><P>\n<font class=littletext><table border='0' cellpadding='0' cellspacing='7' width='130'>";
$field_e="</FIELDSET>";

$notes=unserialize($char['notes']);
$num_notes=count($notes);
$logs=unserialize($char['log']);
$num_logs=count($logs);

$influence = $char['infl'];
if (!$influence) $infl="<font class='foottext'>No influence";
else $infl=number_format($influence)."<font class='foottext'> infl";


echo "</td><td valign='middle'>";
	
if (!$is_same) {
// OTHER CHARACTER ACTIONS
	
	echo $field_s;
	
	// BATTLE
	$find_battle = unserialize($charother['find_battle']);
	$numbahs=array("Zero","One","Two","Three","Four","Five","Six","Seven","Eight","Nine","Ten","Ten");
	if ($char[level] >= $charother[level]-4) $duel_link = "duel.php?time=$time&id=$id";
	else $duel_link = "javascript:popConfirm('You will gain no Exp due to the large difference in levels. Duel anyway?','duel.php?time=$time&id=$id')";
	if ($char[location]==$charother[location]) {
		if ($find_battle[$char['id']] <= time() - 600 && !$is_creator && $char['location']==$charother['location'])
			echo "<tr><td><a href=\"$duel_link\"><img border='0' src='images/duel.gif' alt='duel'></a></td><td><a href=\"$duel_link\"><font class='littletext'>Duel <font class='foottext_f'>(".($battlelimit-$charother['battlestoday'])." left)</a></td></tr>";
		elseif ($find_battle[$char['id']] > time() - 600) {
			$time_to_battle=intval((600 - (time() - $find_battle[$char['id']]))/60 + 1);
			echo "<tr><td><img src='images/noduel.gif' alt='duel'></td><td class='littletext'>".$numbahs[$time_to_battle]."&nbsp;minute";
			if ($time_to_battle!=1) echo "s";
			echo "</td></tr>";
		}
	}
	// SUPPORT
	if ($char[society] == $charother[society] && $char[society]) {
		if ($charother['support']!=$char['id']) echo "<tr><td><a href='bio.php?name=$name&last=$lastname&time=$time&support=1'><img border='0' src='images/support.gif' alt='support'></a></td><td><a href='bio.php?name=$name&last=$lastname&time=$time&support=1'><font class='littletext'>Support</a></td></tr>";
		else echo "<tr><td><a href='bio.php?name=$name&last=$lastname&time=$time&support=2'><img border='0' src='images/support.gif' alt='support'></a></td><td><a href='bio.php?name=$name&last=$lastname&time=$time&support=2'><font class='littletext'>Unsupport</a></td></tr>";
	}
	// INVITE
	if ( $char[society] != $charother[society] && ($societyo[invite] == 1 || ($societyo[invite] == 2 && strtolower($societyo[leader]) == strtolower($charother[name]) && strtolower($societyo[leaderlast]) == strtolower($charother[lastname])) && $socnameother))
		echo "<tr><td><a href=bio.php?name=$name&last=$lastname&time=$time&join=59320112><img border='0' src='images/invite.gif' alt='invite'></a></td><td><a href=bio.php?name=$name&last=$lastname&time=$time&join=59320112><font class='littletext'>Send invite</a></td></tr>";
	/* TRANSFER CLAN LEADERSHIP
	if (!$_GET[transfer] && $char[society] == $charother[society] && strtolower($societyo[leader]) == strtolower($charother[name]) && strtolower($societyo[leaderlast]) == strtolower($charother[lastname]))
		echo "<tr><td><a href=bio.php?name=$name&last=$lastname&transfer=1&time=$time><img border='0' src='images/leader.gif' alt='transfer leadership'></a></td><td><a href=bio.php?name=$name&last=$lastname&transfer=1&time=$time><font class='littletext'>Make&nbsp;Leader</a></td></tr>";
	*/
	// TRADE
	echo "<tr><td><a href=trade.php?time=$time&name=$name&lastname=$lastname><img border='0' src='images/barter.gif' alt='trade'></a></td><td><a href=trade.php?time=$time&name=$name&lastname=$lastname><font class='littletext'>Offer trade</a></td></tr>";
	// BEFRIEND
	if (!isset($friends[$char[id]][0])) echo "<tr><td><img border='0' src='images/handshake.gif' alt='trade'></td><td class='foottext'>[<a href=bio.php?set_s=1&time=$time&name=$name&last=$lastname><font class='foottext'>Friend</a>&nbsp;|&nbsp;<a href=bio.php?set_s=2&time=$time&name=$name&last=$lastname><font class='foottext'>Enemy</a>]</td></tr>";
	else echo "<tr><td><img border='0' src='images/handshake.gif' alt='trade'></td><td class='foottext'>[<a href=bio.php?set_s=3&time=$time&name=$name&last=$lastname><font class='foottext'>Neutral</a>]</td></tr>";
	
	echo "</table>".$field_e;
}
elseif ($num_notes || $num_logs) {
// SAME CHARACTER ACTIONS
	
	echo $field_s;

	if ($num_notes)
		echo "<tr><td><a href=notes.php?time=$time><img border='0' src='images/barter.gif' alt='Mes'></a></td><td><a href=notes.php?time=$time><font class='littletext'>Messages <font class='foottext'>[".$num_notes."]</a></td></tr>";
	if ($num_logs)
		echo "<tr><td><a href=log.php?time=$time><img border='0' src='images/shield.gif' alt='Log'></a></td><td><a href=log.php?time=$time><font class='littletext'>Def log <font class='foottext'>[".$num_logs."]</a></td></tr>";
	
	echo "</table>".$field_e;
}

echo "<td><img border='0' bordercolor='#000000' src='$avatar'></td>";
if ($char[type] == 'Channeler') $extra_type = '&nbsp;Channeler<br>';
else $extra_type = '';

// DISPLAY USER STUFF
echo "
	<td valign='middle'>
	<FIELDSET class='abox'>
	<LEGEND><font $font_size><b>$name $lastname</b></LEGEND><P>
	<font class='foottext'>
	$society
	<font class='littletext'>
	".$extra_type."&nbsp;<i>Level:</i> $lvl<br>
	<table border='0' cellpadding='0' cellspacing='7'>
	<tr><td><img src='images/health.gif' alt='health'></td><td class='littletext'>$health <font class='foottext'>health</td></tr>
";
if ($is_same && $char['travelmode']) {
	if ($char['travelmode']*4+8<=$char['feedneed']) echo "<tr><td><img src='images/horse.gif' alt='exp'></td><td class='foottext'>Needs feeding</td></tr>";
	else echo "<tr><td><img src='images/horse.gif' alt='exp'></td><td class='littletext'>".intval(($char['travelmode']*4+8-$char['feedneed'])/2)."<font class='foottext'> trips left</td></tr>";
}
$percent_up = 100-intval(100*($char[exp_up]-$char[exp])/$char[exp_up_s]);
if ($percent_up > 99) $percent_up = 99;
echo "
	<tr><td><img src='images/wins.gif' alt='wins'></td><td class='littletext'>$exp <font class='foottext'>$win_per"."wins</td></tr>
	<tr><td><img src='images/support.gif' alt='infl'></td><td class='littletext'>".$infl."</td></tr>
	<tr><td><img src='images/xp.gif' alt='exp'></td><td class='littletext'>".number_format($char[exp])." <font class='foottext'>exp</td></tr>
	<tr><td><img src='images/lvlup.gif' alt='%up'></td><td class='littletext'>".number_format($percent_up)."%<font class='foottext'> lvl up</td></tr>
	<tr><td><img src='images/gold.gif' alt='gold'></td><td class='littletext'>$gold <font class='foottext'>gold</td></tr>
	</table>
	</FIELDSET>
";
?>

</td>
</tr>
</table>
<center>
<table border="0" cellspacing="0" cellpadding="0" width='400'>
<tr><td><center>
<table border="0" cellspacing="0" cellpadding="5">
<tr><td><font class="littletext">
<p align=left>
<?php
$curnote=$char['about'];
$curnote=nl2br($curnote);
echo "<i>$curnote</i>";
?>
</td></tr>
</table>
</td></tr>
</table>

<?php
if (!$is_same)
{
?>
<br><br><font class="littletext">
<b>Send Message:</b>
<form action="bio.php?message=Messenger dispatched with note&name=<?php echo "$name"; ?>&last=<?php echo "$lastname"; ?>" method="post">
<textarea class="form" name="note" rows="6" cols="60" wrap="soft"></textarea><br>

<input type="hidden" name="submitted" value="1" />
<br>

<center><input type="submit" name="submit" id="showBut" value="Dispatch" class="form" style="display: <?php if (time() - intval($charother['lastpost']) > $wait_post) echo "block"; else echo "none"; ?>">
  <div id="showBut2" style="display: <?php if (time() - intval($charother['lastpost']) > $wait_post) echo "none"; else echo "block"; ?>">
  	<font class="littletext_f">Wait 15 seconds between notes...
  </div>

</form>

<?php
} else include('admin/friends.php');
?>

</center>

<?php
include('footer.htm');
?>
