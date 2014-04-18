<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	echo"<table>";
	$sql   = "SELECT * FROM achievement_percent WHERE appID = ".$_GET['id'];
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$ach[$row[1]] = $row[2];
	}
		
	$sql   = "SELECT * FROM achievement_master WHERE appID = ".$_GET['id'];
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		echo "<tr><td>".$row[1]."</td><td><img src='".$row[2]."' /></td><td><img src='".$row[3]."' /></td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td><td>".$ach[$row[1]]."</td></tr>";
	}
?>