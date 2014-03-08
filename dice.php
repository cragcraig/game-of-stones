<?php

/* establish a connection with the database */
include("admin/connect.php");
include("admin/userdata.php");

$game = $_GET[game];
$wager = abs(intval($_POST[wager]));
if (($wager > $char[gold] || $wager <= 0) && $game) {$game = 0; $message="Why don't you put your money where your mouth is?";} else $message="<b>Tavern Dice Games</b>";
if ($wager > 100000000) {$game = 0; $message="Sorry, the current wager limit is 100 Million gold";}
$dice = '';
$list = '';
$players = $_POST[players];
$gtype = $_POST[gtype];
$p_name = array("One","Two","Three","Four","Five");
$p_info = '';
$winning_score = 0;
$winners = 0;
$text = '';

function ComputeScore($list,$gtype)
{
	$score = 0;
	$temp_pairs = array('0','0');
	for ($x=0; $x<6; $x++)
	{
		$temp_score = 0;
		if ($list[$x] == 5 && !$temp_score) $temp_score = intval("7".$x."0"); // five of a kind
		if ($list[$x] == 4 && !$temp_score) $temp_score = intval("6".$x."0"); // four of a kind
		if ($list[$x] == 3 && !$temp_score) for ($y=0; $y<5; $y++) if ($list[$y] == 2) $temp_score = intval("5".$x.$y); // full house
		if ($list[$x] == 3 && !$temp_score) $temp_score = intval("3".$x."0"); // three of a kind
		if ($list[$x] == 2 && !$temp_score) // add to pair list
		{
			$temp_pairs[1]=$temp_pairs[0];
			$temp_pairs[0]=$x+1;
		}
		if ($temp_score > $score) $score = $temp_score;
	}
	if ($score < 500 && !$gtype) // straight, if Tops
	{
		if ($list[0] && $list[1] && $list[2] && $list[3] && $list[4]) $temp_score = 410;
		if ($list[1] && $list[2] && $list[3] && $list[4] && $list[5]) $temp_score = 420;
	}
	if ($score < 400) // use pairs
	{
		if ($temp_pairs[1]) $score = intval("2".$temp_pairs[0].$temp_pairs[1]);
		elseif ($temp_pairs[0]) $score = intval("1".$temp_pairs[0]."0");
	}
	return $score;
}


// START GAME

if ($game)
{
	// COMPUTE WINNER
	for ($p=0; $p<$players; $p++)
	{
		for ($x=0; $x<5; $x++)
		{
			$dice[$p][$x] = rand(1,6);
			$list[$p][$dice[$p][$x]-1]+=1;
		}
		$p_info[$p] = ComputeScore($list[$p],$gtype);
		if ($p_info[$p] > $winning_score) $winning_score=$p_info[$p];
	}
	
	// DISPLAY
	$text .= "<center><font class='littletext' style='font-size: 14px'>";
	$text .= "<table border='0' cellpadding='10' cellspacing='0'>";
	for ($p=0; $p<$players; $p++)
	{
		$text .= "<tr><td><font class='littletext'>";
		if ($p == 0) $text .= "<i>Your Roll</i>";
		else $text .= "<i>Player ".$p_name[$p]."'s Roll</i>";
		$text .= "</td><td width='5'></td><td><table rules='none' border='";
		if ($p_info[$p] == $winning_score) {$text .= "2"; $winners++;}
		else $text .= "0";
		$text .= "' bordercolor='ED3915' cellpadding='2' cellspacing='0'>";
		for ($x=0; $x<5; $x++) $text .= "<td><img src='dice/d".$gtype.$dice[$p][$x].".gif'></td>";
		$text .= "</table></td></tr>";
	}
	$winnings = intval(($wager*$players)/$winners);
	$text .= "<tr><td colspan='3'><center><font class='littletext'><i><b>This round was worth </i>".number_format($winnings)."<i> gold</i>";
	if ($winners>1) $text .= "<i> - $winners way tie</i>";
	$text .= "</td></tr></table><br><br>";
	
	// UPDATE DATABASE
	if (intval($p_info[0]) == intval($winning_score)) $gold = $char[gold] + $winnings - $wager;
	else $gold = $char[gold] - $wager;
	
	mysql_query("UPDATE Users SET gold='$gold' WHERE id='$id'");
	$char[gold] = $gold;
}
// DISPLAY
include("header.htm");
echo $text;
if (!$game) echo "<center><img src='dice/dice.gif'><br><br>";
?>
<center>
<table border=0 cellpadding=4 cellspacing=0>
	<form method="post" action="dice.php?game=1">
		<tr><?php if ($game) echo "<td rowspan='5'><img src='dice/dice.gif'></td>"; ?>
		<td><center><input type="Submit" name="submit" value="Toss the Dice" class="form"></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><font class='littletext'><b>Wager</b>: 
		<input type="text" name="wager" id="wager" value="<?php if (!$wager) echo "10"; else echo $wager;?>" class="form" size="11" maxlength="20">
		<b>g</b></td></tr>
		<tr><td><font class='littletext'><b>Players</b>: 
		<select name="players" size="1" class="form">
			<option <?php if ($players == 2) echo "SELECTED"; ?> value="2">Two</option>
			<option <?php if ($players == 3) echo "SELECTED"; ?> value="3">Three</option>
			<option <?php if ($players == 4) echo "SELECTED"; ?> value="4">Four</option>
			<option <?php if ($players == 5) echo "SELECTED"; ?> value="5">Five</option>
		</select>
		</td></tr>
		<tr><td><font class='littletext'><b>Game</b>: 
		<select name="gtype" size="1" class="form">
			<option <?php if ($gtype == 'd') echo "SELECTED"; ?> value="d">Crowns</option>
			<option <?php if ($gtype == '') echo "SELECTED"; ?> value="">Tops</option>
		</select>
		</td></tr>
	</form>
</table>

<?php
include('footer.htm');
?>
