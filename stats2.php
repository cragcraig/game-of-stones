<?php
// establish a connection with the database
include("admin/connect.php");
include("admin/userdata.php");

$uppoint=$_REQUEST[uppoint];
$id = $char['id'];
$points=$char['points'];

// LEVEL UP
if ($_GET[doit] == 1)
{
$exp=$char['exp'];
$lvl=$char['level'];
$expfree = $char[exp] - $char[expused];
$levelup = 80 + ($char[level] * 20);
$expused = $char[expused];

if ( $expfree >= $levelup )
{
$lvl++;
$points++;
$expused = $expused + $levelup;
$gainvigor=$char['vigor'] + 0.5 * ($char['vitality'] + 10);
if ( $gainvigor > $char['vitality'] + 10 ) { $gainvigor = $char['vitality'] + 10 ;}

// IF LEVEL IS A MULTIPLE OF TEN THEN DO COOL STUFF
if ( $lvl / 10 == intval($lvl / 10) )
{
// ASK IF THEY WANT TO JOIN THE DARK SIDE
if ($char[goodbad] == 0 && $char[type] != "Wolfkin")
{
$notearray=unserialize($char['notes']);
$lengtharray=count($notearray);

$notearray[$lengtharray][0]="<b>A seedy looking man covered in soot approaches you...</b><br><br><i>You know, this Creator is not all he is cracked up to be. If you are interested in serving a greater master with the reward of immortality, <b><a href=turnevil.php?time=$curtime>I might be able to help.</a></i></b>";
$notearray[$lengtharray][1]="0";
$notearray[$lengtharray][2]="A";
$notearray[$lengtharray][3]="Darkfriend";
$notearrays=serialize($notearray);

if (strlen($notearrays) < 65535)
{
$queryd = "UPDATE Users SET notes='$notearrays' WHERE id=$id";
$result = mysql_query($queryd);
}
}
}
// END JOIN DARK SIDE

$query = "UPDATE Users SET vigor='$gainvigor', level='$lvl', expused='$expused', points='$points' WHERE id=$id";
$result = mysql_query($query);

header("Location: $server_name/stats.php?time=$time");
exit;
}
// END LEVEL UP
}

// UP POINT

if ($uppoint > 0)
{
$sword=$char['sword'];
$axe=$char['axe'];
$spear=$char['spear'];
$bow=$char['bow'];
$staff=$char['staff'];
$knives=$char['knives'];

$vitality=$char['vitality'];
$special=$char['special'];
$vigor=$char['vigor'] + 1;
$expfree = $char[exp] - $char[expused];

$swordexp = ($sword*10 + 50);
$sword ++;
$axeexp = ($axe*10 + 50);
$axe ++;
$spearexp = ($spear*10 + 50);
$spear ++;
$bowexp = ($bow*10 + 50);
$bow ++;
$staffexp = ($staff*10 + 50);
$staff ++;
$knivesexp = ($knives*10 + 50);
$knives ++;
$vitalityexp = ($vitality*10 + 50);
$vitality ++;
$specialexp = ($special*10 + 50);
$special ++;

$swordexp2 = $char[expused] + $swordexp;
$axeexp2 = $char[expused] + $axeexp;
$spearexp2 = $char[expused] + $spearexp;
$bowexp2 = $char[expused] + $bowexp;
$staffexp2 = $char[expused] + $staffexp;
$knivesexp2 = $char[expused] + $knivesexp;
$vitalityexp2 = $char[expused] + $vitalityexp;
$specialexp2 = $char[expused] + $specialexp;


if ($uppoint == 10 && $vitalityexp <= $expfree) $query = "UPDATE Users SET vitality='$vitality', vigor='$vigor', expused='$vitalityexp2' WHERE id=$id";
if ($uppoint == 1 && $swordexp <= $expfree) $query = "UPDATE Users SET sword='$sword', expused='$swordexp2' WHERE id=$id";
if ($uppoint == 2 && $axeexp <= $expfree) $query = "UPDATE Users SET axe='$axe', expused='$axeexp2' WHERE id=$id";
if ($uppoint == 3 && $spearexp <= $expfree) $query = "UPDATE Users SET spear='$spear', expused='$spearexp2' WHERE id=$id";
if ($uppoint == 4 && $bowexp <= $expfree) $query = "UPDATE Users SET bow='$bow', expused='$bowexp2' WHERE id=$id";
if ($uppoint == 5 && $staffexp <= $expfree) $query = "UPDATE Users SET staff='$staff', expused='$staffexp2' WHERE id=$id";
if ($uppoint == 6 && $knivesexp <= $expfree) $query = "UPDATE Users SET knives='$knives', expused='$knivesexp2' WHERE id=$id";
if ($uppoint == 7 && $specialexp <= $expfree) $query = "UPDATE Users SET special='$special', expused='$specialexp2' WHERE id=$id";
$result = mysql_query($query);
include("admin/userdata.php");
}

include("admin/damage.php");

include('header.htm');
?>

<font face="VERDANA"><font class="littletext"><center>
<table border="1" cellpadding="10" rules=rows cellspacing="0" bgcolor="#0f0f0f" bordercolor="#999999" width="90%"><tr><td>
<center><font class="bigtext"><b>Stats</b><font class="littletext"><br>
</td></tr></table>

<br><font class="littletext">
<!-- MAIN PAGE -->

<?php
$exp=$char['exp'];
$lvl=$char['level'];
$expfree = $char[exp] - $char[expused];
$levelup = 80 + ($char[level] * 20);
$expused = $char[expused];
$type=$char['type'];
$points=$char['points'];


