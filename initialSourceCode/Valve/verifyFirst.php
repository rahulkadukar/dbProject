<?php
	include 'Functions/database.php';		
	
	$sql   = "SELECT * FROM `valve`.`valve_user_games` WHERE lastUpdate = 10000000";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		echo $row[0]."<br>";
		$appid = $row[0];
		
		$sql = "INSERT INTO `valve`.`unknown_list` (`appID` ) VALUES ('".$appid."')";
		$result = mysql_query($sql);
		
		$sql = "DELETE FROM `valve`.`valve_user_games` WHERE `appID` = ".$appid;
		$result = mysql_query($sql);
	}
?>