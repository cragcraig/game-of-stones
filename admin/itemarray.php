<?php

// ITEM TYPES

$item_type = array ( 
'0' => "armor",
'1' => "sword",
'2' => "axe",
'3' => "spear",
'4' => "bow",
'5' => "bludgeon",
'6' => "knife",
'7' => "shield",
'8' => "weave&nbsp;knowledge",
'12' => "talisman",
'13' => "herb",
);

// ITEM BASE

$item_base = array ( 

'fist' => array("A1 B2",5),
'body' => array("N1 D0 E2",0),

'rags' => array("N5 D0 E1 rN5 rD0 rE1 rN3 wT0 wL10",0),
'leather armor' => array("N5 D2 E3 rD1 rE4 rN7 wT0 wL20",0),
'cadinsor' => array("N25 D0 E1 rD0 rE1 rN15 wT0 wL30",0),
'cloak' => array("N15 D5 E7 rD3 rE4 rN18 rN7 wT0 wL40",0),
'sturdy leather armor' => array("N5 D7 E10 rD10 rE11 rN8 rO12 wT0 wL50",0),
'chain link armor' => array("N10 D12 E14 rD7 rE10 rN11 wT0 wL60",0),
'hunters cloak' => array("N20 D17 E15 rD15 rE15 rN20 wT0 wL70",0),
'myrddraal armor' => array("N15 D20 E24 C10 T10 rD20 rE22 rN16 rO15 wT0 wL80",0),
'splint mail' => array("N15 rN21 D25 E30 rD23 rE28 wT0 wL90",0),
'plate mail' => array("N16 rN23 D30 E35 rD27 rE32 wT0 wL100",0),
'golden breastplate' => array("G35 D34 E38 rD32 rE35 N13 rN19 wT0 wL120",0),
'spiked armor' => array("T25 H3 D40 E45 rD35 rE40 N20 rN12 wT0 wL140",0),
'royal breastplate' => array("N20 rN23 D45 E51 rD43 rE48 wT0 wL160",0),


'trolloc blade' => array("A3 B5 T1 wT1 wL10",1),
'short sword' => array("A6 B8 wT1 wL20",1),
'sword' => array("A9 B12 wT1 wL30",1),
'long sword' => array("A15 B18 C2 wT1 wL40",1),
'tulwar' => array("A21 B25 wT1 wL50",1),
'bastard sword' => array("A27 B32 C3 wT1 wL60",1),
'claymore' => array("A34 B37 wT1 wL70",1),
'broad sword' => array("A40 B44 wT1 wL80",1),
'battle sword' => array("A49 B53 wT1 wL90",1),
'flamberge' => array("A58 B62 wT1 wL100",1),
'winged sword' => array("A69 B75 C5 wT1 wL120",1),
'dark blade' => array("A79 B84 H5 T17 wT1 wL140",1),
'giant blade' => array("A79 B84 G13 wT1 wL160",1),
'heron mark blade' => array("A86 B92 C12 wT1 wL180",1),


'trolloc axe' => array("A2 B6 T1 wT2 wL10",2),
'hatchet' => array("A5 B9 H5 wT2 wL20",2),
'hand axe' => array("A8 B13 H5 wT2 wL30",2),
'cleaver' => array("A17 B20 H7 wT2 wL40",2),
'half moon blade' => array("A24 B28 H8 wT2 wL50",2),
'double bladed axe' => array("A31 B35 H8 wT2 wL60",2),
'combat axe' => array("A37 B41 H9 wT2 wL70",2),
'balanced axe' => array("A44 B48 H9 wT2 wL80",2),
'battle axe' => array("A54 B58 H9 wT2 wL90",2),
'bone splitter' => array("A64 B74 H8 wT2 wL110",2),
'executioner' => array("A77 B81 H9 wT2 wL130",2),
'spiked axe' => array("A86 B90 H10 T14 wT2 wL150",2),
'great axe' => array("A90 B97 H11 S1 wT2 wL170",2),


'sharpened stick' => array("A2 B3 rA1 rB2 wT3 wL10",3),
'iron spear' => array("A3 B5 rA1 rB4 wT3 wL20",3),
'short spear' => array("A4 B6 rA5 rB6 wT3 wL30",3),
'glaive' => array("A8 B9 rA7 rB9 wT3 wL40",3),
'long spear' => array("A10 B12 rA11 rB13 wT3 wL50",3),
'pike' => array("A13 B16 rA14 rB16 wT3 wL60",3),
'javelin' => array("A14 B16 rA20 rB21 wT3 wL70",3),
'poleaxe' => array("A20 B23 rA20 rB21 wT3 wL80",3),
'lance' => array("A25 B27 rA21 rB24 wT3 wL90",3),
'septum' => array("A28 B31 rA30 rB30 wT3 wL110",3),
'trident' => array("A35 B38 rA34 rB37 wT3 wL130",3),
'black lance' => array("A42 B45 rA43 rB47 T13 H12 wT3 wL150",3),
'combat spear' => array("A42 B46 rA44 rB46 O4 rO6 wT3 wL170",3),


'sling' => array("rA3 rB5 wT4 R2 wL10",4),
'short bow' => array("rA6 rB8 wT4 R3 wL20",4),
'light crossbow' => array("rA9 rB12 rO10 wT4 R5 wL30",4),
'long bow' => array("rA15 rB18 wT4 R3 wL40",4),
'battle bow' => array("rA21 rB25 wT4 wL50",4),
'horn bow' => array("rA27 rB32 wT4 R6 wL60",4),
'heavy crossbow' => array("rA34 rB37 rO14 wT4 R7 wL70",4),
'long battle bow' => array("rA40 rB44 wT4 R7 wL80",4),
'heavy long bow' => array("rA49 rB53 wT4 R8 wL90",4),
'composite crossbow' => array("rA58 rB62 wT4 R9 wL110",4),
'hunters bow' => array("rA69 rB75 wT4 R10 wL130",4),
'silver bow' => array("rA80 rB86 C7 wT4 R15 wL150",4),
'warriors bow' => array("rA87 rB93 wT4 R12 wL170",4),

'short staff' => array("A2 B4 D0 E2 wT5 wL10",5),
'cudgel' => array("A4 B5 D2 E3 wT5 wL20",5),
'spiked club' => array("A6 B9 D3 E3 wT5 wL30",5),
'mallet' => array("A10 B12 D5 E6 wT5 wL40",5),
'quarter staff' => array("A18 B20 D4 E7 wT5 wL50",5),
'mace' => array("A22 B26 D6 E8 wT5 wL60",5),
'flail' => array("A28 B34 D6 E7 wT5 wL70",5),
'morning star' => array("A35 B39 D6 E7 wT5 wL80",5),
'maul' => array("A44 B48 D6 E7 wT5 wL90",5),
'war hammer' => array("A48 B50 D11 E12 wT5 wL110",5),
'battle staff' => array("A55 B60 D12 E16 wT5 wL130",5),
'spiked maul' => array("A62 B65 D16 E19 wT5 wL150",5),
'battle hammer' => array("A68 B73 D18 E21 wT5 wL170",5),


'stone knife' => array("A1 B2 rA1 rB2 wT6 wL10",6),
'bone knife' => array("A3 B6 wT6 wL20",6),
'steel bladed knife' => array("A6 B9 wT6 wL30",6),
'throwing knives' => array("rA10 rB15 wT6 wL40",6),
'belt knife' => array("A16 B20 wT6 wL50",6),
'long knife' => array("A23 B27 wT6 wL60",6),
'long throwing knives' => array("rA28 rB33 wT6 wL70",6),
'dagger' => array("A30 B37 wT6 wL80",6),
'battle dagger' => array("A35 B42 wT6 wL90",6),
'balanced throwing knives' => array("rA42 rB47 wT6 wL110",6),
'kriss' => array("A42 B46 wT6 wL130",6),
'stilletto' => array("A45 B48 wT6 wL150",6),
'ruby dagger' => array("A48 B53 T34 H10 wT6 wL170",6),

'shield' => array("D1 E3 rD1 rE3 oD1 oE4 wT7 wL10",7),
'buckler' => array("D6 E9 rD7 rE10 oD7 oE9 wT7 wL30",7),
'kite shield' => array("D16 E20 rD19 rE22 oD15 oE19 wT7 wL50",7),
'tower shield' => array("D25 E33 rD25 rE32 oD26 oE32 wT7 wL80",7),
'golden shield' => array("D40 E45 rD39 rE44 oD48 oE52 G5 wT7 wL110",7),
'spiked shield' => array("D48 E53 rD49 rE52 oD40 oE42 H6 wT7 wL150",7),

'firespark' => array("oA3 oB5 F7 wT2 wL10",8),
'static field' => array("oA17 oB25 wT2 wL30",8),
'upheaval' => array("oA45 oB60 cR5 wT2 wL40",8),
'lightning' => array("oA60 oB90 F20 wT2 wL50",8),
'waterspike' => array("oA100 oB140 oD50 oE65 wT2 wL70",8),
'fireball' => array("oA100 oB140 F40 wT2 wL70",8),
'flood' => array("oA160 oB200 oD80 oE100 cR10 wT2 wL130",8),
'earthquake' => array("oA220 oB280 cR15 wT2 wL130",8),
'suppression' => array("oA200 oB260 oD120 oE140 cR15 wT2 wL150",8),
'eruption' => array("oA300 oB370 cR20 wT2 wL150",8),
'powersurge' => array("oA400 oB470 F30 cR15 wT2 wL180",8),
'balefire' => array("oA530 oB600 F50 cR20 wT2 wL200",8),

'talisman' => array("A0 B0",12),
);
// TALISMAN MUST BE LAST ITEM FOR SHOP CODE TO WORK CORRECTLY

