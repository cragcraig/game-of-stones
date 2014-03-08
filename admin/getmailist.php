<?php

function valid_email($email) {
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
     if (!ereg("^(([A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
} 

include("connect.php");
$start = intval($_REQUEST[start]);

$num = 100;
$subject = "Notice: a new Game of Stones version is out";
$message = "GoS version 2.5 is now online! This release includes the world map, like version 2.0, but gets rid of the resources and adds back random item finds and talismans as they were in the 'fun' version. The best of both worlds!\n\nAs always, <a href='http://gostones.net'>http://gostones.net</a>.\n\nThanks,\nCraig H, Creator of GoS";

$result = mysql_query("SELECT id, name, lastname, email FROM Users LIMIT $start,$num",$db);

echo "<a href='getmailist.php?start=".($_REQUEST[start] + $num)."'>Get Next Batch</a><br><br>";

while ($char = mysql_fetch_array($result)) {
	if (valid_email($char[email])) {
		echo $char[name]." ".$char[lastname]." &#60;".$char[email]."&#62;, ";
	}
}

?>
