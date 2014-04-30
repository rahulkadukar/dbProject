<?php
//Use only for testing purposes and delete when done do not run this file
	$uid = $_GET['b'];
	if($uid != '35')
		exit;
		
	error_reporting(E_ALL ^ E_NOTICE);
	include 'functions/database.php';		
	
	$sql = "TRUNCATE `valve`.`user_data`";
	$result = $link->query($sql);

	$sql = "TRUNCATE `valve`.`user_games`";
	$result = $link->query($sql);
	
	$sql = "TRUNCATE `valve`.`user_friends`";
	$result = $link->query($sql);
	
	$sql = "TRUNCATE `valve`.`user_master`";
	$result = $link->query($sql);

?>