$some_prefixes = array('','sturdy','enhanced','light','large','small','cheap','hawkers','flawed','tarnished','weak','master smithed','ancient','dark','tainted');


$item_ix = array(

'new' => "O15 rO15 N15 rN15 A3 B2 D3 E2 rD3 rE2 sL30",
'sharp' => "O10 A3 B2 sL10",
'blunt' => "O-7 sL-10",
'rusted' => "O-15 sL-10",
'sturdy' => "O10 rO10 sL10",
'weak' => "rO-5 O-15",
'old' => "rO-15 0-5 sL-10",
'aged' => "O-5 rO-5 N-5",
'broken' => "O-8 rO-7 N-7",
'strong' => "N10 rN10 D3 E2 rD3 rE2",
'worn' => "N-7 rN-7",
'ragged' => "N-10 rN-10",
'brutal' => "O25 A7 B8 sL40",
'fine' => "O22 N25 A3 B3 R7 sL30",
'lords' => "rO20 rA4 rB5 sL35",
'sturdy' => "N15 rO12 A3 B2 sL20",
'rugged' => "N10 O8 sL20",
'power wrought' => "A7 B12 rA8 rB11 pX5 sL30",
'blacksmiths' => "A16 B18 sL40",
'warriors' => "A5 B6 rA8 rB8 G8 sL35",
'slayers' => "rO20 rA7 rB9 R5 sL20",
'light' => "O5 rO7 A2 B3 rA2 rB3 sL25",
'master smithed' => "C12 sL35",
'ancient' => "pX2 A4 B5 sL30",
'hawkers' => "O-10 rO-10 N-15 sL-15",
'tarnished' => "O-10 rO-10 sL-10",
'flawed' => "O-20 rO-20 N-25 rN-15 sL-25",

'berserkers' => "O30 rO30 N-20 rN-20 sL40",
'burning' => "F4 sL20",
'soulless' => "pG-10 O15 F8 T10 P12 sL50",
'foul' => "O5 T13 P7 sL40",
'noble' => "N5 rN5 pG5 F4 sL20",
'blademasters' => "pX4 D3 E5 rD3 rE5 N10 rN10 C10 R3 sL50",

'deadly' => "A16 B18 T20 P7 R7 sL50",

'poisonous' => "A1 B2 P8 sL10",
'venomous' => "A2 B3 P15 sL20",

'tainted' => "A2 B3 T9 sL30",
'corrupt' => "A2 B3 T13 sL40",
'dread' => "A6 B8 T16 R4 F3 sL50",
'forsaken' => "A10 B11 T25 R5 sL60",

'lucky' => "pG5 cR20 C7 R6 sL40",
'wealthy' => "pG6 O-10 rO-10 sL30",

'of snakes' => "P15 sT1 sL20",
'of serpents' => "P30 sT1 sL80",
'of taint' => "T25 sT4 sL70",
'of shayol ghul' => "T45 sT4 sL100",

'of the waste' => "A7 B8 rN17 sT2 sL30",
'of stealth' => "U1 pG5 pX-5 sT0 sL40",
'of shadow' => "C20 sT1 sL50",
'of light' => "N20 rN20 oN20 G10 sT1 sL60",
'of darkness' => "O10 rO10 N-10 A9 B10 sT2 sL30",
'of brigands' => "C15 sT0 sL40",
'of wildmen' => "C22 sT0 sL60",
'of warriors' => "O20 A10 B10 sT2 sL60",
'of the blight' => "O25 N-10 rN-10 sT1 sL40",
'of the blasted lands' => "T47 A12 B15 H7 sT2 sL100",
'of ogier' => "D15 rD15 E20 rE20 sT1 sL60",
'of hunters' => "O15 C20 S1 sT1 sL50",
'of avendesora' => "G15 sT0 sL50",
'of aiel' => "G15 N10 rN12 sT1 sL45",
'of prosperity' => "G35 rO50 pG10 pX-9 sT0 sL70",
'of the green man' => "G20 pX3 sT0 sL50",
'of madmen' => "H94 O95 oO95 rO98 pG-10 C12 sT2 sL60",
'of madness' => "H99 O83 oO80 rO83 sT2 sL30",
'of legend' => "G17 O12 pX4 R5 sT2 sL40",
'of kings' => "O20 rO-26 pG5 sT2 sL40",
'of queens' => "rO22 O-27 pX5 sT0 sL40",
'of craftsmanship' => "O15 rO17 R4 sT0 sL40",
'of wolfkin' => "rO15 O19 rN13 N14 G5 sT1 sL55",
'of birds' => "rA1 rB2 sT1 sL20",
'of ravens' => "rA1 rB2 T2 sT1 sL10",
'of eagles' => "A1 B2 pG3 O10 sT0 sL10",

'of archers' => "rA8 rB10 rO10 N10 sL50",
'of far sight' => "rA7 rB9 R10 sL40",
'of sight' => "rA4 rB5 rO5 C6 sL30",

'of guardians' => "C7 N15 rN15 rO10 sL30",
'of reaping' => "C7 pG25 sL30",
'of life' => "pX5 G15 sL30",
'of myth' => "rO20 rA2 rB2 C5 sL50",
'of the gholam' => "oD 50 oE60 oN50 sL40",
'of piercing' => "rA7 rB11 rO10 pX4 sL30",
'of havoc' => "O10 T10 pG7 sL50",
'of birgette' => "pX5 R35 sL60",
'of revenge' => "pG20 H10 T16 A10 B14 O22 C7 N-10 rN-10 sL80",
'of the vanguard' => "D2 E3 rD8 rE10 rN12 sL40",
'of brawlers' => "D8 E10 rD2 rE3 N13 sL40",

'of strength' => "D2 E3 rD2 rE2 N15 rN15 sL10",

'of servants' => "oO17 F4 cR12 sL40",
'of knowledge' => "oO13 oN25 sL50",
'of weaves' => "oO10 oN12 sL30"
);

