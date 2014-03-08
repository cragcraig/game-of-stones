<?php

$enemy_id = intval($_GET[id]);

include("admin/connect.php");
include("admin/userdata.php");

if ($char[battlestoday] >= $battlelimit || $enemy_id == $id)  {header("Location: $server_name/bio.php?message=You have reached your battle limit for today"); exit;}

function new_bline($int, $string) {
	static $a = 0;
	if (!$int) {
		$a++;
		return "myBattle[".($a-1)."] = \"$string\";\n";
	}
	else return $a;
}

// FIND BATTLE
$find_battle = unserialize($char[find_battle]);
$char2 = mysql_fetch_array(mysql_query("SELECT * FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.id='$enemy_id'"));
if ($find_battle[$enemy_id] > time() - 600) {header("Location: $server_name/bio.php?time=".time()."&name=".$char2['name']."&last=".$char2['lastname']."&message=You must wait before attacking this character again"); exit;}
else $find_battle[$enemy_id] = time();
$choose_battle = 1;
$find_battle = serialize($find_battle);
if (!$char2[id]) {header("Location: $server_name/bio.php?message=Unable to find the opponent"); exit;}
if ($char['location'] != $char2['location']) {header("Location: $server_name/bio.php?message=It is impossible to duel from different locations"); exit;}

include('admin/skills.php');
include('admin/equipped.php');
include("admin/itemarray.php");

/*
THESE ALLOW A LOWER CASE "o" OR "r" TO DENOTE DAMAGE TYPE
A=lower attack
B=upper attack
D=lower defense
E=upper defense
O=offensive multiplier
N=defensive multiplier (subtract from opponent's next attack)
// THESE ARE AS IS, NO ADDITIONAL LETTERS
P=poison
T=taint
F=fire
cR=crushing
R=% chance critical hit
pG=gold steal
pX=xp gain
S=stun (any value turns it on this turn)
U=unblockable attack (any value turns it on this turn)
C=chance of going again
G=gain G percentage life from attack this turn
H=percent of attack you take as well
NP=no physical damage allowed this turn
*/

// EQUIPMENT DEGRADE ARRAYS

$map_itm_type = array(3,0,0,2,1,2,0,3);

$ran_words = array('flows through','performs','executes','strikes with','attacks with','lashes out with','completes','carries out');

$char_attack = array(
'max_health' => array($char[vitality],$char2[vitality]),
'health' => array($char[vitality],$char2[vitality]),
'def_mult' => array(0,0),
'o_def_mult' => array(0,0),
'r_def_mult' => array(0,0),
'def' => array(0,0),
'o_def' => array(0,0),
'r_def' => array(0,0),
'speed' => array(0,0),
'poison' => array(0,0),
'taint' => array(0,0),
'burn' => array(0,0),
'stun' => array(0,0),
'final_pow' => array($char['final_pow'],$char2['final_pow']),
);
if ($char[name] == $char2[name] || ucwords($char[name]) == "The" || ucwords($char2[name]) == "The") {$name1 = $char[name]." ".$char[lastname]; $name2 = $char2[name]." ".$char2[lastname];} else {$name1 = $char[name]; $name2 = $char2[name];}
$player_word = array("deal","take",$name1,$name2,"5483C9","E14545","left","right");

// FIRST TURN DEFENSE FOR OPPONENT
$char_stats = cparse(itp($item_base[$itm_b[0][0]][0]." ".$item_ix[$itm_b[0][1]]." ".$item_ix[$itm_b[0][2]],$item_base[$itm_b[0][0]][1])." ".itp($item_base[$itm_b[1][0]][0]." ".$item_ix[$itm_b[1][1]]." ".$item_ix[$itm_b[1][2]],$item_base[$itm_b[1][0]][1])." ".itp($item_base[$itm_b[2][0]][0]." ".$item_ix[$itm_b[2][1]]." ".$item_ix[$itm_b[2][2]],$item_base[$itm_b[2][0]][1]),0);
if ($char_stats['N']) $char_attack[def_mult][1] = $char_stats['N']; else $char_attack[def_mult][1] = 0;
if ($char_stats['D']) $char_attack[def][1] = rand($char_stats['D'],$char_stats['E']); else $char_attack[def][1] = 0;

