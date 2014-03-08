<?php
$db = mysql_connect("localhost", "root","");
if (!$db) {echo "<br><b>Could not connect to the MySQL database. Please try again in a few minutes.</b>"; exit;}
mysql_select_db("gos",$db);
$time=time();
$subfile="tbrk";
$server_name = "http://".$_SERVER['SERVER_NAME']."/gos";
$div_img = "<table border=0 cellpadding=0 cellspacing=0 width=550 height=1 background=\"images/divider.gif\"><tr><td></td></tr></table>";

// CONSTANTS
$battlelimit = 30;
$max_gold = 10000000000;
$enable_producers = 0;
$inv_max = 10;
$is_firefox=substr_count($_SERVER["HTTP_USER_AGENT"],"Firefox");
?>
