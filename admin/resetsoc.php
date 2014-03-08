<html>
<head>
<title>Admin Recreate Society Table</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<u>Resets the table "Soc"</u><br><br>
<?php
// Connect
include("connect.php");

// Drop Old Table
$query  = 'DROP TABLE Soc';
$result = mysql_query($query);
echo "<b>Results</b><br><br>Drop Old Table: $result";

// Create New Table
$query = 'CREATE TABLE Soc( '.
'id int NOT NULL AUTO_INCREMENT, '.
'name varchar(30), '.
'leader varchar(30), '.
'leaderlast varchar(30), '.
'about TEXT,'.
'forum TEXT,'.
'stance TEXT,'.
'blocked TEXT,'.
'invite int, '.
'allow int, '.
'members int, '.
'score int, '.
'PRIMARY KEY (id),'.
'INDEX (name(3))'.
')';
$result = mysql_query($query);
echo "<br>Create New Table: $result";


// SET EVERYONE TO NO SOCIETIES
$querya = "UPDATE Users SET society='' WHERE 1";
$result = mysql_query($querya);


// Close Database
mysql_close($db);
?>

<br><br>
~Table reset and recreator for societies "THE START"~
</body>
</html>
