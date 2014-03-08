<?php
$skills = unserialize($char['skills']);
if ($enemy_id) $skills2 = unserialize($char2['skills']);

function show_sign($thing) {
if ($thing < 0) return $thing; else return "+".$thing;
}
// SKILL DATA
// array(description,effect,neutral/good/evil,battle_text,health_added);
$skill_stat = array(
'Falling Leaf' => array("Slightly increases ranged damage","rO5",0,"<ran> Falling Leaf"),
'Bundling Straw' => array("Slightly increases melee damage","O5",0,"<ran> Bundling Straw"),
'Watered Silk' => array("Slightly increases defense","N5 rN5",0,"<ran> Watered Silk"),
'Striking the Spark' => array("Increases damage","O15 rO15",0,"<ran> Striking the Spark"),
'Parting the Silk' => array("Life gaining attack","G10",0,"<ran> Parting the Silk"),
'Hummingbird Kisses the Honeyrose' => array("Damaging attack with adequate defense","O15 N10 rN5",0,"<ran> Hummingbird Kisses the Honeyrose"),
'Cat Dances on the Wall' => array("Ranged attack","rO10 R5",0,"<ran> Cat Dances on the Wall"),
'Wood Grouse Dances' => array("Melee attack edged with poison","O20 pG20 P5",0,"<ran> Wood Grouse Dances"),
'Heron Spreads Its Wings' => array("Small defensive manuver","O10 rO10 N15 rN15",0,"<ran> Heron Spreads Its Wings"),
'Courtier Taps His Fan' => array("Fast ranged attack and small life gain","rO15 G15 C9",0,"<ran> Courtier Taps His Fan"),
'Kingfisher Takes a Silverback' => array("Strong melee attack with nasty side effects","O30 H5 T15",2,"<ran> Kingfisher Takes a Silverback"),
'Leaf on the Breeze' => array("Quick attack with sufficent defense","O15 rO15 rN15 N10 C9",0,"<ran> Leaf on the Breeze"),
'Water Flows Downhill' => array("Rejuvinating attack","O15 rO15 G20 R5",0,"<ran> Water Flows Downhill"),
'Dove Takes Flight' => array("Fast Long distance attack","rO20 R5 C12",1,"<ran> Dove Takes Flight"),
'Lion on the Hill' => array("Powerful melee attack with taint and poison","O40 T20 P10 H5",2,"<ran> Lion on the Hill"),
'Unfolding of the Fan' => array("Strong melee attack with good blocking","O25 N15 rN15 pG25",2,"<ran> Unfolding of the Fan"),
'The Wind and the Rain' => array("Combined attack","O25 rO25 C6 N15 rN10",0,"<ran> The Wind and the Rain"),
'The Wind Blows Over the Wall' => array("Long distance healing attack","rO25 G20 C9 N10",1,"<ran> The Wind Blows Over the Wall"),
'Lizard in the Thornbush' => array("Critical shot with some life gain","rO35 R10 G10",1,"<ran> Lizard in the Thornbush"),
'Swallow Takes Flight' => array("Strong Melee attack for stealing gold","O50 T15 P10 pG30 H5",2,"<ran> Swallow Takes Flight"),
'Cat on Hot Sand' => array("Close combat block that fills you with experiance","O40 rN15 N10 pX5",2,"<ran> Cat on Hot Sand"),
'Stones Falling From a Cliff' => array("Long distance attack with healing abilities","rO35 G25 N10 rN10",1,"<ran> Stones Falling From a Cliff"),
'Lightning of Three Prongs' => array("Powerful ranged attack","rO45 C12 R10",1,"<ran> Lightning of Three Prongs"),
'Thistledown Floats on the Whirlwind' => array("Tainted melee attack","O65 T35 G10",2,"<ran> Thistledown Floats on the Whirlwind"),
'Grapevine Twines' => array("Thieving attack to fill you with experience","O50 pG30 pX5",2,"<ran> Grapevine Twines"),
'Arc of the Moon' => array("Healing attack with strong melee block","rO45 G30 P15 N20 rN15",1,"<ran> Arc of the Moon"),
'Whirlwind on the Mountain' => array("Quick ranged attack to steal gold and deal massive damage","rO55 C18 R10 pG20",1,"<ran> Whirlwind on the Mountain"),
'Boar Rushes Down the Mountain' => array("Ultimate melee attack. All weapons can be equipped to either hand.","O100 T25 P20 N20 rN20 C16 G10",4,"<ran> Boar Rushes Down the Mountain"),
'Tower of the Morning' => array("Ultimate ranged attack. All melee damage always counts as ranged.","rO90 C24 R15 N20 rN10 pX10",3,"<ran> Tower of the Morning"),);
?>
