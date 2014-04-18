<?php
//Use only for testing purposes and delete when done
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	
	$sql = "TRUNCATE `steam`.`game_master`";
	$result = mysql_query($sql);

	$sql = "TRUNCATE `steam`.`game_developers`";
	$result = mysql_query($sql);
	
	$sql = "TRUNCATE `steam`.`game_description`";
	$result = mysql_query($sql);
	
	$sql = "TRUNCATE `steam`.`game_publishers`";
	$result = mysql_query($sql);
	
	$sql = "TRUNCATE `steam`.`game_genres`";
	$result = mysql_query($sql);
	
	$sql = "TRUNCATE `steam`.`dlc_list`";
	$result = mysql_query($sql);
		
	$sql = "TRUNCATE `steam`.`package_games`";
	$result = mysql_query($sql);
?>