// ITEM NAME GENERATOR FUNCTION

function iname($item,$itmlist)
{
$name = ucwords($itmlist[$item][1]);
if ($name) $name .= " ";
$name .= ucwords($itmlist[$item][0]);
if ($itmlist[$item][2]) $name .= " ".str_replace("Of","of",ucwords($itmlist[$item][2]));
return $name;
}

// PARSER FUNCTION

function cparse($string,$resolve)
{
// RESOLVE all common terms and place in array
$resolve = explode(" ",$string);
for ($x=0; $x<count($resolve); $x++)
{
if (!preg_match('/^[a-z]*$/i',$resolve[$x][0].$resolve[$x][1])) // one letter code
$array[$resolve[$x][0]] += intval($resolve[$x][1].$resolve[$x][2].$resolve[$x][3].$resolve[$x][4].$resolve[$x][5]);
else // two letter code
$array[$resolve[$x][0].$resolve[$x][1]] += intval($resolve[$x][2].$resolve[$x][3].$resolve[$x][4].$resolve[$x][5].$resolve[$x][6]);
}
if ($array['P']>100) $array['P']=100;
if ($array['C']>95) $array['C']=95;
return $array;
}

// ITEM STRING REDUCER FUNCTION

function itp($string,$type)
{
$array = cparse($string,0);
// Handle item parsing
if ($type == 8)
{
if ($array[P]) $string .= " P".(-$array[P]);
if ($array[T]) $string .= " T".(-$array[T]);
}
if ($type == 1 || $type == 4 || $type == 6 || $type == 8)
{
if ($array[D]) $string .= " D".(-$array[D]);
if ($array[E]) $string .= " E".(-$array[E]);
if ($array[rD]) $string .= " rD".(-$array[rD]);
if ($array[rE]) $string .= " rE".(-$array[rE]);
if ($array[oD] && $type != 8) $string .= " oD".(-$array[oD]);
if ($array[oE] && $type != 8) $string .= " oE".(-$array[oE]);
}
if ($type == 2 || $type == 3 || $type == 5 || $type == 6 || $type == 8)
{
if ($array[N]) $string .= " N".(-$array[N]);
if ($array[rN]) $string .= " rN".(-$array[rN]);
if ($array[oN] && $type != 8) $string .= " oN".(-$array[oN]);
}
if ($type == 7 || $type == 0 || $type == 8)
{
if ($array[A]) $string .= " A".(-$array[A]);
if ($array[B]) $string .= " B".(-$array[B]);
if ($array[rA]) $string .= " rA".(-$array[rA]);
if ($array[rB]) $string .= " rB".(-$array[rB]);
if ($array[oA] && $type != 8) $string .= " oA".(-$array[oA]);
if ($array[oB] && $type != 8) $string .= " oB".(-$array[oB]);
if ($array[O]) $string .= " O".(-$array[O]);
if ($array[rO]) $string .= " rO".(-$array[rO]);
if ($array[oO] && $type != 8) $string .= " oO".(-$array[oO]);
}
if ($type == 1 || $type == 2 || $type == 5)
{
if ($array[rA]) $string .= " rA".(-$array[rA]);
if ($array[rB]) $string .= " rB".(-$array[rB]);
if ($array[oA]) $string .= " oA".(-$array[oA]);
if ($array[oB]) $string .= " oB".(-$array[oB]);
if ($array[rO]) $string .= " rO".(-$array[rO]);
if ($array[oO]) $string .= " oO".(-$array[oO]);
}
if ($type == 4)
{
if ($array[A]) $string .= " A".(-$array[A]);
if ($array[B]) $string .= " B".(-$array[B]);
if ($array[O]) $string .= " O".(-$array[O]);
}
return $string;
}

