<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	echo "<table>";
	
	$sql   = "SELECT * FROM `steam`.`dlc_list` WHERE lastUpdate = 0 LIMIT 0,1";
	$games = mysql_query($sql);
	while($ro = mysql_fetch_row($games)){
		$sql   = "SELECT * FROM `steam`.`valve_user_games` WHERE appID >= ".$ro[0]." LIMIT 0,25";
		$games = mysql_query($sql);
		while($row = mysql_fetch_row($games)){
			echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
		}
	}
?>