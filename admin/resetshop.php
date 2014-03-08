<html>
<head>
<title>Admin Recreate Society Table</title>
</head>
<body>
<u>Resets the table "Shop"</u><br><br>
<?php
// Connect
include("connect.php");

// Drop Old Table
$query  = 'DROP TABLE Shop';
$result = mysql_query($query);
echo "<b>Results</b><br><br>Drop Old Table: $result";

// Create New Table
$query = 'CREATE TABLE Shop( '.
'id int NOT NULL AUTO_INCREMENT, '.
'name varchar(30), '.
'inventory TEXT,'.
'PRIMARY KEY (id),'.
'UNIQUE id (id))';
$result = mysql_query($query);
echo "<br>Create New Table: $result";


// Close Database
mysql_close($db);
?>

<br><br>
~Table reset and recreator for the shop~
</body>
</html>