// COMBINE LIKE BATTLE COMMANDS

function clbc($string)
{
$array = cparse($string,0);
$return_string = "";
foreach ($array as $x => $y)
{
$return_string .= $x.$y." ";
}
return $return_string;
}

// LEVEL TO WORTH CONVERTER

function lvl2worth($lvl)
{
return intval($lvl+10/9*pow($lvl*0.9,3)+100);
}

// LINEAR TO EXP HEALTH

function lin2exp_hp($lvl)
{
return intval(pow($lvl,1.35)*1.5+19 + pow($lvl-1,1.08));
}

// ITEM INFO

function show_sign1($thing) {
if ($thing < 0) return $thing; else return "+".$thing;
}

function itm_info($char_stats)
{
	$bonus="";
	if ($char_stats['A'] < 0) $char_stats['A'] = 0;
	if ($char_stats['B'] < $char_stats['A']) $char_stats['B'] = $char_stats['A'];
	if ($char_stats['D'] < 0) $char_stats['D'] = 0;
	if ($char_stats['E'] < $char_stats['D']) $char_stats['E'] = $char_stats['D'];
	if (intval($char_stats['pG'])>95) $char_stats['pG']=95;

	if ($char_stats['U']) $bonus .= "Unblockable<br>";
	if ($char_stats['S']) $bonus .= "Stun<br>";
	if ($char_stats['A'] || $char_stats['B']) $bonus .= $char_stats['A']."-".$char_stats['B']." melee dmg<br>";
	if ($char_stats['rA'] || $char_stats['rB']) $bonus .= $char_stats['rA']."-".$char_stats['rB']." ranged dmg<br>";
	if ($char_stats['oA'] || $char_stats['oB']) $bonus .= $char_stats['oA']."-".$char_stats['oB']." OP dmg<br>";
	if ($char_stats['F']) $bonus .= show_sign1($char_stats['F'])." fire dmg<br>";
	if ($char_stats['D'] || $char_stats['E']) $bonus .= $char_stats['D']."-".$char_stats['E']." melee block<br>";
	if ($char_stats['rD'] || $char_stats['rE']) $bonus .= $char_stats['rD']."-".$char_stats['rE']." ranged block<br>";
	if ($char_stats['oD'] || $char_stats['oE']) $bonus .= $char_stats['oD']."-".$char_stats['oE']." OP block<br>";
	if ($char_stats['O']) $bonus .= show_sign1($char_stats['O'])."% melee dmg<br>";
	if ($char_stats['rO']) $bonus .= show_sign1($char_stats['rO'])."% ranged dmg<br>";
	if ($char_stats['oO']) $bonus .= show_sign1($char_stats['oO'])."% OP dmg<br>";
	if ($char_stats['N']) $bonus .= show_sign1($char_stats['N'])."% melee def<br>";
	if ($char_stats['rN']) $bonus .= show_sign1($char_stats['rN'])."% ranged def<br>";
	if ($char_stats['oN']) $bonus .= show_sign1($char_stats['oN'])."% OP def<br>";
	if ($char_stats['H']) $bonus .= $char_stats['H']."% dmg to self<br>";
	if ($char_stats['G']) $bonus .= $char_stats['G']."% health gain<br>";
	if ($char_stats['R']) $bonus .= show_sign1($char_stats['R'])."% critical<br>";
	if ($char_stats['cR']) $bonus .= show_sign1($char_stats['cR'])."% crushing force<br>";
	if ($char_stats['pG']) $bonus .= show_sign1($char_stats['pG'])."% gold steal<br>";
	if ($char_stats['pX']) $bonus .= show_sign1($char_stats['pX'])."% exp gain<br>";
	if ($char_stats['C']) $bonus .= show_sign1($char_stats['C'])."% agility<br>";
	if ($char_stats['P']) $bonus .= $char_stats['P']."% poison dmg<br>";
	if ($char_stats['T']) $bonus .= show_sign1($char_stats['T'])." taint dmg<br>";
	return str_replace(" ", "&nbsp;", $bonus);
}

