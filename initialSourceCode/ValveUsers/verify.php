<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	ini_set('max_execution_time', 3000);
	$time = date("U");
	$time -= 3000000;
	
	$sql = "SELECT * FROM `valve`.`user_details`";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){	
		$sql = "SELECT COUNT(*) FROM `valve`.`game_details` WHERE `steamID64` = '".$row[0]."'";
		$fetch = mysql_query($sql);
		while($rowo = mysql_fetch_row($fetch)){	
			if(!$rowo[0]){
				echo $row[0]."<br>"	;	
				echo $sql."<br>";
			}
		}
	}
	
	
?>