if ($char_stats['rN']) $char_attack[r_def_mult][1] = $char_stats['rN']; else $char_attack[r_def_mult][1] = 0;
if ($char_stats['rD']) $char_attack[r_def][1] = rand($char_stats['rD'],$char_stats['rE']); else $char_attack[r_def][1] = 0;

if ($char_stats['oN']) $char_attack[o_def_mult][1] = $char_stats['oN']; else $char_attack[o_def_mult][1] = 0;
if ($char_stats['oD']) $char_attack[o_def][1] = rand($char_stats['oD'],$char_stats['oE']); else $char_attack[o_def][1] = 0;

// BATTLE
$turn = 1;
$turn_n = 0;
$switch_turn = 1;
$z = 0;
$array_gen = "";
$turns_row=0;

////////////////////////////////////////////////////////////////////////////////////////////////////////////

while ($char_attack[health][$turn] > 0 && $char_attack[health][$turn_n] > 0 && $z < 10) {
	// SWITCH TURNS
	$z++;
	if ($switch_turn) {
		if ($turn) $turn = 0; else $turn = 1;
		$turn_n = 1-$turn;
		$turns_row=0;
	}
	else $turns_row++;
	$switch_turn = 1;

	// CHOOSE SKILL
	$skill_used = rand(0,3);
	if ($skill_used == 3) $skill_used = 1;
	if (!$turn) $skill_name = $skills[$skill_used]; else $skill_name = $skills2[$skill_used];

	// CLEAR all temporary arrays and get info from weapons and attacks
	$damage = 0;
	$char_stats = array();
	if ($turn == 0) $weapon = "A0 B0 ".$skill_stat[$skill_name][1]." ".itp($item_base[$itm_a[0][0]][0]." ".$item_ix[$itm_a[0][1]]." ".$item_ix[$itm_a[0][2]],$item_base[$itm_a[0][0]][1])." ".itp($item_base[$itm_a[1][0]][0]." ".$item_ix[$itm_a[1][1]]." ".$item_ix[$itm_a[1][2]],$item_base[$itm_a[1][0]][1])." ".itp($item_base[$itm_a[2][0]][0]." ".$item_ix[$itm_a[2][1]]." ".$item_ix[$itm_a[2][2]],$item_base[$itm_a[2][0]][1]);
	else  $weapon = "A0 B0 ".$skill_stat[$skill_name][1]." ".itp($item_base[$itm_b[0][0]][0]." ".$item_ix[$itm_b[0][1]]." ".$item_ix[$itm_b[0][2]],$item_base[$itm_b[0][0]][1])." ".itp($item_base[$itm_b[1][0]][0]." ".$item_ix[$itm_b[1][1]]." ".$item_ix[$itm_b[1][2]],$item_base[$itm_b[1][0]][1])." ".itp($item_base[$itm_b[2][0]][0]." ".$item_ix[$itm_b[2][1]]." ".$item_ix[$itm_b[2][2]],$item_base[$itm_b[2][0]][1]);
	if ($_GET[data]) echo "<font class='littletext' color='black'>".$player_word[$turn+2]."=".$weapon." :: ";

	// RESOLVE all common terms and place in array
	$char_stats = cparse($weapon,0);

	// MAKE SURE NO STATS ARE TOO HIGH
	if ($char_stats['P']>100) $char_stats['P']=100;
	if ($char_stats['C']>95) $char_stats['C']=95;

	// TEXT
	$attach = "<br>";
	$text = "<font class=littletext><p align='".$player_word[$turn+6]."'>".$player_word[$turn+2]." ".str_replace('<ran>',$ran_words[rand(0,count($ran_words)-1)]."<i>",$skill_stat[$skill_name][3])."</i>.";

	// STUN
	if ($char_stats['S']) $char_attack[def_mult][$turn_n] -= 10;
	if ($char_stats['S']) $char_attack[r_def_mult][$turn_n] -= 10;
	if ($char_stats['S']) $char_attack[o_def_mult][$turn_n] -= 10;

	// GOOD FINAL POWER
	if ($char_attack[final_pow][$turn] == 3) {
		$char_stats['rA'] += $char_stats['A'];
		$char_stats['rB'] += $char_stats['B'];
		$char_stats['A'] = 0;
		$char_stats['B'] = 0;
	}
	
	// MELEE DAMAGE
	$damage = rand($char_stats['A'],$char_stats['B']);
	if ($char_stats['U']) $opponent_defense = 0; else $opponent_defense = $char_attack[def_mult][$turn_n]; // UNBLOCKABLE
	if ($char_stats['O']) $damage = intval($damage * (100 + $char_stats['O'] - $opponent_defense)/100 + 0.5);
	$damage -= $char_attack[def][$turn_n];
	$char_attack[def][$turn] = 0;
	if ($char_stats['D']) $char_attack[def][$turn] = rand($char_stats['D'],$char_stats['E']); else $char_attack[def][$turn] = 0;

	// RANGED DAMAGE
	$damage2 = rand($char_stats['rA'],$char_stats['rB']);
	if ($char_stats['U']) $opponent_defense = 0; else $opponent_defense = $char_attack[r_def_mult][$turn_n]; // UNBLOCKABLE
	if ($char_stats['rO']) $damage2 = intval($damage2 * (100 + $char_stats['rO'] - $opponent_defense)/100 + 0.5);
	$damage2 -= $char_attack[r_def][$turn_n];
	$char_attack[r_def][$turn] = 0;
	if ($char_stats['rD']) $char_attack[r_def][$turn] = rand($char_stats['rD'],$char_stats['rE']); else $char_attack[r_def][$turn] = 0;

	// ONE POWER DAMAGE
	$damage3 = rand($char_stats['oA'],$char_stats['oB']);
	$opponent_defense = $char_attack[o_def_mult][$turn_n];
	if ($char_stats['oO']) $damage3 = intval($damage3 * (100 + $char_stats['oO'] - $opponent_defense)/100 + 0.5);
	$damage3 -= $char_attack[o_def][$turn_n];
	$char_attack[o_def][$turn] = 0;
	if ($char_stats['oD']) $char_attack[o_def][$turn] = rand($char_stats['oD'],$char_stats['oE']); else $char_attack[o_def][$turn] = 0;

	// FIRE DAMAGE (doesn't count towards poison)
	$damage4 = intval($char_stats['F']);

	// COMBINE DAMAGES
	if ($damage < 0) $damage = 0;
	if ($damage2 < 0) $damage2 = 0;
	if ($damage3 < 0) $damage3 = 0;
	if (!$char_stats['NP']) $damage = $damage + $damage2 + $damage3; else $damage = $damage3;
	if (intval($char_stats['R'] + $char_stats['cR'])>rand(0,99)) $damage *= 2;
	$damage_minuspoison = intval($damage - $damage * $char_stats['P']/100 + 0.5);
	$char_attack[health][$turn_n] -= $damage_minuspoison + $damage4;
	if ($damage_minuspoison > 0) $text .= " ".$player_word[$turn_n+2]." takes <b>$damage_minuspoison</b> damage.";
	if ($damage4 > 0) $text .= $attach.$player_word[$turn_n+2]." is scorched for $damage4 fire damage.";

	// DEFENSE
	if ($char_stats['N']) $char_attack[def_mult][$turn] = $char_stats['N']; else $char_attack[def_mult][$turn] = 0;
	if ($char_stats['rN']) $char_attack[r_def_mult][$turn] = $char_stats['rN']; else $char_attack[r_def_mult][$turn] = 0;
	if ($char_stats['oN']) $char_attack[o_def_mult][$turn] = $char_stats['oN']; else $char_attack[o_def_mult][$turn] = 0;

	// GAIN LIFE
	if ($char_stats['G']) $health_gain = intval($damage * ($char_stats['G']/100) + 0.5); else $health_gain = 0;
	$char_attack[health][$turn] += $health_gain;
	if ($health_gain) $text .= $attach.$player_word[$turn+2]." gains $health_gain health.";

	// POISON
	if ($char_stats['P']) $char_attack[poison][$turn_n] += $damage * $char_stats['P']/100;
	$poison_damage = intval($char_attack[poison][$turn_n] + 0.5);
	$char_attack[health][$turn_n] -= $poison_damage;
	$char_attack[poison][$turn_n] = intval($char_attack[poison][$turn_n]*0.5 + 0.5);
	if ($char_attack[poison][$turn_n]) $text .= $attach."Poison wracks ".$player_word[$turn_n+2]."'s body for $poison_damage damage.";

	// ADD TAINT TO OPPONENT
	if ($char_stats['T']) {
		$char_attack[taint][$turn_n] += $char_stats['T'];
		$text .= $attach.$player_word[$turn_n+2]."'s taint increases by ".$char_stats['T'].".";
	}

	// PLAYER TAKES TAINT DAMAGE
	if (rand(1,100)%2) {
		$taint_damage = intval($char_attack[taint][$turn] + 0.5);
		$char_attack[health][$turn] -= $taint_damage;
		if ($taint_damage) $text .= $attach.$player_word[$turn+2]." takes $taint_damage taint damage.";
	}
	$char_attack[taint][$turn] = intval($char_attack[taint][$turn]*0.7 + 0.5);

	// HURT SELF
	if ($char_stats['H']) $health_take = intval($damage * ($char_stats['H']/100) + 0.5); else $health_take = 0;
	$char_attack[health][$turn] -= $health_take;
	if ($health_take) $text .= $attach.$player_word[$turn+2]." is injured in the attack and takes $health_take damage.";

	// GO AGAIN
	if ($turns_row) $char_stats['C']=$char_stats['C']/$turns_row;
	if ($char_stats['C'] && rand(1,100) <= $char_stats['C']) $switch_turn = 0;

	// DISPLAY RESULTS
	if ($turn) $wide = 30; else $wide = 70;
	if ($char_attack[health][$turn] < 0) $char_attack[health][$turn] = 0;
	if ($char_attack[health][$turn_n] < 0) $char_attack[health][$turn_n] = 0;
	if ($char_attack[health][$turn] <= 0.33*$char_attack[max_health][$turn]) $h1 = 5; else $h1 = 4;
	if ($char_attack[health][$turn_n] <= 0.33*$char_attack[max_health][$turn_n]) $h2 = 5; else $h2 = 4;
	$text = new_bline(0,$text.$attach.$player_word[$turn+2]." has <font color='".$player_word[$h1]."'><b>".$char_attack[health][$turn]."</b><font color='C6CCD8'> health and ".$player_word[$turn_n+2]." has <font color='".$player_word[$h2]."'><b>".$char_attack[health][$turn_n]."</b><font color='C6CCD8'> health.<br><br>");
	$array_gen .= $text;
}
if ($char_attack[health][0] == 0) {$winner = $player_word[3]; $score1 = 0;  $score2 = 1;}
elseif ($char_attack[health][1]) {if ($char_attack[health][1] >= $char_attack[health][0]) {$winner = $player_word[3]; $score1 = 0;  $score2 = 1;} else {$winner = $player_word[2]; $score1 = 1;  $score2 = 0;}}
else {$winner = $player_word[2]; $score1 = 1;  $score2 = 0;}
$win_thing = "<font class='bigtext'><center><b>$winner wins</b><br>";
////////////////////////////////////////////////////////////////////////////////////////////////////////////

