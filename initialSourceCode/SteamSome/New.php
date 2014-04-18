<?php
include 'Steam.php';
include 'database.php';

/* Total number of ID's scanned */
$sql = "SELECT COUNT(*) FROM `steamdata`.`user_summary`"; 
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$id_scanned = $row[0];

/* Total number of private ID's scanned */
$sql = "SELECT COUNT(*) FROM `steamdata`.`user_summary` WHERE `Private` = 1"; 
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$id_private = $row[0];

/* Total number of friends per ID */
$sql = "SELECT AVG(`Friends`) FROM `steamdata`.`friend_summary`";
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$friend_avg = $row[0];

/* Total number of games per ID */
$sql = "SELECT AVG(`Games`) FROM `steamdata`.`game_summary`";
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$game_avg = $row[0];

/* Total number of hours per ID */
$sql = "SELECT AVG(`Hours`) FROM `steamdata`.`game_summary`";
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$hours_avg = $row[0];

/* Total number of games not played per ID */
$sql = "SELECT AVG(`Never`) FROM `steamdata`.`game_summary`";
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$never_avg = $row[0];

echo "<table>";
echo "<tr><td>ID's Scanned</td><td>".$id_scanned."</td></tr>";
echo "<tr><td>Private ID's</td><td>".$id_private."</td></tr>";
echo "<tr><td>Friends on average</td><td>".$friend_avg."</td></tr>";
echo "<tr><td>Games on average</td><td>".$game_avg."</td></tr>";
echo "<tr><td>Hours played on average</td><td>".$hours_avg."</td></tr>";
echo "<tr><td>Never played games</td><td>".$never_avg."</td></tr>";
echo "</table>";

echo "<table>";
/* Top 25 most played games */
$sql = "SELECT  `GameID` , SUM(  `Hours` ) 
FROM  `game_data` 
GROUP BY  `GameID` 
ORDER BY SUM(  `Hours` ) DESC 
LIMIT 0 , 100";
$result = mysql_query($sql);
while($row = mysql_fetch_row($result))
{
	$sql = "SELECT * FROM `steamdata`.`game_name` WHERE `AppID` = ".$row[0];
	$game = mysql_query($sql);
	$name = mysql_fetch_row($game);

	$sql = "SELECT COUNT(*) FROM `steamdata`.`game_data` WHERE `GameID` = ".$row[0];
	$game = mysql_query($sql);
	$number = mysql_fetch_row($game);
	
	echo "<tr><td>".$name[1]."</td><td>".$number[0]."</td><td>".$row[1]."</td></tr>";
}


?>