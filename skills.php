<?php
// establish a connection with the database
include("admin/connect.php");
include("admin/userdata.php");
include("admin/itemarray.php");

$message=$_GET['message'];
$uparray = array("O1","");

// INCREASE SKILL TREE LEVEL
if ($upskill = $_GET[up] && $_GET[up] < count($uparray)) {
	if ($char['points'] > 0) {
		$char['']++;
		$char['points']--;
	}
	mysql_query("UPDATE Users LEFT JOIN Users_data ON Users.id=Users_data.id SET Users_data.extra_skills='".$char['extra_skills']."', Users.points='".$char['points']."' WHERE Users.id=$id");
}

if (!$message) if ($char['points']==0) {
	if ($char['points']==0) $message = "You have no skill points to assign";
	else $message = "You have ."$char['points']". skill points to assign";
}
include("header.htm");
?>
<center>
	<!-- CONTENT -->
<table border='0' cellspacing='0' width='100%' cellpadding='0'>
	<tr>
		<td>
			<center>
			
		</td>
	</tr>
</table>


<?php
include('footer.htm');
?>
