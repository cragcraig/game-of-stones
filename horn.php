<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");
$time=time();

//HEADER
$message = "View the <a href='heroes.htm'>Age One Heroes</a> of the Horn";
include('header.htm');

$rank_by = array("score","level","gold","bankgold");
$rank_name = array("Battles Won","Level","Gold","Bank Gold");
$rank_suf = array("wins","","g","g");
$rank_pre = array("","Lvl","","");

$soc_name = $char[society];
if ($soc_name)
{
$society = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='$soc_name'"));
$stance = unserialize($society['stance']);
}
?>

<center>
<!-- TOP TENS -->

<table border=0 cellpadding=0 cellspacing=0><tr>

<?php
// START LOOPS
for ($y = 0; $y < 4; $y++)
{
$query = "SELECT id, name, lastname, level, score, gold, vitality, society, goodevil, bankgold, type FROM Users WHERE (name!='The' OR lastname!='Creator') ORDER BY ".$rank_by[$y]." DESC, exp DESC LIMIT 0,10 ";
$result = mysql_query($query);
$numchar = mysql_num_rows($result);

echo "<td><center><table border=0 cellpadding=5 cellspacing=0 class='abox'><tr><td><center><font class=littletext><b>Top Ten by ".$rank_name[$y]."</b></td></tr></table><br><table border=0 cellpadding=3 cellspacing=0>";

$img_ar = Array("s1","w1");
$x=0;
while ( $listchar = mysql_fetch_array( $result ) )
{
if ($listchar['id'] == $id) $classn="same";
else if ($listchar['goodevil']==1) $classn="good";
elseif ($listchar['goodevil']==2) $classn="evil";
else $classn="neutral";
if ($listchar['type'] == 'Channeler') $classn="channeler";
?>

<tr onMouseOver="this.bgColor='#111111';" onMouseOut="this.bgColor='#000000';"><td width="25"><font class='foottext'><?php echo ($x+1)."."; ?></td>
<td><font class="foottext"><center><?php echo $rank_pre[$y]." ".number_format($listchar[$rank_by[$y]])." ".$rank_suf[$y];?></td>
<td> </td>
<td><p align=left><table border=0 cellpadding=0 cellspacing=0><tr><td><b><center><font class="littletext"><a <?php echo "class='$classn'"; ?> href="bio.php?name=<?php echo $listchar['name']; echo "&last="; echo $listchar['lastname']; echo "&time="; echo time(); ?>"><?php echo $listchar['name']." ".$listchar['lastname']; ?></a></b></td><td width=5></td><td><font class="foottext"><?php if ($stance[str_replace(" ","_",$listchar[society])] && $listchar[id] != $id) echo "<img src=images/".$img_ar[$stance[str_replace(" ","_",$listchar[society])]-1].".gif><td width=10></td>"; ?></td></tr></table></td>
</tr>

<?php
$x++;
if ($x == 10) {break;}
}
?>

</table>
</td><td width="50"></td>

<?php
if ($y == 1) echo "</tr><tr height='50'><td colspan='3'></td></tr><tr>";
}
// END LOOPS
?>

</tr></table><br><br>

<?php
include('footer.htm');
?>















