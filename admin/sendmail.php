<?php

include("connect.php");
$start = intval($_REQUEST[start]);

$num = 100;
$subject = "Notice: a new Game of Stones version is out";
$message = "GoS version 2.5 is now online! This release includes the world map, like version 2.0, but gets rid of the resources and adds back random item finds and talismans as they were in the 'fun' version. The best of both worlds!\n\nAs always, <a href='http://gostones.net'>http://gostones.net</a>.\n\nThanks,\nCraig H, Creator of GoS";

$result = mysql_query("SELECT id, name, lastname, email FROM Users LIMIT $start,$num",$db);

echo "<a href='sendmail.php?start=".($_REQUEST[start] + $num)."'>Send Next Batch</a><br><br>";

while ($char = mysql_fetch_array($result)) {
	if ($char[email]) {
		echo "<b>".$char[id]."</b> ".$char[name]." ".$char[lastname].", <i>".$char[email]."</i> -";
		if (mail($char[name]." ".$char[lastname]." <".$char[email].">", $subject, $message)) echo "success!";
		else echo "<b>failure!</b>";
		echo "<br>";
	}
}

?>
