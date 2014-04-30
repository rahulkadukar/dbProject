<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'functions/database.php';		
	ini_set('max_execution_time', 3000);
	
	$time  = date("U");
	$time  -= 3000000;
	
	$sql   = "SELECT * FROM `steam`.`app_master` WHERE lastUpdate < ".$time." LIMIT 0,1000";
	$gameData = $link->query($sql);
	while($row = $gameData->fetch_array()){
		$appid = $row[0];
		$url  = "http://store.steampowered.com/api/appdetails/?appids=".$appid;
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameInfo');
		
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);                                   

		if($resultStatus['http_code'] == 200){
			$new = json_decode($response,true);
			if($new[$appid]["success"]){
				$sql = "UPDATE `steam`.`app_master` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$appid;
				$result = $link->query($sql);
				
				$sql = "INSERT INTO `steam`.`app_raw_data` (`appID`,`description`) VALUES ('".$appid."','".$response."')";
				$result = $link->query($sql);
			}
			else{
				$sql = "INSERT INTO `steam`.`false_list` (`appID` ) VALUES ('".$appid."')";
				$result = $link->query($sql);
				
				$sql = "DELETE FROM `steam`.`app_master` WHERE `appID` = ".$appid;
				$result = $link->query($sql);
			}
		}
	}
?>