<html>
<head>
<title>Admin Recreate Users Table</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<u>Resets the table "Users and Users_data"</u><br><br>
<?php
// Connect
include("connect.php");

/////////////////////////////////////////////////////////////////////////////////////
// USERS ////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

echo "::USERS TABLE::<br><br>";
// Drop Old Table
$query  = 'DROP TABLE Users';
$result = mysql_query($query);
echo "Drop Old Table: $result";

// Create New Table
$query = 'CREATE TABLE Users( '.
'id int NOT NULL AUTO_INCREMENT, '.
'name char(25) NOT NULL, '.			// Character's name
'lastname char(25) NOT NULL, '.			// Character's house name
'password char(40), '.				// Encoded Password
'email char(40), '.				// email address
'born int, '.					// Time of creation
'sex BOOL, '.					// Male or Female
'exp int, '.					// Experience total
'exp_up int, '.					// Experience total to next lvl
'exp_up_s int, '.				// Experience to next lvl
'skill_x int, '.				// Battle skill table 'x' location
'num_res int, '.				// Slows down resource collecting
'type char(30), '.				// Character Type
'gold bigint unsigned NOT NULL, '.		// Gold
'level int not null, '.				// Level
'vitality int, '.				// Health
'final_pow int, '.				// Final skill tree bonus
'points int, '.					// Free points for attributes
'lastcheck int, '.				// Last time you were on (rounded to the lowest hour in HOURS)
'lastscript int, '.				// Last time you accessed a page
'lastbuy int, '.				// Last time you last made a shop purchase
'lastbank int, '.				// Last time you used the bank
'location char(50) NOT NULL, '.			// Location
'avatar char(200), '.				// Character Avatar
'society char(30), '.				// Current Org
'shopgold bigint, '.				// Shop Till
'pswd char(12), '.				// Password
'support int, '.				// ID of character supported
'infl int, '.					// amount of influence
'ismarket int, '.				// There is a market in the current area
'goodevil int, '.				// Good, Evil, or Neutral
'score int NOT NULL, '.				// Battles Won
'nextbattle int, '.				// Time when the last battle finished / will finish
'battlestoday int, '.				// Number of battles currently, updated every hour
'r_start int, '.				// Most recent time character loaded the resource page
'r_numb int, '.				// Most recent resource collection number
'bankgold int, '.				// Gold in the bank
'travelmode2 int, '.				// Backpack, wagon, etc. for carrying
'travelmode int, '.				// Horse, mule, etc. for transportation
'travelmode_name char(20), '.			// Horse, mule, etc's name
'feedneed int, '.				// Feed needed for transportation
'travelto char(50), '.				// Where is being travelled to
'arrival int, '.				// When character will arrive
'depart int, '.					// When character started travelling
'traveltype int, '.				// By land or boat
'lastpost int, '.				// last post time
'battles int, '.				// Total number of battles
'lvl_skills int, '.				// Last level from which a new skill was choosen
'PRIMARY KEY (id), '.				// INDEXES
'INDEX (level), '.
'INDEX (location),'.
'INDEX (score),'.
'INDEX (exp),'.
'INDEX (name(3)),'.
'INDEX (lastname(3)),'.
'INDEX (support) '.
')';
$result = mysql_query($query);
echo "<br>Create New Table: $result";

/////////////////////////////////////////////////////////////////////////////////////
// USERS_DATA ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

echo "<br><br>::USERS_DATA TABLE::<br><br>";
// Drop Old Table
$query  = 'DROP TABLE Users_data';
$result = mysql_query($query);
echo "Drop Old Table: $result";

// Create New Table
$query = 'CREATE TABLE Users_data( '.
'id int NOT NULL, '.
'itmlist TEXT,'.				// Inventory
'notes TEXT,'.					// Messages Data
'about TEXT,'.					// Character Info
'log TEXT,'.					// Battle Log data
'skills TEXT,'.					// Skill data
'skill_tree TEXT,'.				// Skill tree data
'resources TEXT,'.				// Resource Data
'find_battle TEXT,'.				// People you have attacked in the last ten minutes
'friends TEXT,'.				// Friend data
'extra_skills TEXT,'.		// extra skills (level up points)
'PRIMARY KEY (id)'.				// PRIMARY KEY
')';
$result = mysql_query($query);
echo "<br>Create New Table: $result";


?>

<br><br>
~Table reset and recreator v2 "THE SOCIETIES"~
</body>
</html>
