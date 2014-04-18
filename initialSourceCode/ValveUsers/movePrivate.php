<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	ini_set('max_execution_time', 3000);
	$time = date("U");
	$time -= 3000000;
	
	$sql = "SELECT * FROM `valve`.`user_details` WHERE `visibility` = 1";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){	
		$sql = "INSERT INTO `private_details` ( `steamID64`, `avatar`, `lastUpdate` ) VALUES ('".$row[0]."','".$row[1]."','".date('U')."')";
		$insert = mysql_query($sql);
	}
	
	$sql = "DELETE FROM `valve`.`user_details` WHERE `visibility` = 1";
	$result = mysql_query($sql);
?>