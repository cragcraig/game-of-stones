<?php
$id = $_COOKIE['id'];
$name = $_COOKIE['name'];
$lastname = $_COOKIE['lastname'];
$password = $_COOKIE['password'];
$result = mysql_query("SELECT * FROM Users LEFT JOIN Users_data ON Users.id=Users_data.id WHERE Users.id='$id'");
$char = mysql_fetch_array($result);
if (!($name && $lastname && $password) || $password!=$char['password']) {header("Location: $server_name/verify.php?enabled=1"); exit;}
$curtime=time();

// MAX INVENTORY SIZE (ANY CHANGES ALSO NEED TO BE CHANGED IN THE 'map/places/blacksmith.php' SCRIPT)
$inv_max=10+4*$char['travelmode2'];

// EVERY HOUR
if ($char[lastcheck] > 100000000) $char[lastcheck] = intval(time()/3600)-30;
if (time() - ($char[lastcheck] * 3600) >= 3600)
{
	$hourspast=intval((time() - ($char[lastcheck] * 3600))/3600);

	// UPDATE BATTLES AND RESOURCE COLLECTING
	$num_res = $char[$num_res]-4*$hourspast;
	if ($num_res < 0) $num_res = 0;
	$battlestoday = $char[battlestoday]-2*$hourspast;
	if ($battlestoday > $battlelimit) $battlestoday = $battlelimit;
	if ($battlestoday < 0) $battlestoday = 0;
	mysql_query("UPDATE Users SET battlestoday='$battlestoday', num_res='$num_res' WHERE id='$id'");
	$char[battlestoday] = $battlestoday;

	// UPDATE LAST CHECKED
	$check=intval(time()/3600);
	$query = "UPDATE Users SET lastcheck='$check', lastscript='".time()."' WHERE id='$id'";
	$result = mysql_query($query);

	// IF IT HAS BEEN MORE THAN AN HOUR SINCE LAST USE
	if ($hourspast > 1) {
		mysql_query("UPDATE Users SET find_battle='0' WHERE id='$id'"); // CLEAR TEN MINUTE ARRAY
		//$message = $_GET['message'] = "Age One ends in ".intval((1195581600 - time()) / (60 * 60 * 24))." days";
		$message = $_GET['message'] = "Game of Stones - Age Two";
	}
}

if ($char[gold] < 0 || $char[gold] > $max_gold) // KEEP GOLD BELOW MAX
{
	if ($char[gold] < 0) mysql_query("UPDATE Users SET gold='0' WHERE id='$id'");
	else  mysql_query("UPDATE Users SET gold='$max_gold' WHERE id=$id");
}

if ($char['arrival']<=time() && $char['arrival']!=0) // ARRIVE AT TRAVEL TO PLACE
{
	include($_SERVER['DOCUMENT_ROOT']."/".$subfile."/map/mapdata/coordinates.inc");
	$char['location']=$char['travelto'];
	mysql_query("UPDATE Users SET location='".$char['travelto']."', arrival='0', ismarket='".$location_array[$char['travelto']][3]."' WHERE id='$id'");
}

function level_at($exp,$exp_up,$exp_up_s,$lvl) {
	while ($exp>=$exp_up) {
		$lvl++;
		if ($lvl < 13) $exp_up_s=round($exp_up_s*1.25);
		else $exp_up_s=round($exp_up_s*1.07);
		if ($exp_up_s > 800) $exp_up_s = 800;
		$exp_up+=$exp_up_s;
	}
	return array($lvl,$exp_up,$exp_up_s);
}

function calc_influence($id) {
	$thing = mysql_fetch_array(mysql_query("SELECT SUM(exp) FROM Users WHERE support='$id'"));
	$sum = floor($thing['SUM(exp)']/100);
	return $sum;
}

?>
