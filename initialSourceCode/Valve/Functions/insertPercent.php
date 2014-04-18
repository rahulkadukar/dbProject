<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	echo"<table>";
	$count = 0;
	$sql   = "SELECT DISTINCT appID FROM achievement_master";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$sql = "INSERT INTO `steam`.`achievement_status`  (`appID`) VALUES (".$row[0].")";
		$ins = mysql_query($sql);
		$count ++;
	}
	echo $count;
?>