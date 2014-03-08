<?php
// establish a connection with the database
include("admin/connect.php");
include("admin/userdata.php");

$message=$_GET['message'];
$show=intval($_GET['show']);

// FIGURE OUT BATTLE SKILL PROGRESSION

$s_list = array(
array('Striking the Spark','Parting the Silk','0','0','0','0'),
array('Hummingbird Kisses the Honeyrose','Cat Dances on the Wall','0','0','0','0'),
array('Wood Grouse Dances','Heron Spreads Its Wings','Courtier Taps His Fan','0','0','0'),
array('Kingfisher Takes a Silverback','Leaf on the Breeze','Water Flows Downhill','Dove Takes Flight','0','0'),
array('Lion on the Hill','Unfolding of the Fan','The Wind and the Rain','The Wind Blows Over the Wall','Lizard in the Thornbush','0'),
array('Swallow Takes Flight','Cat on Hot Sand','0','Stones Falling From a Cliff','Lightning of Three Prongs','0'),
array('Thistledown Floats on the Whirlwind','Grapevine Twines','0','Arc of the Moon','Whirlwind on the Mountain','0'),
array('Boar Rushes Down the Mountain','0','0','Tower of the Morning','0','0'),
);

$new_skills = array(0,0);
$level_cur = intval($char['level']/5)-1;
if ($level_cur+1>intval($char['lvl_skills']/5) && $char['level'] <= 40) {
	$x=$char['skill_x'];
	$new_x = array(0,0);
	if ($s_list[$level_cur][$x]) $new_x[0]=$x;
	else $new_x[0]=$x-1;
	if ($s_list[$level_cur][$x+1]) $new_x[1]=$x+1;
	else $new_x[1]=$x-1;
	if ($char['level'] == 40) {
		if ($s_list[$level_cur][$new_x[0]]) $new_x[1]=$new_x[0];
		else $new_x[0]=$new_x[1];
	}
	$new_skills = array($s_list[$level_cur][$new_x[0]],$s_list[$level_cur][$new_x[1]]);
}

// JAVASCRIPT
$javascripts=<<<SJAVA
<SCRIPT LANGUAGE="JavaScript">
	
var resType=0;
var resSub=0;

	function clickTab(tab) {
		resType=tab-1;
		resSub=0;
		for (var x=0; x<=3; x++) {
			if (x != tab) {
				document.getElementById('tc'+x).style.display='none';
				document.getElementById('tab'+x).className='tab_unsel';
			}
		}
		document.getElementById('tab'+tab).className='tab_sel';
		document.getElementById('tc'+tab).style.display='block';
	}
	
	function findPos(obj,xy) {
		var curleft = curtop = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft
			curtop = obj.offsetTop
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft
				curtop += obj.offsetTop
			}
		}
		if (!xy) return curleft;
		else return curtop;
	}
	
</SCRIPT>
SJAVA;

include("admin/skills.php");
include("admin/itemarray.php"); 

$skill_tree=unserialize($char['skill_tree']);

// SWITCH ACTIVE SKILLS
if ($_GET['switch']) {
	if ($_GET['switch']==1) $switch=0;
	else $switch=2;
	$temp=$skills[1];
	$skills[1]=$skills[$switch];
	$skills[$switch]=$temp;
	mysql_query("UPDATE Users_data SET skills='".serialize($skills)."' WHERE id='$id'");
}

// LEARN NEW SKILL
if ($new_skills[$_GET['activate']-1] && $_GET['activate']) {
	$message = $new_skills[$_GET['activate']-1].' learned in place of '.$skills[1];
	$skills[1]=$new_skills[$_GET['activate']-1];
	$char['lvl_skills']=$char['level'];
	if ($skill_stat[$new_skills[$_GET['activate']-1]][2]>=0 && $skill_stat[$new_skills[$_GET['activate']-1]][2]<3) $char['goodevil']=$skill_stat[$new_skills[$_GET['activate']-1]][2];
	if ($skill_stat[$new_skills[$_GET['activate']-1]][2]>=3) $char['final_pow']=$skill_stat[$new_skills[$_GET['activate']-1]][2];
	mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users.goodevil='".$char['goodevil']."', Users_data.skills='".serialize($skills)."', Users.lvl_skills='".$char['lvl_skills']."', Users.skill_x='".$new_x[$_GET['activate']-1]."', Users.final_pow='".$char['final_pow']."' WHERE Users.id='$id'");
	$new_skills = array(0,0);
}

