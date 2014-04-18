<?php
	include 'Functions/database.php';		
	
	$sql   = "SELECT * FROM `valve`.`dlc_master`";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$new[$row[0]] = 'Found';
	}
	
	$sql   = "SELECT * FROM `valve`.`dlc_list`";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$appid = $row[1];
		
		
		if($new[$row[1]] != 'Found'){
			$sql = "INSERT INTO `valve`.`unknown_list` (`appID` ) VALUES ('".$appid."')";
			$result = mysql_query($sql);
		
			$sql = "DELETE FROM `valve`.`dlc_list` WHERE `DLC` = ".$appid;
			$result = mysql_query($sql);
		}
	}

?>