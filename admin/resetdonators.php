<html>
<head>
<title>Recreate donators table</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<u>Resets the table "donate"</u><br><br>
<?php
// Connect
include("connect.php");

// Drop Old Table
$query  = 'DROP TABLE donate';
$result = mysql_query($query);
echo "<b>Results</b><br><br>Drop Old Table: $result";

// Create New Table
$query = 'CREATE TABLE donate( '.
'id int NOT NULL AUTO_INCREMENT, '.
'email char(40), '.
'amount int, '.
'PRIMARY KEY (id)'.
')';
$result = mysql_query($query);
echo "<br>Create New Table: $result";
// Close Database
mysql_close($db);
?>

</body>
</html>