// ITEM COMPONENTS

function add_bigarrays($array1,$array2) {
	for ($x=0; $x<count($array1); $x++)
		for ($y=0; $y<count($array1[0]); $y++)
			$array1[$x][$y]=intval($array1[$x][$y])+intval($array2[$x][$y]);
	return $array1;
}

function add_bigarrays20($array1,$array2) {
	for ($x=0; $x<count($array1); $x++)
		for ($y=0; $y<count($array1[0]); $y++) {
			$array1[$x][$y]=intval($array1[$x][$y])+intval($array2[$x][$y]);
			if ($array1[$x][$y] > 20) $array1[$x][$y] = 20;
		}
	return $array1;
}

function add_arrays($array1,$array2) {
	for ($x=0; $x<count($array1); $x++) $array1[$x]=intval($array1[$x])+intval($array2[$x]);
	return $array1;
}

function itm_req($string) {
	$char_stats = cparse($string,0);
	
	// BASE REQUIREMENTS (Wood, Furs, Ores)
	$types = array(
		array(array(0.8,0.8,1,0.3),array(2,1.8,2,1.2),array(0.9,1,0.8,0.6)),
		array(array(0.8,1,0.7,0),array(0,0,0,0),array(4,3.5,3,1.2)),
		array(array(1,0.85,0.7,.4),array(0,0,0,0),array(3,2.4,0.8,0.4)),
		array(array(3.5,3,3.3,1.1),array(0,0,0,0),array(0.5,0.6,0.7,0.8)),
		array(array(4,3.8,3.2,1),array(0,0,0,0),array(0.8,0.8,1,0)),
		array(array(2,1.6,1.8,0.6),array(0,0,0,0),array(2,1.6,1.8,0.6)),
		array(array(1.3,0.9,1,0),array(0,0,0,0),array(3,2.6,3,1.3)),
		array(array(1,1.1,0.8,0.4),array(2,2.2,1.9,1),array(1,1.1,0.8,0.4)),
	);
	$types_s = array(
		array(array(2,2.5,2.1,1),array(0,0,0,0),array(0,0,0,0)),
		array(array(0,0,0,0),array(2,2,2.5,1.1),array(0,0,0,0)),
		array(array(0,0,0,0),array(0,0,0,0),array(2,1.8,2,1)),
		array(array(1,1.2,1,0.6),array(2,2,1.3,1),array(2,2,1.3,1)),
	);
	$supplies = $types[intval($char_stats['wT'])];
	$supplies_s = $types[intval($char_stats['sT'])];
	if (!$char_stats['sL']) $supplies_s=array(array(0,0,0,0),array(0,0,0,0),array(0,0,0,0));
	if (!$char_stats['wL']) $supplies=array(array(0,0,0,0),array(0,0,0,0),array(0,0,0,0));
	for ($x=0; $x<3; $x++) {
		for ($y=0; $y<4; $y++) {
			$supplies[$x][$y] = intval($supplies[$x][$y]*$char_stats['wL']/10)+intval($supplies_s[$x][$y]*$char_stats['sL']/10);
			$supplies[$x][$y] -= $y*3;
			if ($supplies[$x][$y]<0) $supplies[$x][$y] = 0;
			if ($supplies[$x][$y]>20) $supplies[$x][$y] = 20;
		}
	}
	return $supplies;
}