$vitality=$char['vitality'];
$vigor=$char['vigor'];
$maxvigor=$char['vitality'] + 10;
$special=$char['special'];

$sword=$char['sword'];
$axe=$char['axe'];
$spear=$char['spear'];
$bow=$char['bow'];
$staff=$char['staff'];
$knives=$char['knives'];

$duelsleft = $char['dayattacks'];
if ($duelsleft < 0) $duelsleft = 0;

if ($type == Channeler) $specialn="One Power";
?>

<!-- Upgrade Level -->
<center>
<table border=0>
<tr>
<td valign="center"><img src="tiny/armor.gif"></td><td valign="center"> <?php echo "$levelup"; ?> exp required: </td><td  valign="center"><form method="post" action="stats.php?doit=1" ><br><input type="Submit" name="submit" value="Level Up" class="form" ></form></td>
</tr>
</table>


<!-- Status Boxes -->

<table border="0" cellpadding="5"><tr><td valign="top">

<tr>
<td>
<font class="littletext"><center><b>Character Status</b>
</td>
<td></td>
<td>
<font class="littletext">
<center><b>Weapon Skills</b>
</td></tr>
<tr><td valign="top">

<table border="1" height="15" cellpadding="4" cellspacing="0" bordercolor=#000000 rules=rows bgcolor="#0f0f0f">
<tr>
<td valign="top" width="70"><font class="littletext"><p align="left"><b><img src="tiny/level.gif"></b></td><td valign="center" width="100"><p align="right"><font class="littletext"><b>Level <?php echo "$lvl"; ?> </td></tr><tr>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/exp.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$expfree"; ?> exp </td></tr><tr>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/gold.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo number_format ( $char['gold'], 0, '.', ','); ?> gold </td></tr><tr>
<?php if ($def_damage_min == $def_damage_max) { ?>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/attack.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$def_damage_min"; ?> Atk </td></tr><tr>
<?php } else { ?>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/attack.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$def_damage_min"." - "."$def_damage_max"; ?> Atk </td></tr><tr>
<?php } ?>
<?php if ($def_armor_min == $def_armor) { ?>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/defense.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$def_armor"; ?> Def </td></tr><tr>
<?php } else { ?>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/defense.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$def_armor_min"." - "."$def_armor"; ?> Def </td></tr><tr>
<?php } ?>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/turn.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$duelsleft"; ?> Turns</td></tr><tr>
<td valign="top"><font class="littletext"><p align="left"><b><img src="tiny/stamina.gif"></b></td><td valign="center"><p align="right"><font class="littletext"><b><?php echo "$vigor"." / "."$maxvigor"; ?> Stamina</td>
</tr>
</table><font class="littletext">
<br><br>

</td><td width="15"></td><td valign="top">

<table border="1" width="200" cellpadding="4" cellspacing="0" bordercolor=#000000 rules=rows bgcolor="#0f0f0f"><font class="littletext">
<tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=10"><img src="tiny/shield.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$vitality"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($vitality*10 + 50), 0, '.', ','); ?></b> exp</td></tr><tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=1"><img src="tiny/sword.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$sword"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($sword*10 + 50), 0, '.', ','); ?></b> exp</td></tr><tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=2"><img src="tiny/axe.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$axe"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($axe*10 + 50), 0, '.', ','); ?></b> exp</td></tr><tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=3"><img src="tiny/spear.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$spear"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($spear*10 + 50), 0, '.', ','); ?></b> exp</td></tr><tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=4"><img src="tiny/bow.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$bow"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($bow*10 + 50), 0, '.', ','); ?></b> exp</td></tr><tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=5"><img src="tiny/staff.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$staff"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($staff*10 + 50), 0, '.', ','); ?></b> exp</td></tr><tr>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=6"><img src="tiny/knives.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$knives"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($knives*10 + 50), 0, '.', ','); ?></b> exp</td></tr>
<?php if ($type == Channeler) { ?>
<td><p align="left"><font class="littletext"><b><a href="stats.php?uppoint=7"><img src="tiny/power.gif" border="0"></a></b></td><td><center><font class="littletext">lvl <b><?php echo "$special"; ?></b> </td><td><p align="right"><font class="littletext"><b><?php echo number_format(($special*10 + 50), 0, '.', ','); ?></b> exp</td></tr>
<?php } ?>
</table><font class="littletext">

<!-- FORM STUFF -->
<table border="0" cellspacing="15"><tr><td><font class="littletext">
<form method="post" action="stats.php" >
<select name="uppoint" size="1" class="form" >
	<option <?php if ($uppoint == 10) echo "selected"; ?> value="10">Stamina</option>
	<option <?php if ($uppoint == 1) echo "selected"; ?> value="1">Sword</option>
	<option <?php if ($uppoint == 2) echo "selected"; ?> value="2">Axe</option>
	<option <?php if ($uppoint == 3) echo "selected"; ?> value="3">Spear</option>
	<option <?php if ($uppoint == 4) echo "selected"; ?> value="4">Bow</option>
	<option <?php if ($uppoint == 5) echo "selected"; ?> value="5">Bludgeon</option>
	<option <?php if ($uppoint == 6) echo "selected"; ?> value="6">Knives</option>
<?php if ($type == Channeler) { ?>
	<option <?php if ($uppoint == 7) echo "selected"; ?> value="7"><?php echo "$specialn"; ?></option>
<?php } ?>
</select>
<input type="Submit" name="submit" value="Improve" class="form" >
</form>
</td></tr></table>

</td></tr></table>

<?php
include('footer.htm');
?>