// No Cheating
if (!$_GET[data]) $battle_view = 1200; else $battle_view = intval($_GET[data]+10);
$time_dif = time()-$char[nextbattle];
$update_battles='';
if ($time_dif >= 0 && $char[battlestoday] < $battlelimit) {
	$char[battlestoday]++;
	$update_battles=", Users.nextbattle='".intval($time+($battle_view*0.001)*(1+new_bline(1,"")))."', Users.battlestoday='".$char[battlestoday]."'";
}
elseif ($time_dif < 0) {header("Location: $server_name/bio.php?time=$time&message=Wait until the battle finishes"); exit;}

////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ATTACKER'S PASSIVE EFFECTS
$passive = "A0 B0 ".$skill_stat[$skills[0]][1]." ".$skill_stat[$skills[1]][1]." ".$skill_stat[$skills[2]][1]." ".$item_base[$itm_a[0][0]][0]." ".$item_ix[$itm_a[0][1]]." ".$item_ix[$itm_a[0][2]]." ".$item_base[$itm_a[1][0]][0]." ".$item_ix[$itm_a[1][1]]." ".$item_ix[$itm_a[1][2]]." ".$item_base[$itm_a[2][0]][0]." ".$item_ix[$itm_a[2][1]]." ".$item_ix[$itm_a[2][2]];
$char_stats = cparse($passive,0);
$g_steal = intval($char_stats['pG']);
if ($g_steal>95) $g_steal=95;
$x_gain = intval($char_stats['pX']);

