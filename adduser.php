<?php
include("admin/connect.php");

//gather user variables from last page where inputed
$username=trim($_POST[userid]);
$lastname=trim($_POST[last]);
$actualpass=$_POST[password];
$actualpass2=$_POST[pass2];
$channeler=$_POST[channeler];
$password=sha1($actualpass);
$email=$_POST[email];
$sex=$_POST[sex];
$check_transfer=$_POST[transfer];
$born=time();

// clear stuff that could be transferred
$about="";
$type="";
$avatar="";


// transfer from old game
if ($check_transfer) {
	mysql_select_db("goston5_gos",$db);
	$result = mysql_query("SELECT * FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.name='$username' AND Users.lastname='$lastname'");
	$char = mysql_fetch_array($result);
	if ($char['name'] && $password==$char['password']) { // do the transfer
		$about = $char['about'];
		$type = $char['type'];
		$avatar = $char['avatar'];
		$email = $char['email'];
	}
	else { // fail
		header("Location: $server_name/create.php?time=$born&message=That Character/Password combination cannot be found in the previous Age&passer=".$char['password']);
		exit;
	}
	mysql_close($db);
	include("admin/connect.php");
}

// CREATE CHARACTER

$rand = rand(1,2);
if ($rand == 1) $startat="Aren Mador";
if ($rand == 2) $startat="Dorelle Caromon";
$startat="Aren Mador";
// FIRST NOTE
$query = "SELECT * FROM Users WHERE name = '$username' AND lastname = '$lastname' ";
$resultb = mysql_query($query, $db);
if (mysql_fetch_row($resultb)) {
	include('headerno.htm');
	echo "<text class=littletext><br><center><br>";
	echo "<center>The Character <b>$username $lastname</b> already exists. Please choose another name.";

}
elseif (strlen($lastname) > 2 && strlen($lastname) < 11 && strlen($username) > 2 && strlen($username) < 11 && strlen($actualpass) > 4 && strlen($actualpass) < 11 && !eregi("[^a-z]+",$lastname)  && !eregi("[^a-z]+",$username) && strlen($email) <= 40 && $actualpass == $actualpass2 && $_POST[noalt]) {

	// CREATE CHANNELER
	$num_start = 0;
	if ($channeler || $type == "Channeler") {
		$query = "SELECT * FROM donate WHERE email='$email'";
		$result = mysql_query($query, $db);
		$result = mysql_fetch_array($result);
		$cost = 5;
		if ($type == "Channeler") $result[amount] = intval($result[amount]) + $cost;
		if (intval($result[amount]) >= $cost) {
			$result[amount] -= $cost;
			
			if ($type != "Channeler") {
			$notes = array (
			array ( "<b>Welcome to Age Two of GoS and thanks for the donation!<br><br></b>You have enough credit for ".intval($result[amount]/$cost)." more channelers.<br><br>Channelers are not normal characters. They wield the awesome force of the One Power, this allows them to travel much faster and makes them much harder to defeat in battle. However, this means they can not steal gold or count against normal characters&#39; wins.", 0, "The", "Creator","$born"),
			);
			{
			else {
			$notes = array (
			array ( "<b>Welcome to Age Two of GoS!<br><br>This character has been reborn from Age One.", 0, "The", "Creator","$born"),
			);
			}
			
			$type = "Channeler";
			$num_start = 2;
			
			mysql_query("UPDATE donate SET amount='".$result[amount]."' WHERE email='$email'");	
		}
		else {
			include('headerno.htm');
			echo "<text class=littletext><br><center><br><b>This email does not have enough credit associated with it.</b><br><br>If you just donated, remember that it can be a few days before the credit shows up here.";
			exit;
		}
	}
	else {
		$notes = array (
		array ( "<b>Welcome to Age Two of GoS</b><br><br>It is the end of the Breaking of the world. Everything has been reduced to a cruel struggle for survival. Those few heroes who rise from the ashes of the Age of Legends will shape the future of civilization for Ages to come.<br><br>Check out the <a href=preview.php>Overview</a> for an introduction to this version&#39;s gameplay.", 0, "The", "Creator","$born"),
		);
	}
	
	// NORMAL CHARACTER STUFF
	
	$log = serialize(array());
	include("admin/setitems.php");
	$notes = serialize($notes);
	$lvl_up = 40; // EXP TO LEVEL UP FOR THE FIRST TIME
	$skills = serialize(array("Bundling Straw","Watered Silk","Falling Leaf"));
	$resources = serialize(array(array('0','0','0','0'),array('0','0','0','0'),array('0','0','0','0')));
	$lastcheck = intval($born/3600)-30;
	$ismarket=1;
	$sql = "INSERT INTO Users (name, lastname, password, pswd, avatar,  email, born, sex, type, gold, level, vitality, points, lastcheck, lastscript, lastbuy, society, shopgold, score, nextbattle, battlestoday, bankgold, lastbank, location, travelmode, travelmode_name, feedneed, travelmode2, travelto, arrival, depart, traveltype, battles, lvl_skills, exp, exp_up, exp_up_s, skill_x, num_res, goodevil, final_pow, support, infl, ismarket, r_numb) VALUES ('$username','$lastname', '$password', '$actualpass', '$avatar', '$email', '$born', '$sex', '$type', '20', '1', '20', '3', '$lastcheck', '0', '0', '', '0', '0', '0', '0', '0', '0', '$startat', '0', '', '0', '$num_start', '$startat', '0', '0', '0', '0', '0', '0', '$lvl_up', '$lvl_up', '0', '0', '0', '0', '0', '0', '1', '0')";
	$result = mysql_query($sql, $db);
	$query = "SELECT * FROM Users WHERE name = '$username' AND lastname = '$lastname' ";
	$resultb = mysql_query($query, $db);
	$char = mysql_fetch_array($resultb);
	$id=$char['id'];
	$friends=serialize(array());
	$sql2 = "INSERT INTO Users_data (id, itmlist, notes, about, log, skills, resources, find_battle, friends) VALUES ('$id', '$itms', '$notes', '$about', '$log', '$skills', '$resources', '0', '$friends')";
	$result2 = mysql_query($sql2, $db);

	// REDIRECT TO LOGIN
	if ($id && $result2) {
		setcookie("id", "$id", time()+99999999, "/");
		setcookie("name", "$username", time()+99999999, "/");
		setcookie("lastname", "$lastname", time()+99999999, "/");
		setcookie("password", "$password", time()+99999999, "/");
		// IF 100th character, optimize database
		$result = mysql_query("SELECT name, id FROM Users WHERE name='$username' AND lastname='$lastname'");
		$new_id = mysql_fetch_array($result);
		if ($new_id[id]/100 == intval($new_id[id]/100)) {mysql_query("OPTIMIZE TABLE Users"); mysql_query("OPTIMIZE TABLE Users_data");}
		// REDIRECT
		header("Location: $server_name/bio.php?time=$born");
		exit;
	}
	echo "Something really strange went wrong with this creation - please report it to craigrun@gmail.com";
	exit;
}
else {
	include('headerno.htm');
	echo "<text class=littletext><br><center><br><br><b><center>This Character could not be created<br><br><br><br><center><table><tr><td class='littletext'><p align=left><b>1.</b> The first and last names must be between 3 and 10 characters in length <br><br><b>2.</b> The password must be between 5 and 10 characters in length<br><br><b>3.</b> Both parts of the name must consist only of letters (no spaces)<br><br><b>4.</b> The E-Mail address must not exceed 40 characters<br><br><b>5. <i>You must agree to the terms</i></b></td></tr></table>";
}
?>

<br>

<?php
include('footer.htm');
?>