function itm_req_list($string) {
	$supplies = itm_req($string);
	$text = '';
	for ($x=0; $x<3; $x++) {
		for ($y=0; $y<4; $y++) {
			$text .= intval($supplies[$x][$y]).",";
		}
	}
	$text = substr_replace($text,"",-1);
	return $text;
}

// ITEM VALUE PRODUCER

function item_val($string) {
	$array = itm_req($string);
	$num = 0;
	for ($x=0; $x<3; $x++) {
		for ($y=0; $y<4; $y++) {
			$num += intval($array[$x][$y]);
		}
	}
	$worth = round($num * 6.5);
	if ($worth < 0) $worth = 0;
	return $worth;
}

function lvl_req($string) {
	$char_stats = cparse($string,0);
	$lvl = round(pow(($char_stats['wL'])/10,1.2)*1.8) + round($char_stats['sL'] / 30);
	return $lvl < 1 ? 1 : $lvl;
}

function old_lvl_req($string) {
	$char_stats = cparse($string,0);
	$lvl = round(pow(($char_stats['wL']+$char_stats['sL'])/10,1.2)*1.8);
	return $lvl < 1 ? 1 : $lvl;
}

function item_val_old($string)
{
	$array = cparse($string,0);

	$damage = 0.45*($array[A] + $array[B] + $array[rA] + $array[rB] + $array[oA] + $array[oB] + $array[D] + $array[E] + $array[rD] + $array[rE] + $array[oD] + $array[oE]);

	$percent = 3*$array[P] + $array[N] + $array[rN] + $array[oN] + $array[O] + $array[rO] + $array[oO] + 2*$array[C] + 2.5*$array[G] - 1.5*$array[H] + 15*$array[S] + 15*$array[U];

	if ($damage) $damage = $damage*(100+$percent)/100;
	elseif ($percent) $damage = 50*(100+$percent)/100;
	$damage += 2.5*$array[T];

	$worth = intval(($damage + ((1/9)*$damage*$damage*$damage ))*10);

	if ($worth > 10000) $worth = intval(($worth/100)+0.5)*100;
	if ($worth < 1) $worth = 1;
	return $worth;
}
?>