// UPDATE SCORES FOR CLOSE LEVELS
if ($char2[level] >= $char[level]-4) {
	$soc_name = $char[society];
	if ($soc_name != "" && $score1 && $char['society']!=$char2['society']) mysql_query("UPDATE Soc SET score=score+1 WHERE name='$soc_name' ");
	if ($char[type] == $char2[type]) {$score1 += $char[score]; $char['battles']++;}
	else $score1 = $char[score];
	if ($char[type] == $char2[type]) {$score2 += $char2[score]; $char2['battles']++;}
	else $score2 = $char2[score];
	if ($winner == $player_word[2]) $char[exp]+=round(20.0*(100.0+$x_gain)/100.0);
	else $char[exp]+=round(10*(100.0+$x_gain)/100.0);
	$update=level_at($char[exp],$char[exp_up],$char[exp_up_s],$char[level]);
	if ($update[0]>$char[level]) $char[points]+=$update[0]-$char[level];
	if ($update[0]-$char[level] && $char['support'] && $char['society']) {
		mysql_query("Update Users SET infl=infl+".intval($update[0]-$char[level])." WHERE id='".$char['support']."'");
		$user = mysql_fetch_array(mysql_query("SELECT * FROM Users WHERE society='".$char['society']."' ORDER BY infl DESC LIMIT 1"));
		$soci = mysql_fetch_array(mysql_query("SELECT * FROM Soc WHERE name='".$char['society']."'"));
		if ($soci['leader']!=$user['name'] ||  $soci['leaderlast']!=$user['lastname']) mysql_query("UPDATE Soc SET leader='".$user['name']."', leaderlast='".$user['lastname']."' WHERE name='".$char['society']."'");
	}
	$char[level]=$update[0];
	$char[exp_up]=$update[1];
	$char[exp_up_s]=$update[2];
}
else {
	$score1 = $char[score];
	$score2 = $char2[score];
}

