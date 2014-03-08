<?php
include("admin/itemarray.php");

for ($x=1; $x<=20; $x+=1) echo "<b>".$x."</b>= ".lvl_req("wL".($x*10))."<br>";
?>