include("header.htm");

function draw_skill($name,$skill_data,$col) {
	$colors = array('efefef','546ee2','df1e22');
	$text = "<FIELDSET class='abox' style='background-color: $col;'><LEGEND><font class=littletext style='color: #".$colors[($skill_data[2] < 3 ? $skill_data[2] : $skill_data[2] - 2)].";'><b>".$name."</b></LEGEND><P>\n<font class=foottext>";
	$text .= "<i>".$skill_data[0]."</i><br><br><font class='littletext'>".itm_info(cparse($skill_data[1],0));
	$text .= "</FIELDSET>";
	return $text;
}

$color = array("000000","171717","AAAAAA");
$cell_size = 75;
?>
<center>
	<!-- CONTENT -->
<table border='0' cellspacing='0' width='100%' cellpadding='0'>
	<tr>
		<td><center>
			<table border='0' cellspacing='0' width='90%' cellpadding='0'>
				<tr>
		<?php
				for ($x = 0; $x < 3; $x++) {
					if ($x > 0) echo "<td width='25'>&nbsp;</td>"; 
					echo "<td width='33%' valign='top'>";
					if ($x!=1) echo "<br>";
					echo draw_skill($skills[$x],$skill_stat[$skills[$x]],'#090909');
					if ($x==1) echo "<table class='blank' width='100%'><td align='left' valign='top'><a href='stats.php?switch=1&time=".time()."'><font class='littletext'><font color='#546ee2'>&#171;&nbsp;<font class='foottext'><font color='#546ee2'>switch</a></td><td valign='middle' class='foottext_f'><center><b>50%</b></td><td align='right' valign='top'><a href='stats.php?switch=2&time=".time()."'><font class='foottext'><font color='#546ee2'>switch<font class='littletext'><font color='#546ee2'>&nbsp;&#187;</a></td></table>";
					else echo "<font class='foottext_f'><center><b>25%</b>";
					echo "</td>";
				}
				?>
				</tr>
			</table>
			</td>
			</tr>
				<?php
				// NEW SKILLS
				if ($new_skills[0] && $new_skills[1]) {
					echo "<tr><td height='50'>&nbsp;</td></tr><tr><td><center>";
					echo "<table border='0' width='350' cellpadding='5' cellspacing='0' style='vertical-align: middle;'><tr>";
					echo "<td width='50%' height='120' valign='middle'>".draw_skill($new_skills[0],$skill_stat[$new_skills[0]],'#0F0F0F')."<a href=\"javascript:popConfirm('Permanently replace ".$skills[1]." with ".$new_skills[0]."?','stats.php?activate=1&time=".time()."')\"><center><font class='foottext'>&#8593; activate &#8593;</a></td>";
					echo "<td valign='middle' class='littletext'>&nbsp;or&nbsp;</td>";
					echo "<td width='50%' height='120' valign='middle'>".draw_skill($new_skills[1],$skill_stat[$new_skills[1]],'#0F0F0F')."<a href=\"javascript:popConfirm('Permanently replace ".$skills[1]." with ".$new_skills[1]."?','stats.php?activate=2&time=".time()."')\"><center><font class='foottext'>&#8593; activate &#8593;</a></td>";
					echo "</tr></td></table>";
					echo "</td></tr>";
				}
				elseif ($char['level']<40) echo "<tr><td height='50'>&nbsp;</td></tr><tr><td class='littletext_f' height='60'><center>- New skills will be avaliable at level ".(intval($char['level']/5)*5+5)." -</td></tr>";											
			?>
</table>


<?php
include('footer.htm');
?>