// FIND ITEM
$des_message = '';
$rand_val = rand(0,2);
if (($rand_val < 2 && $winner == $player_word[2]) || (rand(0,2) == 0 && $winner != $player_word[2])) {
	if (count($itmlist) < $inv_max) { // find item
		$find_array = array();
		$l1 = 0;
		if (rand(1,100) > 25) {
			foreach ($item_base as $name => $data) { // check item level requirements
				if (old_lvl_req($data[0]) <= $char['level'] && $name != "talisman" && $data[1] != 8 && $name != "fist"&& $name != "body") $find_array[$l1++] = $name;
			}
			$finditem = $find_array[rand(0,count($find_array)-1)];
			$lvlspace = $char['level'] - old_lvl_req($item_base[$finditem][0]);
		}
		else { // find talisman
			$finditem = "talisman";
			$lvlspace = round($char['level'] * 0.3 + 1);
		}
		$find1_array = array();
		$l1 = 0;
		$prefix = '';
		if (rand(1,100)%2) { // find prefix
			foreach ($item_ix as $name => $data) { // check item level requirements
				if (old_lvl_req($data) <= $lvlspace && ($name[0] != 'o' || $name[1] != 'f')) $find1_array[$l1++] = $name;
			}
			$prefix = $find1_array[rand(0,count($find1_array)-1)];
			$lvlspace *= 0.5;
		}
		$find2_array = array();
		$l1 = 0;
		$suffix = '';
		if (rand(1,100)%2 || ($prefix == '' && $finditem == 'talisman')) { // find suffix
			foreach ($item_ix as $name => $data) { // check item level requirements
				if (old_lvl_req($data) <= $lvlspace && $name[0] == 'o' && $name[1] == 'f') $find2_array[$l1++] = $name;
			}
			$suffix = $find2_array[rand(0,count($find2_array)-1)];
		}
		if ($finditem == 'talisman' && $prefix == '' && $suffix == '') $suffix == "of ravens";
		// add item and display message
		$findnum = count($itmlist);
		if ($finditem) {
			$itmlist[$findnum][0] = $finditem;
			$itmlist[$findnum][1] = $prefix;
			$itmlist[$findnum][2] = $suffix;
			$itmlist[$findnum][3] = 0;
			$des_message = "You found a <a href=javascript:popUp('itemstat.php?base=".str_replace(" ","_",$finditem)."&prefix=".str_replace(" ","_",$prefix)."&suffix=".str_replace(" ","_",$suffix)."')>".iname($findnum,$itmlist)."</a>!";
		}
	}
	else $des_message = "You were unable to pick up an item<br>because your inventory is full";
}

