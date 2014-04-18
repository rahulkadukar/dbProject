<?php
	include 'Functions/database.php';		
	
	$sql   = "SELECT * FROM `valve`.`game_master`";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$row[1] = replaceSpecial($row[1]);
		$sql = "UPDATE `valve`.`game_master` SET `name` =  '".$row[1]."' WHERE `appID` = ".$row[0];
		$result = mysql_query($sql);
	}

	
	$sql   = "SELECT * FROM `valve`.`dlc_master`";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$row[1] = replaceSpecial($row[1]);
		$sql = "UPDATE `valve`.`dlc_master` SET `name` =  '".$row[1]."' WHERE `appID` = ".$row[0];
		$result = mysql_query($sql);
	}
	
	function replaceSpecial($str){
		$chunked = str_split($str,1);
		$str = ""; 
		foreach($chunked as $chunk){
			$num = ord($chunk);
			if ($num >= 32 && $num <= 123)
				$str.=$chunk;
		}   
		return $str;
	} 
?>