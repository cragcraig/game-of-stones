<?php

include("connect.php");
$start = intval($_REQUEST[start]);

$num = 100;
$subject = "a new Game of Stones version is out";
$message = "GoS version 2.5 is now online! This release includes the world map, like version 2.0, but gets rid of the resources and adds back random item finds and talismans as they were in the 'fun' version. The best of both worlds!\n\nAs always, <a href='http://gostones.net'>http://gostones.net</a>.\n\nThanks,\nCraig H, Creator of GoS";

if (mail("craigrun@gmail.com", $subject, $message)) echo "success";

?>