// LOOSE GOLD
$gold1 = $char[gold];
$gold2 = $char2[gold];
$g_steal2 = 0;
if (($char[type] != 'Channeler' && $winner == $player_word[2]) || ($char2[type] != 'Channeler' && $winner == $player_word[3]) || ($char[type] == 'Channeler' && $char2[type] == 'Channeler')) {
	if ($winner == $player_word[3]) {$gold2 += intval(((10+$g_steal2)/100)*$gold1+0.5); $gold1 = intval($gold1*((90-$g_steal2)/100)+0.5);}
	else {$gold1 += intval(((10+$g_steal)/100)*$gold2+0.5); $gold2 = intval($gold2*((90-$g_steal)/100)+0.5);}
}
if ($gold1<0) $gold1=0;
if ($gold2<0) $gold2=0;

if (abs($char[gold]-$gold1)) $win_thing .= "<font class='littletext'><center><b>and takes ".number_format(abs($char[gold]-$gold1))." gold</b><br><br>";
$win_thing .= "<center><font class=littletext>".$des_message;
$array_gen .= new_bline(0,$win_thing);

// UPDATE DEFENSE LOG
$notearray=unserialize($char2['log']);
$lengtharray=count($notearray);

if ($winner == $player_word[3]) $notearray[$lengtharray][0]="<br><font class=bigtext><b>You Win</b><br><font class=littletext> ".$char_attack[health][1]."/".$char2[vitality]." health to ".$char_attack[health][0]."/".$char[vitality]." health.<br><br>You take ".number_format($char[gold]-$gold1)." gold.";
else  $notearray[$lengtharray][0]="<br><font class=bigtext><b>You Lose</b><br><font class=littletext> ".$char_attack[health][1]."/".$char2[vitality]." health to ".$char_attack[health][0]."/".$char[vitality]." health.<br><br>".number_format($char2[gold]-$gold2)." gold is taken.";
$notearray[$lengtharray][1]="0";
$notearray[$lengtharray][2]=$char[name];
$notearray[$lengtharray][3]=$char[lastname];
$notearray[$lengtharray][4]=time();
$notearrays=serialize($notearray);

if (count($notearray) <= 50) {
	$notes = ", Users_data.log='$notearrays'";
} else $notes='';
// Health
if ($char['type'] != "Channeler" || $char['level'] <= 1) $new_health = lin2exp_hp($char['level']);
else $new_health = lin2exp_hp($char['level'] * 3);
// UPDATE DATABASE
$result = mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users.gold='".abs($gold1)."' ".$update_battles.", Users.score='$score1', Users.battles='".$char[battles]."', Users_data.find_battle='".$find_battle."', Users.exp='".$char[exp]."', Users.level='".$char[level]."', Users.exp_up='".$char[exp_up]."', Users.exp_up_s='".$char[exp_up_s]."', Users.points='".$char[points]."', Users_data.itmlist='".serialize($itmlist)."', Users.vitality='".$new_health."' WHERE Users.id='$id' ");
$result = mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users.gold='".abs($gold2)."' ".$notes.", Users.score='$score2', Users.battles='".$char2[battles]."', Users_data.itmlist='".serialize($itmlist2)."'  WHERE Users.id='$enemy_id' ");

// THE ACTUAL PAGE DRAWING
$message = "<b>".$char[name]." ".$char[lastname]."</b> vs <b>".$char2[name]." ".$char2[lastname]."</b> - ".($battlelimit-$char[battlestoday])." / $battlelimit battles remaining";
$names_battle = "<b>".$char[name]." ".$char[lastname]."</b> vs <b>".$char2[name]." ".$char2[lastname]."</b> - ";

// JAVASCRIPT
$javascripts=<<<SJAVA
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin

var myTurn = 0;
var myBattle = new Array();
$array_gen

window.onLoad = startBattle();

function startBattle() {
	window.self.setInterval("updateBattle()",$battle_view);
}

function updateBattle() {
  if (myBattle.length > myTurn) {
	  document.getElementById("battleBox").rows[0].cells[0].innerHTML = document.getElementById("battleBox").rows[0].cells[0].innerHTML+myBattle[myTurn];
	  if (myTurn > 5) location.href="#goto";
	}
  myTurn++;
}
// End -->
</SCRIPT>
SJAVA;
include('header.htm');

echo "<center><table border=0 cellpadding=0 cellspacing=0 width='600' id='battleBox'><tr><td>";
echo "</td></tr></table><br><br><a name=\"goto\"></a>";

include('footer.htm');
?>
