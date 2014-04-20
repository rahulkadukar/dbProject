<?php
//Use only for testing purposes and delete when done do not run this file
	error_reporting(E_ALL ^ E_NOTICE);
	include 'functions/database.php';		
	
	$sql = "TRUNCATE `valve`.`game_master`";
	$result = $link->query($sql);

	$sql = "TRUNCATE `valve`.`game_developers`";
	$result = $link->query($sql);
	
	$sql = "TRUNCATE `valve`.`game_description`";
	$result = $link->query($sql);
	
	$sql = "TRUNCATE `valve`.`game_publishers`";
	$result = $link->query($sql);
	
	$sql = "TRUNCATE `valve`.`game_genres`";
	$result = $link->query($sql);
	
	$sql = "TRUNCATE `valve`.`dlc_list`";
	$result = $link->query($sql);
		
	$sql = "TRUNCATE `valve`.`mod_list`";
	$result = $link->query($sql);

	$sql = "TRUNCATE `valve`.`random_list`";
	$result = $link->query($sql);
?>