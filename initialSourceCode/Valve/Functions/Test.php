<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';	
	
	$sql   = "SELECT COUNT(*) FROM `steam`.`achievement_master` WHERE `appID` = ".$_GET['id'];
	$games = mysql_query($sql);
	while($rowo = mysql_fetch_row($games)){	
		$master = $rowo[0];
	}
	
	$sql   = "SELECT COUNT(*) FROM `steam`.`achievement_percent` WHERE `appID` = ".$_GET['id'];
	$games = mysql_query($sql);
	while($rowo = mysql_fetch_row($games)){	
		$percent = $rowo[0];
	}
	
	if($percent == $master){
		$sql = "UPDATE `steam`.`achievement_status` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$rowo[0];
		$result = mysql_query($sql);
	}
?>