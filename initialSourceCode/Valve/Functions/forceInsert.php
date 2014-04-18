<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';
	
	$sql = "SELECT * FROM achievement_percent WHERE appID =".$_GET['id'];
	echo $sql;
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){	
		$new[$row[1]] = $row[2];
	}
	
	//var_dump($new);
	for($i=1; $i<=$_GET['n']; $i++){
		if($new[$i]){
			
		}
		else{
			$sql = "INSERT INTO `steam`.`achievement_percent` (`appID`, `achNumber`, `percent`) VALUES ('".$_GET['id']."', '".$i."', '0.000')";
			$games = mysql_query($sql);
		}
		
	}
?>