<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	$sql   	= "SELECT * FROM game_master WHERE achievements > 0";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){
		$new[$row[0]] = 1;
	}
	
	$sql   = "SELECT * FROM achievement_links";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		if(!$new[$row[0]]){
			$sql = "INSERT INTO `steam`.`missing_achievements` (`appID` ) VALUES ('".$row[0]."')";
			$result = mysql_query($sql);
			
			$sql = "DELETE FROM `steam`.`achievement_links` WHERE `appID` = ".$row[0];
			$result = mysql_query($sql);
		}
	}
?>